# Website quản lý và tính tiền điện

## 1. Giới thiệu

Tên hiển thị trên trang đăng nhập là **Tính Tiền Điện**. Website hỗ trợ nhân viên quản lý khách hàng, điện kế, bảng giá điện và hóa đơn; tra cứu thông tin và theo dõi hóa đơn chưa thanh toán.

README này hướng dẫn chạy ứng dụng và cung cấp bộ test case kiểm thử hộp đen tham khảo cho báo cáo giữa kỳ. Các kết quả mong đợi là tiêu chí cần kiểm tra, **không phải kết quả đã chạy**. Hãy thực hiện test trên môi trường cục bộ rồi điền kết quả thực tế và ảnh minh chứng trước khi đưa vào báo cáo.

## 2. Chức năng trong phạm vi kiểm thử

- Đăng nhập/đăng xuất nhân viên.
- Quản lý nhân viên và đổi mật khẩu.
- Quản lý khách hàng: thêm, sửa, xóa, tìm kiếm.
- Quản lý điện kế: thêm, sửa, xóa, tra cứu.
- Xem và thêm bảng giá điện.
- Lập hóa đơn từ chỉ số điện, xem chi tiết, in hóa đơn và cập nhật trạng thái thanh toán.
- Tra cứu khách hàng, điện kế, hóa đơn; xem danh sách nợ và chi tiết nợ.

## 3. Hướng dẫn chạy website trên Windows

### Cách A: XAMPP (khuyến nghị)

1. Cài XAMPP có Apache, MySQL và PHP. Khởi động **Apache** và **MySQL**.
2. Chép thư mục dự án vào `C:\xampp\htdocs\tinhtiendien` (hoặc dùng thư mục dự án hiện tại nhưng cấu hình VirtualHost tương ứng).
3. Mở phpMyAdmin tại `http://localhost/phpmyadmin`.
4. Import file `testdien.sql`. File tạo các bảng và dữ liệu mẫu trong cơ sở dữ liệu `testdien`.
5. Kiểm tra thông tin kết nối trong `model/config.php`. Cấu hình hiện tại là:
   - MySQL host: `localhost`
   - Database: `testdien`
   - Username: `root`
   - Password: rỗng

   Nếu MySQL trên máy bạn dùng thông tin khác, chỉnh cấu hình cục bộ cho đúng. Không đưa mật khẩu thật vào README hoặc commit lên Git.
6. Mở `http://localhost/tinhtiendien/`. Trang gốc chuyển tới trang đăng nhập.
7. Tài khoản mẫu trong dữ liệu SQL: **`nv1@gmail.com`**, mật khẩu **`12345`**. Chỉ dùng tài khoản này trên cơ sở dữ liệu cục bộ được import từ dump; không sử dụng cho môi trường công khai.

Nếu thiếu thư mục `vendor`, chạy `composer install` tại thư mục dự án. Trong repository hiện đã có `vendor/autoload.php`.

### Cách B: PHP built-in server

Có thể chạy từ thư mục gốc của dự án nếu đã cài PHP CLI:

```powershell
php -S 127.0.0.1:8000
```

Sau đó truy cập `http://127.0.0.1:8000/`. MySQL vẫn cần được bật và cấu hình đúng như phần trên.

### Lưu ý trước khi kiểm thử

- Dùng database kiểm thử riêng, không chạy các ca thêm/sửa/xóa hoặc cập nhật thanh toán trên dữ liệu thật.
- `testdien.sql` có dữ liệu mẫu từ năm 2024; có thể dùng khách hàng/hóa đơn đã tồn tại để tra cứu. Với ca ghi dữ liệu, dùng mã test riêng (ví dụ `KH_TEST_01`) và xóa dữ liệu test sau khi hoàn tất nếu ứng dụng cho phép.
- Ghi lại trình duyệt, độ phân giải, ngày giờ test và bản dữ liệu SQL đã dùng.
- Một số đầu vào có thể bị giao diện chặn bởi `required`; vẫn cần kiểm tra cả việc gửi dữ liệu rỗng nếu quy trình môn học yêu cầu kiểm tra phía máy chủ.

