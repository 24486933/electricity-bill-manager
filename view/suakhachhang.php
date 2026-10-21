<style>
    body {
        font-family: Arial, sans-serif;
    }

    form {
        width: 90%;
        padding: 20px;
        margin: 0 auto;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    label {
        color: #333;
        display: block;
        margin-bottom: 10px;
    }

    input[type="email"],
    input[type="password"],
    input[type="text"],
    input[type="number"],
    select {
        width: calc(100% - 10px);
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    input[type="submit"] {
        background-color: #333;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #333;
    }

    button a {
        text-decoration: none;
        color: #333;
    }

    button {
        background-color: #f0f0f0;
        border: none;
        color: #333;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 4px;
        transition-duration: 0.4s;
    }

    button:hover {
        background-color: #ddd;
    }
</style>
<?php
session_start();

if (isset($_SESSION['id_nv'])) {
    ?>
    <?php
    // Include các file cần thiết
    require_once "../model/KhachHang_Model.php";
    require_once ('../model/config.php');

    // Kiểm tra nếu có mã khách hàng được truyền từ trang quản lý khách hàng
    if (isset($_GET['makh'])) {
        // Lấy mã khách hàng từ URL
        $makh = $_GET['makh'];

        // Tạo đối tượng KhachHang_Model
        $khachhangModel = new KhachHang_Model();

        // Lấy thông tin khách hàng từ CSDL
        $khachhang = $khachhangModel->getKhachHangByMaKH($makh);

        // Kiểm tra nếu khách hàng tồn tại
        if ($khachhang) {
            // Gán các giá trị vào biến
            $tenkh = $khachhang['tenkh'];
            $diachi = $khachhang['diachi'];
            $dt = $khachhang['dt'];
            $cccd = $khachhang['cccd'];
        } else {
            // Hiển thị thông báo nếu không tìm thấy khách hàng
            echo "Không tìm thấy thông tin khách hàng.";
            exit();
        }
    } else {
        // Hiển thị thông báo nếu không có mã khách hàng được truyền
        echo "Mã khách hàng không được cung cấp.";
        exit();
    }

    ?>

    <!DOCTYPE html>
    <html>

    <head>
        <title>Sửa thông tin khách hàng</title>
    </head>

    <body>
        <h1>Sửa thông tin khách hàng</h1>
        <form action="../controller/xuly_suaKH.php" method="post">
            <!-- Hiển thị mã khách hàng -->
            Mã KH: <?php echo $makh; ?><br>
            <!-- Sử dụng input hidden để truyền mã khách hàng -->

            <input type="hidden" name="makh" value="<?php echo $makh; ?>"><br>
            Tên KH: <input type="text" name="tenkh" value="<?php echo $tenkh; ?>"><br>
            Địa chỉ: <input type="text" name="diachi" value="<?php echo $diachi; ?>"><br>
            Điện thoại: <input type="text" name="dt" value="<?php echo $dt; ?>"><br>
            CCCD: <input type="text" name="cccd" value="<?php echo $cccd; ?>"><br>
            <input type="submit" value="Sửa">
        </form>
        <br>
        <button><a href="../controller/khachhang.php?act=quanlykhachhang">Quay lại</a></button>
    </body>

    </html>

<?php } else {
    header('location: ../controller/nhanvien.php?act=login');
} ?>