<?php
require_once('../model/config.php');

class KhachHang_Model {
    public function getAllKhachHang() {
        global $conn;
        $sql = "SELECT * FROM khachhang";
        return pdo_query($sql);
    }
   
    public function isMaKHTonTai($makh) {
        $sql = "SELECT COUNT(*) AS count FROM khachhang WHERE makh = ?";
        $row = pdo_query_one($sql, $makh);
        return $row['count']>0;
    }
    public function addKhachHang($makh, $tenkh, $diachi, $dt, $cccd) {
        global $conn;
        $errors = array();
        if ($this->isMaKHTonTai($makh)) {
            $errors[] = "Mã khách hàng đã tồn tại trong CSDL.";
        }
        //Kiểm tra mã khách hàng có đủ 13 số không
        if (strlen($makh) != 13) {
            $errors[] = "Mã khách hàng phải có đủ 13 số.";
        }

        // Kiểm tra tên khách hàng không được để trống
        if (empty($tenkh)) {
            $errors[] = "Vui lòng nhập tên khách hàng.";
        }

        // Kiểm tra địa chỉ không được để trống
        if (empty($diachi)) {
            $errors[] = "Vui lòng nhập địa chỉ.";
        }

        // Kiểm tra số điện thoại chỉ được nhập số và không được để trống
        if (!is_numeric($dt) || empty($dt)) {
            $errors[] = "Số điện thoại không hợp lệ.";
        }

        // Kiểm tra CCCD chỉ được nhập số, không được để trống, và phải có đủ 12 số
        if (!is_numeric($cccd) || empty($cccd) || strlen($cccd) != 12) {
            $errors[] = "CCCD không hợp lệ.";
        }

        // Nếu có lỗi, trả về mảng chứa các lỗi
        if (!empty($errors)) {
            return $errors;
        }

        // Nếu không có lỗi, thực hiện thêm thông tin khách hàng vào cơ sở dữ liệu
        $sql = "INSERT INTO khachhang (makh, tenkh, diachi, dt, cccd) VALUES (?, ?, ?, ?, ?)";
        pdo_execute($sql, $makh, $tenkh, $diachi, $dt, $cccd);
        
        // Trả về true để cho biết thêm thông tin khách hàng thành công
        return true;
    }


    public function deleteKhachHang($makh) {
        global $conn;
    
        // Kiểm tra xem khách hàng có điện kế không
        $sql = "SELECT COUNT(*) AS count FROM dienke WHERE makh = ?";
        $row = pdo_query_one($sql, $makh);
    
        // Nếu khách hàng có điện kế, không cho phép xóa và thông báo cho người dùng
        if ($row['count'] > 0) {
            echo "<script>alert('Khách hàng có điện kế, không thể xóa!'); window.location.href = document.referrer;</script>";
            return;
        }
    
        // Nếu khách hàng không có điện kế, tiến hành xóa
        $sql_delete = "DELETE FROM khachhang WHERE makh = ?";
        pdo_execute($sql_delete, $makh);
    
        // Thông báo xóa thành công và chuyển hướng người dùng về trang trước
        echo "<script>alert('Xóa khách hàng $makh thành công!'); window.location.href = document.referrer;</script>";
    }
    

    public function updateKhachHang($makh, $tenkh, $diachi, $dt, $cccd) {
        global $conn; // Sử dụng kết nối đã được thiết lập trong file config.php
        $errors = array();
        if (empty($tenkh)) {
            $errors[] = "Vui lòng nhập tên khách hàng.";
        }

        // Kiểm tra địa chỉ không được để trống
        if (empty($diachi)) {
            $errors[] = "Vui lòng nhập địa chỉ.";
        }

        // Kiểm tra số điện thoại chỉ được nhập số và không được để trống
        if (!is_numeric($dt) || empty($dt)) {
            $errors[] = "Số điện thoại không hợp lệ.";
        }
        // Kiểm tra CCCD chỉ được nhập số, không được để trống, và phải có đủ 12 số
        if (!is_numeric($cccd) || empty($cccd) || strlen($cccd) != 12) {
            $errors[] = "CCCD không hợp lệ.";
        }
            // Chuẩn bị câu truy vấn UPDATE
            $sql = "UPDATE khachhang SET makh = ?, tenkh = ?, diachi = ?, dt = ?, cccd = ? WHERE makh = ?";
            pdo_execute($sql, $makh, $tenkh, $diachi, $dt, $cccd,$makh);
            return true;
    }
    function getKhachHangByMaKH($makh) {
        // Khởi tạo một mảng để lưu trữ dữ liệu khách hàng
        $customerInfo = array();
    
        // Truy vấn để lấy thông tin khách hàng từ mã khách hàng
        $sql = "SELECT * FROM khachhang WHERE makh = ?";
        $customerData = pdo_query($sql, $makh);
    
        // Kiểm tra xem có dữ liệu khách hàng không
        if ($customerData) {
            // Lặp qua kết quả từ truy vấn
            foreach ($customerData as $customer) {
                // Lấy thông tin của khách hàng
                $customerInfo['makh'] = $customer['makh'];
                $customerInfo['tenkh'] = $customer['tenkh'];
                $customerInfo['diachi'] = $customer['diachi'];
                $customerInfo['dt'] = $customer['dt'];
                $customerInfo['cccd'] = $customer['cccd'];

            }
        }
    
        // Trả về thông tin khách hàng
        return $customerInfo;
    }
    
   
}
?>
