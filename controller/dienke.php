<?php
    include_once "../model/config.php"; 
    include_once "../model/DienKe.php"; 
    include_once "../model/DienKeKhachHang.php"; 
    include_once "../model/KhachHang.php"; 

    if (isset($_GET['act'])) {  
        switch ($_GET['act']) {
            case "quanlydienke":

                $data=showData();
                include "../view/quanlydienke.php";
                break;
            case "kqsearch":
                if(isset($_POST['makh'])){
                    $makh = $_POST['makh'];
                    if($makh != ''){
                        $search_KH_DK = searchIDKH($makh);
                    }
                }
                if(isset($_POST['nameKH'])){
                    $name = $_POST['nameKH'];
                    if($name != ''){
                        $search_KH_Name_DK = searchNameKH($name);
                    }
                }
                include "../view/ketquasearch.php";
                break;
            case "themdienke":
                session_start();

                $last_madk= last_IDDK();
                if(isset($_POST['addDienke'])){
                    $error_messages = array();
                    date_default_timezone_set('Asia/Ho_Chi_Minh');

                    $madk = $_POST['madk'];
                    $makh = $_POST['makh'];
                    $ngaysx = $_POST['ngaysx'];
                    $ngaylap = $_POST['ngaylap'];
                    $mota = $_POST['mota'];
                    $trangthai = $_POST['trangthai'];

                    if(strlen($mota) > 100){
                        $error_messages[] = "Giá trị của Mô tả vượt quá giới hạn cho phép.";
                    }
                    if (!strtotime($ngaysx)) {
                        $error_messages[] = "Định dạng ngày sản xuất không hợp lệ.";
                    }
                    if (!strtotime($ngaylap)) {
                        $error_messages[] = "Định dạng ngày lắp không hợp lệ";
                    }
                    // Kiểm tra định dạng mã điện kế
                    if(!preg_match('/^\d{8}$/', $madk)){
                        $error_messages[] = "Mã điện kế chỉ được nhập số và tối đa 8 số.";
                    }
                    if(strtotime($ngaysx) > time() || strtotime($ngaylap) > time()){
                        $error_messages[] = "Ngày sản xuất và ngày lắp không được lớn hơn ngày hiện tại.";
                    }
                    // Kiểm tra ngày sản xuất và ngày lắp
                    if(strtotime($ngaysx) >= strtotime($ngaylap)){
                        $error_messages[] = "Ngày sản xuất phải nhỏ hơn ngày lắp.";
                    }
                    if($madk == '' && $makh == '' && $ngaysx == '' && $ngaylap == '' && $mota =='' && $trangthai == ''){
                        $error_messages[] = "Các trường dữ liệu không được để trống.";
                    }
                    if($trangthai != 0 && $trangthai != 1){
                        $error_messages[] = "Trạng thái điện kế không hợp lệ.";
                    }
                    if (empty($error_messages)) {
                        addDienKe($madk, $makh, $ngaysx, $ngaylap, $mota, $trangthai);
                        echo "<script>alert('Thêm điện kế mã ".$madk." thành công!');window.location.href = '../controller/dienke.php?act=quanlydienke';</script>";  
                    }
                    //lưu các lỗi check đc vào session
                    $_SESSION['error_messages'] = $error_messages;
                    
                    //lưu các lỗi check đc vào session
                    $_SESSION['error_messages'] = $error_messages;
                    if (isset($_SESSION['error_messages']) && !empty($_SESSION['error_messages'])) {
                        echo '<div class="errormesseger">';
                        echo 'Thông báo có lỗi!!!';
                        echo '<p>';
                        foreach ($_SESSION['error_messages'] as $error_message) {
                            echo $error_message . '<br>';
                        }
                        echo '</p></div>';
                        unset($_SESSION['error_messages']);
                    }

                }
                include "../view/themdienke.php";
                break;
            case "suadienke":
                if(isset($_POST['editDienKe'])){
                    $madk = $_POST['madk'];
                    $showDataDienke = show_DK_BY_ID($madk);
                    if(isset($showDataDienke) && !empty($showDataDienke)){
                        $show_madk=$showDataDienke['madk'];
                        $show_makh = $showDataDienke['makh'];
                        $show_ngaysx= $showDataDienke['ngaysx'];
                        $show_ngaylap=$showDataDienke['ngaylap'];
                        $show_mota_dk = $showDataDienke['mota'];
                        $show_trangthai=$showDataDienke['trangthai'];
                    }else{
                        echo 'Không tồn tại!!!';
                    }
                }
                if(isset($_POST['suaDienKe'])){
                    $madk = $_POST['madk'];
                    $ngaysx = $_POST['ngaysx'];
                    $ngaylap = $_POST['ngaylap'];
                    $mota = $_POST['mota'];
                    $trangthai = $_POST['trangthai'];

                    $hasError=true;
                   
                    if(strlen($mota) > 100){
                        echo '<script>alert("Giá trị của Mô tả vượt quá giới hạn cho phép.");</script>';
                        $hasError = false;
                    }
                    if (!strtotime($ngaysx)) {
                        echo '<script>alert("Định dạng ngày sản xuất không hợp lệ.");</script>';
                        $hasError = false;
                    }
                    if (!strtotime($ngaylap)) {
                        echo '<script>alert("Định dạng ngày lắp không hợp lệ.");</script>';
                        $hasError = false;
                    }
                    
                    if(strtotime($ngaysx) > time() || strtotime($ngaylap) > time()){
                        echo '<script>alert("Ngày sản xuất và ngày lắp không được lớn hơn ngày hiện tại.");</script>';
                        $hasError = false;
                    }
                    // Kiểm tra ngày sản xuất và ngày lắp
                    if(strtotime($ngaysx) >= strtotime($ngaylap)){
                        echo '<script>alert("Ngày sản xuất phải nhỏ hơn ngày lắp.");</script>';
                        $hasError = false;
                    }
                    if($madk == '' && $makh == '' && $ngaysx == '' && $ngaylap == '' && $mota =='' && $trangthai == ''){
                        echo '<script>alert("Các trường dữ liệu không được để trống.");</script>';
                        $hasError = false;
                    }
                    if($trangthai != 0 && $trangthai != 1){
                        echo '<script>alert("Trạng thái điện kế không hợp lệ.");</script>';
                        $hasError = false;
                    }
            
                    if($hasError == true){
                        editDienKe($ngaysx, $ngaylap, $mota, $trangthai, $madk);
                        echo "<script>alert('Sửa điện kế có mã ".$madk. " thành công!');window.location.href = '../controller/dienke.php?act=quanlydienke';</script>";  
                    }else{
                        echo "<script>window.location.href = '../controller/dienke.php?act=quanlydienke';</script>";  
                    }

                }
                include "../view/suadienke.php";
                break;
            case "xoadienke":
                if(isset($_GET['madk'])){
                    $madk_xoa = $_GET['madk'];   
                    $ktratrcxoa= kiemTraXoa($madk_xoa);
                    if(!$ktratrcxoa){
                         deleteDienKe($madk_xoa);
                          // Thông báo xóa điện kế thành công
                        echo "<script>alert('Xóa điện kế mã ".$madk_xoa." thành công!');window.location.href = '../controller/dienke.php?act=quanlydienke'</script>";
                        exit();
                    }else{
                        echo "<script>alert('Không thể xóa điện kế do đã tồn tại ở các hóa đơn!');window.location.href = '../controller/dienke.php?act=quanlydienke';</script>"; 
                        exit(); 
                    }
                }
                break;
            default:
            include "../view/login.php";
            break;
        }
    } else { 
        include "../view/login.php";
    }
?>