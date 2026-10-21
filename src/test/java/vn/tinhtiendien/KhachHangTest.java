
package vn.tinhtiendien;

import java.time.Duration;
import java.util.List;
import java.util.UUID;

import org.junit.jupiter.api.AfterAll;
import org.junit.jupiter.api.BeforeAll;
import org.junit.jupiter.api.Order;
import org.junit.jupiter.api.Test;
import org.junit.jupiter.api.TestMethodOrder;
import org.junit.jupiter.api.MethodOrderer.OrderAnnotation;

import org.openqa.selenium.Alert;
import org.openqa.selenium.By;
import org.openqa.selenium.JavascriptExecutor;
import org.openqa.selenium.NoAlertPresentException;
import org.openqa.selenium.TimeoutException;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.chrome.ChromeOptions;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

import static org.junit.jupiter.api.Assertions.*;

@TestMethodOrder(OrderAnnotation.class)
public class KhachHangTest {

    private static WebDriver driver;
    private static WebDriverWait wait;

    private static final String BASE_URL =
        "http://localhost/file_ban_2/file_ban_2/file_ban_2/tinhtiendien";

    private static final String LOGIN_URL =
        BASE_URL + "/controller/nhanvien.php?act=login";

    private static final String CUSTOMER_URL =
        BASE_URL + "/controller/khachhang.php?act=quanlykhachhang";

    private static final String ADD_URL =
        BASE_URL + "/view/themkhachhang.php";

    private static final long STEP_DELAY_MS = 500;

    private static String username;
    private static String password;

    private static String customerId;
    private static String customerName;
    private static String customerAddress;

    private static final String customerPhone = "0987654321";
    private static final String customerCccd = "012345678901";

    private static boolean customerCreated = false;

    @BeforeAll
    static void setUp() {
        username = System.getenv("TEST_USERNAME");
        password = System.getenv("TEST_PASSWORD");

        if (username == null || username.isBlank()
                || password == null || password.isBlank()) {
            throw new IllegalStateException(
                "Vui long khai bao TEST_USERNAME va TEST_PASSWORD."
            );
        }

        ChromeOptions options = new ChromeOptions();
        options.addArguments("--start-maximized");

        driver = new ChromeDriver(options);
        wait = new WebDriverWait(driver, Duration.ofSeconds(15));
        driver.manage().timeouts().implicitlyWait(Duration.ZERO);

        String random = UUID.randomUUID()
            .toString().substring(0, 8);

        customerName = "Selenium KH " + random;
        customerAddress = "Dia chi kiem thu Selenium";

        System.out.println("=== BAT DAU KIEM THU KHACH HANG ===");
    }

    @AfterAll
    static void tearDown() {
        if (driver != null) {
            try {
                acceptAlertIfPresent();
            } catch (RuntimeException ignored) {
                // Bo qua loi khi dong trinh duyet.
            }

            driver.quit();
        }

        System.out.println("=== KET THUC KIEM THU KHACH HANG ===");
    }

    private static void sleep(long ms) {
        try {
            Thread.sleep(ms);
        } catch (InterruptedException e) {
            Thread.currentThread().interrupt();
            throw new RuntimeException(
                "Qua trinh kiem thu bi gian doan.", e
            );
        }
    }

    private static void step(String message) {
        System.out.println("[BUOC] " + message);
        sleep(STEP_DELAY_MS);
    }

    private static boolean exists(By locator) {
        return !driver.findElements(locator).isEmpty();
    }

    private static WebElement visible(By locator) {
        return wait.until(
            ExpectedConditions.visibilityOfElementLocated(locator)
        );
    }

    private static void fill(By locator, String value) {
        WebElement element = visible(locator);
        element.clear();
        element.sendKeys(value);
        sleep(500);
    }

    private static void click(By locator) {
        wait.until(
            ExpectedConditions.elementToBeClickable(locator)
        ).click();

        sleep(STEP_DELAY_MS);
    }

    private static String bodyText() {
        return driver.findElement(By.tagName("body")).getText();
    }

    // Doc va dong hop thoai JavaScript neu co.
    private static String acceptAlertIfPresent() {
        try {
            Alert alert = new WebDriverWait(
                driver, Duration.ofSeconds(3)
            ).until(ExpectedConditions.alertIsPresent());

            String message = alert.getText();
            System.out.println("[THONG BAO] " + message);
            alert.accept();

            sleep(1000);
            return message;

        } catch (TimeoutException | NoAlertPresentException e) {
            return "";
        }
    }

    // Dang nhap neu dang o trang login.
    private static void loginIfNeeded() {
        if (!driver.getCurrentUrl().contains("act=login")) {
            return;
        }

        step("Mo trang dang nhap");
        driver.get(LOGIN_URL);

        By userLocator;

        if (exists(By.name("email"))) {
            userLocator = By.name("email");
        } else if (exists(By.name("username"))) {
            userLocator = By.name("username");
        } else {
            userLocator = By.cssSelector(
                "input[type='email'], input[type='text']"
            );
        }

        By passwordLocator = exists(By.name("password"))
            ? By.name("password")
            : By.cssSelector("input[type='password']");

        fill(userLocator, username);
        fill(passwordLocator, password);

        click(By.cssSelector(
            "button[type='submit'], input[type='submit']"
        ));

        assertFalse(
            driver.getCurrentUrl().contains("act=login"),
            "Dang nhap that bai. Kiem tra tai khoan va mat khau."
        );

        System.out.println(
            "[THONG TIN] Dang nhap thanh cong: "
                + driver.getCurrentUrl()
        );
    }

