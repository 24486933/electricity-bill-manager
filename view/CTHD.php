<link rel="stylesheet" href="../assets/css/cthd.css?v=2">
<button onclick="goBack()">Quay lại trang trước</button>
        <script>
            function goBack() {
                window.history.back();
            }
        </script>
<?php
session_start();

if (isset($_SESSION['id_nv'])) {
    ?>
    <h2> Thông tin chi tiết </h2>
    <?php if ($CTHD_search) {
        ?>
        <?php foreach ($CTHD_search as $showct) { ?>
            <table class="info" border="1">
                <tr>
                    <th> Mã hóa đơn </th>
                    <th> Mã khách hàng </th>
                    <th> Mã điện kế </th>
                    <th> Tên khách hàng</th>
                    <th> Địa chỉ </th>
                    <th> Điện thoại </th>
                    <th> Căn cước công dân </th>
                    <th>Điện năng tiêu thụ</th>
                </tr>
                <tr>
                    <td><?php echo $showct['mahd'] ?></td>
                    <td><?php echo $showct['makh'] ?></td>
                    <td><?php echo $showct['madk'] ?></td>
                    <td><?php echo $showct['tenkh'] ?></td>
                    <td><?php echo $showct['diachi'] ?></td>
                    <td><?php echo $showct['dt'] ?></td>
                    <td><?php echo $showct['cccd'] ?></td>
                    <td><?php echo $showct['dntt'] ?> Kwh</td>
                </tr>

            </table>
            <?php
        }
    }
    ?>
    <?php if ($CTHD_search_TT) {
        $tongtienthanhtoan = 0;
        ?>
        <span class="b">Bảng tính tiền điện</span>
        <table border='1' class="infor">
            <tr>
                <th>ID</th>
                <th>Mã hóa đơn</th>
                <th>Tên bậc</th>
                <th>Số Kwh</th>
                <th>Đơn giá</th>
                <th>Sản lượng Kwh</th>
                <th>Thành tiền</th>
            </tr>
            <?php
            foreach ($CTHD_search_TT as $row) {
                if ($row['sanluongKwh'] > 0 && $row['thanhtien'] > 0) {
                    if ($row['densokw'] == null) {
                        $row['densokw'] = "trở lên";
                    }
                    ?>

                    <tr>
                        <td><?php echo $row['id_tinhdien']; ?></td>
                        <td><?php echo $row['mahd']; ?></td>
                        <td><?php echo $row['tenbac']; ?></td>
                        <td><?php echo $row['tusokw'] . '-' . $row['densokw']; ?></td>
                        <td><?php echo $row['dongia']; ?></td>
                        <td><?php echo $row['sanluongKwh']; ?></td>
                        <td><?php echo $row['thanhtien']; ?> VNĐ</td>
                    </tr>
                    <?php
                    $thanhtien_float = floatval(str_replace('.', '', $row['thanhtien']));
                    $tongtienthanhtoan += $thanhtien_float;
                }
            }
            ?>
            <tr>
                <td colspan="6" style="text-align:center; color:red; font-size:25px; font-weight:bold;">Tổng tiền</td>
                <td style="text-align:center; color:red; font-size:25px; font-weight:bold;">
                    <?php echo number_format($tongtienthanhtoan, 0, '.', '.'); ?>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="text-align:center; color:red; font-size:25px; font-weight:bold;">Thuế(VAT) +10%</td>
                <td style="text-align:center; color:red; font-size:25px; font-weight:bold;">
                    <?php echo $showct['tienthue'] ?>
                </td>
            </tr>
 
            <tr>
                <td colspan="6" style="text-align:center; color:red; font-size:25px; font-weight:bold;">Tổng thành tiền</td>
                <td style="text-align:center; color:red; font-size:25px; font-weight:bold;">
                    <?php echo number_format((($tongtienthanhtoan*0.1) + $tongtienthanhtoan),0, '.', '.');  ?>
                </td>
            </tr>

        </table>
        <?php
    } else {
        echo "<span>Không có dữ liệu phù hợp.</span>";
    } ?>

    <br>
    <span class="b">Bảng giá điện áp dụng cho hóa đơn</span>
    <?php if ($CTHD_search_TT) { ?>
        <table border='1' class="infor">
            <tr>
                <th>Tên Bậc</th>
                <th>Từ số KW</th>
                <th>Đến số KW</th>
                <th>Đơn giá</th>
                <th>Ngày bắt đầu áp dụng</th>
            </tr>
            <?php
            foreach ($CTHD_search_TT as $showbg) {
                if ($showbg['densokw'] == null) {
                    $showbg['densokw'] = "trở lên";
                }
                ?>
                <tr>
                    <td><?php echo $showbg['tenbac']; ?></td>
                    <td><?php echo $showbg['tusokw']; ?></td>
                    <td><?php echo $showbg['densokw']; ?></td>
                    <td><?php echo $showbg['dongia'] ?></td>
                    <td><?php echo $showbg['ngayapdung']; ?></td>
                </tr>
                <?php
            }
            echo '</table> ';
    }
    ?>
        

    <?php } else {
    header('location: ../controller/nhanvien.php?act=login');
} ?>
    