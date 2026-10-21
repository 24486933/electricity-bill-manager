<link rel="stylesheet" href="../assets/css/quanlygiadien.css?v=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<?php
session_start();

if (isset($_SESSION['id_nv'])) {
    ?>
    <div>
        <button class="btn"><a href="../controller/tiendien.php?act=quanly">Quay lại</a></button><br>
        <button class="btn"><a href="../controller/giadien.php?act=themgiadien">Thêm bảng giá điện mới</a></button>
    </div>
    <h2>XEM BẢNG GIÁ ĐIỆN THEO NGÀY ÁP DỤNG</h2>
    <label for="ngay">Chọn ngày:</label>
    <form id="formNgay" method="post">
        <select id="ngay" name="ngay" class="custom-select" onfocus='this.size=10;' onblur='this.size=1;' onchange="submitForm()">
            <option>Chọn ngày muốn xem</option>
            <?php
            if (isset($ngay_chon) && !empty($ngay_chon)) {
                foreach ($ngay_chon as $row) {
                    echo "<option value='" . $row["ngayapdung"] . "'>" . $row["ngayapdung"] . "</option>";
                }
            } else {
                echo "<option value=''>Không có dữ liệu</option>";
            }
            ?>
        </select>
        <br>
    </form>
    <?php if (isset($show_cbb) && !empty($show_cbb)) {
        if (isset($_POST['ngay'])) {
            $ngay_chon = $_POST['ngay'];
        }
        ?>
        <h4>Bảng giá điện ngày: <?php echo $ngay_chon ?>
        </h4>
        <table border="1">
            <tr>
                <th>Tên Bậc</th>
                <th>Từ số KW</th>
                <th>Đến số KW</th>
                <th>Đơn giá</th>
            </tr>
            <?php foreach ($show_cbb as $rows) { ?>
                <tr>
                    <td><?php echo $rows['tenbac'] ?></td>
                    <td><?php echo $rows['tusokw'] ?></td>
                    <?php
                    if ($rows['densokw'] == null) {
                        $densokwnull = "Trở lên";
                    } else {
                        $densokwnull = $rows['densokw'];
                    }
                    ?>
                    <td><?php echo $densokwnull ?></td>
                    <td><?php echo $rows['dongia'] ?></td>
                </tr>

                <?php
            }
            echo '</table>';
    }
    ?>


        <?php
        if (isset($showall_bangdien) && !empty($showall_bangdien)) {
            // Số lượng hóa đơn hiển thị trên mỗi trang
            $invoicesPerPage = isset($_GET['size']) ? (int) $_GET['size'] : 16;
            // Trang hiện tại
            $currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;
            // Vị trí bắt đầu của dữ liệu trên trang hiện tại
            $startIndex = ($currentPage - 1) * $invoicesPerPage;

            // Lọc các hóa đơn có mã hóa đơn (giả sử mã hóa đơn là mahd)
            $filteredInvoices = array_filter($showall_bangdien, function ($giadien) {
                return isset ($giadien['mabac']);
            });
            // Tổng số hóa đơn
            $totalInvoices = count($filteredInvoices);
            // Tính tổng số trang
            $totalPages = ceil($totalInvoices / $invoicesPerPage);
            // Lấy danh sách hóa đơn cho trang hiện tại
            $invoicesToShow = array_slice($filteredInvoices, $startIndex, $invoicesPerPage);
            ?>
            <h4>Danh sách toàn bộ giá điện</h4>
            <table border="1">
                <tr>
                    <th>Tên Bậc</th>
                    <th>Từ số KW</th>
                    <th>Đến số KW</th>
                    <th>Đơn giá</th>
                    <th>Ngày áp dụng</th>
                </tr>
                <?php
                foreach ($invoicesToShow as $rows) {
                    ?>
                    <tr>
                    <?php if ($rows['tenbac'] == "Bậc 1") { ?>
                            <td style="background-color: yellow;font-weight:bold; color: red;"><?php echo $rows['tenbac'] ?></td>
                            <td style="background-color: yellow;font-weight:bold; color: red;"><?php echo $rows['tusokw'] ?></td>
                            <?php
                            if ($rows['densokw'] == null) {
                                $densokwnull = "Trở lên";
                            } else {
                                $densokwnull = $rows['densokw'];
                            }
                            ?>
                            <td style="background-color: yellow;font-weight:bold; color: red;"><?php echo $densokwnull ?></td>
                            <td style="background-color: yellow;font-weight:bold; color: red;"><?php echo $rows['dongia'] ?></td>
                            <td style="background-color: yellow;font-weight:bold; color: red;"><?php echo $rows['ngayapdung'] ?></td>
                        <?php } else { ?>
                            <td><?php echo $rows['tenbac'] ?></td>
                            <td><?php echo $rows['tusokw'] ?></td>
                            <?php
                            if ($rows['densokw'] == null) {
                                $densokwnull = "Trở lên";
                            } else {
                                $densokwnull = $rows['densokw'];
                            }
                            ?>
                            <td><?php echo $densokwnull ?></td>
                            <td><?php echo $rows['dongia'] ?></td>
                            <td><?php echo $rows['ngayapdung'] ?></td>
                        <?php } ?>


                    </tr>
                    <?php
                }
                echo "</table>";
                if ($totalPages > 1) {
                    echo '<div></div><div>';
                    if ($currentPage > 1) {
                        echo '<button class="navi selected"><a   href="?act=quanlygiadien&p=' . ($currentPage - 1) . '&size=' . $invoicesPerPage . '">&lt;</a></button>';
                    }
                    $start = max(1, $currentPage - 1);
                    $end = min($totalPages, $start + 3);
                    for ($i = $start; $i <= $end; $i++) {
                        if ($i == $currentPage) {
                            echo '<button class="navi selected chosse"><span>' . $i . '</span></button>';
                        } else {
                            echo '<button class="navi selected"><a   href="?act=quanlygiadien&p=' . $i . '&size=' . $invoicesPerPage . '">' . $i . '</a></button>';
                        }
                    }
                    if ($currentPage < $totalPages) {
                        echo '<button class="navi selected"><a  href="?act=quanlygiadien&p=' . ($currentPage + 1) . '&size=' . $invoicesPerPage . '">&gt;</a></button>';
                    }
                    echo '</div>';
                }
        }
        ?>

            <script>
                function submitForm() {
                    document.getElementById("formNgay").submit();
                }
            </script>

        <?php } else {
    header('location: ../controller/nhanvien.php?act=login');
} ?>