    private static void openManagement() {
        loginIfNeeded();

        step("Mo trang quan ly khach hang");
        driver.get(CUSTOMER_URL);

        if (driver.getCurrentUrl().contains("act=login")) {
            loginIfNeeded();
            driver.get(CUSTOMER_URL);
        }

        assertFalse(
            driver.getCurrentUrl().contains("act=login"),
            "Phien dang nhap khong hop le."
        );

        assertTrue(
            bodyText().contains("Danh sách khách hàng")
                || bodyText().contains("Thêm khách hàng")
                || exists(By.cssSelector("a.sua")),
            "Khong mo duoc trang quan ly khach hang."
        );
    }

    private static void openAddForm() {
        openManagement();

        step("Mo bieu mau them khach hang");
        driver.get(ADD_URL);

        if (driver.getCurrentUrl().contains("act=login")) {
            loginIfNeeded();
            driver.get(ADD_URL);
        }

        assertFalse(
            driver.getCurrentUrl().contains("act=login"),
            "Phien dang nhap khong hop le khi mo bieu mau them."
        );

        assertTrue(
            exists(By.name("makh"))
                && exists(By.name("tenkh"))
                && exists(By.name("diachi"))
                && exists(By.name("dt"))
                && exists(By.name("cccd")),
            "Bieu mau them khach hang khong day du cac truong."
        );
    }

    // Truong makh co the la hidden, khong dung visibility.
    private static String readCustomerId() {
        WebElement element = wait.until(
            ExpectedConditions.presenceOfElementLocated(
                By.name("makh")
            )
        );

        String id = element.getAttribute("value");

        assertNotNull(id, "Khong doc duoc ma khach hang.");
        assertTrue(
            id.matches("\\d{13}"),
            "Ma khach hang phai gom 13 chu so. Gia tri: " + id
        );

        System.out.println(
            "[THONG TIN] Ma khach hang du kien: " + id
        );

        return id.trim();
    }

    private static void fillAddForm(
            String name,
            String address,
            String phone,
            String cccd) {

        fill(By.name("tenkh"), name);
        fill(By.name("diachi"), address);
        fill(By.name("dt"), phone);
        fill(By.name("cccd"), cccd);
    }

    // In cac gia tri se duoc gui len server de tim truong bi thieu.
    private static void logAddFormValues() {
        System.out.println("[KIEM TRA] URL: " + driver.getCurrentUrl());

        WebElement form = driver.findElement(
            By.cssSelector("form[name='addForm']")
        );

        System.out.println(
            "[KIEM TRA] Action: " + form.getAttribute("action")
        );
        System.out.println(
            "[KIEM TRA] Method: " + form.getAttribute("method")
        );

        String[] fields = {"makh", "tenkh", "diachi", "dt", "cccd"};

        for (String field : fields) {
            WebElement element = driver.findElement(By.name(field));
            String value = element.getAttribute("value");

            System.out.println(
                "[KIEM TRA] " + field + " = "
                    + (value == null ? "<null>" : value)
            );
        }
    }

    private static String submitAddForm() {
        logAddFormValues();

        click(By.cssSelector(
            "form[name='addForm'] input[type='submit']"
        ));

        String alertMessage = acceptAlertIfPresent();
        sleep(STEP_DELAY_MS);

        return alertMessage;
    }

    private static boolean customerExistsInManagement(String name) {
        openManagement();
        return bodyText().contains(name);
    }

    // KH01: Kiem tra trang quan ly.
    @Test
    @Order(1)
    void KH01_truyCapTrangQuanLy() {
        driver.get(LOGIN_URL);
        loginIfNeeded();
        openManagement();

        assertTrue(
            bodyText().contains("Danh sách khách hàng")
                || bodyText().contains("Thêm khách hàng")
                || exists(By.cssSelector("a.sua")),
            "Noi dung trang quan ly khong dung."
        );

        System.out.println("[DAT] KH01 - Truy cap trang quan ly");
    }

