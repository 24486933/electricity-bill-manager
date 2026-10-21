<style>
    body {
        font-family: Arial, sans-serif;
    }

    form {
        width: 90%;
        padding: 20px;
        margin: 0 auto;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    label {
        color: #333;
        display: block;
        margin-bottom: 10px;
    }

    input[type="email"],
    input[type="password"],
    input[type="text"],
    input[type="number"],
    select {
        width: calc(100% - 10px);
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    input[type="submit"] {
        background-color: #333;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    button a {
        text-decoration: none;
        color: #333;
    }

    button {
        background-color: #f0f0f0;
        border: none;
        color: #333;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 4px;
        transition-duration: 0.4s;
    }

    button:hover {
        background-color: #ddd;
    }
    /* Điều chỉnh CSS cho lớp "errormesseger" */
.errormesseger {
    color: red;
    font-weight: bold;
    width: 100%;
    text-align: center;
    margin-bottom: 20px; /* Thêm khoảng cách dưới */
    padding: 20px;
    background-color: #ffffff;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
</style>
<button><a href="../controller/nhanvien.php?act=quanlynhanvien">Quay lại</a></button>

<?php
if (isset($showin4) && !empty($showin4)) {
    ?>
    <h2>Sửa thông tin </h2>
    <form method="post" action="">
        <input type="hidden" id="manv" name="manv" value="<?php echo $showin4['manv'] ?>" required>

        <label for="tennv">Tên nhân viên:</label>
        <input type="text" id="tennv" name="tennv" value="<?php echo $showin4['tennv'] ?>" required><br>

        <label for="diachi">Địa chỉ:</label>
        <input type="text" id="diachi" name="diachi" value="<?php echo $showin4['diachi'] ?>"><br>

        <label for="dt">Điện thoại:</label>
        <input type="text" id="dt" name="dt" value="<?php echo $showin4['dt'] ?>"><br>

        <label for="cccd">CCCD:</label>
        <input type="text" id="cccd" name="cccd" value="<?php echo $showin4['cccd'] ?>"><br>


        <?php
        if (isset($_SESSION['quyen']) && $_SESSION['quyen'] == 1) {
            ?>
            <?php
            if ($showin4['quyen'] == 0) {
                echo '
                <label for="quyen">Quyền:</label>
            <select id="quyen" name="quyen">
            <option value="0">Nhân viên</option>
            <option value="1">Quản lý</option>
            </select><br>

            ';
            } else {
                echo '
                
            <input type="hidden" name="quyen" value="-1">
            Không thể sửa quyền người này do đang là quản lý, muốn chỉnh sửa vui lòng liên hệ QTV.<br>
            ';
            }

        }
        ?>

        <input type="submit" name="suaNhanVien" value="Sửa thông tin">
    </form>
<?php } ?>