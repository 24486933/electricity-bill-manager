<link rel="stylesheet" href="../assets/css/nhapchiso.css?v=2">
<link rel="stylesheet" href="../assets/css/quanlyhoadon.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

<style>
    span {
        position: relative;
        left: 35px;
        font-weight: bold;
    }

    .mot {
        margin-top: 30px;
        width: 40%;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        left: 20px;
    }

    .hai {
        width: 45%;
        height: 90%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .quak {
        display: flex;
        justify-content: space-between;
        margin-left: 20px;
    }
</style>
<?php
if (isset($_SESSION['id_nv'])) {
    ?>
    <link rel="stylesheet" href="../assets/css/nhapchiso.css">
    <?php
    //sau khi thêm thành công sẽ chạy đoạn này, hiện thông báo thành công=> show thông tin hd vừa thêm
    if (isset($_GET['mahd'])) {
        if (isset($_SESSION['success_messager'])) {
            echo '<div style="color: green; font-weight: bold; font-size: larger; text-align: center; border: 2px solid green; padding: 5px; display: table; margin: 20px auto; border-radius: 5px;">';
            echo $_SESSION['success_messager'] . '<br>';
            echo '</div>';
            unset($_SESSION['success_messager']);
        }
        if ($show_hd_add) { ?>
            <table border="1">
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
                    <th colspan=2>Công cụ</th>
                </tr>
                <tr>
                    <td><?php echo $show_hd_add['mahd'] ?></td>
                    <td><?php echo $show_hd_add['manv'] ?></td>
                    <td><?php echo $show_hd_add['ky'] ?></td>
                    <td><?php echo $show_hd_add['tungay'] ?></td>
                    <td><?php echo $show_hd_add['denngay'] ?></td>
                    <td><?php echo $show_hd_add['chisodau'] ?></td>
                    <td><?php echo $show_hd_add['chisocuoi'] ?></td>
                    <td><?php echo $show_hd_add['tongthanhtien'] ?></td>
                    <td><?php echo $show_hd_add['ngaylaphd'] ?></td>
                    <?php
                    if ($show_hd_add['tinhtrang'] == 0) { ?>
                        <td>Chưa thanh toán</td>
                        <td><?php echo '<a  style="color:#054e59;" href="../controller/tiendien.php?act=in&mahd=' . $show_hd_add['mahd'] . '">In giấy báo điện</a>'; ?>
                        </td>
                        <td ><?php echo '<a style="color:#249c14;" name="hoanthanh"  href="../controller/tiendien.php?act=tinhdien&action=dathanhtoan&code=' . $show_hd_add['mahd'] . '">Đã thanh toán</a>'; ?>
                        </td>
                    <?php } else { ?>
                        <td>Đã thanh toán</td>
                        <td><?php echo '<a href="../controller/tiendien.php?act=in&mahd=' . $show_hd_add['mahd'] . '">In hóa đơn</a>'; ?>
                        </td>
                    <?php
                    }
                    ?>
                </tr>
            </table>
            <span style=""> Thông tin chi tiết </span>
            <?php if ($show_cthd_byhd) {
                ?>
                <?php foreach ($show_cthd_byhd as $showct) { ?>
                    <div class="mot">
                        <div class="hai">
                            <div class="quak">
                                Mã hóa đơn:
                                <span>
                                    <?php echo $showct['mahd'] ?>
                                </span>
                            </div>
                            <div class="quak"> Mã khách hàng:
                                <span> <?php echo $showct['makh'] ?></span>
                            </div class="quak">
                            <div class="quak"> Mã điện kế:
                                <span> <?php echo $showct['madk'] ?></span>
                            </div>
                            <div class="quak">
                                Tên khách hàng: <span> <?php echo $showct['tenkh'] ?></span>
                            </div>
                        </div>
                        <div class="hai">
                            <div class="quak">
                                Địa chỉ: <span> <?php echo $showct['diachi'] ?></span>
                            </div>
                            <div class="quak"> Điện thoại: <span> <?php echo $showct['dt'] ?></span>
                            </div>
                            <div class="quak"> Căn cước công dân: <span> <?php echo $showct['cccd'] ?></span>
                            </div>
                            <div class="quak"> Điện năng tiêu thụ: <span> <?php echo $showct['dntt'] ?> Kwh</span>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
            <?php if ($show_tt_byhd) {
                $tongtienthanhtoan = 0;
                ?>
                <table border='1'>
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
                    foreach ($show_tt_byhd as $row) {
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
                                <td><?php echo $row['thanhtien']; ?></td>
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
                            <?php echo number_format($tongtienthanhtoan, 0, '.', '.'); ?></td>
                    </tr>
                </table>
                <?php
            } else {
                echo "Không có dữ liệu phù hợp.";
            } ?>
            <br>
            <span>Bảng giá điện áp dụng cho hóa đơn</span>
            <?php if ($show_tt_byhd) { ?>
                <table border='1'>
                    <tr>
                        <th>Tên Bậc</th>
                        <th>Từ số KW</th>
                        <th>Đến số KW</th>
                        <th>Đơn giá</th>
                        <th>Ngày bắt đầu áp dụng</th>
                    </tr>
                    <?php
                    foreach ($show_tt_byhd as $showbg) {
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
                <button class="back"><a href="../controller/tiendien.php?act=quanlydien">Xong</a></button>
            <?php
        }
    } else {
        ?>
            <button class="back"><a href="../controller/tiendien.php?act=quanlydien">Quay lại</a></button>
            <?php
                date_default_timezone_set('Asia/Ho_Chi_Minh');

                $microtime = microtime(true);

                $seconds = (int)$microtime; // Ép sang int để dùng cho date()
                $micro   = sprintf("%06d", ($microtime - $seconds) * 1e6); // Lấy micro giây chính xác

                $mahd = date("ymdHis", $seconds) . substr($micro, 0, 2); // Ghép lại mã hóa đơn
            ?>

            <h2>Giá điện hiện hành[ĐANG ÁP DỤNG]</h2>
            <table>
                <tr>
                    <th>Mã Bậc</th>
                    <th>Tên Bậc</th>
                    <th>Từ số KW</th>
                    <th>Đến số KW</th>
                    <th>Đơn giá</th>
                    <th>Ngày áp dụng</th>
                </tr>
                <?php
                if ($result1) {
                    foreach ($result1 as $row) {
                        if ($row['densokw'] == null) {
                            $row['densokw'] = "trở lên";
                        }
                        echo "<tr>";
                        echo "<td>" . $row['mabac'] . "</td>";

                        echo "<td>" . $row['tenbac'] . "</td>";
                        echo "<td>" . $row['tusokw'] . "</td>";
                        echo "<td>" . $row['densokw'] . "</td>";
                        echo "<td>" . $row['dongia'] . "</td>";
                        echo "<td>" . $row['ngayapdung'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Không có dữ liệu</td></tr>";
                }
                ?>
            </table>
            <!-- form lấy madk check dữ liệu số kwh cuối & đến ngày -->
            <form id="searchForm" action="" method="post">
                <input type="hidden" name="selected_madk_check" id="selected_madk_check" readonly>
            </form>
            <h2>Form tính tiền điện</h2>
            <form id="secondForm" method="post" action="">
                <div class="form">
                    <input type="hidden" id="mahd" name="mahd" value="<?php echo $mahd; ?>" readonly>
                    <label for="madk">Mã điện kế:</label>
                    <input type="text" name="selected_madk" id="selected_madk" readonly><br><br>

                    <label for="madk">Kỳ:</label>
                    <input type="text" name="ky" id="ky" placeholder="Nhập kỳ theo định dạng mm/yyyy"><br><br>

                    <label for="tusokw">Từ ngày</label>
                    <input type="date" id="tungay" name="tungay"
                        value="<?php if (isset($ngaylaphd_show)) {
                            echo $ngaylaphd_show;
                        } else {
                            echo isset($_POST['tungay']) ? $_POST['tungay'] : '';
                        } ?>"><br><br>

                    <label for="tusokw">Đến ngày</label>
                    <input type="date" id="denngay" name="denngay"
                        value=" <?php echo isset($_POST['denngay']) ? $_POST['denngay'] : ''; ?>"><br><br>

                    <label for="tusokw">Từ số KW:</label>
                    <input type="text" id="tusokw" name="tusokw"
                        value="<?php if (isset($csc_show)) {
                            echo $csc_show;
                        } else {
                            echo isset($_POST['tusokwP']) ? $_POST['tusokwP'] : '';
                        } ?>"
                        required oninput="tinhTongKW()" onkeypress="return isNumberKey(event)" <?php if (isset($csc_show)) { echo 'readonly'; } ?> ><br><br>

                    <label for="densokw">Đến số KW:</label>
                    <input type="text" id="densokw" name="densokw"
                        value="<?php echo isset($_POST['densokwP']) ? $_POST['densokwP'] : ''; ?>" required
                        oninput="tinhTongKW()" onkeypress="return isNumberKey(event)"><br><br>
                </div>
                <div class="form">
                    <label for="kq">Điện năng tiêu thụ (KW):</label>
                    <input type="text" id="kq" name="kq" value="<?php echo isset($_POST['kq']) ? $_POST['kq'] : ''; ?>"
                        readonly><br><br>

                    <label for="tongtien">Thành tiền (VNĐ):</label>
                    <input type="text" id="tongtien" name="tongtien"
                        value="<?php echo isset($_POST['tongtien']) ? $_POST['tongtien'] : ''; ?>" readonly><br><br>

                    <label for="tongtien">Thuế (VAT) + 10%:</label>
                    <input type="text" id="thue" name="thue" value="<?php echo isset($_POST['thue']) ? $_POST['thue'] : ''; ?>"
                        readonly><br><br>

                    <label for="tongtienphaitt">Tổng tiền phải thanh toán (VNĐ):</label>
                    <input type="text" id="tongtienphaitt" name="tongtienphaitt"
                        value="<?php echo isset($_POST['tongtienphaitt']) ? $_POST['tongtienphaitt'] : ''; ?>" readonly><br><br>
                    <?php
                    $result1 = showGiaHienHanh();

                    if ($result1) {
                        foreach ($result1 as $row) {
                            echo "<input type='hidden' name='mabac[]' value='" . $row['mabac'] . "' readonly>";
                        }
                    } else {
                        echo "<p>Không có dữ liệu về giá điện hiện hành.</p>";
                    }
                    ?>
                    <input type="submit" name="submit" value="Tính tiền điện" class="btn-submit">
                    <input type="submit" name="addhd" id="addhd_button" value="Thêm hóa đơn" class="btn-submit" title="Hãy tính tiền điện trước khi thêm hóa đơn." disabled>
                </div>
            </form>
            <?php
    }
    ?>
        </body>

        </html>
        <script src="../assets/js/nhapcs.js?v=1"></script>
        <script>
    // Hàm này sẽ xác định nếu giá trị đầu vào là số
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        // 46 là mã ASCII của dấu thập phân (.)
        // 48 đến 57 là mã ASCII của các chữ số từ 0 đến 9
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
</script>
    <?php } else {
    header('location: ../controller/nhanvien.php?act=login');
} ?>