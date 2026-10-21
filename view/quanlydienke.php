<link rel="stylesheet" href="../assets/css/quanlydienke.css?v=4">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<?php
session_start();
if (isset($_SESSION['id_nv'])) {
    ?>
    <?php
    if (isset($_GET['size'])) {
        $erro_size = $_GET['size'];
        if ($erro_size <= 0) {
            header('location: ../controller/tiendien.php?act=quanlydienke&p=1&size=5');
        }
    }
    ?>
    <?php
    if (isset($_GET['p'])) {
        $erro_page = $_GET['p'];
        if ($erro_page <= 0) {
            header('location: ../controller/tiendien.php?act=quanlydienke&p=1&size=5');
        }
    }
    ?>
    <link rel="stylesheet" href="../assets/css/quanlydien.css">
    <button class="button"><a href="../controller/tiendien.php?act=quanly">Quay lại</a></button>

    <h2>Tìm kiếm khách hàng</h2>
    <form id="searchForm" class="ffff">
        <div class="aa">
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
    </form>
    <div id="searchResults"></div>

    <h2>Thông tin khách hàng</h2>
    <?php
    if (isset($data)) {
        if ($data && !empty($data)) {
            ?>
            <?php

            $listsPerPage = isset($_GET['size']) ? (int) $_GET['size'] : 10;
            $currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;
            $startIndex = ($currentPage - 1) * $listsPerPage; // tính sl kh ở mỗi trang và trang htai
            $filteredCustomers = array_filter($data['khachhang'], function ($khachhang) {
                return isset ($khachhang['makh']);
            });

            // xếp mảng giảm dần theo mã
            usort($filteredCustomers, function ($a, $b) {
                return $b['makh'] <=> $a['makh'];
            });

            // lấy sl dữ liệu của khách hàng
            $totalCustomers = count($filteredCustomers);
            // tính trang
            $totalPages = ceil($totalCustomers / $listsPerPage);
            // hiện dl cho trang hiện tại
            $listsToShow = array_slice($filteredCustomers, $startIndex, $listsPerPage);
            ?>
            <table>
                <tr>
                    <th>Mã KH</th>
                    <th>Tên KH</th>
                    <th>Địa chỉ</th>
                    <th>Điện thoại</th>
                    <th>CMND</th>
                    <th>Xem điện kế</th>
                </tr>
                <?php
                foreach ($listsToShow as $khachhang) {
                    echo "<tr>
                    <td>" . $khachhang['makh'] . "</td>
                    <td>" . $khachhang['tenkh'] . "</td>
                    <td>" . $khachhang['diachi'] . "</td>
                    <td>" . $khachhang['dt'] . "</td>
                    <td>" . $khachhang['cccd'] . "</td>
                    <td><button class='xem' id='button_" . $khachhang['makh'] . "' onclick=\"showDienKe('" . $khachhang['makh'] . "')\">Xem</button></td>
                    </tr>";
                    echo "<tr id='dienke_row_" . $khachhang['makh'] . "' style='display: none;'>
                    <td colspan='6'>
                        <div id='dienke_container_" . $khachhang['makh'] . "'>";
                    if (isset($khachhang['dienke']) && !empty($khachhang['dienke'])) {
                        echo "<h2>Thông tin điện kế Mã khách hàng: " . $khachhang['makh'] . "</h2>
                        <table id='dienke_table_" . $khachhang['makh'] . "'>
                            <tr>
                                <th>Mã ĐK</th>
                                <th>Mã KH</th>
                                <th>Ngày sản xuất</th>
                                <th>Ngày lắp</th>
                                <th>Mô tả</th>
                                <th>Trạng thái</th>
                                <th colspan='2'>Chọn tool</th>
                            </tr>";
                        foreach ($khachhang['dienke'] as $dienke) {
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
                            <button class='sua' type='submit' name='editDienKe'>Sửa</button>
                    </form>
                    
                        </td>";
                            if (kiemTraXoa($dienke['madk'])) {
                                echo "<td>Không thể xóa điện kế này do đã tồn tại hóa đơn. </td>";
                            } else {
                                echo '<td><button class="xoa"> <a style="text-decoration: none;font-weight: bold;color: white;" href="../controller/dienke.php?act=xoadienke&madk='.$dienke['madk'].'" onclick="return confirm(\'Bạn có chắc chắn muốn xóa không?\')">Xóa</a></button></td>';
                            }
                            echo "
                        </tr>
                        ";
                        }
                        echo "</table>
                        <br>
                        <input type='submit' class='add' name='addDienKe' id='addDienKe' value='Thêm điện kế mới' onclick=\"addNewDienKe('" . $khachhang['makh'] . "')\">";
                    } else {
                        echo "Khách hàng này chưa có điện kế";
                        echo " <br>
                <input type='submit' class='add' name='addDienKe' id='addDienKe' value='Thêm điện kế mới' onclick=\"addNewDienKe('" . $khachhang['makh'] . "')\">";
                    }
                    echo "</div>
                </td>
                </tr>";
                }
                ?>
            </table>
            <?php
            // hiện các liên kết phân trang
            if ($totalPages > 1) {
                echo '<div></div>
                <div>';
                if ($currentPage > 1) {
                    echo '<button class="navi selected"><a href="?act=quanlydienke&p=' . ($currentPage - 1) . '&size=' . $listsPerPage . '">&lt;</a></button>';
                }
                // Tính & hiện các liên kết phân trang
                $start = max(1, $currentPage - 1);
                $end = min($totalPages, $start + 3);
                for ($i = $start; $i <= $end; $i++) {
                    if ($i == $currentPage) {
                        echo '<button class="navi selected chosse"><span >' . $i . '</span></button>';
                    } else {
                        echo '<button class="navi selected"><a href="?act=quanlydienke&p=' . $i . '&size=' . $listsPerPage . '">' . $i . '</a></button>';
                    }
                }
                if ($currentPage < $totalPages) {
                    echo '<button class="navi selected"><a href="?act=quanlydienke&p=' . ($currentPage + 1) . '&size=' . $listsPerPage . '">&gt;</a></button>';
                }
                echo '</div>';
            }

        ?>
        <?php
        } else {
            echo 'Không có khách hàng - điện kế nào';
        }
    }
    ?>

    <script>
        function addNewDienKe(makh) {
            localStorage.setItem('selectedMakh', makh);
            window.location.href = "../controller/dienke.php?act=themdienke";
        }
    </script>
    <script src="../assets/js/dienkekh.js"></script>
    <script src="../assets/js/autosubmitsearch.js"></script>
    <script src="../assets/js/timkhachhang.js"></script>

<?php } else {
    header('location: ../controller/nhanvien.php?act=login');
} ?>