## 4. Kiểm thử hộp đen là gì và thường kiểm tra những gì?

Kiểm thử hộp đen đánh giá phần mềm qua giao diện và kết quả quan sát được, không cần đọc hoặc dựa vào cách viết mã bên trong. Người kiểm thử nhập dữ liệu, thao tác như người dùng và so sánh kết quả thực tế với yêu cầu/kết quả mong đợi.

Các kỹ thuật phù hợp cho website này:

1. **Phân vùng tương đương:** chia đầu vào thành nhóm hợp lệ/không hợp lệ, ví dụ mã khách hàng tồn tại/không tồn tại, chỉ số điện là số/không phải số.
2. **Kiểm thử giá trị biên:** thử giá trị nhỏ nhất, lớn nhất và sát biên, ví dụ chỉ số cuối bằng chỉ số đầu, nhỏ hơn chỉ số đầu, hoặc lớn hơn một đơn vị.
3. **Bảng quyết định:** kiểm tra tổ hợp điều kiện, ví dụ đúng/sai tài khoản kết hợp với mật khẩu đúng/sai; hóa đơn đã/chưa thanh toán.
4. **Kiểm thử chuyển trạng thái:** theo dõi trạng thái trước/sau, ví dụ chưa thanh toán → đã thanh toán → trạng thái hiển thị và thống kê nợ được cập nhật.
5. **Kiểm thử luồng nghiệp vụ đầu-cuối:** khách hàng → điện kế → nhập chỉ số → lập hóa đơn → tra cứu/in → xác nhận thanh toán.
6. **Kiểm thử giao diện và khả dụng:** thông báo lỗi dễ hiểu, nút điều hướng, phân trang, dữ liệu tiếng Việt, hiển thị trên kích thước màn hình khác nhau.

Mỗi test case cần có: mã test, chức năng/mục tiêu, điều kiện trước, dữ liệu đầu vào, các bước thực hiện, kết quả mong đợi, kết quả thực tế, trạng thái Pass/Fail/Blocked, mã lỗi (nếu có) và ảnh minh chứng.

**Quy tắc ghi nhận:** chỉ đánh Pass khi kết quả thực tế đáp ứng toàn bộ kết quả mong đợi. Nếu khác, đánh Fail và ghi rõ bước gây lỗi, thông báo nhận được, ảnh chụp và mức độ ảnh hưởng. Không đoán kết quả thực tế từ nội dung README.

## 5. Bộ test case tham khảo

Mặc định mọi test case cần nhân viên đăng nhập nếu chức năng yêu cầu. Với các ca ghi dữ liệu, dùng bản database thử nghiệm và dữ liệu test riêng. Điền cột “Kết quả thực tế / Pass-Fail” trong Word sau khi chạy.

### A. Đăng nhập và điều hướng

| ID | Mục tiêu | Dữ liệu / bước thực hiện | Kết quả mong đợi | Kết quả thực tế / Pass-Fail |
|---|---|---|---|---|
| AUTH-01 | Đăng nhập hợp lệ | Nhập `nv1@gmail.com` / `12345`, bấm **Đăng nhập**. | Đăng nhập thành công, chuyển tới màn hình quản lý và hiển thị người dùng. | **Đạt** — vào màn hình quản lý, hiển thị “Võ Thị C”. |
| AUTH-02 | Từ chối mật khẩu sai | Nhập `nv1@gmail.com` / `sai123`, bấm **Đăng nhập**. | Không đăng nhập; hiển thị thông báo lỗi, vẫn ở trang đăng nhập. | **Đạt** — bị từ chối, hiện “Sai tên đăng nhập hoặc mật khẩu.” |
| AUTH-03 | Kiểm tra trường bắt buộc | Để trống tên đăng nhập hoặc mật khẩu rồi gửi form. | Không đăng nhập; trường bắt buộc được chỉ rõ hoặc có thông báo phù hợp. | Chưa chạy |
| AUTH-04 | Đăng xuất | Đăng nhập rồi bấm **Đăng xuất**. | Phiên kết thúc và quay về trang đăng nhập; mở lại màn hình quản lý không còn phiên hợp lệ. | **Đạt** — quay lại và mở URL quản lý sau đăng xuất đều không hiển thị màn hình quản lý. |

