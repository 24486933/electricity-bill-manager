<link rel="stylesheet" href="../assets/css/quanlydienke.css?v=5">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .out {
        position: relative;
        top: 70px;
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

    .b {
        position: relative;
        left: 70px;
    }
</style>
<?php
if (isset($_SESSION['id_nv'])) {
    ?>
    <div class="tim">
        <h2>Tìm kiếm khách hàng</h2>
        <form action="" method="post" class="ffff">
            <div class="aa" style="width: 300px;">
                <b>Tìm theo mã</b>
                <input class="cir" name="searchKH" type="radio" value="0" onclick="chosseSearch_KH();" <?php if (!isset($search_KH_by_Name))
                    echo "checked"; ?>>
            </div>
            <div class="bb">
                <b>Tìm theo tên</b>
                <input class="cir" name="searchKH" type="radio" value="1" onclick="chosseSearch_KH();" <?php if (isset($search_KH_by_Name))
                    echo "checked"; ?>>
            </div>

            <div id="search_By_ID" style="display: <?php if (!isset($search_KH_by_Name))
                echo "block";
            else
                echo "none"; ?>;">
                <input type="text" id="makh" name="makh" class="aaaa" placeholder="Nhập mã khách hàng...">
            </div>
            <div id="search_By_Name" style="display: <?php if (isset($search_KH_by_Name))
                echo "block";
            else
                echo "none"; ?>;">
                <input type="text" id="nameKH" name="nameKH" class="aaaa" placeholder="Nhập tên khách hàng...">
            </div>
            <input type="submit" class='xem' name="searchCustomer" value="Tìm kiếm" style="position:relative;left:390px; ">
        </form>
    </div>


    <?php
    if (isset($search_KH)) {
        if ($search_KH && !empty($search_KH)) {
            echo '<b class="b">Tìm thấy khách hàng có mã: ' . $makh . '</b>';

            echo ' <table border="1">
                <tr>
                    <th>Tên KH</th>
                    <th>Địa chỉ</th>
                    <th>Điện thoại</th>
                    <th>CMND</th>
                    <th>Xem điện kế</th>
                </tr>';
            foreach ($search_KH['khachhang'] as $rows) {
                echo "<tr>
                    <td>" . $rows['tenkh'] . "</td>
                    <td>" . $rows['diachi'] . "</td>
                    <td>" . $rows['dt'] . "</td>
                    <td>" . $rows['cccd'] . "</td>
                    <td><button  class='xem' id='button_" . $rows['makh'] . "' onclick=\"showDienKe('" . $rows['makh'] . "')\">Xem</button></td>
                    </tr>";
                echo "<tr id='dienke_row_" . $rows['makh'] . "' style='display: none;'>
                    <td colspan='6'>
                        <div id='dienke_container_" . $rows['makh'] . "'>";
                if (isset($rows['dienke'])) {
                    echo "<h2>Thông tin điện kế Mã khách hàng: " . $rows['makh'] . "</h2>
                    <form id='hoadon' method='post' action='../controller/tiendien.php?act=tinhdien' onsubmit='return kiemTraChon()'> 
                        <table border='1' id='dienke_table_" . $rows['makh'] . "'>
                            <tr>
                                <th>Mã ĐK</th>
                                <th>Mã KH</th>
                                <th>Ngày sản xuất</th>
                                <th>Ngày lắp</th>
                                <th>Mô tả</th>
                                <th>Trạng thái</th>
                                <th>Chọn để lập hóa đơn</th>
                            </tr>";
                    foreach ($rows['dienke'] as $dienke) {
                        echo "<tr>
                        <td>" . $dienke['madk'] . "</td>
                        <td>" . $dienke['makh'] . "</td>
                        <td>" . $dienke['ngaysx'] . "</td>
                        <td>" . $dienke['ngaylap'] . "</td>
                        <td>" . $dienke['mota'] . "</td>";
                        if ($dienke['trangthai'] == 1) {
                            $status_dk = "Còn sử dụng";
                            echo "<td>" . $status_dk . "</td>
                            <td><input type='radio' name='selected_id'/>
                            </td>";
                        } else {
                            $status_dk = "Đã ngừng sử dụng";
                            echo "<td>" . $status_dk . "</td>
                            <td>Không thể lập hóa đơn cho điện kế này</td>";
                        }
                        echo "
                        </tr>
                        ";
                    }
                    echo "</table>
                        <input type='submit'  class='add' name='submit_button' id='submit_button' value='Lập hóa đơn'>
                        </form>";
                }
                echo "</div>
                </td>
                </tr>";
            }
            echo '</table>';
        } else {
            echo '<b class="b"> Không tìm thấy khách hàng có mã ' . $makh . ' trong CSDL</b>';
        }

    }
    ?>

    <?php
    if (isset($search_KH_by_Name)) {
        if ($search_KH_by_Name && !empty($search_KH_by_Name)) {
            echo '<b class="b">Tìm thấy các khách hàng có tên: ' . $tenkh . '</b>';
            echo ' <table>
                <tr>
                    <th>Tên KH</th>
                    <th>Địa chỉ</th>
                    <th>Điện thoại</th>
                    <th>CMND</th>
                    <th>Xem điện kế</th>
                </tr>';
            foreach ($search_KH_by_Name['khachhang'] as $rows) {
                echo "<tr>
                    <td>" . $rows['tenkh'] . "</td>
                    <td>" . $rows['diachi'] . "</td>
                    <td>" . $rows['dt'] . "</td>
                    <td>" . $rows['cccd'] . "</td>
                    <td><button  class='xem' id='button_" . $rows['makh'] . "' onclick=\"showDienKe('" . $rows['makh'] . "')\">Xem</button></td>
                    </tr>";
                echo "<tr id='dienke_row_" . $rows['makh'] . "' style='display: none;'>
                    <td colspan='6'>
                        <div id='dienke_container_" . $rows['makh'] . "'>";
                if (isset($rows['dienke'])) {
                    echo "<h2>Thông tin điện kế Mã khách hàng: " . $rows['makh'] . "</h2>
                    <form id='hoadon' method='post' action='../controller/tiendien.php?act=tinhdien' onsubmit='return kiemTraChon()'> 
                        <table id='dienke_table_" . $rows['makh'] . "'>
                            <tr>
                                <th>Mã ĐK</th>
                                <th>Mã KH</th>
                                <th>Ngày sản xuất</th>
                                <th>Ngày lắp</th>
                                <th>Mô tả</th>
                                <th>Trạng thái</th>
                                <th>Chọn để lập hóa đơn</th>
                            </tr>";
                    foreach ($rows['dienke'] as $dienke) {
                        echo "<tr>
                        <td>" . $dienke['madk'] . "</td>
                        <td>" . $dienke['makh'] . "</td>
                        <td>" . $dienke['ngaysx'] . "</td>
                        <td>" . $dienke['ngaylap'] . "</td>
                        <td>" . $dienke['mota'] . "</td>";
                        if ($dienke['trangthai'] == 1) {
                            $status_dk = "Còn sử dụng";
                            echo "<td>" . $status_dk . "</td>
                            <td><input type='radio' name='selected_id'/>
                            </td>";
                        } else {
                            $status_dk = "Đã ngừng sử dụng";
                            echo "<td>" . $status_dk . "</td>
                            <td>Không thể lập hóa đơn cho điện kế này</td>";
                        }
                        echo "
                        </tr>
                        ";
                    }
                    echo "</table>
                        <input type='submit'  class='add' name='submit_button' id='submit_button' value='Lập hóa đơn'>
                        </form>";
                }
                echo "</div>
                </td>
                </tr>";
            }
            echo '</table>';
        } else {
            echo '<b class="b">Không tìm thấy khách hàng có tên ' . $tenkh . ' trong CSDL</b>';
        }

    }
    ?>
    <br>
    <button class="out"><a href="../controller/tiendien.php?act=quanly">Thoát</a></button>

    <script src="../assets/js/dienkekh.js?v=1"></script>
    <script src="../assets/js/timkhachhang.js"></script>

<?php } else {
    header('location: ../controller/nhanvien.php?act=login');
} ?>