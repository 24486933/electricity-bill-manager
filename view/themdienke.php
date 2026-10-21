<link rel="stylesheet" href="../assets/css/themdienke.css?">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<?php
if (isset($_SESSION['id_nv'])) {
    ?>
    <div class="content">
        <form method="post" action="">
            <h2 class="h">Thêm điện kế cho khách hàng mã: <span id="makhhtml"></span></h2>
            <input type="hidden" name="makh" id="makh" readonly>
            <div class="aaa">
                <label for="madk">Mã điện kế:</label>
                <?php if (isset($last_madk)) {
                    $id_dk = $last_madk['max_madk'];
                    $id_dk = intval($id_dk) + 1;
                    $id_dk_padded = str_pad($id_dk, 8, "0", STR_PAD_LEFT);
                    echo '<input type="text" name="madk" id="madk" value="' . $id_dk_padded . '" required> <br>';
                } else { ?>
                    <input type="text" name="madk" id="madk" required> <br>
                <?php } ?>

            </div>
            <div class="aaa"> <label for="ngaysx">Ngày sản xuất:</label>
                <input type="datetime-local" name="ngaysx" id="ngaysx" required>
            </div>
            <div class="aaa"> <label for="ngaylap">Ngày lắp:</label>
                <input type="datetime-local" name="ngaylap" id="ngaylap" required>
            </div>
            <div class="aaa">
                <label for="mota">Mô tả:</label>
                <textarea name="mota" id="mota" rows="4" cols="50" required></textarea>
            </div>
            <div class="aaa"> <label for="trangthai">Trạng thái:</label>
                <select class="se" name="trangthai" id="trangthai" required>
                    <option value="1">Sử dụng</option>
                    <option value="0">Chưa sử dụng</option>
                </select>
                
                <input class="sub" type="submit" name="addDienke" value="Thêm điện kế">
            </div>


        </form>
        <button class="back" onclick="goBack()"><a href="../controller/dienke.php?act=quanlydienke" style=" font-weight: bold; text-decoration: none; color: white;">Quay lại</a></button>
       
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var selectedMakh = localStorage.getItem('selectedMakh');
                if (selectedMakh) {
                    document.getElementById('makh').value = selectedMakh;
                    document.getElementById('makhhtml').innerText = selectedMakh;
                } else {
                    alert('Thông tin không hợp lệ. Vui lòng thử lại.');
                }
            });
        </script>
    </div>


<?php } else {
    header('location: ../controller/nhanvien.php?act=login');
} ?>