### B. Khách hàng

| ID | Mục tiêu | Dữ liệu / bước thực hiện | Kết quả mong đợi | Kết quả thực tế / Pass-Fail |
|---|---|---|---|---|
| CUS-01 | Tìm khách hàng theo mã có thật | Vào **Quản lý khách hàng**, tìm mã khách hàng có trong dữ liệu mẫu, ví dụ `1`. | Hiển thị khách hàng khớp mã; thông tin không bị nhầm với khách hàng khác. | **Đạt** — mã `1` trả về “Khách hàng 1”. |
| CUS-02 | Tìm khách hàng không tồn tại | Tìm mã test không có trong dữ liệu, ví dụ `KH_KHONG_CO`. | Hiển thị kết quả rỗng hoặc thông báo không tìm thấy; trang không lỗi. | **Đạt** — hiện “Không tìm thấy khách hàng trong CSDL.” |
| CUS-03 | Thêm khách hàng hợp lệ | Thêm mã mới `KH_TEST_01`, tên `Khách hàng kiểm thử`, địa chỉ `Địa chỉ test`, điện thoại và CCCD hợp lệ theo biểu mẫu. | Lưu được một khách hàng; khách hàng xuất hiện trong danh sách/tìm kiếm và các trường khớp dữ liệu nhập. | **Đạt** — thêm và tra cứu được; mã tự sinh `0000000000020`. Đã xóa bản ghi test. |
| CUS-04 | Sửa thông tin khách hàng | Mở bản ghi khách hàng test đã tạo, đổi tên hoặc địa chỉ rồi lưu. | Thông tin mới được hiển thị sau khi lưu/tải lại; mã khách hàng không tự đổi. | **Đạt** — sửa bản ghi test thành công và dữ liệu cập nhật hiển thị sau khi lưu. |
| CUS-05 | Xóa khách hàng không có liên kết | Tạo khách hàng test chưa gắn điện kế/hóa đơn; chọn xóa và xác nhận. | Khách hàng bị xóa và không còn xuất hiện khi tìm lại. Nếu hủy hộp thoại xác nhận thì dữ liệu vẫn còn. | **Đạt** — xóa khách hàng test không có điện kế; tìm lại không còn bản ghi. |
| CUS-06 | Ngăn xóa khách hàng đang được sử dụng | Thử xóa khách hàng đã có điện kế hoặc hóa đơn. | Hệ thống không xóa dữ liệu đang được tham chiếu và giải thích lý do. | **Đạt** — giao diện báo không thể xóa và không cung cấp nút xóa; dữ liệu liên kết được giữ. |

### C. Điện kế và bảng giá

| ID | Mục tiêu | Dữ liệu / bước thực hiện | Kết quả mong đợi | Kết quả thực tế / Pass-Fail |
|---|---|---|---|---|
| MTR-01 | Thêm điện kế hợp lệ | Chọn khách hàng test; nhập mã điện kế mới, ngày sản xuất/ngày lắp hợp lệ, mô tả và trạng thái. | Điện kế được lưu, liên kết đúng khách hàng và xuất hiện trong danh sách/tra cứu. | **Đạt** — tạo điện kế `00000030` cho khách hàng `10`; đã thử sửa mô tả và xóa để dọn dữ liệu test. |
| MTR-02 | Tra cứu điện kế không tồn tại | Tra cứu mã `DK_KHONG_CO`. | Không trả về điện kế khác; hiển thị kết quả rỗng/thông báo phù hợp. | **Đạt** — không có kết quả cho mã giả; điện kế `00000014` cũng tra cứu đúng ở trang Tra cứu. |
| RATE-01 | Xem bảng giá theo ngày | Vào **Quản lý giá điện**, chọn ngày đã có bảng giá. | Hiển thị đúng các bậc/đơn giá được lưu cho ngày đã chọn; các dòng cùng ngày được nhóm hợp lý. | **Đạt** — ngày `2024-05-03 15:54:58` hiển thị đủ bậc và đơn giá. |
| RATE-02 | Từ chối đơn giá không hợp lệ | Trong môi trường test, thử thêm bảng giá với đơn giá bằng `0` hoặc âm. | Không lưu bảng giá không hợp lệ; hiển thị lỗi rõ ràng. | **Không đạt** — giá `0` bị từ chối, nhưng giá `-1` được chuẩn hóa thành `0.001` và ứng dụng báo thêm thành công; hai dòng test đã được dọn. |
| RATE-03 | Kiểm tra khoảng bậc không hợp lệ | Thử giá trị “Từ số kWh” bằng hoặc lớn hơn “Đến số kWh”. | Không lưu bậc giá sai thứ tự; trường lỗi được thông báo. | **Đạt** — khoảng 200–200 và 250–200 đều bị từ chối với thông báo khoảng bắt đầu phải nhỏ hơn kết thúc. |

