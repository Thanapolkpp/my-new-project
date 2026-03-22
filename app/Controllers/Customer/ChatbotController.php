<?php
namespace App\Controllers\Customer;

use App\Models\Product;

class ChatbotController
{
    private $db;
    private $apiKey = 'AIzaSyCkMcijLu8aEBibtOzZKexUqsiV3y6MrsQ';

    public function __construct()
    {
        require_once ROOT_PATH . '/app/Legacy/LegacyDatabase.php';
        $this->db = (new \LegacyDatabase())->getConnection();
    }

    public function chat()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $userMessage = $input['message'] ?? '';

        if (empty($userMessage)) {
            echo json_encode(['error' => 'Empty message']);
            return;
        }

        // 1. ดึงข้อมูลสินค้าทั้งหมดจากฐานข้อมูลเพื่อใช้เป็นบริบท (Context)
        $productModel = new Product($this->db);
        $products = $productModel->all();

        $productContext = "รายการสินค้าในร้าน TECH GPU STORE ปัจจุบันมีดังนี้:\n";
        foreach ($products as $p) {
            $price = $p['price'] - ($p['discount'] ?? 0);
            $stock_status = $p['stock'] > 0 ? "มีของ ({$p['stock']} ชิ้น)" : "สินค้าหมด";
            $productContext .= "- ชื่อ: {$p['product_name']}, ราคา: {$price} บาท, สต็อก: {$stock_status}, รายละเอียด: {$p['detail']}\n";
        }

        // 2. สร้าง Prompt สำหรับสั่ง Gemini
        $prompt = "คุณคือ 'TECH GPU Assistant' เป็นพนักงานขายของร้านขายอุปกรณ์คอมพิวเตอร์และแคตตาล็อกสินค้า\n";
        $prompt .= "ข้อมูลสินค้าในร้าน:\n" . $productContext . "\n\n";
        $prompt .= "คำถามจากลูกค้า: " . $userMessage . "\n";
        $prompt .= "คำสั่งสำคัญ: จงตอบคำถามลูกค้าแบบสุภาพ เป็นกันเอง แนะนำสินค้าที่ตรงกับความต้องการจากข้อมูลร้านค้าที่มีอยู่เท่านั้น หากแนะนำสินค้า กรุณาแสดงข้อมูลเป็นรูปแบบ 'ตาราง (HTML Table)' ที่ประกอบด้วยคอลัมน์ [ชื่อสินค้า, ราคา, สต็อก, รายละเอียดเบื้องต้น] เพื่อให้ลูกค้าอ่านง่าย โดยใช้ class ของ Bootstrap 5 เช่น <table class='table table-bordered table-striped text-white'>...</table> ห้ามใช้ Markdown table เด็ดขาด เลี่ยงการใช้ Markdown style format ให้ใช้ HTML tag ตรงๆ ภายในคำตอบได้เลย";

        // 3. ส่ง Request ไปยัง Gemini API (Gemini-pro)
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent?key=" . $this->apiKey;

        $data = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ],
            // Optional settings
            "generationConfig" => [
                "temperature" => 0.4,
                "topK" => 32,
                "topP" => 1,
                "maxOutputTokens" => 1024,
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $responseData = json_decode($response, true);
            if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                $botReply = $responseData['candidates'][0]['content']['parts'][0]['text'];

                // ลบ markdown wrapper html (ถ้า ai ดื้อส่งมา)
                $botReply = preg_replace('/```html/i', '', $botReply);
                $botReply = preg_replace('/```/i', '', $botReply);

                echo json_encode(['reply' => trim($botReply)]);
            } else {
                echo json_encode(['error' => 'Invalid response from AI model']);
            }
        } else {
            echo json_encode(['error' => 'API request failed', 'details' => $response]);
        }
    }
}
