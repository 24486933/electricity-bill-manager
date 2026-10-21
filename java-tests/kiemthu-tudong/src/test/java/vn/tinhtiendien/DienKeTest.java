
package vn.tinhtiendien;

import java.time.Duration;
import java.util.List;

import org.junit.jupiter.api.AfterEach;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

import static org.junit.jupiter.api.Assertions.*;

public class DienKeTest {

    private WebDriver driver;
    private WebDriverWait wait;

    private static final long STEP_DELAY_MS = 500;

    private static final String BASE_URL =
            "http://localhost/file_ban_2/file_ban_2/file_ban_2/tinhtiendien";

    private static final String LOGIN_URL =
            BASE_URL + "/controller/nhanvien.php?act=login";

    private static final String DIENKE_URL =
            BASE_URL + "/controller/dienke.php?act=quanlydienke";

    private static final String TEST_CUSTOMER_ID = "0000000000014";

    @BeforeEach
    public void setUp() {
        driver = new ChromeDriver();
        driver.manage().window().maximize();

        wait = new WebDriverWait(
                driver,
                Duration.ofSeconds(15)
        );

        pauseForObservation();
    }

    private void pauseForObservation() {
        try {
            Thread.sleep(STEP_DELAY_MS);
        } catch (InterruptedException e) {
            Thread.currentThread().interrupt();
            throw new RuntimeException(
                    "Observation pause interrupted",
                    e
            );
        }
    }

    private void login() {
        driver.get(LOGIN_URL);

        String username = System.getenv("TEST_USERNAME");
        String password = System.getenv("TEST_PASSWORD");

        if (username == null || username.isBlank()) {
            throw new IllegalStateException(
                    "Environment variable TEST_USERNAME is missing"
            );
        }

        if (password == null || password.isBlank()) {
            throw new IllegalStateException(
                    "Environment variable TEST_PASSWORD is missing"
            );
        }

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

        wait.until(
                ExpectedConditions.urlContains("act=quanly")
        );

        pauseForObservation();
    }

    private void openDienKePage() {
        login();

        driver.get(DIENKE_URL);

        wait.until(
                ExpectedConditions.presenceOfElementLocated(
                        By.id("searchForm")
                )
        );

        wait.until(
                ExpectedConditions.presenceOfElementLocated(
                        By.id("searchResults")
                )
        );

        pauseForObservation();
    }

    private void selectSearchById() {
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

        wait.until(
                ExpectedConditions.visibilityOfElementLocated(
                        By.id("search_By_ID")
                )
        );
    }

    private WebElement findCustomerTable() {
        List<WebElement> tables = driver.findElements(
                By.cssSelector(
                        "#searchResults table, body > table"
                )
        );

        for (WebElement table : tables) {
            String text = table.getText();

            if (text.contains("Tên KH")
                    && text.contains("Xem điện kế")) {
                return table;
            }
        }

        fail(
                "Customer table was not found. Current URL: "
                        + driver.getCurrentUrl()
                        + "; searchResults: "
                        + driver.findElement(
                                By.id("searchResults")
                        ).getText()
        );

        return null;
    }

    private String getFirstCustomerId(WebElement table) {
        List<WebElement> buttons = table.findElements(
                By.cssSelector("button[id^='button_']")
        );

        assertFalse(
                buttons.isEmpty(),
                "No customer view button was found"
        );

        String buttonId = buttons.get(0).getAttribute("id");

        assertNotNull(buttonId);
        assertTrue(buttonId.startsWith("button_"));

        return buttonId.substring("button_".length());
    }

