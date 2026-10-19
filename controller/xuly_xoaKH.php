<?php
// Include các file cần thiết
require_once "../model/KhachHang_Model.php";
require_once('../model/config.php');

// Kiểm tra xem có tham số `makh` được gửi từ trang trước không
if(isset($_GET['makh'])) {
    // Lấy mã khách hàng cần xóa từ tham số truyền vào
    $makh = $_GET['makh'];

    // Tạo đối tượng KhachHang_Model
    $khachhangModel = new KhachHang_Model();

    // Thực hiện xóa khách hàng
    $khachhangModel->deleteKhachHang($makh);

    // Hiển thị thông báo xóa thành công và chuyển hướng người dùng về trang trước đó
    echo "<script>alert('Xóa khách hàng $makh thành công!'); window.location.href = document.referrer;</script>";
} else {
    // Nếu không có tham số `makh`, hiển thị thông báo lỗi
    echo "<script>alert('Không có mã khách hàng được chỉ định để xóa!'); window.location.href = document.referrer;</script>";
}
?>