### D. Hóa đơn và tính tiền

| ID | Mục tiêu | Dữ liệu / bước thực hiện | Kết quả mong đợi | Kết quả thực tế / Pass-Fail |
|---|---|---|---|---|
| BILL-01 | Tính điện năng tiêu thụ | Chọn điện kế test; nhập chỉ số đầu `100`, chỉ số cuối `150`. | Điện năng tiêu thụ hiển thị `50 kWh` (chỉ số cuối trừ chỉ số đầu). | **Đạt** — với chỉ số `3000` → `3050`, giao diện tính `50 kWh`, tiền điện `50.000`, VAT `5.000`, tổng `55.000 VNĐ`. |
| BILL-02 | Kiểm tra chỉ số cuối nhỏ hơn chỉ số đầu | Nhập chỉ số đầu `150`, chỉ số cuối `100`, rồi yêu cầu tính. | Không tạo hóa đơn; yêu cầu nhập chỉ số cuối lớn hơn chỉ số đầu. | **Đạt về validation** — chỉ số cuối `2999` nhỏ hơn `3000` bị từ chối; giao diện hiện cảnh báo chỉ số, sau đó cảnh báo sản lượng không hợp lệ. Console vẫn ghi nhận lỗi JavaScript khi xử lý form. |
| BILL-03 | Kiểm tra chỉ số không phải số | Nhập chữ/ký tự vào chỉ số (nếu giao diện cho phép) rồi tính. | Dữ liệu bị từ chối; không tạo hóa đơn; thông báo chỉ số phải là số. | **Không đạt** — input số chặn chữ, nhưng `12a` thành `12`, sản lượng hiện `NaN`; không có thông báo rõ và kết quả tính trước vẫn còn. Không lưu hóa đơn. |
| BILL-04 | Kiểm tra sản lượng bằng 0 | Nhập hai chỉ số bằng nhau, ví dụ `100` và `100`. | Không lập hóa đơn có sản lượng âm/không hợp lệ; thông báo hoặc quy tắc nghiệp vụ được áp dụng nhất quán. | **Đạt** — chỉ số `3000` → `3000` bị từ chối với thông báo sản lượng không được nhỏ hơn hoặc bằng 0; không tạo hóa đơn. |
| BILL-05 | Đối chiếu tiền theo bậc | Tạo phép tính có sản lượng đi qua nhiều bậc giá. Ghi lại giá đang áp dụng và đối chiếu từng bậc, tổng tiền điện, thuế và tổng thanh toán. | Sản lượng mỗi bậc không bị đếm thiếu/trùng; tổng tiền bằng tổng các bậc; thuế và tổng thanh toán khớp quy tắc hiển thị (mã hiện tại thể hiện thuế 10%). | **Đạt** — `150 kWh`: bậc 1 `100.000`, bậc 2 `60.000`, tiền điện `160.000`, VAT `16.000`, tổng `176.000 VNĐ`. |
| BILL-06 | Lưu và tra cứu hóa đơn mới | Hoàn tất luồng lập hóa đơn với điện kế test; ghi lại mã hóa đơn được tạo. Tải lại danh sách và tra cứu mã đó. | Hóa đơn được lưu đúng một lần, mã hóa đơn tra cứu được, các chỉ số/kỳ/tổng tiền khớp dữ liệu vừa nhập. | **Đạt** — hóa đơn `26101313544195` lưu một lần, 50 kWh, tổng `55.000 VNĐ`; tải lại vẫn tra cứu được. Dữ liệu test đã dọn. |
| BILL-07 | Xem chi tiết và in hóa đơn | Mở chi tiết hóa đơn test rồi chọn chức năng in. | Chi tiết hóa đơn khớp dữ liệu; bản in/giấy báo có mã hóa đơn, thông tin cần thiết và số tiền đúng. | **Không đạt** — chi tiết xem được nhưng in giấy báo lỗi do thiếu `DejaVuSansCondensed.ttf` ở đường dẫn WAMP cố định. |