    @Test
    public void testDK01_TimKiemDienKeTheoMaKhachHang() {
        openDienKePage();
        selectSearchById();

        WebElement customerIdInput = wait.until(
                ExpectedConditions.visibilityOfElementLocated(
                        By.id("makh")
                )
        );

        customerIdInput.clear();
        customerIdInput.click();

        // Lấy nội dung hiện tại trước khi phát sinh yêu cầu tìm kiếm.
        String previousResults = driver.findElement(
                By.id("searchResults")
        ).getAttribute("innerHTML");

        customerIdInput.sendKeys(TEST_CUSTOMER_ID);

        System.out.println(
                "Customer ID expected: [" + TEST_CUSTOMER_ID + "]"
        );

        wait.until(d -> {
            System.out.println("Current URL: " + driver.getCurrentUrl());
            System.out.println("Input value: "
                    + driver.findElement(By.id("makh")).getAttribute("value"));
            System.out.println("Results HTML before wait: "
                    + driver.findElement(By.id("searchResults"))
                            .getAttribute("innerHTML"));
            WebElement input = d.findElement(By.id("makh"));

            return TEST_CUSTOMER_ID.equals(
                    input.getAttribute("value")
            );
        });

        System.out.println(
                "Customer input value confirmed: ["
                        + driver.findElement(By.id("makh"))
                                .getAttribute("value")
                        + "]"
        );

        // Chờ HTML kết quả thay đổi sau yêu cầu AJAX.
        wait.until(d -> {
            String currentHtml = d.findElement(
                    By.id("searchResults")
            ).getAttribute("innerHTML");

            return currentHtml != null
                    && !currentHtml.trim().isEmpty()
                    && !currentHtml.equals(previousResults);
        });

        WebElement results = driver.findElement(
                By.id("searchResults")
        );

        String resultText = results.getText();

        System.out.println("Search result: [" + resultText + "]");

        assertFalse(
                resultText.trim().isEmpty(),
                "Search should return results or a not-found message"
        );

        assertFalse(
                resultText.contains(
                        "Không tìm thấy khách hàng trong CSDL."
                ),
                "The expected customer should be found"
        );

        assertTrue(
                resultText.contains(TEST_CUSTOMER_ID)
                        || resultText.contains("Tên KH"),
                "Search results should contain customer information"
        );

        pauseForObservation();
    }

    @Test
    public void testDK02_MoVaDongChiTietDienKe() {
        openDienKePage();

        WebElement customerTable = findCustomerTable();
        String customerId = getFirstCustomerId(customerTable);

        By buttonLocator = By.id("button_" + customerId);
        By detailRowLocator = By.id("dienke_row_" + customerId);

        wait.until(
                ExpectedConditions.elementToBeClickable(
                        buttonLocator
                )
        ).click();

        // Chờ hàng chi tiết mở và nút đổi thành Đóng.
        wait.until(d -> {
            List<WebElement> rows =
                    d.findElements(detailRowLocator);

            List<WebElement> buttons =
                    d.findElements(buttonLocator);

            return !rows.isEmpty()
                    && !buttons.isEmpty()
                    && !"none".equals(
                            rows.get(0).getCssValue("display")
                    )
                    && "Đóng".equals(
                            buttons.get(0).getText().trim()
                    );
        });

        WebElement detailRow = driver.findElement(
                detailRowLocator
        );

        WebElement button = driver.findElement(
                buttonLocator
        );

        assertTrue(
                detailRow.isDisplayed(),
                "Detail row should be visible after clicking Xem"
        );

        assertEquals(
                "Đóng",
                button.getText().trim()
        );

        pauseForObservation();

        button.click();
        System.out.println("Customer ID: " + customerId);
        System.out.println("Detail row exists: "
                + !driver.findElements(detailRowLocator).isEmpty());
        System.out.println("Button text after click: ["
                + driver.findElement(buttonLocator).getText().trim() + "]");
        System.out.println("Detail row display after click: "
                + driver.findElement(detailRowLocator).getCssValue("display"));

        // Chờ hàng chi tiết đóng và nút trở về Xem.
        wait.until(d -> {
            List<WebElement> rows =
                    d.findElements(detailRowLocator);

            List<WebElement> buttons =
                    d.findElements(buttonLocator);

            return !rows.isEmpty()
                    && !buttons.isEmpty()
                    && "none".equals(
                            rows.get(0).getCssValue("display")
                    )
                    && "Xem".equals(
                            buttons.get(0).getText().trim()
                    );
        });

        assertEquals(
                "none",
                driver.findElement(detailRowLocator)
                        .getCssValue("display"),
                "Detail row should be hidden after clicking Dong"
        );

        assertEquals(
                "Xem",
                driver.findElement(buttonLocator).getText().trim()
        );

        pauseForObservation();
    }

    @AfterEach
    public void tearDown() {
        if (driver != null) {
            try {
                pauseForObservation();
            } finally {
                driver.quit();
            }
        }
    }
}
