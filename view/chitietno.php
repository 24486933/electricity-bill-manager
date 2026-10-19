<link rel="stylesheet" href="../assets/css/quanlydienke.css?v=2">
<style>
    .back {
        width: 100px;
        padding: 12px;
        margin-top: 10px;
        background-color: rgb(74, 70, 70);
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        position: relative;
        top: 10px;
        left: 10px;
    }

    .back a {
        text-decoration: none;
        color: white;
        font-weight: bold;
    }
</style>
<?php
session_start();
if (isset($_SESSION['id_nv'])) {
    ?>
    <?php
    if (isset($show_chitiet) && !empty($show_chitiet)) {

        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $ngayHienTai = new DateTime();
        echo '<table border="1">';
        echo '<tr>';
        echo '<th>Mã hóa đơn</th>';
        echo '<th>Kỳ</th>';
        echo '<th>Mã điện kế</th>';
        echo '<th>Trạng thái điện kế</th>';
        echo '<th>Tiền điện</th>';
        echo '<th>Tiền thuế</th>';
        echo '<th>Tiền phải thanh toán</th>';
        echo '<th>Thông tin</th>';
        echo '</tr>';

        foreach ($show_chitiet as $rows) {
            $timestamp = strtotime($rows['ngaylaphd']);
            $current_time = time();
            $time_difference = $current_time - $timestamp;
            $seconds = $time_difference;
            $minutes = round($seconds / 60);           // value 60 is seconds tương ứng 1p
            $hours = round($seconds / 3600);           // value 3600 is 60 minutes * 60 sec tương ứng 1h
            $days = round($seconds / 86400);          // value 86400 is 24 hours * 60 minutes * 60 sec tương ứng 1 ngày
            $weeks = round($seconds / 604800);         // value 604800 is 7 days * 24 hours * 60 minutes * 60 sec tương ứng 1 tuần
            $months = round($seconds / 2629440);       // value 2629440 is ((365+365+365+365+366)/5/12) days * 24 hours * 60 minutes * 60 sec tương ứng 1 tháng
            $years = round($seconds / 31553280);       // value 31553280 is ((365+365+365+365+366)/5) days * 24 hours * 60 minutes * 60 sec tương ứng 1 năm

            if ($seconds <= 60) {
                $output = "<span style='color: green;'>vài giây trước</span>";
            } elseif ($minutes <= 60) {
                $output = "<span style='color: green;'>$minutes phút trước</span>";
            } elseif ($hours <= 24) {
                $output = "<span style='color: green;'>$hours giờ trước</span>";
            } elseif ($days <= 7) {
                $output = "<span style='color: green;'>$days ngày trước</span>";
            } elseif ($weeks <= 4.3) {  // 4.3 tuần gần bằng 1 tháng
                $output = "<span style='color: blue;'>$weeks tuần trước</span>";
            } elseif ($months <= 12) {
                $output = "<span style='color: red;'>$months tháng trước</span>";
            } else {
                $output = "<span style='color: red;'>$years năm trước</span>";
            }

            echo '<tr>';
            echo '<td>' . $rows['mahd'] . '</td>';
            echo '<td>' . $rows['ky'] . '</td>';
            echo '<td>' . $rows['madk'] . '</td>';
            if ($rows['trangthai'] == 0) {
                echo '<td>Không còn sử dụng</td>';
            } else {
                echo '<td>Còn sử dụng</td>';
            }
            echo '<td>' . $rows['tongtiendien'] . ' VNĐ</td>';
            echo '<td>' . $rows['tienthue'] . ' VNĐ</td>';
            echo '<td>' . $rows['tongthanhtien'] . ' VNĐ</td>';
            //chuyển chuỗi tgian sang dạng datetime
            $ngayLapHD = DateTime::createFromFormat('Y-m-d H:i:s', $rows['ngaylaphd']);
            // Kiểm tra xem ngày lập hóa đơn có hợp lệ không trước khi sử dụng diff()
            if ($ngayLapHD instanceof DateTime) {
                // Tính toán số ngày từ ngày lập hóa đơn đến ngày hiện tại
                $soNgay = $ngayLapHD->diff($ngayHienTai)->days;
                echo '<td>Thời gian tạo hóa đơn: ' . $output . ' - ' . $soNgay . ' ngày chưa thanh toán</td>';
            } else {
                echo '<td>Ngày không hợp lệ</td>';
            }

            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo 'Không có dữ liệu nào !!!';
    }
    ?>
    <button class="back"><a href="../controller/theodoino.php?act=theodoino">Quay lại</a></button>
<?php } else {
    header('location: ../controller/nhanvien.php?act=login');
} ?>