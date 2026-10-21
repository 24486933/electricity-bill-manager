<?php
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
function getCustomerInfoByID($makh)
{
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

            // Truy vấn để lấy thông tin điện kế của khách hàng
            $sql_dienke = "SELECT * FROM dienke WHERE makh = ?";
            $dienkeData = pdo_query($sql_dienke, $customer['makh']);

            // Nếu có thông tin điện kế, thêm vào mảng thông tin khách hàng
            if ($dienkeData) {
                $customerInfo['dienke'] = $dienkeData;
            }
        }
    }

    // Trả về thông tin khách hàng
    return $customerInfo;
}

function searchIDKH($makh)
{
    $data = array();
    $sql_khachhang = "SELECT * FROM khachhang WHERE makh = ?";
    $khachhang = pdo_query($sql_khachhang, $makh);

    foreach ($khachhang as $kh) {
        $idkh = $kh['makh'];
        if ($idkh !== null) {
            $sql_dienke = "SELECT * FROM dienke WHERE makh = ?";
            $dienke = pdo_query($sql_dienke, $idkh);
            $kh['dienke'] = $dienke;
            $data['khachhang'][] = $kh;
        }
    }
    return $data;
}

function searchNameKH($tenkh)
{
    $data = array();
    $sql_khachhang = "SELECT * FROM khachhang WHERE tenkh LIKE ?";
    $khachhang = pdo_query($sql_khachhang, "%$tenkh%");

    foreach ($khachhang as $kh) {
        $idkh = $kh['makh'];
        if ($idkh !== null) {
            $sql_dienke = "SELECT * FROM dienke WHERE makh = ?";
            $dienke = pdo_query($sql_dienke, $idkh);
            $kh['dienke'] = $dienke;
            $data['khachhang'][] = $kh;
        }
    }
    return $data;
}

function checkXoaKH($makh){
    $sql ="SELECT * FROM khachhang kh join dienke dk on kh.makh = dk.makh WHERE kh.makh=?";
    $result = pdo_query($sql,$makh);

    return count($result) > 0;
}

function getLastCustomerID() { 
    $sql = "SELECT LPAD(MAX(CAST(TRIM(LEADING '0' FROM makh) AS UNSIGNED)), 13, '0') AS last_makh FROM khachhang";
    return pdo_query_one($sql);
}
