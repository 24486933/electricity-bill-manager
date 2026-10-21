
package vn.tinhtiendien;

import java.text.Normalizer;
import java.time.Duration;
import java.util.List;
import java.util.Locale;
import java.util.regex.Pattern;

import org.junit.jupiter.api.AfterEach;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;

import org.openqa.selenium.By;
import org.openqa.selenium.JavascriptExecutor;
import org.openqa.selenium.Keys;
import org.openqa.selenium.StaleElementReferenceException;
import org.openqa.selenium.TimeoutException;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

import static org.junit.jupiter.api.Assertions.*;

public class TraCuuTest {

    private WebDriver driver;
    private WebDriverWait wait;

    private static final long STEP_DELAY_MS = 500;

    private static final Duration THOI_GIAN_CHO =
            Duration.ofSeconds(15);

    private static final String BASE_URL =
            "http://localhost/file_ban_2/file_ban_2/file_ban_2/tinhtiendien";

    private static final String LOGIN_URL =
            BASE_URL + "/controller/nhanvien.php?act=login";

    private static final String KH_URL =
            BASE_URL + "/controller/tracuu.php?act=tracuukhachhang";

    private static final String DK_URL =
            BASE_URL + "/controller/tracuu.php?act=tracuudienke";

    private static final String HD_URL =
            BASE_URL + "/controller/tracuu.php?act=tracuuhoadon";

    private static final String MA_KHACH_HANG = "1";
    private static final String MA_DIEN_KE = "00000012";
    private static final String MA_HOA_DON = "240412155314";

    @BeforeEach
    void setUp() {
        driver = new ChromeDriver();
        driver.manage().window().maximize();

        wait = new WebDriverWait(
                driver,
                THOI_GIAN_CHO
        );

        try {
            String username = System.getenv("TEST_USERNAME");
            String password = System.getenv("TEST_PASSWORD");

            if (username == null || username.isBlank()) {
                throw new IllegalStateException(
                        "Thiếu biến môi trường TEST_USERNAME"
                );
            }

            if (password == null || password.isBlank()) {
                throw new IllegalStateException(
                        "Thiếu biến môi trường TEST_PASSWORD"
                );
            }

            driver.get(LOGIN_URL);

            WebElement usernameInput = wait.until(
                    ExpectedConditions.visibilityOfElementLocated(
                            By.name("username")
                    )
            );

            WebElement passwordInput = wait.until(
                    ExpectedConditions.visibilityOfElementLocated(
                            By.name("password")
                    )
            );

            usernameInput.clear();
            usernameInput.sendKeys(username);

            passwordInput.clear();
            passwordInput.sendKeys(password);

            pauseForObservation();

            WebElement loginButton = wait.until(
                    ExpectedConditions.elementToBeClickable(
                            By.name("dangnhap")
                    )
            );

            loginButton.click();

            wait.until(d ->
                    d.getCurrentUrl().contains("tiendien.php")
            );

            assertTrue(
                    driver.getCurrentUrl().contains("tiendien.php"),
                    "Đăng nhập không chuyển đến trang tiendien.php"
            );

            pauseForObservation();

        } catch (RuntimeException | Error e) {
            if (driver != null) {
                driver.quit();
                driver = null;
            }
            throw e;
        }
    }

    @AfterEach
    void tearDown() {
        if (driver != null) {
            try {
                driver.quit();
            } finally {
                driver = null;
            }
        }
    }

    private void pauseForObservation() {
        try {
            Thread.sleep(STEP_DELAY_MS);
        } catch (InterruptedException e) {
            Thread.currentThread().interrupt();
            throw new RuntimeException(
                    "Thời gian chờ quan sát bị gián đoạn",
                    e
            );
        }
    }

    private String chuanHoa(String text) {
        if (text == null) {
            return "";
        }

        String result = Normalizer.normalize(
                text,
                Normalizer.Form.NFD
        );

        result = Pattern.compile("\\p{M}+")
                .matcher(result)
                .replaceAll("");

        return result.toLowerCase(Locale.ROOT);
    }

    private String noiDungTrang() {
        return driver.findElement(
                By.tagName("body")
        ).getText();
    }

    private boolean coNoiDung(String text) {
        return chuanHoa(noiDungTrang())
                .contains(chuanHoa(text));
    }

