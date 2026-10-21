
package vn.tinhtiendien;

import org.junit.jupiter.api.Test;
import org.openqa.selenium.Alert;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

import java.time.Duration;

import static org.junit.jupiter.api.Assertions.assertTrue;

class TrangQuanLyTest {

    private static final String BASE_URL =
        "http://localhost/file_ban_2/file_ban_2/file_ban_2/tinhtiendien";

    private static final String LOGIN_URL =
        BASE_URL + "/controller/nhanvien.php?act=login";

    private static final String QUAN_LY_URL =
        BASE_URL + "/controller/tiendien.php?act=quanly";

    private static final int STEP_DELAY_MS = 500;
    private static final int RESULT_DELAY_MS = 500;

    private WebDriverWait createWait(WebDriver driver) {
        return new WebDriverWait(driver, Duration.ofSeconds(15));
    }

    private void pause(int milliseconds) {
        try {
            Thread.sleep(milliseconds);
        } catch (InterruptedException e) {
            Thread.currentThread().interrupt();
            throw new RuntimeException("Test interrupted", e);
        }
    }

    private void login(WebDriver driver) {
        String username = System.getenv("TEST_USERNAME");
        String password = System.getenv("TEST_PASSWORD");

        assertTrue(
            username != null && !username.isBlank(),
            "Can dat bien moi truong TEST_USERNAME"
        );

        assertTrue(
            password != null && !password.isBlank(),
            "Can dat bien moi truong TEST_PASSWORD"
        );

        driver.get(LOGIN_URL);
        pause(STEP_DELAY_MS);

        WebDriverWait wait = createWait(driver);

        WebElement usernameInput = wait.until(
            ExpectedConditions.visibilityOfElementLocated(
                By.name("username")
            )
        );

        usernameInput.sendKeys(username);
        pause(STEP_DELAY_MS);

        WebElement passwordInput = driver.findElement(
            By.name("password")
        );

        passwordInput.sendKeys(password);
        pause(STEP_DELAY_MS);

        driver.findElement(By.name("dangnhap")).click();

        wait.until(d ->
            d.getCurrentUrl().contains("tiendien.php")
                && d.getCurrentUrl().contains("act=quanly")
        );

        pause(STEP_DELAY_MS);
    }

    // TC01: Dang nhap thanh cong
    @Test
    void testDangNhapThanhCongChuyenDenTrangQuanLy() {
        WebDriver driver = new ChromeDriver();

        try {
            driver.manage().window().maximize();

            login(driver);

            assertTrue(
                driver.getCurrentUrl().contains("tiendien.php"),
                "Dang nhap thanh cong phai chuyen den trang quan ly"
            );

            assertTrue(
                driver.getCurrentUrl().contains("act=quanly"),
                "URL phai co hanh dong quan ly"
            );

            System.out.println(
                "TC01 PASS: Dang nhap thanh cong va chuyen den trang quan ly."
            );

            pause(RESULT_DELAY_MS);
        } finally {
            driver.quit();
        }
    }

    // TC02: Dang nhap sai va hien thi thong bao loi
    @Test
    void testDangNhapSaiHienThiThongBaoLoi() {
        WebDriver driver = new ChromeDriver();

        try {
            driver.manage().window().maximize();
            driver.get(LOGIN_URL);

            pause(STEP_DELAY_MS);

            WebDriverWait wait = createWait(driver);

            WebElement usernameInput = wait.until(
                ExpectedConditions.visibilityOfElementLocated(
                    By.name("username")
                )
            );

            usernameInput.sendKeys(
                "invalid_account_123@example.com"
            );

            pause(STEP_DELAY_MS);

            driver.findElement(By.name("password"))
                .sendKeys("invalid_password_123456");

            pause(STEP_DELAY_MS);

            driver.findElement(By.name("dangnhap")).click();

            Alert alert = wait.until(
                ExpectedConditions.alertIsPresent()
            );

            String alertText = alert.getText();

            assertTrue(
                alertText.contains("Sai tên"),
                "Thong bao phai cho biet thong tin dang nhap sai"
            );

            System.out.println(
                "TC02 PASS: Ung dung hien thi thong bao dang nhap sai."
            );

            pause(STEP_DELAY_MS);
            alert.accept();

            pause(RESULT_DELAY_MS);

            assertTrue(
                driver.getCurrentUrl().contains("nhanvien.php"),
                "Dang nhap sai phai giu lai trang dang nhap"
            );
        } finally {
            driver.quit();
        }
    }

    // TC03: Chan truy cap trang quan ly khi chua dang nhap
    @Test
    void testTrangQuanLyChuyenDenDangNhapKhiChuaDangNhap() {
        WebDriver driver = new ChromeDriver();

        try {
            driver.manage().window().maximize();

            // Truy cap truc tiep bang trinh duyet moi, chua dang nhap
            driver.get(QUAN_LY_URL);

            pause(STEP_DELAY_MS);

            WebDriverWait wait = createWait(driver);

            wait.until(d ->
                d.getCurrentUrl().contains("nhanvien.php")
                    && d.getCurrentUrl().contains("act=login")
            );

            assertTrue(
                driver.getCurrentUrl().contains("act=login"),
                "Nguoi chua dang nhap phai duoc chuyen den trang dang nhap"
            );

            WebElement usernameInput = wait.until(
                ExpectedConditions.visibilityOfElementLocated(
                    By.name("username")
                )
            );

            assertTrue(
                usernameInput.isDisplayed(),
                "Bieu mau dang nhap phai hien thi sau khi chuyen huong"
            );

            System.out.println(
                "TC03 PASS: Trang quan ly chan truy cap khi chua dang nhap."
            );

            pause(RESULT_DELAY_MS);
        } finally {
            driver.quit();
        }
    }
}