### E. Tra cứu, theo dõi nợ và thanh toán

| ID | Mục tiêu | Dữ liệu / bước thực hiện | Kết quả mong đợi | Kết quả thực tế / Pass-Fail |
|---|---|---|---|---|
| SEARCH-01 | Tra cứu hóa đơn có thật | Tìm mã hóa đơn đã có trong dữ liệu mẫu hoặc mã tạo tại BILL-06. | Hiển thị đúng hóa đơn; mở được chi tiết tương ứng. | **Đạt** — mã `24050315553546` trả về hóa đơn đúng, xem được chi tiết. |
| SEARCH-02 | Tra cứu hóa đơn không tồn tại | Tìm `HD_KHONG_CO`. | Không hiển thị nhầm hóa đơn; báo kết quả rỗng/thông báo phù hợp. | **Đạt** — giao diện thông báo không tìm thấy hóa đơn. |
| DEBT-01 | Xem danh sách nợ và tổng quan | Vào **Tra cứu → Theo dõi nợ**, mở **Xem Tổng Quan**. | Danh sách chỉ gồm hóa đơn chưa thanh toán; tổng quan/tổng nợ nhất quán với dữ liệu test. | **Đạt** — danh sách 8 khách hàng; tổng quan 8 khách hàng, 31 hóa đơn, `20.048.112 VNĐ`; tổng các khoản nợ khớp. |
| DEBT-02 | Đánh dấu hóa đơn đã thanh toán | Chọn một hóa đơn chưa thanh toán của dữ liệu test và bấm **Đã thanh toán**. | Trạng thái đổi sang đã thanh toán; hóa đơn không còn được tính trong danh sách nợ chưa thanh toán sau khi tải lại. | **Đạt** — hóa đơn đổi sang đã thanh toán và khách hàng biến mất khỏi danh sách nợ sau khi tải lại. |
| DEBT-03 | Xem chi tiết nợ | Từ khách hàng đang nợ, mở **Xem chi tiết nợ**. | Chỉ hiển thị hóa đơn chưa thanh toán thuộc đúng khách hàng. | **Đạt** — khách hàng mã `10` có 7 hóa đơn; tổng `6.906.338 VNĐ` khớp danh sách. |

### Kết quả chạy thực tế

Trong báo cáo Word có 105 test case. Sau khi chạy bổ sung 36 ca, tổng kết là **88 đạt, 15 không đạt, 2 Blocked, 0 chưa chạy**. Đã thực thi 103/105 ca; T025 và T052 bị chặn bởi giới hạn giao diện, không được tự suy diễn thành Pass.

