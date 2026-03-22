document.addEventListener('DOMContentLoaded', () => {
    // ค้นหาฟอร์มทั้งหมดที่ชี้ไปยัง /cart/add
    const cartForms = document.querySelectorAll('form[action$="/cart/add"]');
    cartForms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault(); // ป้องกันการรีโหลดหน้า
            
            const formData = new FormData(this);
            formData.append('ajax', '1'); // ส่งตัวแปรระบุว่าเป็นการเรียกแบบ AJAX
            
            // หาปุ่มและไอคอนในปุ่ม
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnHtml = submitBtn.innerHTML;
            
            // เปลี่ยนปุ่มเป็นสถานะกำลังโหลด
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> รอสักครู่...';
            submitBtn.disabled = true;

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // 1. อัปเดตตัวเลขแสดงจำนวนตะกร้า
                    document.getElementById('cart-badge').innerText = data.cartCount;
                    
                    // หา base url จาก action ของฟอร์ม (ลบ /cart/add ออก)
                    const baseUrl = this.action.replace(/\/cart\/add$/, '');
                    
                    // 2. แจ้งเตือน Pop-up สวยๆ ที่มุมขวาล่าง
                    Toastify({
                      text: data.message + " (คลิกเพื่อดูตะกร้า)",
                      duration: 3000,
                      destination: baseUrl + "/cart",
                      newWindow: false,
                      gravity: "bottom", // บน หรือ ล่าง
                      position: "right", // ซ้าย กลาง ขวา
                      stopOnFocus: true, 
                      style: {
                        background: "linear-gradient(to right, #0d6efd, #0dcaf0)",
                        borderRadius: "10px",
                        boxShadow: "0 4px 12px rgba(0,0,0,0.15)",
                        fontWeight: "bold",
                        fontFamily: "'Kanit', sans-serif",
                        padding: "12px 20px",
                        color: "white",
                        cursor: "pointer"
                      },
                    }).showToast();
                } else {
                    // เผื่อมีข้อผิดพลาดส่งมาจากฝั่งเซิร์ฟเวอร์
                     Toastify({
                        text: "⚠️ " + (data.message || "เกิดข้อผิดพลาด"),
                        duration: 3000,
                        gravity: "bottom",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right, #dc3545, #f8d7da)",
                            color: "white",
                            borderRadius: "10px"
                        }
                    }).showToast();
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
            } finally {
                // คืนค่าปุ่มกลับเป็นเหมือนเดิม
                submitBtn.innerHTML = originalBtnHtml;
                submitBtn.disabled = false;
            }
        });
    });
});
