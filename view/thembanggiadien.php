<link rel="stylesheet" href="../assets/css/thembacgiadien.css?v=2">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<?php

if (isset($_SESSION['id_nv'])) {
    ?>
    <?php
    if (isset($_POST['them'])) {
        echo '
        <div class="side-bar">
            <img src="../assets/user.jpg" alt="" width="40px" class="img">
        </div>';
        echo '<div class="content">';
        echo '<button class="btnnn"><a href="../controller/giadien.php?act=quanlygiadien">Hủy</a></button>';
        $so_bac = $_POST['so_bac'];

        if($so_bac > 0){ 
        echo '<span class="span">Nhập thông tin giá điện cho ' . $so_bac . ' bậc</span>';
        echo '<form id="idform" method="post" action="">';

        for ($i = 0; $i < $so_bac; $i++) {
            echo '<h3>Thông tin bậc ' . ($i + 1) . '</h3>';

            echo '<input type="hidden" name="tenbac[]" value="Bậc ' . ($i + 1) . '" required>';

            echo '<label class="lso" for="tusokw">Từ số KW:</label>';
            if ($i === 0) {
                echo '<input class="so" type="number" id="tusokw_' . $i . '" name="tusokw[]" value="0" required readonly>';
            } else {
                echo '<input class="so" type="number" id="tusokw_' . $i . '" name="tusokw[]" required>';
            }

            echo '<label  class="lso" for="densokw">Đến số KW:</label>';
            if ($i === $so_bac - 1) {
                echo '<input type="text" class="so" id="densokw_' . $i . '" name="densokw[]" value="Trở lên" readonly>';
            } else {
                echo '<input type="number"  class="so" id="densokw_' . $i . '" name="densokw[]"  oninput="calculateNextTusokw(this)" required>';
            }

            echo '<label  class="lso" for="dongia">Đơn giá:</label>';
            echo '<input class="so" type="text" id="dongia_' . $i . '" name="dongia[]" required>';

            echo '<br><br>';
        }
        echo '<input class="btnnnn" type="submit" name="submit" value="Thêm">';
        echo '</form>
        </div>';
    }else{
        echo '<br><span style="color:red; font-weight: bold; font-size: 25px;">Số bậc phải lớn hơn 0<br>
        <a href="../controller/giadien.php?act=themgiadien">Vui lòng nhập lại</a></span>
        ';
    }

    } else {
        echo '            <div class="side-bar">
        <img src="../assets/user.jpg" alt="" width="40px" class="img">

    </div>';
    echo '<div class="content">';

        echo '<span>Thêm giá điện</span>';
        echo '<form method="post" action="" onsubmit="return kiemTraSoBac()" class="them">';
        echo '<label for="so_bac">Nhập số bậc bảng giá điện mới:</label>';
        echo '<input type="number" name="so_bac" id="so_bac" required>';
        echo '<input type="submit" name="them" value="Tiếp tục" class="sub">';
        echo '</form>';
        echo '<br><button class="btnn"><a href="../controller/tiendien.php?act=quanly">Hủy</a></button>
        ';
        echo '</div>';

    }
    ?>
    <script src="../assets/js/giadienthem.js"></script>
    <script>
        // Trình định nghĩa hàm kiểm tra giá trị nhập vào
        function kiemTraSoBac() {
            var soBac = document.getElementById('so_bac').value;
            var nguong = 10; // ngưỡng
            if(soBac > 0 ){
                if (soBac > nguong) {
                    var conformMessage = 'Bạn có chắc chắn muốn nhập ' + soBac + ' bậc?'; // Thông báo xác nhận
                    if (confirm(conformMessage)) {
                        return true;
                        } else {
                            return false;
                        }
                    } else {
                    return true;
                }
            }else{
                alert("Số bậc phải lớn hơn 0, vui lòng nhập lại.");
                return false;
            }
            
        }

        // Hàm tính toán giá trị "Từ số KW" cho bậc tiếp theo
        function calculateNextTusokw(input) {
            var densokw = parseInt(input.value);
            var getI = parseInt(input.id.split('_')[1]);
            var tusokwInput = document.getElementById('tusokw_' + (getI + 1));
            var tusokw = densokw + 1;
            tusokwInput.value = tusokw;
        }

    </script>
<script>
        function checkLastDensokwValue() {
            var lastDensokwInput = document.getElementById('densokw_<?php echo $so_bac - 1; ?>');
            var lastDensokwValue = lastDensokwInput.value;
            if (lastDensokwValue !== "Trở lên") {
                alert("Vui lòng chỉ đặt giá trị của trường 'Đến số KW' cuối cùng là 'Trở lên'.");
                lastDensokwInput.focus(); // Di chuyển con trỏ đến trường "Đến số KW" cuối cùng
                return false;
            }
            return true;
        }
            // Gắn sự kiện kiểm tra giá trị nhập liệu khi gửi biểu mẫu
            document.getElementById('idform').onsubmit = function() {
            return checkLastDensokwValue();
        };
</script>

<?php } else {
    header('location: ../controller/nhanvien.php?act=login');
} ?>