| Mã Word | Kết quả thực tế | Trạng thái |
|---|---|---|
| T006 | Khoảng trắng đầu/cuối trong thông tin đăng nhập bị từ chối; ở lại trang đăng nhập. | Đạt |
| T007 | Chuỗi SQL injection trong tên đăng nhập bị từ chối; không vượt xác thực. | Đạt |
| T016 | Tạo nhân viên test, sửa tên/số điện thoại và xác nhận dữ liệu còn sau khi tải lại. | Đạt |
| T019 | Đổi mật khẩu, đăng xuất rồi đăng nhập lại thành công bằng mật khẩu mới. | Đạt |
| T024 | Thêm khách hàng thành công; mã tự sinh `0000000000020`, giao diện không có trường nhập mã. | Đạt |
| T025 | Không thể nhập mã khách hàng hiện hữu để thử trùng qua giao diện. | Blocked |
| T047 | Tạo bảng giá hợp lệ 6 bậc, hiển thị đúng các bậc/đơn giá. | Đạt |
| T049 | Hai khoảng bằng nhau `200–200` và giảm `250–200` đều bị từ chối. | Đạt |
| T050 | Chữ không nhập được vào trường đơn giá kiểu số; không lưu giá không hợp lệ. | Đạt |
| T051 | Gửi form bảng giá rỗng bị trình duyệt chặn bởi trường bắt buộc. | Đạt |
| T052 | Form không có trường ngày áp dụng; không thể nhập ngày tương lai qua UI. | Blocked |
| T054 | 250 kWh phân bổ 100/100/50; tiền điện `290.000`, VAT `29.000`, tổng `319.000 VNĐ`. | Đạt |
| T055 | `1000.5` bị format thành `10.005` mà không cảnh báo; lần nhập không được lưu. | Không đạt |
| T056 | Chọn điện kế mở đúng form; chưa có hóa đơn cũ nên chỉ số đầu/kỳ trước để trống. | Đạt |
| T060 | Chỉ số đầu `-1`, cuối `60` được tính thành 61 kWh; tổng `67.100 VNĐ`. Không lưu hóa đơn. | Không đạt |
| T061 | `12a` thành `12`, sản lượng hiện `NaN`; không có lỗi rõ và kết quả trước vẫn còn. | Không đạt |
| T062 | Với chỉ số đầu bị khóa `3000`, chỉ số cuối `2999` bị cảnh báo và nút thêm hóa đơn bị khóa. | Đạt |
| T065 | 99/100/101 kWh lần lượt có tổng `108.900`/`110.000`/`111.320 VNĐ`. | Đạt |
| T067 | Lưu hóa đơn `26101313544195`: 50 kWh, tổng `55.000 VNĐ`, đúng khách hàng/điện kế. | Đạt |
| T068 | Nhấn nhanh lưu hai lần chỉ tạo một hóa đơn `26101313565292`; không có bản ghi trùng. | Đạt |
| T069 | Ngày 14/10 đến 13/10/2026 bị chặn khi lưu với thông báo ngày bắt đầu phải nhỏ hơn ngày kết thúc. | Đạt |
| T070 | Không chọn điện kế hiện thông báo yêu cầu chọn; không tạo hóa đơn. | Đạt |
| T077 | Hóa đơn chuyển từ chưa thanh toán sang đã thanh toán; nút thao tác đổi thành In hóa đơn. | Đạt |
| T078 | Gửi lại thao tác thanh toán giữ trạng thái đã thanh toán, không sinh hóa đơn mới. | Đạt |
| T080 | Tra cứu theo mã và tên trả về đúng khách hàng test. | Đạt |
| T089 | Khách hàng có hóa đơn đã thanh toán không xuất hiện trong danh sách nợ. | Đạt |
| T090 | Hóa đơn chưa thanh toán `11.000 VNĐ` xuất hiện đúng trong danh sách nợ. | Đạt |
| T091 | Chi tiết nợ ghi điện `10.000`, thuế `1.000`, tổng `11.000 VNĐ`; thanh toán xong thì hết nợ. | Đạt |
| T096 | Tải lại chi tiết vẫn có hóa đơn và 50 kWh, không phát sinh bản ghi mới. | Đạt |
| T097 | Hóa đơn liên kết đúng khách hàng, điện kế và bảng giá; chi tiết tiền khớp. | Đạt |
| T099 | Sáu liên kết menu chính mở đúng trang chức năng. | Đạt |
| T101 | Khi MySQL dừng, trang lộ SQLSTATE, PHP Fatal error và đầy đủ stack trace; khởi động MySQL thì web chạy lại. | Không đạt |
| T102 | URL `cthd` thiếu mã hóa đơn phát lộ PHP `Undefined variable` warnings; chuỗi có dấu nháy không trả dữ liệu ngoài ý muốn. | Không đạt |
| T103 | Luồng khách hàng → điện kế → tính 50 kWh → lưu hóa đơn → xem chi tiết hoàn tất, tổng `55.000 VNĐ`. | Đạt |
| T104 | Hóa đơn xuất hiện trong nợ trước thanh toán và biến mất sau khi chuyển trạng thái. | Đạt |
| T105 | Đăng xuất, đăng nhập lại; dữ liệu hóa đơn/khách hàng vẫn tồn tại trong phiên mới. | Đạt |

