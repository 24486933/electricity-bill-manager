<?php
    function addDienKe($madk, $makh, $ngaysx, $ngaylap, $mota, $trangthai){
        $sql= "INSERT INTO dienke (madk, makh, ngaysx, ngaylap, mota, trangthai) VALUES (?,?,?,?,?,?)";
        pdo_execute($sql, $madk, $makh, $ngaysx, $ngaylap, $mota, $trangthai);
    }

    function editDienKe($ngaysx, $ngaylap, $mota, $trangthai, $madk){
        $sql = "UPDATE dienke SET ngaysx = ?, ngaylap = ?, mota = ?, trangthai = ? WHERE madk = ?";
        pdo_execute($sql, $ngaysx, $ngaylap, $mota, $trangthai, $madk);
    }

    function kiemTraXoa($madk){
        $sql ="SELECT * FROM dienke dk join cthoadon cthd on dk.madk = cthd.madk WHERE dk.madk= ?";
        $result = pdo_query($sql, $madk);
        return count($result) > 0;
    }
    function deleteDienKe($madk) {
        $sql = "DELETE FROM dienke WHERE madk = ?";
        pdo_execute($sql, $madk);
    }
    function searchIDDK($madk){
        $sql = "SELECT * FROM dienke WHERE madk=?";
        return pdo_query_one($sql, $madk);
    }

    function last_IDDK(){
        //TRIM để loại bỏ tất cả các ký tự '0' từ phía trước của chuỗi 
        // LEADING để chỉ định rằng loại bỏ ký tự '0' từ phía trước của chuỗi. Điều này loại bỏ các số '0' dư thừa từ mã điện kế.
        // hàm CAST để chuyển đổi chuỗi đã chuẩn hóa thành một giá trị số nguyên
        // hàm LPAD để thêm các ký tự '0' vào đầu mã điện kế để đảm bảo rằng tất cả các mã đều có cùng độ dài
        $sql = "SELECT LPAD(MAX(CAST(TRIM(LEADING '0' FROM madk) AS UNSIGNED)), 8, '0') AS max_madk FROM dienke";
        return pdo_query_one($sql);
    }
    
    function show_DK_BY_ID($madk){
        $sql = "SELECT * FROM dienke WHERE madk=?";
        return pdo_query_one($sql, $madk);
    }
?>