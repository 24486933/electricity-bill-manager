<style>
    .manage-side {
        width: 90%;
        height: 200px;
        margin: 0 auto;
        position: relative;
        top: 40px;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .btn {
        width: 100%;
        height: 25%;
        border-radius: 10px;
        display: flex;
        justify-content: space-around;
        flex-direction: column;
        align-items: center;
    }


    .btn a {
        text-decoration: none;
        color: var(--text-color-bold);
        font-weight: bold;
        font-size: 20px;
    }

    span {
        position: relative;
        left: 80px;
    }

    .av {
        background-color: #333;
        width: 110px;
        height: 30px;
        border-radius: 5px;
        color: white;
        font-weight: bold;
        position: relative;
        left: 20px;
        top: 10px;
    }

    .av a {
        text-decoration: none;
        color: white;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<?php
session_start();


if (isset($_SESSION['id_nv'])) {
    ?> <button class="av"><a href="../controller/tiendien.php?act=quanly">Quay lại</a></button><br>
    <div class="manage-side">
        <div class="btn">

            <a href="../controller/tracuu.php?act=tracuukhachhang">Tra cứu khách hàng <span> |</span></a>
        </div>
        <div class="btn">
            <a href="../controller/tracuu.php?act=tracuudienke">Tra cứu điện kế <span> |</span>
        </div></a>

        <div class="btn">
            <a href="../controller/tracuu.php?act=tracuuhoadon">Tra cứu hóa đơn <span> |</span></a>
        </div>
        <div class="btn">
            <a href="../controller/theodoino.php?act=theodoino">Theo dõi nợ</a>
        </div>
    </div>
<?php } else {
    header('location: ../controller/nhanvien.php?act=login');
} ?>