<?php
namespace App\Models;
use PDO;

class UserOld
{
    private $conn;
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function findByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByLogin($login)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :login OR name = :login LIMIT 1");
        $stmt->execute([':login' => $login]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO users (name, email, password, is_admin) VALUES (:name, :email, :password, :is_admin)";
        $stmt = $this->conn->prepare($sql);

        // Default is_admin is 0 (customer)
        $is_admin = isset($data['is_admin']) ? $data['is_admin'] : 0;

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        return $stmt->execute([
            ':name' => $data['username'], // Mapping form field 'username' to DB column 'name'
            ':email' => $data['email'],
            ':password' => $hashedPassword,
            ':is_admin' => $is_admin
        ]);
    }

    public function findById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function setOtp($email, $otp)
    {
        $sql = "UPDATE users SET otp_code = :otp, otp_expires_at = DATE_ADD(NOW(), INTERVAL 10 MINUTE) WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':otp' => $otp,
            ':email' => $email
        ]);
    }

    public function verifyOtp($email, $otp)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email AND otp_code = :otp AND otp_expires_at > NOW() LIMIT 1");
        $stmt->execute([':email' => $email, ':otp' => $otp]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function clearOtp($email)
    {
        $stmt = $this->conn->prepare("UPDATE users SET otp_code = NULL, otp_expires_at = NULL WHERE email = :email");
        return $stmt->execute([':email' => $email]);
    }

    public function updateProfile($id, $data)
    {
        // สร้าง query อัตโนมัติ เฉพาะฟิลด์ที่ส่งมา
        $fields = [];
        $params = [':id' => $id];

        if (isset($data['name'])) {
            $fields[] = 'name = :name';
            $params[':name'] = $data['name'];
        }
        if (isset($data['email'])) {
            $fields[] = 'email = :email';
            $params[':email'] = $data['email'];
        }
        if (isset($data['address'])) {
            $fields[] = 'address = :address';
            $params[':address'] = $data['address'];
        }
        if (isset($data['phone'])) {
            $fields[] = 'phone = :phone';
            $params[':phone'] = $data['phone'];
        }
        if (!empty($data['password'])) {
            $fields[] = 'password = :password';
            $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }
}