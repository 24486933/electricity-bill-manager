<?php
    function checkAcccount($username, $password) {
        $sql = "SELECT * FROM nhanvien WHERE taikhoan=? and matkhau=?  ";
        return pdo_query($sql, $username, $password);
    }
    function check_Info_ById($id){
        $sql= "SELECT * FROM nhanvien WHERE manv=?";
        return pdo_query_one($sql, $id);
    }

    function themNhanVien($tk, $mk, $name, $dc, $dt, $cccd, $quyen){
        $sql= "INSERT INTO nhanvien (taikhoan, matkhau, tennv, diachi, dt, cccd, quyen) VALUES (?,?,?,?,?,?,?)";
        pdo_execute($sql, $tk, $mk, $name, $dc, $dt, $cccd, $quyen);
    }

    function suaNhanVien($name, $dc, $dt, $cccd, $quyen, $manv){
        $sql ="UPDATE nhanvien SET tennv= ?, diachi =? , dt =? , cccd = ?, quyen = ? WHERE manv = ?";
        pdo_execute($sql, $name, $dc, $dt, $cccd, $quyen, $manv);
    }
    function updateInfo_NV($mkm, $tk, $mkc){
        $sql="UPDATE nhanvien SET matkhau= ? WHERE taikhoan = ? and matkhau=?";
        pdo_execute($sql, $mkm, $tk, $mkc);
    }
    function show_All_NhanVien(){
        $sql = "SELECT * FROM nhanvien";
        return pdo_query($sql);
    }

    function kiemTraTruocXoa($manv){
        $sql = "select * from nhanvien nv join hoadon hd on nv.manv = hd.manv where nv.manv = ?";
        $result = pdo_query($sql, $manv);

        return count($result) > 0;
    }

    function deleteNhanVien($manv){
        $sql= "DELETE FROM nhanvien WHERE manv=?";
        pdo_execute($sql, $manv);
    }
    function kiem_tra_so_dien_thoai_user($so_dien_thoai)
    {
        //độ dài khác 10 và ko phải số thì false
        if (strlen($so_dien_thoai) !== 10 || !is_numeric($so_dien_thoai)) {
            return false;
        }

        $dau_so = substr($so_dien_thoai, 0, 3);
        $viettel = array("086", "096", "097", "098", "032", "033", "034", "035", "036", "037", "038", "039");
        $mobifone = array("089", "090", "093", "070", "079", "077", "076", "078");
        $vinaphone = array("088", "091", "094", "083", "084", "085", "081", "082");
        $dau_so_hop_le = array_merge($viettel, $mobifone, $vinaphone);

        if (in_array($dau_so, $dau_so_hop_le)) {
            return true;
        }

        return false;
    }
    function checktrungtenuser($email){
        $sql = "SELECT taikhoan FROM nhanvien WHERE taikhoan=?";
        $result = pdo_query($sql, $email);
        return count($result) > 0;
    }

?>