    private void moTrangVaKiemTraForm(
            String url,
            String tenTruong,
            String tenNut) {

        driver.get(url);

        wait.until(d ->
                "complete".equals(
                        ((JavascriptExecutor) d)
                                .executeScript(
                                        "return document.readyState"
                                )
                )
        );

        wait.until(
                ExpectedConditions.presenceOfElementLocated(
                        By.name(tenTruong)
                )
        );

        wait.until(
                ExpectedConditions.presenceOfElementLocated(
                        By.name(tenNut)
                )
        );

        assertTrue(
                driver.getCurrentUrl().contains("tracuu.php"),
                "Không mở được trang tra cứu: " + url
        );

        assertTrue(
                !driver.findElements(By.name(tenTruong)).isEmpty(),
                "Không tìm thấy trường nhập: " + tenTruong
        );

        assertTrue(
                !driver.findElements(By.name(tenNut)).isEmpty(),
                "Không tìm thấy nút gửi biểu mẫu: " + tenNut
        );

        pauseForObservation();
    }

    private void nhapVaGuiForm(
            String tenTruong,
            String giaTri,
            String tenNut) {

        WebElement input = wait.until(
                ExpectedConditions.elementToBeClickable(
                        By.name(tenTruong)
                )
        );

        input.click();
        input.clear();
        input.sendKeys(giaTri);

        assertEquals(
                giaTri,
                input.getAttribute("value"),
                "Ô nhập không chứa đúng dữ liệu: " + tenTruong
        );

        pauseForObservation();

        // Ưu tiên nhấn nút gửi biểu mẫu.
        WebElement button = wait.until(
                ExpectedConditions.elementToBeClickable(
                        By.name(tenNut)
                )
        );

        button.click();

        try {
            wait.until(d -> {
                String bodyText = d.findElement(
                        By.tagName("body")
                ).getText();

                boolean hasTable = !d.findElements(
                        By.cssSelector("table")
                ).isEmpty();

                boolean hasResultMessage =
                        chuanHoa(bodyText).contains(
                                chuanHoa("Tìm thấy")
                        )
                        || chuanHoa(bodyText).contains(
                                chuanHoa("Không tìm thấy")
                        );

                return hasTable || hasResultMessage;
            });
        } catch (TimeoutException e) {
            inChanDoan(
                    "Không xuất hiện kết quả sau khi tra cứu trường "
                            + tenTruong
            );
            throw e;
        }

        pauseForObservation();
    }

    private void inChanDoan(String thongTin) {
        System.out.println("===== CHẨN ĐOÁN TRACUU =====");
        System.out.println("Thông tin: " + thongTin);
        System.out.println("URL hiện tại: " + driver.getCurrentUrl());

        try {
            System.out.println(
                    "Nội dung trang: " + noiDungTrang()
            );
        } catch (Exception e) {
            System.out.println(
                    "Không lấy được nội dung trang: "
                            + e.getMessage()
            );
        }

        try {
            System.out.println(
                    "HTML trang: "
                            + driver.findElement(By.tagName("body"))
                                    .getAttribute("innerHTML")
            );
        } catch (Exception e) {
            System.out.println(
                    "Không lấy được HTML: " + e.getMessage()
            );
        }
    }

    private boolean coMaTrongBang(String ma) {
        return driver.findElements(
                        By.cssSelector("table tr td")
                )
                .stream()
                .anyMatch(td -> {
                    try {
                        return td.getText().trim().equals(ma);
                    } catch (StaleElementReferenceException e) {
                        return false;
                    }
                });
    }

    private boolean coThongBaoKhongTimThay(String thongBao) {
        return coNoiDung(thongBao);
    }

    private void chonTraCuuKhachHangTheoMa() {
        WebElement radio = wait.until(
                ExpectedConditions.elementToBeClickable(
                        By.cssSelector(
                                "input[name='searchKH'][value='0']"
                        )
                )
        );

        if (!radio.isSelected()) {
            radio.click();
        }

        pauseForObservation();
    }

    // TC01: Tra cứu khách hàng theo mã tồn tại.
    @Test
    void TC01_traCuuKhachHangTheoMa() {
        moTrangVaKiemTraForm(
                KH_URL,
                "makh",
                "searchCustomer"
        );

        chonTraCuuKhachHangTheoMa();

        nhapVaGuiForm(
                "makh",
                MA_KHACH_HANG,
                "searchCustomer"
        );

        if (coThongBaoKhongTimThay(
                "Không tìm thấy khách hàng có mã")) {
            fail(
                    "Ứng dụng báo không tìm thấy khách hàng "
                            + MA_KHACH_HANG
                            + ". Nội dung trang: "
                            + noiDungTrang()
            );
        }

        assertTrue(
                coNoiDung("Tìm thấy khách hàng có mã:")
                        && noiDungTrang().contains(MA_KHACH_HANG),
                "Không thấy kết quả khách hàng. Nội dung trang: "
                        + noiDungTrang()
        );
    }

