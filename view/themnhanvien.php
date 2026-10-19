<style>
    body {
        font-family: Arial, sans-serif;
        overflow-y: auto;
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
        color: white;
    }

    button {
        background-color: #333;
        border: none;

        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 4px;
        transition-duration: 0.4s;
        position: relative;
        right: -770px;
        top: -670px;

    }

    button:hover {
        background-color: #d4d4d4;

    }

    button a:hover {
        color: #333;
    }

    .side-bar {
        width: 280px;
        height: 100vh;
        background-color: #333;
        position: fixed;
        /* để sidebar luôn cố định trên màn hình */
        top: 5px;
        left: 5px;
        border-radius: 5px;
        overflow-y: auto;
        /* Cho phép cuộn nếu nội dung quá dài */
    }

    .img {
        width: 180px;
        border-radius: 50%;
        position: relative;
        left: 50px;
        top: 30px;
    }

    .content {
        width: 100%;
        height: 100vh;
        display: flex;
        justify-content: space-between;
    }

    .manage {
        width: calc(100% - 280px);
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    form {
        width: 90%;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        position: relative;
        left: 300px;
    }

    h2 {
        position: relative;
        left: -80px;
    }

    .errormesseger {
        color: red;
        font-weight: bold;
        width: 100%;
        text-align: center;
        margin-bottom: 20px;
        /* Thêm khoảng cách dưới */
        padding: 20px;
        background-color: #ffffff;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>
<div class="content">
    <div class="side-bar">
        <img src="../assets/user.jpg" alt="" width="40px" class="img">
    </div>
    <div class="manage">
        <h2>Thêm tài khoản nhân viên </h2>
        <form method="post" action="">

            <label for="taikhoan">Tài khoản:</label>
            <input type="email" id="taikhoan" name="taikhoan" required><br>

            <label for="matkhau">Mật khẩu:</label>
            <input type="password" id="matkhau" name="matkhau" required><br>

            <label for="tennv">Tên nhân viên:</label>
            <input type="text" id="tennv" name="tennv" required><br>

            <label for="diachi">Địa chỉ:</label>
            <input type="text" id="diachi" name="diachi"><br>

            <label for="dt">Điện thoại:</label>
            <input type="text" id="dt" name="dt"><br>

            <label for="cccd">CCCD:</label>
            <input type="text" id="cccd" name="cccd"><br>

            <label for="quyen">Quyền:</label>
            <select id="quyen" name="quyen">
                <option value="0">Nhân viên</option>
                <option value="1">Quản lý</option>
            </select><br>

            <input type="submit" name="themNhanVien" value="Thêm nhân viên">
        </form>

        <button><a href="../controller/nhanvien.php?act=quanlynhanvien">Quay lại</a></button>
    </div>
</div>