⚡ Đồ án: Hệ thống Quản lý và Tính tiền điện sinh hoạt
📖 Mô tả dự án
Ứng dụng web được xây dựng nhằm hỗ trợ nhân viên công ty điện lực tối ưu hóa quy trình quản lý và tính tiền điện sinh hoạt. Hệ thống cung cấp các công cụ toàn diện từ việc quản lý thông tin khách hàng, thiết lập giá điện bậc thang, đến tự động hóa quá trình tính toán, xuất hóa đơn và theo dõi công nợ.

✨ Các chức năng chính
👥 Quản lý khách hàng: Thêm, sửa, xóa thông tin khách hàng. Hỗ trợ chọn nhanh điện kế tương ứng để lập hóa đơn.

💰 Quản lý giá điện: Quản lý danh mục giá điện linh hoạt. Cho phép thêm, sửa, xóa và tùy chỉnh mức giá theo từng bậc thang điện.

⏱️ Quản lý điện kế: Quản lý danh sách và trạng thái các công tơ điện.

🧾 Quản lý hóa đơn:

Lập và in hóa đơn giao cho khách hàng.

Mã hóa đơn được sinh tự động và duy nhất dựa trên thời gian thực hệ thống (Định dạng: NămThángNgàyGiờPhútGiây).

Tích hợp các ràng buộc kiểm tra logic chặt chẽ, đảm bảo an toàn và tính chính xác tuyệt đối khi lập biên lai.

🔍 Tra cứu & Thống kê: Hỗ trợ tìm kiếm thông tin nhanh chóng, theo dõi tình trạng nợ đọng của khách hàng và thống kê dữ liệu.

👨‍💼 Quản lý nhân viên: Quản lý danh sách nhân viên công ty và tài khoản truy cập hệ thống.

⚙️ Hướng dẫn cài đặt
Chuẩn bị mã nguồn: Tải source code về và giải nén vào thư mục của Web Server (ví dụ: htdocs đối với XAMPP hoặc www đối với Laragon).

Nhập cơ sở dữ liệu (Import Database):

Mở trình quản lý cơ sở dữ liệu (ví dụ: phpMyAdmin).

Tạo một database mới cho dự án.

Tiến hành import file database có sẵn trong mã nguồn.

⚠️ Lưu ý quan trọng: Trước khi bấm Nhập (Import), hãy tìm phần Other options (Các tùy chọn khác) và tắt (bỏ tích) mục kiểm tra khóa ngoại (Enable foreign key checks) để tránh lỗi.

Cấu hình kết nối:

Mở file cấu hình theo đường dẫn: model/config.php.

Thay đổi tên database, username, và password sao cho khớp với môi trường localhost của bạn.

Khởi chạy ứng dụng: Mở trình duyệt và truy cập theo đường dẫn:
http://localhost/ten_project_cua_ban

🔐 Tài khoản Quản trị
Sử dụng tài khoản sau để đăng nhập vào hệ thống:

Tên đăng nhập: nv1@gmail.com

Mật khẩu: 12345
