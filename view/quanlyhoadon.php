<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/quanlyhoadon.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Document</title>
</head>
<!-- kiểm tra get size và page hợp lệ ko, ko thì trả về mặc định -->
<?php
if(isset($_GET['size'])){
    $erro_size = $_GET['size'];
    if($erro_size <= 0 ){
        header('location: ../controller/tiendien.php?act=quanlyhoadon&p=1&size=5');
    }
}
?>
<?php
if(isset($_GET['p'])){
    $erro_page = $_GET['p'];
    if($erro_page <= 0 ){
        header('location: ../controller/tiendien.php?act=quanlyhoadon&p=1&size=5');
    }
}
?>
<body>
    <?php
    session_start();

    if (isset($_SESSION['id_nv'])) {
        ?>
        <button class="control"><a href="../controller/tiendien.php?act=quanly">Quay lại</a></button>
        <div>
            <button class="control"><a href="../controller/tiendien.php?act=quanlydien">Thêm hóa đơn mới</a></button>
        </div>

        <!-- form tìm hóa đơn -->
        <h2>Tìm hóa đơn</h2>
        <form id="searchForm" class="aaaa">
            <label for="mahd">Mã hóa đơn:</label>
            <input type="text" id="mahd" name="mahd" placeholder="Nhập mã hóa đơn để tìm...">
        </form>
        <!-- trả kết quả về div có id searchResults -->
        <div id="searchResults"></div>

        <h2>Danh sách hóa đơn</h2>
        <?php
        if (isset($show_data_bill_all) && !empty($show_data_bill_all)) {
            // Số hóa đơn hiển thị trên mỗi trang
            $billsPerPage = isset($_GET['size']) ? (int) $_GET['size'] : 15;
            // Trang hiện tại
            $currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;
            // Vị trí bắt đầu của dữ liệu trên trang hiện tại
            $startIndex = ($currentPage - 1) * $billsPerPage;

            // Lọc hóa đơn có mã hóa đơn
            $filteredBills = array_filter($show_data_bill_all, function ($hoadon) {
                return isset ($hoadon['mahd']);
            });

            // Tổng số hóa đơn
            $totalBills = count($filteredBills);
            // Tính tổng số trang
            $totalPages = ceil($totalBills / $billsPerPage);
            // Lấy danh sách hóa đơn cho trang hiện tại
            $billsToShow = array_slice($filteredBills, $startIndex, $billsPerPage);
            ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Mã hóa đơn</th>
                        <th>Mã khách hàng</th>
                        <th>Tên khách hàng</th>
                        <th>Kỳ</th>
                        <th>Từ ngày</th>
                        <th>Đến ngày</th>
                        <th>Chỉ số đầu</th>
                        <th>Chỉ số cuối</th>
                        <th>Tổng tiền thanh toán</th>
                        <th>Ngày lập hóa đơn</th>
                        <th>Tình trạng</th>
                        <th>Người lập hóa đơn</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($billsToShow as $rows) {
                        ?>
                        <tr>
                         <td><a style="color:red" href="../controller/tracuu.php?act=cthd&mahd=<?php echo $rows['mahd']; ?> "><?php echo $rows['mahd']; ?></a></td>
                            <td><?php echo $rows['makh']; ?></td>
                            <td><?php echo $rows['tenkh']; ?></td>
                            <?php
                            $show_nv = check_Info_ById($rows['manv']);
                            if (isset($show_nv) && !empty($show_nv)) {
                                $nguoilaphd = $show_nv['tennv'];
                            } else {
                                $nguoilaphd = "";
                            }
                            ?>

                            <td><?php echo $rows['ky']; ?></td>
                            <td><?php echo $rows['tungay']; ?></td>
                            <td><?php echo $rows['denngay']; ?></td>
                            <td><?php echo $rows['chisodau']; ?></td>
                            <td><?php echo $rows['chisocuoi']; ?></td>
                            <td class="total"><?php echo $rows['tongthanhtien']; ?> VNĐ</td>
                            <td><?php echo $rows['ngaylaphd']; ?></td>
                            <?php if ($rows['tinhtrang'] == 1) {
                                echo "<td style='color: green; font-weight: bold;'>Đã thanh toán</td>";
                            } else {
                                echo "<td style='color: red; font-weight: bold;'>Chưa thanh toán</td>";
                            }
                            ?>
                            <td class="totall"><?php echo $nguoilaphd ?></td>

                        </tr>

                        <?php
                    }
                    echo '</tbody>
</table>';
if ($totalPages > 1) {
    echo '<div></div><div>';
    if ($currentPage > 1) {
        echo '<button class="navi selected"><a href="?act=quanlyhoadon&p=' . ($currentPage - 1) . '&size=' . $billsPerPage . '">&lt;</a></button>';
    }
    $start = max(1, $currentPage - 1);
    $end = min($totalPages, $start + 3);
    for ($i = $start; $i <= $end; $i++) {
        if ($i == $currentPage) {
            echo '<button class="navi selected chosse"><span>' . $i . '</span></button>';
        } else {
            echo '<button class="navi selected"><a href="?act=quanlyhoadon&p=' . $i . '&size=' . $billsPerPage . '">' . $i . '</a></button>';
        }
    }
    if ($currentPage < $totalPages) {
        echo '<button class="navi selected"><a href="?act=quanlyhoadon&p=' . ($currentPage + 1) . '&size=' . $billsPerPage . '">&gt;</a></button>';
    }

    echo '</div>';
}
        }else{
            echo 'Không có hóa đơn nào.';
        }
        ?>
    <script>
        document.getElementById("mahd").addEventListener("input", function () {
            var input = this.value;
            if (input.trim() !== '') {
                searchInvoice(input);
            } else {
                document.getElementById("searchResults").innerHTML = '';
            }
        });
        document.getElementById("mahd").addEventListener("keydown", function (event) {
            if (event.keyCode === 13) { // 13 là mã phím của phím Enter
                event.preventDefault(); // Ngăn chặn hành động mặc định của phím Enter
                var input = this.value;
                if (input.trim() !== '') {
                    searchInvoice(input);
                }
            }
        });
        function searchInvoice(input) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    document.getElementById("searchResults").innerHTML = this.responseText;
                }
            };
            xhr.open("POST", "../controller/tiendien.php?act=kqsearch", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("mahd=" + input);
        }
    </script>

    <?php } else {
        header('location: ../controller/nhanvien.php?act=login');
    } ?>
</body>

</html>