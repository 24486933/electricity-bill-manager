<?php
session_start();

if (isset($_SESSION['id_nv'])) {
    ?>
    <?php
    if (isset($search_DH)) {
        if ($search_DH && !empty($search_DH)) {
            echo ' <table border="1" class="hd">
                    <tr>
                        <th>Mã hóa đơn</th>
                        <th>Người lập hóa đơn</th>
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
                    </tr>';
            foreach ($search_DH as $rows) {
                echo '<tr>
                        <td>' . $rows['mahd'] . '</td>'; ?>
                <?php
                $show_nv = check_Info_ById($rows['manv']);
                if (isset($show_nv) && !empty($show_nv)) {
                    $nguoilaphd = $show_nv['tennv'];
                } else {
                    $nguoilaphd = "";
                }
                ?>

                <?php echo '<td>' . $nguoilaphd . '</td>
                        <td>' . $rows['ky'] . '</td>
                        <td>' . $rows['tungay'] . '</td>
                        <td>' . $rows['denngay'] . '</td>
                        <td>' . $rows['chisodau'] . '</td>
                        <td>' . $rows['chisocuoi'] . '</td>
                        <td>' . $rows['tongthanhtien'] . ' VNĐ</td>
                        <td>' . $rows['ngaylaphd'] . '</td>';
                if ($rows['tinhtrang'] == 0) {
                    echo '<td>Chưa thanh toán</td>';
                    echo '<td><a style="color:red" href="../controller/tracuu.php?act=cthd&mahd=' . $rows['mahd'] . '">Xem chi tiết hóa đơn</a></td>';
                    echo '<td><a style="color:green" href="../controller/tiendien.php?act=in&mahd=' . $rows['mahd'] . '">In giấy báo điện</a></td>';
                    echo '<td><a name="hoanthanh"  href="../controller/tiendien.php?act=tinhdien&action=dathanhtoan&code=' . $rows['mahd'] . '">Đã thanh toán</a> </td>';
                } else {
                    echo '<td>Đã thanh toán</td>';
                    echo '<td><a style="color:red" href="../controller/tracuu.php?act=cthd&mahd=' . $rows['mahd'] . '">Xem chi tiết hóa đơn</a></td>';
                    echo '<td colspan="2"><a  style="color:green" href="../controller/tiendien.php?act=in&mahd=' . $rows['mahd'] . '">In hóa đơn</a> </td>';
                }
                echo '</tr> ';
            }
            echo '</table';
        } else {
            echo "Không tìm thấy hóa đơn trong CSDL.";
        }
    } else {
        echo '';
    }

    if (isset($search_KH_DK)) {
        if ($search_KH_DK && !empty($search_KH_DK)) {
            echo ' <table border="1" class="kh">
                <tr>
                    <th>Tên KH</th>
                    <th>Địa chỉ</th>
                    <th>Điện thoại</th>
                    <th>CMND</th>
                    <th>Xem điện kế</th>
                    <th>Hành động</th>
                </tr>';
            foreach ($search_KH_DK['khachhang'] as $rows) {
                echo "<tr>
                    <td>" . $rows['tenkh'] . "</td>
                    <td>" . $rows['diachi'] . "</td>
                    <td>" . $rows['dt'] . "</td>
                    <td>" . $rows['cccd'] . "</td>
                    <td><button class='xem' id='button_" . $rows['makh'] . "' onclick=\"showDienKe('" . $rows['makh'] . "')\">Xem</button></td>
                    <td><a href='../view/suakhachhang.php?makh=" . $rows['makh'] . "'><button class='xem'>Sửa</button></a>
                    <a href='../controller/xuly_xoaKH.php?makh=" . $rows['makh'] . "'><button class='xem'>Xóa</button></a></td>
                    </tr>";
                echo "<tr id='dienke_row_" . $rows['makh'] . "' style='display: none;'>
                    <td colspan='6'>
                        <div id='dienke_container_" . $rows['makh'] . "'>";
                if (isset($rows['dienke']) && !empty($rows['dienke'])) {
                    echo "<h2>Thông tin điện kế Mã khách hàng: " . $rows['makh'] . "</h2>
                        <table border='1' id='dienke_table_" . $rows['makh'] . "'>
                            <tr>
                                <th>Mã ĐK</th>
                                <th>Mã KH</th>
                                <th>Ngày sản xuất</th>
                                <th>Ngày lắp</th>
                                <th>Mô tả</th>
                                <th>Trạng thái</th>
                                <th colspan='2'>Chọn tool</th>
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
                        } else {
                            $status_dk = "Đã ngừng sử dụng";
                        }
                        echo "
                        <td>" . $status_dk . "</td>
                        <td>                                
                        <form method='post' action='../controller/dienke.php?act=suadienke'>
                        <input type='hidden'  name='madk' value='" . $dienke['madk'] . "'>
                        <button type='submit' class='sua' name='editDienKe'>Sửa</button>
                    </form>
                    
                        </td>";

                        if (kiemTraXoa($dienke['madk'])) {
                            echo "<td>Không thể xóa điện kế này do đã tồn tại hóa đơn. </td>";
                        } else {
                            echo "<td>
                            <form id='deleteForm' method='post' action=''>
                                <input type='hidden'  name='madk' value='" . $dienke['madk'] . "'>
                                <button type='submit' class='xoa' name='deleteDienKe' onclick='showConfirmation()'>Xóa</button>
                            </form>
                        </td>";
                        }
                        echo "
                        </tr>
                        ";
                    }
                    echo "</table>
                        <br>
                        <input type='submit' class='add' name='addDienKe' id='addDienKe' value='Thêm điện kế mới' onclick=\"addNewDienKe('" . $rows['makh'] . "')\">";
                } else {
                    echo "Khách hàng này chưa có điện kế";
                    echo " <br>
                <input type='submit' name='addDienKe' id='addDienKe' value='Thêm điện kế mới' onclick=\"addNewDienKe('" . $rows['makh'] . "')\">";
                }
                echo "</div>
                </td>
                </tr>";
            }
            echo '</table>';
        } else {
            echo '<span style="position:relative;left:400px">Không tìm thấy khách hàng trong CSDL.</span>';
        }
    }

    if (isset($search_KH_Name_DK)) {
        if ($search_KH_Name_DK && !empty($search_KH_Name_DK)) {
            echo ' <table  border="1" class="kh">
                <tr>
                    <th>Tên KH</th>
                    <th>Địa chỉ</th>
                    <th>Điện thoại</th>
                    <th>CMND</th>
                    <th>Xem điện kế</th>
                </tr>';
            foreach ($search_KH_Name_DK['khachhang'] as $rows) {
                echo "<tr>
                    <td>" . $rows['tenkh'] . "</td>
                    <td>" . $rows['diachi'] . "</td>
                    <td>" . $rows['dt'] . "</td>
                    <td>" . $rows['cccd'] . "</td>
                    <td><button class='xem' id='button_" . $rows['makh'] . "' onclick=\"showDienKe('" . $rows['makh'] . "')\">Xem</button></td>
                    </tr>";
                echo "<tr id='dienke_row_" . $rows['makh'] . "' style='display: none;'>
                    <td colspan='6'>
                        <div id='dienke_container_" . $rows['makh'] . "'>";
                if (isset($rows['dienke']) && !empty($rows['dienke'])) {
                    echo "<h2>Thông tin điện kế Mã khách hàng: " . $rows['makh'] . "</h2>
                        <table border='1' id='dienke_table_" . $rows['makh'] . "'>
                            <tr>
                                <th>Mã ĐK</th>
                                <th>Mã KH</th>
                                <th>Ngày sản xuất</th>
                                <th>Ngày lắp</th>
                                <th>Mô tả</th>
                                <th>Trạng thái</th>
                                <th colspan='2'>Chọn tool</th>
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
                        } else {
                            $status_dk = "Đã ngừng sử dụng";
                        }
                        echo "
                        <td>" . $status_dk . "</td>
                        <td>                                
                        <form method='post' action='../controller/dienke.php?act=suadienke'>
                        <input type='hidden' name='madk' value='" . $dienke['madk'] . "'>
                        <button type='submit' class='sua' name='editDienKe'>Sửa</button>
                    </form>
                    
                        </td>";

                        if (kiemTraXoa($dienke['madk'])) {
                            echo "<td>Không thể xóa điện kế này do đã tồn tại hóa đơn. </td>";
                        } else {
                            echo "<td>
                            <form id='deleteForm' method='post' action=''>
                                <input type='hidden' name='madk' value='" . $dienke['madk'] . "'>
                                <button class='xoa' type='submit' name='deleteDienKe' onclick='showConfirmation()'>Xóa</button>
                            </form>
                        </td>";
                        }
                        echo "
                        </tr>
                        ";
                    }
                    echo "<table> 
                <br>
                <input type='submit'  name='addDienKe' class='add' id='addDienKe' value='Thêm điện kế mới' onclick=\"addNewDienKe('" . $rows['makh'] . "')\">";
                } else {
                    echo "Khách hàng này chưa có điện kế";
                    echo " <br>
                <input type='submit' class='add' name='addDienKe' id='addDienKe' value='Thêm điện kế mới' onclick=\"addNewDienKe('" . $rows['makh'] . "')\">";
                }
                echo "</div>
                </td>
                </tr>";
            }
            echo '</table>';
        } else {
            echo '<span style="position:relative;left:400px">Không tìm thấy khách hàng trong CSDL.</span>';
        }
    }
?>
<?php

} else {
    header('location: ../controller/nhanvien.php?act=login');
} ?>