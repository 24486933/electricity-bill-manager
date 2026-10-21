<link rel="stylesheet" href="../assets/css/quanlydienke.css?v=5">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    h1 {
        margin: 10px auto;
        text-align: center;
    }

    .fafawf {
        background-color: rgb(31, 61, 87);
        width: 100px;
        height: 30px;
        border-radius: 5px;
    }

    .fafawf a {
        position: relative;
        left: -5px;
        text-decoration: none;
    }
</style>
<?php
session_start();

if (isset($_SESSION['id_nv'])) {
    ?>
    <button class="button"><a href="../controller/tiendien.php?act=quanly">Quay lại</a></button>

    <div>
        <form action="../view/themkhachhang.php" method="post">
            <input type="submit" value="Thêm khách hàng" class="button"
                style="color:white ; font-weight: bold; margin-top:10px; width:170px;cursor:pointer">
        </form>
    </div>

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

    <?php
    if (isset($khachhangs)) {
        $listsPerPage = isset($_GET['size']) ? (int) $_GET['size'] : 10;
        $currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;
        $startIndex = ($currentPage - 1) * $listsPerPage; // tính sl kh ở mỗi trang và trang htai
        $filteredCustomers = array_filter($khachhangs, function ($khachhang) {
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

        <h1>Danh sách khách hàng</h1>
        <table border='1' style="border-collapse: collapse">
            <tr>
                <th>Mã KH</th>
                <th>Tên KH</th>
                <th>Địa chỉ</th>
                <th>Điện thoại</th>
                <th>CCCD</th>
                <th colspan="2">Hành động</th>
            </tr>
            <?php foreach ($listsToShow as $khachhang): ?>
                <tr>
                    <td><?php echo $khachhang['makh']; ?></td>
                    <td><?php echo $khachhang['tenkh']; ?></td>
                    <td><?php echo $khachhang['diachi']; ?></td>
                    <td><?php echo $khachhang['dt']; ?></td>
                    <td><?php echo $khachhang['cccd']; ?></td>
                    <td>
                        <button class="fafawf">
                            <a class="sua" href="../view/suakhachhang.php?makh=<?php echo $khachhang['makh']; ?>">Sửa</a>
                        </button>
                    </td>
                    <?php if (!checkXoaKH($khachhang['makh'])) { ?>
                        <td><button class="xoa"> <a style="text-decoration: none;font-weight: bold;color: white;" href="../controller/xuly_xoaKH.php?makh=<?php echo $khachhang['makh']; ?>"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này không?')">Xóa</a></button></td>
                    <?php } else { ?>
                        <td>Không thể xóa khách hàng do đã tồn tại điện kế/hóa đơn.</td>
                    <?php } ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
    }
    // hiện các liên kết phân trang
    if ($totalPages > 1) {
        echo '<div></div>
                <div>';
        if ($currentPage > 1) {
            echo '<button class="navi selected"><a href="?act=quanlykhachhang&p=' . ($currentPage - 1) . '&size=' . $listsPerPage . '">&lt;</a></button>';
        }
        // Tính & hiện các liên kết phân trang
        $start = max(1, $currentPage - 1);
        $end = min($totalPages, $start + 3);
        for ($i = $start; $i <= $end; $i++) {
            if ($i == $currentPage) {
                echo '<button class="navi selected chosse"><span >' . $i . '</span></button>';
            } else {
                echo '<button class="navi selected"><a href="?act=quanlykhachhang&p=' . $i . '&size=' . $listsPerPage . '">' . $i . '</a></button>';
            }
        }
        if ($currentPage < $totalPages) {
            echo '<button class="navi selected"><a href="?act=quanlykhachhang&p=' . ($currentPage + 1) . '&size=' . $listsPerPage . '">&gt;</a></button>';
        }
        echo '</div>';
    }

    ?>
    
    <script src="../assets/js/dienkekh.js"></script>
    <script src="../assets/js/timkhachhang.js"></script>
    <script src="../assets/js/autosubmitsearch.js"></script>
    <script>
        function showConfirmation() {
            if (confirm("Bạn có chắc chắn muốn xóa không?")) {
                // Nếu xác nhận, thay đổi giá trị action và submit form
                document.getElementById("deleteForm").action = "../controller/dienke.php?act=xoadienke";
                document.getElementById("deleteForm").submit();
            } else {

                event.preventDefault();
                return false;
            }
        }
    </script>
    <script>
        function addNewDienKe(makh) {
            localStorage.setItem('selectedMakh', makh);
            window.location.href = "../controller/dienke.php?act=themdienke";
        }
    </script>
<?php } else {
    header('location: ../controller/nhanvien.php?act=login');
} ?>