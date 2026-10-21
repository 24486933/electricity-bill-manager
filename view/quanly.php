<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/quanly.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php
    if (isset($_SESSION['id_nv'])) {
        ?>
        <div class="content">
            <div class="side-bar">
                <img src="../assets/user.jpg" alt="" width="40px" class="img">
                <b>Xin chào, <?php echo $_SESSION['name'] ?> </b>
            </div>
            <div class="manage">
                <button class="button"><a href="../controller/nhanvien.php?act=logout">Đăng
                        xuất</a></button>
                <span>MÀN HÌNH QUẢN LÝ</span>
                <div class="manage-side">
                    <button class="btn">
                        <img src="../assets/admin.webp" alt="" width="40px">
                        <a href="../controller/tiendien.php?act=quanlyhoadon">Quản lý hóa đơn <i
                                class="fa-solid fa-gear"></i></a>
                    </button>
                    <button class="btn">
                        <img src="../assets/admin.webp" alt="" width="40px">
                        <a href="../controller/giadien.php?act=quanlygiadien">Quản lý giá điện <i
                                class="fa-solid fa-gear"></i></a>
                    </button>
                    <button class="btn">
                        <img src="../assets/admin.webp" alt="" width="40px">
                        <a href="../controller/dienke.php?act=quanlydienke">Quản lý điện kế <i
                                class="fa-solid fa-gear"></i></a>
                    </button>
                    <button class="btn">
                        <img src="../assets/admin.webp" alt="" width="40px">
                        <a href="../controller/khachhang.php?act=quanlykhachhang">Quản lý khách hàng <i
                                class="fa-solid fa-gear"></i></a>
                    </button>
                    <button class="btn">
                        <img src="../assets/admin.webp" alt="" width="40px">
                        <a href="../controller/tracuu.php?act=tracuu">Tra cứu <i class="fa-solid fa-gear"></i></a>
                    </button>
                    <button class="btn">
                        <img src="../assets/admin.webp" alt="" width="40px">
                        <a href="../controller/nhanvien.php?act=quanlynhanvien">Quản lý nhân viên <i
                                class="fa-solid fa-gear"></i></a>
                    </button>
                </div>
                <?php
    } else {
        header('location: ../controller/nhanvien.php?act=login');
    }
    ?>
        </div>
    </div>
</body>
</html>