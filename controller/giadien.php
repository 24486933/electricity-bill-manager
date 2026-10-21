<?php
    include_once "../model/config.php"; 
    include_once "../model/GiaDien.php"; 

    if (isset($_GET['act'])) {  
        switch ($_GET['act']) {
            case "quanlygiadien":

                $showall_bangdien = giadien_showAll();
                if(isset($_POST['ngay'])) {
                    $ngay_chon = $_POST['ngay'];
                    $show_cbb = showGia_cbb($ngay_chon);
                }
                $ngay_chon = cbbNgay();
                include "../view/quanlygiadien.php";
                break;
            case "themgiadien":
                session_start();

                if(isset($_POST['submit'])){
                    date_default_timezone_set('Asia/Ho_Chi_Minh');
                    $ngayapdung = date('Y-m-d H:i:s');
                    $error_messages = array();
            
                    foreach ($_POST['tenbac'] as $key => $value) {
                        $tenbac = $_POST['tenbac'][$key];
                        $tusokw = $_POST['tusokw'][$key];
                        $densokw = $_POST['densokw'][$key];
                        $dongia = $_POST['dongia'][$key];
                        
                        if ($densokw === "Trở lên") {
                            $densokw_check = 9999;
                        }else{
                            $densokw_check = $densokw;
                        }
                        // Đọc giới hạn cho mỗi cột từ bảng mô tả
                        $max_length_tenbac = 50;
                        $max_value_tusokw = 2147483647; // Giá trị tối đa của INT(11)
                        $max_value_densokw = 2147483647; // Giá trị tối đa của INT(11)
                        $max_length_dongia = 255;
                        // Kiểm tra trước khi thêm vào cơ sở dữ liệu, ktra vượt kích thước lưu trữ csdl
                        if (strlen($tenbac) > $max_length_tenbac) {
                            $error_messages[] = "Độ dài tên bậc vượt quá giới hạn cho phép.";
                        }

                        if ($tusokw  > $max_value_tusokw) {
                            $error_messages[] = "Giá trị của Từ số KW vượt quá giới hạn cho phép.";
                        }
                        //ktra độ dài densokw có vượt quá kích thước cho phép của table ko
                        if ($densokw_check > $max_value_densokw) {
                            $error_messages[] = "Giá trị của Đến số KW vượt quá giới hạn cho phép.";
                        }
                        //ktra đơn giá có nhỏ hơn = 0 ko
                        if ($dongia <= 0) {
                            $error_messages[] = "Đơn giá phải lớn hơn 0.";
                        }
                        //ktra độ dài đơn giá có vượt quá kích thước cho phép của table ko
                        if (strlen($dongia) > $max_length_dongia) {
                            $error_messages[] = "Độ dài của Đơn giá vượt quá giới hạn cho phép.";
                        }
                        //các trường ko rỗng
                        if ($tenbac == '' || $tusokw == '' || $densokw_check =='' || $dongia == '') {
                            $error_messages[] = "Các trường dữ liệu không được rỗng.";
                        }
                        //phải là số và ko đc để null
                        if (!is_numeric($tusokw) || $tusokw < 0 || !is_numeric($densokw_check) || $densokw_check < 0 || !is_numeric($dongia) || $dongia < 0) {
                            $error_messages[] = "Các trường dữ liệu phải là số và lớn hơn bằng 0.";
                        }
                        if($tusokw >= $densokw_check){
                            $error_messages[] = "Số Kwh bắt đầu của một bậc phải nhỏ hơn số Kwh kết thúc của bậc đó.";
                        }
                        if (strtotime($ngayapdung) > strtotime(date('Y-m-d H:i:s'))) {
                            $error_messages[] = "Ngày áp dụng cho giá điện phải nhỏ hơn ngày hiện tại.";
                        }
                               
                    }
                        if (empty($error_messages)) {
                        foreach($_POST['tenbac'] as $key => $value){
                            $tenbac = $_POST['tenbac'][$key];
                            $tusokw = $_POST['tusokw'][$key];
                            $densokw = $_POST['densokw'][$key];
                            $dongia = $_POST['dongia'][$key];
                            // Kiểm tra nếu trường densokw có giá trị là "Trở lên", gán giá trị null cho biến tương ứng
                            if ($densokw === "Trở lên") {
                                $densokw = null;
                            }
                                themDien($tenbac, $tusokw, $densokw, $dongia, $ngayapdung);
                                $_SESSION['success_messager'] = "Thêm thành công";
                            }
                        } 
                    //lưu các lỗi check đc vào session
                    $_SESSION['error_messages'] = $error_messages;
                    
                    if (isset($_SESSION['error_messages']) && !empty($_SESSION['error_messages'])) {
                        echo '<p style="color: red; font-size: 20px; font-weight: bold; position: relative; top: 55%; left: 42%; margin: 0; width: 500px; border-radius: 10px; box-shadow: 0 0 5px red; padding: 10px;">Thông báo có lỗi !!!<br>';
                        foreach ($_SESSION['error_messages'] as $error_message) {
                            echo $error_message . '<br>';
                        }
                        echo '</p>';
                        unset($_SESSION['error_messages']);
                    }
                    if (isset($_SESSION['success_messager']) && !empty($_SESSION['success_messager'])) {
                        echo '<div style="color: green; font-size: 20px; font-weight: bold; position: relative; top: 55%; left: 42%; margin: 0; width: 500px; border-radius: 10px; box-shadow: 0 0 5px green; padding: 10px;">';
                        echo $_SESSION['success_messager'] . '<br>';
                        echo '</div>';
                        unset($_SESSION['success_messager']);
                        // Thêm một bản ghi vào lịch sử duyệt
                        echo '<script>
                        if (typeof window.history.pushState === "function") {
                            window.history.pushState({}, "", window.location.href);
                            window.onpopstate = function (event) {
                                window.history.pushState({}, "", window.location.href);
                            };
                        }
                    </script>';
                    }
                    
                }
                include "../view/thembanggiadien.php";
                break;
            default:
            include "../view/login.php";
            break;
        }
    } else { 
        include "../view/login.php";
    }
?>