Các lỗi đáng chú ý trong toàn bộ lượt kiểm thử:

- **T101:** mất kết nối MySQL làm lộ SQLSTATE và PHP stack trace đầy đủ; MySQL đã được khởi động lại và website hoạt động bình thường.
- **T102:** URL chi tiết hóa đơn thiếu tham số làm lộ PHP warnings.
- **T055:** formatter diễn giải sai giá thập phân; **T060:** chấp nhận chỉ số âm; **T061:** đầu vào lỗi dẫn đến `NaN` và giữ kết quả cũ.
- Chọn **Xem** điện kế vẫn hiện chi tiết nhưng phát sinh JavaScript `TypeError: Cannot set properties of null (setting 'checked')` tại `assets/js/dienkekh.js`.
- Thêm điện kế với mã khách hàng không tồn tại hoặc mã điện kế trùng làm lộ PDO fatal error/stack trace (lỗi ràng buộc 1452 và duplicate key 1062).
- Bảng giá chấp nhận khoảng bậc chồng lấn (0–200 và 150–trở lên) rồi báo thêm thành công; đơn giá âm `-1` được chuẩn hóa thành `0.001` và lưu thành công. Các dòng kiểm thử đã được dọn.
- Chức năng in giấy báo điện lỗi do thiếu font `DejaVuSansCondensed.ttf` tại đường dẫn WAMP cố định.
- Action cập nhật thanh toán với mã hóa đơn không tồn tại mở nhầm form tính điện và phát sinh lỗi JavaScript; không đổi trạng thái hóa đơn.
- Để trống ô tìm hóa đơn trong danh sách quản lý vẫn giữ nguyên danh sách, không nhắc nhập mã.
- Trang tính điện tiếp tục có một số lỗi JavaScript trong console dù kết quả tính hợp lệ được quan sát là đúng.
- Bảng quản lý nhân viên không co theo màn hình nhỏ: tại viewport `375×667`, nội dung trang rộng `1233 px` và các bảng rộng tới `1225 px`, gây tràn ngang.
- **SEC-01 — phát hiện XSS:** tìm khách hàng theo tên với payload thử an toàn `<script>alert(1)</script>` làm trình duyệt thực thi JavaScript và bật alert `1`. Cần escape đầu ra theo ngữ cảnh và retest sau khi sửa.

Hai hóa đơn thử nghiệm đã được tạo, chuyển sang trạng thái đã thanh toán để kiểm tra luồng nợ/thanh toán, rồi xóa cùng chi tiết hóa đơn. Nhân viên, khách hàng, điện kế và các bậc giá phát sinh trong lượt này cũng đã được dọn khỏi `testdien`; truy vấn xác nhận không còn các bản ghi test đó. MySQL được khởi động lại sau T101. Hai ảnh bằng chứng SEC-01 vẫn được nhúng trong Word.

## 6. Gợi ý chụp ảnh cho slide

Chụp ảnh màn hình trực tiếp từ trình duyệt sau khi chạy test; ảnh cần thấy được tên trang/chức năng và kết quả liên quan. Không cần đưa tất cả ảnh test case lên slide.

