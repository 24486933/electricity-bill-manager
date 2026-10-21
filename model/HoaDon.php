<?php

    function show_Bill_All(){
        $sql= "SELECT * FROM hoadon hd join cthoadon cthd on hd.mahd = cthd.mahd join dienke dk on cthd.madk = dk.madk join khachhang kh on dk.makh = kh.makh ORDER BY CAST(hd.mahd AS UNSIGNED) DESC";
        return pdo_query($sql);
    }
    function show_Data_HD_ID($madk){
        $sql="SELECT hoadon.chisocuoi, hoadon.ngaylaphd, hoadon.denngay FROM cthoadon join hoadon on cthoadon.mahd = hoadon.mahd 
        where cthoadon.madk=? 
        ORDER BY ngaylaphd DESC LIMIT 1";
        return pdo_query($sql,$madk);
    }

    function themhd($mahd, $manv, $ky,$tungay,$denngay,$chisodau, $chisocuoi, $tongthanhtien, $ngaylaphd, $tinhtrang){
        $sql = "INSERT INTO hoadon (mahd, manv, ky, tungay, denngay,chisodau,chisocuoi,tongthanhtien,ngaylaphd,tinhtrang) VALUES (?, ?,?, ?,?,?,?,?,?,?)";
        pdo_execute($sql, $mahd, $manv, $ky,$tungay,$denngay,$chisodau, $chisocuoi, $tongthanhtien, $ngaylaphd, $tinhtrang);
    }
    
    function showhd(){
        $sql = "SELECT * FROM hoadon";
        return pdo_query($sql);
    }

    function show_HD_BY_ID($idhd){
        $sql = "SELECT * FROM hoadon WHERE mahd=?";
        return pdo_query_one($sql, $idhd);
    }
    
    function show_HD_BY_Tung_ID($idhd){
        $sql = "SELECT * FROM hoadon WHERE mahd LIKE ?";
        return pdo_query($sql, "$idhd%");
    }

    function updateTrangThai($trangthai, $mahd){
        $sql = "UPDATE hoadon SET tinhtrang=? WHERE mahd=?";
        pdo_execute($sql, $trangthai, $mahd);
    }

    // function check_unpaid($tinhtrang){
    //     $sql = "SELECT * FROM hoadon hd join cthoadon cthd on hd.mahd =cthd.mahd
    //                                     join dienke dk on cthd.madk = dk.madk
    //                                     join khachhang kh on dk.makh = kh.makh
    //             WHERE hd.tinhtrang = ?";
    //    return pdo_query($sql, $tinhtrang);
    // }
    function check_unpaid($tinhtrang){
        //loại bỏ tất cả dấu . thành "", dùng cast chuyển chuỗi số thành giá trị nguyên, format định dạng lại giá trị, Cấu trúc '0' được truyền vào để chỉ định rằng không có số lẻ sau dấu chấm.
        //Cuối cùng, chúng ta sử dụng hàm REPLACE để thay thế dấu phẩy (,) bằng dấu chấm (.) trong giá trị đã được định dạng
        $sql = "SELECT kh.makh, kh.tenkh, kh.diachi, kh.dt, kh.cccd, REPLACE(FORMAT(SUM(CAST(REPLACE(hd.tongthanhtien, '.', '') AS SIGNED)), 0), ',', '.') AS sum_money
        FROM hoadon hd JOIN cthoadon cthd ON hd.mahd = cthd.mahd
                        JOIN dienke dk ON cthd.madk = dk.madk
                        JOIN khachhang kh ON dk.makh = kh.makh
        WHERE hd.tinhtrang = ?
        GROUP BY kh.makh, kh.tenkh
        ORDER BY CAST(kh.makh AS UNSIGNED)";
       return pdo_query($sql, $tinhtrang);
    }
    //SUM(CAST(REPLACE(hd.tongthanhtien, '.', '') AS SIGNED))
    function checkNoLauNhat($tinhtrang){
        $sql = "SELECT min(ngaylaphd) as nolau, hd.mahd, kh.makh, kh.tenkh, kh.diachi, kh.dt, kh.cccd 
        FROM hoadon hd JOIN cthoadon cthd ON hd.mahd = cthd.mahd
                    JOIN dienke dk ON cthd.madk = dk.madk
                    JOIN khachhang kh ON dk.makh = kh.makh
        WHERE hd.tinhtrang = ?";
        return pdo_query_one($sql, $tinhtrang);
    }

    function demSLHD($tinhtrang){
        $sql = "SELECT count(mahd) as sohd
        FROM hoadon WHERE tinhtrang = ?";
        return pdo_query_one($sql, $tinhtrang);
    }
    function show_unpaid_by_id($tinhtrang, $makh){
        $sql = "SELECT * FROM hoadon hd join cthoadon cthd on hd.mahd =cthd.mahd
                                        join dienke dk on cthd.madk = dk.madk
                                        join khachhang kh on dk.makh = kh.makh
                WHERE hd.tinhtrang = ? and kh.makh = ?";
       return pdo_query($sql, $tinhtrang, $makh);
    }
?>