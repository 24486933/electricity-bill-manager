<div align="center">
  <h1> Hệ Thống Quản Lý Và Tính Tiền Điện Sinh Hoạt</h1>
  <p><i>Đồ án hỗ trợ nhân viên công ty điện lực tối ưu hóa quy trình quản lý, tính toán và xuất hóa đơn tiền điện.</i></p>
</div>

---

## Mô tả dự án
Đồ án được xây dựng nhằm cung cấp một giải pháp phần mềm toàn diện cho các công ty điện lực. Hệ thống hỗ trợ nhân viên thực hiện các nghiệp vụ một cách nhanh chóng, chính xác từ khâu quản lý thông tin khách hàng, thiết lập giá điện bậc thang linh hoạt, đến tự động hóa quá trình tính toán, xuất hóa đơn và theo dõi công nợ khách hàng.

## Các chức năng nổi bật

  - **Quản lý khách hàng:** Thêm mới, chỉnh sửa, xóa thông tin khách hàng. Liên kết trực tiếp để chọn điện kế và lập hóa đơn nhanh chóng.
  - **Quản lý giá điện:** Hệ thống giá điện bậc thang linh hoạt. Cho phép Thêm/Sửa/Xóa mức giá; mỗi lần cập nhật sẽ tự động điều chỉnh tùy thuộc vào số bậc và giá tiền của từng bậc.
  - **Quản lý điện kế:** Theo dõi trạng thái, thông số công tơ điện của từng hộ gia đình.
  - **Quản lý hóa đơn:**
  - Lập và in hóa đơn thanh toán trực tiếp.
  - **Mã hóa đơn thông minh:** Tự động khởi tạo mã hóa đơn duy nhất dựa trên thời gian thực (Định dạng: `NămThángNgàyGiờPhútGiây`).
  - **An toàn & Chính xác:** Đã xây dựng các ràng buộc kiểm tra dữ liệu chặt chẽ để đảm bảo an toàn tuyệt đối khi lập biên lai.
  - **Tra cứu & Thống kê:** Công cụ tìm kiếm mạnh mẽ, hỗ trợ theo dõi nợ đọng và thống kê số liệu hoạt động.
  - **Quản lý nhân viên:** Kiểm soát danh sách nhân viên công ty và quyền truy cập vào hệ thống.

## Công nghệ sử dụng
*   **Back-end:** PHP
*   **Cơ sở dữ liệu:** MySQL
*   **Giao diện:** HTML, CSS, JavaScript

## Hướng dẫn cài đặt

Vui lòng làm theo các bước dưới đây để thiết lập và chạy dự án trên môi trường Localhost của bạn:

1. **Chuẩn bị môi trường:** Đảm bảo máy tính đã cài đặt XAMPP, WAMP, hoặc Laragon.
2. **Tải mã nguồn:** Clone repository này hoặc giải nén file source code vào thư mục root của web server (ví dụ: `htdocs` đối với XAMPP).
3. **Thiết lập Cơ sở dữ liệu:**
   - Truy cập trang quản lý `phpMyAdmin` (thường là `http://localhost/phpmyadmin`).
   - Tạo một Database mới.
   - Chọn Database vừa tạo và tiến hành **Import (Nhập)** file `.sql` có sẵn trong thư mục source code.
   -  **Lưu ý rất quan trọng:** Trong thẻ Import, tìm đến phần *Other options (Các tùy chọn khác)* và **TẮT mục kiểm tra khóa ngoại (Enable foreign key checks)** trước khi nhấn nút Thực thi/Nhập.
4. **Cấu hình kết nối:**
   - Mở file `model/config.php` bằng Text Editor (VS Code, Notepad++...).
   - Đổi lại tên database, username và password sao cho khớp với cấu hình MySQL trên máy của bạn.
5. **Chạy ứng dụng:** Mở trình duyệt web và truy cập theo đường dẫn:
   ```bash
   http://localhost/ten_project_cua_ban
   ```

## Tài khoản Quản trị

Sử dụng tài khoản mặc định dưới đây để đăng nhập vào hệ thống:

| Vai trò | Email đăng nhập | Mật khẩu |
| :--- | :--- | :--- |
| **Quản trị viên / Nhân viên** | `nv1@gmail.com` | `12345` |


## Nhiệm vụ kiểm thử 
- Nhánh Hưng kiểm thử hộp đen
- Nhánh Khánh kiểm thử hộp trắng
- Nhánh Trung kiểm thử tự động
- Nhánh Phúc kiểm thử nâng cao
---