1. **Ảnh 1 – Trang đăng nhập:** tên “Tính Tiền Điện” và form đăng nhập.
2. **Ảnh 2 – Màn hình quản lý:** menu chức năng sau khi đăng nhập.
3. **Ảnh 3 – Ca thành công:** ví dụ khách hàng được tìm thấy hoặc thông tin khách hàng test sau khi thêm/sửa.
4. **Ảnh 4 – Ca lỗi/biên:** thông báo khi nhập sai mật khẩu, hoặc khi chỉ số cuối nhỏ hơn chỉ số đầu. Ưu tiên ảnh minh họa test case thất bại nếu phát hiện lỗi.
5. **Ảnh 5 – Tính hóa đơn:** chỉ số đầu/cuối, sản lượng kWh, các bậc giá và tổng tiền/thuế.
6. **Ảnh 6 – Theo dõi nợ/thanh toán:** trạng thái hóa đơn trước và sau khi xác nhận đã thanh toán; có thể dùng hai ảnh cạnh nhau.
7. **Ảnh 7 – Chi tiết/in hóa đơn:** trang chi tiết hoặc bản xem trước khi in.

Che hoặc làm mờ thông tin cá nhân không cần thiết (điện thoại, CCCD, địa chỉ thật), mật khẩu, cookie và thông tin kết nối. Gắn chú thích ảnh với mã test case, ví dụ “Hình 4. Kết quả BILL-02 – hệ thống từ chối chỉ số cuối nhỏ hơn chỉ số đầu”.

## 7. Đưa test case vào báo cáo Word

Trong Word, có thể trình bày mỗi test case theo bảng sau:

| Trường | Nội dung |
|---|---|
| Mã test case / Tên | Ví dụ: BILL-02 – Từ chối chỉ số cuối nhỏ hơn chỉ số đầu |
| Mục tiêu | Quy tắc/chức năng cần kiểm tra |
| Điều kiện trước | Trạng thái đăng nhập, dữ liệu và trang đang mở |
| Dữ liệu kiểm thử | Giá trị nhập, mã khách hàng/điện kế/hóa đơn |
| Các bước thực hiện | Đánh số từng thao tác để người khác chạy lại được |
| Kết quả mong đợi | Hành vi đúng theo yêu cầu |
| Kết quả thực tế | Ghi đúng nội dung nhìn thấy sau khi chạy |
| Trạng thái | Pass / Fail / Blocked |
| Bằng chứng | Tên ảnh chụp; mã lỗi nếu phát hiện |

Trong báo cáo, mô tả ngắn mục tiêu/phạm vi, môi trường test, kỹ thuật hộp đen sử dụng, bảng test case, tổng số Pass/Fail/Blocked và các lỗi quan trọng. Giữ nguyên mã test case giữa README, Word và chú thích ảnh để dễ đối chiếu.


C:\xampp\php\php.exe -S localhost:8000

Đăng nhập và màn hình chính — giới thiệu website và luồng truy cập.
Quản lý khách hàng hoặc điện kế — minh họa thao tác thêm/sửa/tìm kiếm.
Bảng giá điện — cho thấy dữ liệu giá hoặc một ca kiểm tra biên.
Lập hóa đơn — chụp dữ liệu đầu vào và kết quả tính tiền.
Thanh toán và công nợ — minh họa trạng thái trước/sau thanh toán hoặc kết quả tra cứu.
Một ca không đạt tiêu biểu — chọn lỗi nhìn thấy rõ trên giao diện, ghi mã test case và kết quả mong đợi/thực tế.
Một ca Blocked — nếu muốn giải thích T025 hoặc T052, chụp giao diện thể hiện không có chỗ nhập dữ liệu cần kiểm tra.
Ảnh bằng chứng SEC-01 — có thể dùng ảnh đã có trong báo cáo nếu ảnh đó rõ và phù hợp với nội dung thuyết trình.
C:\xampp\mysql\bin\mysqld.exe --defaults-file=C:\xampp\mysql\bin\my.ini --console
 
C:\xampp\mysql\bin\mysqld.exe --defaults-file=C:\xampp\mysql\bin\my.ini --console