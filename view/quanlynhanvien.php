<link rel="stylesheet" href="../assets/css/quanlyhoadon.css?v=3">
<style>
    body {
        overflow-x: hidden;
    }

    button {
        padding: 10px;
        background-color: #333;
        color: white;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        font-weight: bold;
        position: relative;
        top: 10px;
        margin-bottom: 10px;
        width: 150px;
    }

    button a {
        text-decoration: none;
        color: white;
        font-size: 12px;

    }

    .sua {
        left: -10px;
    }

    h3 {
        position: relative;
        left: 40px;
        width: 500px;
    }

    .form {
        position: relative;
        left: 40px;
    }

    .password {
        width: 700px;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-left: 30px;
        position: relative;
    }

    .pp {
        left: 14px;
    }

    .xacnhan {
        left: 0px;
    }

    .sua {
        position: relative;
        left: 0px;
    }

</style>
<button><a href="../controller/tiendien.php?act=quanly">Quay lại</a></button>
<div>
    <button class="add"><a href="../controller/nhanvien.php?act=themnhanvien">Thêm account mới</a></button>
</div>
<?php
if (isset($checkinfouser) && !empty($checkinfouser)) {
    echo "<h3>Thông tin người dùng đang đăng nhập</h3>";
    echo "<table border='1'>";
    echo "<tr>
                <th>Tài khoản</th>
                <th>Mật khẩu</th>
                <th>Tên nhân viên</th>
                <th>Địa chỉ</th>
                <th>Điện thoại</th>
                <th>CCCD</th>
                <th>Quyền</th>
                <th colspan='3'>Chức năng</th>
            </tr>";

    echo "<tr>";
    // Hiển thị các trường của nhân viên trong các ô của bảng
    echo "<td>" . $checkinfouser['taikhoan'] . "</td>";
    echo "<td>" . substr(str_repeat('*', strlen($checkinfouser['matkhau'])), 0, 10) . "</td>";
    echo "<td>" . $checkinfouser['tennv'] . "</td>";
    echo "<td>" . $checkinfouser['diachi'] . "</td>";
    echo "<td>" . $checkinfouser['dt'] . "</td>";
    echo "<td>" . $checkinfouser['cccd'] . "</td>";
    if ($checkinfouser['quyen'] == 1) {
        echo "<td>Quản lý</td>";
    } else {
        echo "<td>Nhân viên</td>";
    }
    echo "<td> <a href='#' onclick='tChangePasswordForm()'>Đổi mật khẩu</a> </td>";
    echo "<td> 
    <form action='../controller/nhanvien.php?act=suanhanvien' method='post'> 
        <input type='hidden' name='iduser' value='" . $checkinfouser['manv'] . "'>
        <button class='sua' name='edituser' >Sửa </button>
    </form>
</td>";
    echo "</tr>";

    echo "</table>";
}
?>
<div id="changePasswordForm" style="display: none;">
    <h3>Đổi mật khẩu</h3>
    <form class="form" action="" method="post">
        <input type="hidden" name="taikhoan" value="<?php echo $checkinfouser['taikhoan']; ?>">
        <label for="newPassword">Mật khẩu cũ:</label>
        <input class="password pp" type="password" name="oldPassword" id="oldPassword" required><br><br>
        <label for="newPassword">Mật khẩu mới:</label>
        <input class="password" type="password" name="newPassword" id="newPassword" required><br><br>
        <button class="xacnhan" type="submit" name="changePass">Xác nhận</button>
    </form>
</div>
<script>
    var isChangePasswordFormVisible = false;
    function tChangePasswordForm() {
        var changePasswordForm = document.getElementById("changePasswordForm");
        if (isChangePasswordFormVisible) {
            changePasswordForm.style.display = "none";
            isChangePasswordFormVisible = false;
        } else {
            changePasswordForm.style.display = "block";
            isChangePasswordFormVisible = true;
        }
    }
</script>


