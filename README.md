# Kiểm thử nâng cao - Quản lý Tiền điện

Phần này trình bày chi tiết về công tác **Kiểm thử nâng cao** (gồm Kiểm thử bảo mật và Kiểm thử hiệu năng) được thực hiện trên hệ thống **Quản lý Tiền điện nội bộ** nhằm đảm bảo tính an toàn bảo mật và khả năng chịu tải thực tế.

---

## 1. Kiểm thử Bảo mật ứng dụng Web (Security Testing)
* **Công cụ sử dụng:** OWASP ZAP (Zed Attack Proxy).
* **Mục tiêu:** 
  * Quét tự động các lỗ hổng bảo mật phổ biến (SQL Injection, XSS) trên mã nguồn ứng dụng.
  * Kiểm tra và đánh giá cơ chế phòng chống tấn công giả mạo yêu cầu từ phía người dùng (**CSRF Token**).
* **Kết quả:** Hệ thống đã được cấu hình sinh và xác thực mã độc nhất `CSRF Token` cho các form thao tác nhạy cảm, ngăn chặn thành công các yêu cầu trái phép từ bên ngoài phiên làm việc hợp lệ.

---

## 2. Kiểm thử Hiệu năng & Chịu tải (Load Testing)
* **Công cụ sử dụng:** Apache JMeter (Phiên bản 5.6.3).
* **Mục tiêu:** Đánh giá thời gian phản hồi (`Response Time`), tỷ lệ lỗi (`Error Rate`) và thông lượng (`Throughput`) của hệ thống dưới áp lực nhiều kết nối đồng thời.
* **Kịch bản kiểm thử (Test Configuration):**
  * **Module kiểm thử:** Quản lý Nhân viên & Nghiệp vụ (`/do_an_dgkdpm/file_ban_2/tinhtiendien/controller/nhanvien.php?act=quanlynhanvien`)
  * **Số lượng người dùng (`Number of Threads`):** 100 Users
  * **Thời gian tạo (`Ramp-up period`):** 10 giây
  * **Số vòng lặp (`Loop Count`):** 1
