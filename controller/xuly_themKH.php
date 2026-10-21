<?php
// Include các file cần thiết

require_once "../model/KhachHang_Model.php";
require_once "../model/KhachHang.php";
require_once('../model/config.php');

// Kiểm tra nếu form đã được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $makh = $_POST['makh'];
    $tenkh = $_POST['tenkh'];
    $diachi = $_POST['diachi'];
    $dt = $_POST['dt'];
    $cccd = $_POST['cccd'];

    // Kiểm tra ràng buộc dữ liệu
    if (strlen($makh) != 13) {
        echo "Mã khách hàng phải có đủ 13 số.";
        exit();
    }

    if (empty($tenkh) || empty($diachi) || empty($dt) || empty($cccd)) {
        echo "Vui lòng nhập đầy đủ thông tin.";
        exit();
    }

    if(strlen($tenkh) > 50){
        echo "Độ dài tên khách hàng vượt quá độ lớn cho phép [Tối đa 50 ký tự].";
        exit();
    }
    if(strlen($diachi) > 100){
        echo "Độ dài địa chỉ vượt quá độ lớn cho phép [Tối đa 100 ký tự].";
        exit();
    }
    if (!kiem_tra_so_dien_thoai_user($dt)) {
        echo "Số điện thoại không hợp lệ, nhập đúng định dạng và theo đầu số các nhà mạng.";
        exit();
    }

    if (!is_numeric($cccd) || strlen($cccd) != 12) {
        echo "CCCD không hợp lệ.";
        exit();
    }
   

    // Tạo đối tượng KhachHang_Model
    $khachhangModel = new KhachHang_Model();

    // Thêm khách hàng vào CSDL
    $khachhangModel->addKhachHang($makh, $tenkh, $diachi, $dt, $cccd);

    // Hiển thị thông báo thành công
   
    echo "<script>alert('Thêm khách hàng $makh thành công!');window.location.href = '../controller/khachhang.php';</script>";  

    // Trong trang xuly_themKH.php, sau khi thêm thành công



}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Thông báo</title>
</head>
<body>
    <br>
    <a href="<?php echo htmlspecialchars($_SERVER['HTTP_REFERER']); ?>">Quay lại</a>
</body>
</html>
