<?php
// Include các file cần thiết
require_once "../model/KhachHang_Model.php";
require_once('../model/config.php');
require_once "../model/KhachHang.php";

// Kiểm tra nếu form đã được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $makh = $_POST['makh'];
    $tenkh = $_POST['tenkh'];
    $diachi = $_POST['diachi'];
    $dt = $_POST['dt'];
    $cccd = $_POST['cccd'];
    $hasError=true;
    if (empty($tenkh) || empty($diachi) || empty($dt) || empty($cccd)) {
        echo '<script>alert("Vui lòng nhập đầy đủ thông tin.");</script>';
        $hasError = false;    }

    if (!kiem_tra_so_dien_thoai_user($dt)) {
        echo '<script>alert("Số điện thoại không hợp lệ, nhập đúng định dạng và theo đầu số các nhà mạng.");</script>';
        $hasError = false;    
    }

    if (!is_numeric($cccd) || strlen($cccd) != 12) {
        echo '<script>alert("CCCD không hợp lệ.");</script>';
        $hasError = false;    
    }
    if(strlen($tenkh) > 50){
        echo '<script>alert("Độ dài tên khách hàng vượt quá độ lớn cho phép [Tối đa 50 ký tự].");</script>';
        $hasError = false;    
    }
    if(strlen($diachi) > 100){
        echo '<script>alert("Độ dài địa chỉ vượt quá độ lớn cho phép [Tối đa 100 ký tự].");</script>';
        $hasError = false;    
    }
    if($hasError == true){
    // Tạo đối tượng KhachHang_Model
    $khachhangModel = new KhachHang_Model();

    // Thực hiện cập nhật thông tin khách hàng trong CSDL
    $result = $khachhangModel->updateKhachHang($makh, $tenkh, $diachi, $dt, $cccd);

    // Kiểm tra kết quả cập nhật
    if ($result) {
        session_start();
        
        echo "<script>alert('Sửa khách hàng có mã : $makh thành công!');window.location.href = '../controller/khachhang.php';</script>";  
    } else {
        echo "Cập nhật thông tin khách hàng không thành công.";
    }
}else{
    echo "<script>window.location.href = '../controller/khachhang.php?act=quanlykhachhang';</script>";  

}
} else {
    // Hiển thị thông báo nếu không có dữ liệu được gửi từ form
    echo "Dữ liệu không được gửi từ form.";
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
