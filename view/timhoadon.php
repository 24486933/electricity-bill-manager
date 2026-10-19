<link rel="stylesheet" href="../assets/css/quanlyhoadon.css?v=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .text {
        width: 65%;
        height: 40px;
        text-indent: 10px;
        position: relative;
        left: 60px;
    }

    .subb {
        width: 110px;
        height: 40px;
        border-radius: 5px;
        background-color: #333;
        color: white;
        font-weight: bold;
        margin-left: 10px;
        position: relative;
        left: 20px;
    }

    .out {
        position: relative;
        top: 100px;
        width: 150px;
        height: 40px;
        border-radius: 5px;
        background-color: #333;
        color: white;
        font-weight: bold;
        margin-left: 10px;
    }

    .out a {
        text-decoration: none;
        color: white;
    }

    label {
        position: relative;
        left: 40px;
    }
</style>
<?php

if (isset($_SESSION['id_nv'])) {
    ?>
    <div>
        <h2>Tìm kiếm hóa đơn</h2>
        <form action="" method="post">
            <label for="mahd">Mã hóa đơn:</label>
            <input class="text" type="text" id="mahd" name="mahd" placeholder="Nhập mã hóa đơn...">
            <input class="subb" type="submit" name="SearchDH" value="Tìm kiếm">
        </form>
    </div>
    <?php
    if (isset($search_DH)) {
        if ($search_DH && !empty($search_DH)) {
            echo ' <table border="1">
                    <tr>
                        <th>Mã hóa đơn</th>
                        <th>Mã nhân viên</th>
                        <th>Kỳ</th>
                        <th>Từ ngày</th>
                        <th>Đến ngày</th>
                        <th>Chỉ số đầu</th>
                        <th>Chỉ số cuối</th>
                        <th>Tổng thành tiền</th>
                        <th>Ngày lập hóa đơn</th>
                        <th>Tình trạng</th>
                        <th>Chi tiết</th>
                        <th colspan=2>Công cụ</th>
                    </tr>
                    <tr>
                        <td>' . $search_DH['mahd'] . '</td>
                        <td>' . $search_DH['manv'] . '</td>
                        <td>' . $search_DH['ky'] . '</td>
                        <td>' . $search_DH['tungay'] . '</td>
                        <td>' . $search_DH['denngay'] . '</td>
                        <td>' . $search_DH['chisodau'] . '</td>
                        <td>' . $search_DH['chisocuoi'] . '</td>
                        <td>' . $search_DH['tongthanhtien'] . ' VNĐ</td>
                        <td>' . $search_DH['ngaylaphd'] . '</td>';
            if ($search_DH['tinhtrang'] == 0) {
                echo '<td>Chưa thanh toán</td>';
                echo '<td><a style="color:red;" href="../controller/tracuu.php?act=cthd&mahd=' . $search_DH['mahd'] . '">Xem chi tiết hóa đơn</a></td>';
                echo '<td><a style="color:green;" href="../controller/tiendien.php?act=in&mahd=' . $search_DH['mahd'] . '">In giấy báo điện</a></td>';
                echo '<td><a name="hoanthanh"  href="../controller/tiendien.php?act=tinhdien&action=dathanhtoan&code=' . $search_DH['mahd'] . '">Đã thanh toán</a> </td>';
            } else {
                echo '<td>Đã thanh toán</td>';
                echo '<td><a style="color:red;" href="../controller/tracuu.php?act=cthd&mahd=' . $search_DH['mahd'] . '">Xem chi tiết hóa đơn</a></td>';
                echo '<td><a style="color:green;" href="../controller/tiendien.php?act=in&mahd=' . $search_DH['mahd'] . '">In hóa đơn</a> </td>';
            }
            echo '</tr> 
                </table';
        } else {
            echo "Không tìm thấy hóa đơn trong CSDL.";
        }
    } else {
        echo '';
    }
    ?>
    <br>
    <button class="out"><a href="../controller/tiendien.php?act=quanly">Thoát</a></button>

<?php } else {
    header('location: ../controller/nhanvien.php?act=login');
} ?>