    // KH02: Kiem tra them khach hang hop le.
    @Test
    @Order(2)
    void KH02_themKhachHangHopLe() {
        openAddForm();

        customerId = readCustomerId();

        step("Nhap thong tin khach hang");
        fillAddForm(
            customerName,
            customerAddress,
            customerPhone,
            customerCccd
        );

        // Dam bao cac gia tri duoc dien truoc khi gui.
        assertEquals(
            customerName,
            driver.findElement(By.name("tenkh")).getAttribute("value"),
            "Ten khach hang chua duoc dien dung."
        );

        assertEquals(
            customerAddress,
            driver.findElement(By.name("diachi")).getAttribute("value"),
            "Dia chi chua duoc dien dung."
        );

        assertEquals(
            customerPhone,
            driver.findElement(By.name("dt")).getAttribute("value"),
            "So dien thoai chua duoc dien dung."
        );

        assertEquals(
            customerCccd,
            driver.findElement(By.name("cccd")).getAttribute("value"),
            "CCCD chua duoc dien dung."
        );

        step("Gui bieu mau them khach hang");
        String alertMessage = submitAddForm();

        if (alertMessage.contains("Vui lòng nhập")
                || alertMessage.contains("Vui long nhap")) {
            fail(
                "Server bao thieu thong tin. "
                    + "Hay xem cac dong [KIEM TRA] ben tren de tim "
                    + "truong bi thieu. Thong bao: " + alertMessage
            );
        }

        if (!alertMessage.isBlank()) {
            assertTrue(
                alertMessage.contains(customerId)
                    || alertMessage.contains("thành công")
                    || alertMessage.contains("thanh cong"),
                "Thong bao tu may chu khong xac nhan them thanh cong: "
                    + alertMessage
            );
        }

        customerCreated =
            customerExistsInManagement(customerName);

        assertTrue(
            customerCreated,
            "Khong tim thay khach hang tren trang quan ly sau khi them."
        );

        System.out.println(
            "[DAT] KH02 - Them khach hang thanh cong: " + customerId
        );
    }

    // KH03: Kiem tra JavaScript validateForm khi bo trong ten.
    @Test
    @Order(3)
    void KH03_kiemTraDuLieuDauVao() {
        openAddForm();

        step("Kiem tra ten khach hang de trong");

        fillAddForm(
            "",
            "Dia chi kiem thu",
            customerPhone,
            customerCccd
        );

        click(By.cssSelector(
            "form[name='addForm'] input[type='submit']"
        ));

        String nameAlert = acceptAlertIfPresent();

        assertFalse(
            nameAlert.isBlank(),
            "Khong co thong bao khi de trong ten khach hang."
        );

        System.out.println(
            "[THONG TIN] Da phat hien kiem tra ten: " + nameAlert
        );

        // Mo lai form de kiem tra CCCD sai dinh dang.
        openAddForm();

        step("Kiem tra CCCD khong hop le");

        fillAddForm(
            "Kiem thu du lieu sai",
            "Dia chi kiem thu",
            customerPhone,
            "123"
        );

        click(By.cssSelector(
            "form[name='addForm'] input[type='submit']"
        ));

        String cccdAlert = acceptAlertIfPresent();

        boolean invalidCccdDetected =
            !cccdAlert.isBlank()
                && (
                    cccdAlert.contains("CCCD")
                    || cccdAlert.contains("cccd")
                    || cccdAlert.contains("12")
                    || cccdAlert.contains("không hợp lệ")
                    || cccdAlert.contains("khong hop le")
                );

        assertTrue(
            invalidCccdDetected,
            "Khong phat hien thong bao kiem tra CCCD sai dinh dang. "
                + "Thong bao nhan duoc: " + cccdAlert
        );

        System.out.println(
            "[DAT] KH03 - Kiem tra du lieu dau vao"
        );
    }

    // KH04: Kiem tra sua thong tin khach hang.
    @Test
    @Order(4)
    void KH04_suaKhachHang() {
        assertTrue(
            customerCreated,
            "Can tao khach hang thanh cong truoc khi kiem tra sua."
        );

        openManagement();

        step("Tim khach hang can sua");

        List<WebElement> editLinks = driver.findElements(
            By.cssSelector(
                "a.sua[href*='makh=" + customerId + "']"
            )
        );

        assertFalse(
            editLinks.isEmpty(),
            "Khong tim thay lien ket sua khach hang: " + customerId
        );

        editLinks.get(0).click();
        sleep(STEP_DELAY_MS);

        assertTrue(
            driver.getCurrentUrl().contains("suakhachhang.php"),
            "Khong mo duoc bieu mau sua khach hang."
        );

        assertEquals(
            customerName,
            visible(By.name("tenkh")).getAttribute("value"),
            "Ten khach hang hien tai khong dung."
        );

        step("Cap nhat ten va dia chi");

        String newName = customerName + " Updated";
        String newAddress = "Dia chi da cap nhat Selenium";

        fill(By.name("tenkh"), newName);
        fill(By.name("diachi"), newAddress);

        click(By.cssSelector(
            "form input[type='submit'][value='Sửa']"
        ));

        String editAlert = acceptAlertIfPresent();

        if (!editAlert.isBlank()) {
            System.out.println(
                "[THONG TIN] Phan hoi sua khach hang: " + editAlert
            );
        }

        sleep(STEP_DELAY_MS);

        assertTrue(
            customerExistsInManagement(newName),
            "Khong tim thay ten khach hang moi sau khi sua."
        );

        customerName = newName;
        customerAddress = newAddress;

        System.out.println("[DAT] KH04 - Sua khach hang thanh cong");
    }
}
