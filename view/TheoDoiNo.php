<style>
    .close {
        position: relative;
        left: 0px;

    }
</style>
<?php
if (isset($_SESSION['id_nv'])) {
    ?>
    <link rel="stylesheet" href="../assets/css/theodoino.css?v=6">

    <h2>Danh Sách Khách Hàng Chưa Thanh Toán</h2>
    <button class="xem" onclick="openModal()">Xem Tổng Quan</button>
    <div id="myModal" class="modal">
        <!-- Nội dung của modal -->
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Tổng Quan</h3>
            <?php
            if (isset($user_unpaid) && !empty($user_unpaid)) {
                $tongtientongquat = 0;
                $soLuongKhachHang = count($user_unpaid);

                foreach ($user_unpaid as $rows) {
                    $tien = $rows['sum_money'];
                    $tongtientongquat += floatval(str_replace('.', '', $tien));

                }

                $show_tongtien_tongquat = number_format($tongtientongquat, 0, ',', '.');


                echo "<p><b>Số lượng khách hàng chưa thanh toán:</b> $soLuongKhachHang</p>";
                echo "<p><b>Tổng số tiền các khách hàng chưa thanh toán:</b> $show_tongtien_tongquat VNĐ</p>";
                if (isset($demHD) && !empty($demHD)) {
                    if ($demHD) {
                        echo '<p><b>Số lượng hóa đơn chưa thanh toán:</b> ' . $demHD['sohd'];
                    }
                }
                // Tìm khách hàng có nợ cao nhất
                $maxDebtCustomer = null;
                $maxDebtAmount = 0;
                foreach ($user_unpaid as $customer) {
                    $debt = floatval(str_replace('.', '', $customer['sum_money']));
                    if ($debt > $maxDebtAmount) {
                        $maxDebtCustomer = $customer;
                        $maxDebtAmount = $debt;
                    }
                }

                if ($maxDebtCustomer) {
                    echo "<h4>Khách hàng nợ cao nhất:</h4>";
                    echo "<p>Mã KH: " . $maxDebtCustomer['makh'] . "</p>";
                    echo "<p>Tên KH: " . $maxDebtCustomer['tenkh'] . "</p>";
                    echo "<p>Địa chỉ: " . $maxDebtCustomer['diachi'] . "</p>";
                    echo "<p>Số điện thoại: " . $maxDebtCustomer['dt'] . "</p>";
                    echo "<p>CCCD: " . $maxDebtCustomer['cccd'] . "</p>";
                    echo "<p>Số tiền nợ: " . $maxDebtCustomer['sum_money'] . " VNĐ</p>";
                } else {
                    echo "<p>Chưa phân tích được khách hàng nợ cao nhất !!!</p>";
                }
                if (isset($checkNoLau) && !empty($checkNoLau)) {
                    if ($checkNoLau) {
                        echo "<h4>Khách hàng có thời gian nợ dài nhất:</h4>";
                        echo "<p>Mã KH: " . $checkNoLau['makh'] . "</p>";
                        echo "<p>Tên KH: " . $checkNoLau['tenkh'] . "</p>";
                        echo "<p>Địa chỉ: " . $checkNoLau['diachi'] . "</p>";
                        echo "<p>Số điện thoại: " . $checkNoLau['dt'] . "</p>";
                        echo "<p>CCCD: " . $checkNoLau['cccd'] . "</p>";
                        echo "<p>Mã hóa đơn: " . $checkNoLau['mahd'] . "</p>";
                        date_default_timezone_set('Asia/Ho_Chi_Minh');
                        $ngayHienTai = new DateTime();
                        //chuyển chuỗi tgian sang dạng datetime
                        $ngayLapHD = DateTime::createFromFormat('Y-m-d H:i:s', $checkNoLau['nolau']);
                        // Kiểm tra xem ngày lập hóa đơn có hợp lệ không trước khi sử dụng diff()
                        if ($ngayLapHD instanceof DateTime) {
                            // Tính toán số ngày từ ngày lập hóa đơn đến ngày hiện tại
                            $soNgay = $ngayLapHD->diff($ngayHienTai)->days;
                            echo '<p>Thời gian nợ: ' . $soNgay . ' ngày chưa thanh toán</p>';
                        } else {
                            echo '';
                        }
                    } else {
                        echo "<p>Chưa phân tích được khách hàng nợ lâu nhất !!!</p>";
                    }
                }

            } else {
                echo "Không có dữ liệu về khách hàng chưa thanh toán.";
            }

            ?>
        </div>
    </div>
    <?php
    if (isset($user_unpaid)) {
        if ($user_unpaid && !empty($user_unpaid)) {
            // Số lượng hóa đơn hiển thị trên mỗi trang
            $invoicesPerPage = isset($_GET['size']) ? (int) $_GET['size'] : 15;
            // Trang hiện tại
            $currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;
            // Vị trí bắt đầu của dữ liệu trên trang hiện tại
            $startIndex = ($currentPage - 1) * $invoicesPerPage;

            // Lọc các hóa đơn có mã hóa đơn (giả sử mã hóa đơn là mahd)
            $filteredInvoices = array_filter($user_unpaid, function ($hd) {
                return isset ($hd['makh']);
            });

            // Tổng số hóa đơn
            $totalInvoices = count($filteredInvoices);
            // Tính tổng số trang
            $totalPages = ceil($totalInvoices / $invoicesPerPage);
            // Lấy danh sách hóa đơn cho trang hiện tại
            $invoicesToShow = array_slice($filteredInvoices, $startIndex, $invoicesPerPage);
            echo "<table border='1'>
            <tr>
                <th>Mã KH</th>
                <th>Tên KH</th>
                <th>Địa chỉ</th>
                <th>Số điện thoại</th>
                <th>CCCD</th>
                <th>Tổng tiền chưa thanh toán</th>
                <th>Chi tiết</th>

            </tr>";
            foreach ($invoicesToShow as $rows) {
                echo "<tr>
                <td>" . $rows["makh"] . "</td>
                <td>" . $rows["tenkh"] . "</td>
                <td>" . $rows["diachi"] . "</td>
                <td>" . $rows["dt"] . "</td>
                <td>" . $rows["cccd"] . "</td>
                <td>" . $rows["sum_money"] . " VNĐ</td>
                <td> 
                    <form method='post' action='../controller/theodoino.php?act=xemchitietno'>
                        <input type='hidden' name='makh' value='" . $rows['makh'] . "'>
                     <a class='addad' href='javascript:void(0);' onclick='this.parentNode.submit();'>Xem chi tiết nợ</a>
                    </form>
                </td>
            </tr>";
            }
            echo "</table>";

            if ($totalPages > 1) {
                echo '<div></div><div>';
                if ($currentPage > 1) {
                    echo '<button class="navi selected"><a href="?act=theodoino&p=' . ($currentPage - 1) . '&size=' . $invoicesPerPage . '">&lt;</a></button>';
                }
                $start = max(1, $currentPage - 1);
                $end = min($totalPages, $start + 3);
                for ($i = $start; $i <= $end; $i++) {
                    if ($i == $currentPage) {
                        echo '<button class="navi selected chosse"><span>' . $i . '</span></button>';
                    } else {
                        echo '<button class="navi selected"><a href="?act=theodoino&p=' . $i . '&size=' . $invoicesPerPage . '">' . $i . '</a></button>';
                    }
                }
                if ($currentPage < $totalPages) {
                    echo '<button class="navi selected"><a href="?act=theodoino&p=' . ($currentPage + 1) . '&size=' . $invoicesPerPage . '">&gt;</a></button>';
                }
            
                echo '</div>';
            }
        }
    }else{
        echo 'Không có dữ liệu về khách hàng chưa thanh toán.';
    }
    ?>
    <script>
        var modal = document.getElementById("myModal");
        function openModal() {
            modal.style.display = "block";
        }
        // nhấn x đóng modal
        function closeModal() {
            modal.style.display = "none";
        }
        // nhấp vùng ngoài modal -> đóng
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

<?php } else {
    header('location: ../controller/nhanvien.php?act=login');
} ?>