    // TC02: Tra cứu điện kế theo mã tồn tại.
    @Test
    void TC02_traCuuDienKeTheoMa() {
        moTrangVaKiemTraForm(
                DK_URL,
                "madk",
                "SearchDK"
        );

        nhapVaGuiForm(
                "madk",
                MA_DIEN_KE,
                "SearchDK"
        );

        if (coThongBaoKhongTimThay(
                "Không tìm thấy điện kế trong CSDL")) {
            fail(
                    "Ứng dụng không tìm thấy điện kế "
                            + MA_DIEN_KE
                            + ". Nội dung trang: "
                            + noiDungTrang()
            );
        }

        assertTrue(
                coMaTrongBang(MA_DIEN_KE),
                "Không tìm thấy mã điện kế trong bảng kết quả. "
                        + "Nội dung trang: "
                        + noiDungTrang()
        );
    }

    // TC03: Tra cứu hóa đơn theo mã tồn tại.
    @Test
    void TC03_traCuuHoaDonTheoMa() {
        moTrangVaKiemTraForm(
                HD_URL,
                "mahd",
                "SearchDH"
        );

        nhapVaGuiForm(
                "mahd",
                MA_HOA_DON,
                "SearchDH"
        );

        if (coThongBaoKhongTimThay(
                "Không tìm thấy hóa đơn trong CSDL")) {
            fail(
                    "Ứng dụng không tìm thấy hóa đơn "
                            + MA_HOA_DON
                            + ". Nội dung trang: "
                            + noiDungTrang()
            );
        }

        assertTrue(
                coMaTrongBang(MA_HOA_DON),
                "Không tìm thấy mã hóa đơn trong bảng kết quả. "
                        + "Nội dung trang: "
                        + noiDungTrang()
        );
    }

    // TC04: Tra cứu hóa đơn rồi mở trang chi tiết.
    @Test
    void TC04_traCuuVaXemChiTietHoaDon() {
        moTrangVaKiemTraForm(
                HD_URL,
                "mahd",
                "SearchDH"
        );

        nhapVaGuiForm(
                "mahd",
                MA_HOA_DON,
                "SearchDH"
        );

        assertTrue(
                coMaTrongBang(MA_HOA_DON),
                "Không tìm thấy hóa đơn trong bảng kết quả. "
                        + "Nội dung trang: "
                        + noiDungTrang()
        );

        WebElement linkChiTiet = wait.until(
                ExpectedConditions.elementToBeClickable(
                        By.cssSelector(
                                "a[href*='act=cthd'][href*='mahd=']"
                        )
                )
        );

        String href = linkChiTiet.getAttribute("href");

        assertNotNull(
                href,
                "Liên kết chi tiết hóa đơn không có địa chỉ."
        );

        assertTrue(
                href.contains("mahd="),
                "Liên kết chi tiết không chứa mã hóa đơn."
        );

        linkChiTiet.click();

        wait.until(ExpectedConditions.urlContains("act=cthd"));
        wait.until(ExpectedConditions.urlContains("mahd="));

        wait.until(
                ExpectedConditions.presenceOfElementLocated(
                        By.tagName("body")
                )
        );

        assertTrue(
                driver.getCurrentUrl().contains("act=cthd"),
                "Không chuyển được sang trang chi tiết hóa đơn."
        );

        assertTrue(
                noiDungTrang().contains(MA_HOA_DON),
                "Trang chi tiết không hiển thị mã hóa đơn. "
                        + "Nội dung trang: "
                        + noiDungTrang()
        );
    }

    // TC05: Tra cứu khách hàng bằng mã không tồn tại.
    @Test
    void TC05_traCuuKhachHangKhongTonTai() {
        moTrangVaKiemTraForm(
                KH_URL,
                "makh",
                "searchCustomer"
        );

        chonTraCuuKhachHangTheoMa();

        String maKhongTonTai = "KH_KHONG_TON_TAI_999";

        nhapVaGuiForm(
                "makh",
                maKhongTonTai,
                "searchCustomer"
        );

        assertTrue(
                coThongBaoKhongTimThay(
                        "Không tìm thấy khách hàng có mã"
                ),
                "Không xuất hiện thông báo khách hàng không tồn tại. "
                        + "Nội dung trang: "
                        + noiDungTrang()
        );

        assertTrue(
                noiDungTrang().contains(maKhongTonTai),
                "Thông báo không chứa mã đã tìm. Nội dung trang: "
                        + noiDungTrang()
        );
    }
}