<?php
if (isset($checkinfouser) && !empty($checkinfouser)) {

    if ($checkinfouser['quyen'] == 1) {
        if (isset($show_All_NV) && !empty($show_All_NV)) {
            $listsPerPage = isset($_GET['size']) ? (int) $_GET['size'] : 10;
            $currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;
            $startIndex = ($currentPage - 1) * $listsPerPage; // tính sl kh ở mỗi trang và trang htai
            $filteredCustomers = array_filter($show_All_NV, function ($user) {
                return isset ($user['manv']);
            });

            // xếp mảng giảm dần theo mã
            usort($filteredCustomers, function ($a, $b) {
                return $b['manv'] <=> $a['manv'];
            });

            // lấy sl dữ liệu của khách hàng
            $totalCustomers = count($filteredCustomers);
            // tính trang
            $totalPages = ceil($totalCustomers / $listsPerPage);
            // hiện dl cho trang hiện tại
            $listsToShow = array_slice($filteredCustomers, $startIndex, $listsPerPage);
            echo "<h2>Danh sách Account</h2>";

            echo "<table border='1'>";
            echo "<tr>
                <th>Tài khoản</th>
                <th>Mật khẩu</th>
                <th>Tên nhân viên</th>
                <th>Địa chỉ</th>
                <th>Điện thoại</th>
                <th>CCCD</th>
                <th>Quyền</th>
                <th colspan='2'>Tool</th>
            </tr>";
            // Duyệt qua từng nhân viên và hiển thị thông tin của họ trong các dòng của bảng
            foreach ($listsToShow as $nv) {
                if ($nv['taikhoan'] != $checkinfouser['taikhoan']) {
                    echo "<tr>";
                    $email = $nv['taikhoan'];
                    // tách phần tên tài khoản và tên miền
                    list($username, $domain) = explode('@', $email);
                    // Lấy kích thước của phần tên tài khoản
                    $usernameLength = strlen($username);
                    // Kiểm tra nếu kích thước lớn hơn 2, thực hiện việc thay thế các ký tự ở giữa thành "*"
                    if ($usernameLength > 2) {
                        // Lấy ký tự đầu và cuối của phần tên tài khoản
                        $firstChar = substr($username, 0, 1);
                        $lastChar = substr($username, -1);
                        // Tạo chuỗi dấu * có độ dài bằng phần giữa của tên tài khoản
                        $hiddenChars = str_repeat('*', $usernameLength - 2);
                        // Tạo tên tài khoản mới với dấu * ở giữa
                        $hiddenUsername = $firstChar . $hiddenChars . $lastChar;
                        // Ghép lại tên tài khoản và tên miền thành email mới
                        $hiddenEmail = $hiddenUsername . '@' . $domain;
                    } else {
                        // Nếu kích thước nhỏ hơn hoặc bằng 2, không thay đổi
                        $hiddenEmail = $email;
                    }
                    echo "<td>" . $hiddenEmail . "</td>";
                    echo "<td>" . substr(str_repeat('*', strlen($nv['matkhau'])), 0, 10) . "</td>";
                    echo "<td>" . $nv['tennv'] . "</td>";
                    echo "<td>" . $nv['diachi'] . "</td>";
                    echo "<td>" . $nv['dt'] . "</td>";
                    echo "<td>" . $nv['cccd'] . "</td>";
                    if ($nv['quyen'] == 1) {
                        echo "<td>Quản lý</td>";
                        echo "<td> 
                        <form action='../controller/nhanvien.php?act=suanhanvien' method='post'> 
                            <input type='hidden' name='iduser' value='" . $nv['manv'] . "'>
                            <button class='sua' name='edituser' >Sửa </button>
                        </form>
                    </td>";
                        echo "<td>Người này là quản lý<br>không thể xóa</td>";
                    } else {
                        echo "<td>Nhân viên</td>";
                        echo "<td> 
                        <form action='../controller/nhanvien.php?act=suanhanvien' method='post'> 
                            <input type='hidden' name='iduser' value='" . $nv['manv'] . "'>
                            <button name='edituser' class='sua' >Sửa </button>
                        </form>
                    </td>";
                        if (kiemTraTruocXoa($nv['manv'])) {
                            echo "<td>Không thể xóa này viên này<br>do đã tồn tại trong các hóa đơn.</td>";
                        } else {
                            echo '<td><button class="xoa"> <a style="text-decoration: none;font-weight: bold;color: white;" href="../controller/nhanvien.php?act=xoanhanvien&manv='.$nv['manv'].'" onclick="return confirm(\'Bạn có chắc chắn muốn xóa không?\')">Xóa</a></button></td>';
                        }
                    }

                    echo "</tr>";
                }
            }
            echo "</table>";
        } else {
            echo "Không có nhân viên nào.";
        }
        if ($totalPages > 1) {
            echo '<div></div>
                    <div>';
            if ($currentPage > 1) {
                echo '<button class="navi selected"><a href="?act=quanlynhanvien&p=' . ($currentPage - 1) . '&size=' . $listsPerPage . '">&lt;</a></button>';
            }
            // Tính & hiện các liên kết phân trang
            $start = max(1, $currentPage - 1);
            $end = min($totalPages, $start + 3);
            for ($i = $start; $i <= $end; $i++) {
                if ($i == $currentPage) {
                    echo '<button class="navi selected chosse"><span >' . $i . '</span></button>';
                } else {
                    echo '<button class="navi selected"><a href="?act=quanlynhanvien&p=' . $i . '&size=' . $listsPerPage . '">' . $i . '</a></button>';
                }
            }
            if ($currentPage < $totalPages) {
                echo '<button class="navi selected"><a href="?act=quanlynhanvien&p=' . ($currentPage + 1) . '&size=' . $listsPerPage . '">&gt;</a></button>';
            }
            echo '</div>';
        }
    }

}
?>
