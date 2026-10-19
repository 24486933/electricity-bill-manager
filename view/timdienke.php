<link rel="stylesheet" href="../assets/css/quanlyhoadon.css?v=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .text {
        width: 75%;
        height: 40px;
        text-indent: 10px;
        position: relative;
        left: 60px;
    }

    .subb {
        width: 140px;
        height: 40px;
        border-radius: 5px;
        background-color: #333;
        color: white;
        font-weight: bold;
        margin-left: 10px;
        position: relative;
        left: 70px;
    }

    .out {
        position: relative;
        top: 100px;
        width: 150px;
        height: 40px;
        border-radius: 5px;
        background-color: #333;
        color: white;
        font-weight: bold;
        margin-left: 10px;
    }

    .out a {
        text-decoration: none;
        color: white;
    }

    label {
        position: relative;
        left: 40px;
    }

    .sua,
    .xoa {
        width: 100px;
        height: 30px;
        border-radius: 5px;
        background-color: #333;
        color: white;
        font-weight: bold;
    }
</style>
<?php

if (isset($_SESSION['id_nv'])) {
    ?>
    <div>
        <h2>Tìm kiếm điện kế</h2>
        <form action="" method="post">
            <label for="madk">Mã điện kế:</label>
            <input class="text" type="text" id="madk" name="madk" placeholder="Nhập mã điện kế...">
            <input class="subb" type="submit" name="SearchDK" value="Tìm kiếm">
        </form>
    </div>

    <?php
    if (isset($search_DK)) {
        if ($search_DK && !empty($search_DK)) {
            echo '<table border="1">
            <tr>
                <th>Mã điện kế</th>
                <th>Mã khách hàng</th>
                <th>Ngày sản xuất</th>
                <th>Ngày lắp</th>
                <th>Mô tả</th>
                <th>Trạng thái</th>
                <th colspan="2">Thao tác</th>
            </tr>';
            echo '<tr>';
            echo '<td>' . $search_DK['madk'] . '</td>';
            echo '<td>' . $search_DK['makh'] . '</td>';
            echo '<td>' . $search_DK['ngaysx'] . '</td>';
            echo '<td>' . $search_DK['ngaylap'] . '</td>';
            echo '<td>' . $search_DK['mota'] . '</td>';
            if ($search_DK['trangthai'] == 0) {
                echo '<td>Không còn sử dụng</td>';
            } else {
                echo '<td>Còn sử dụng</td>';
            }
            echo "<td>                                
            <form method='post' action='../controller/dienke.php?act=suadienke'>
            <input type='hidden' name='madk' value='" . $search_DK['madk'] . "'>
            <button type='submit' class='sua' name='editDienKe'>Sửa</button>
        </form>
        
            </td>";

            if (kiemTraXoa($search_DK['madk'])) {
                echo "<td>Không thể xóa điện kế này do đã tồn tại hóa đơn. </td>";
            } else {
                echo "<td>
                <form id='deleteForm' method='post' action=''>
                    <input type='hidden' name='madk' value='" . $search_DK['madk'] . "'>
                    <button type='submit' class='xoa' name='deleteDienKe' onclick='showConfirmation()'>Xóa</button>
                </form>
            </td>";
            }
            echo '</tr>';
            echo '</table>';
        } else {
            echo "Không tìm thấy điện kế trong CSDL.";
        }
    }
    ?>
    <button class="out"><a href="../controller/tiendien.php?act=quanly">Thoát</a></button>
    <script>
        function showConfirmation() {
            if (confirm("Bạn có chắc chắn muốn xóa không?")) {
                // Nếu xác nhận, thay đổi giá trị action và submit form
                document.getElementById("deleteForm").action = "../controller/dienke.php?act=xoadienke";
                document.getElementById("deleteForm").submit();
            } else {

                event.preventDefault();
                return false;
            }
        }
    </script>

<?php } else {
    header('location: ../controller/nhanvien.php?act=login');
} ?>