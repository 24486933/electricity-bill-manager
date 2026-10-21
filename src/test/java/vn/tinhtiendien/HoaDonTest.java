
package vn.tinhtiendien;

import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

import java.time.Duration;
import java.util.List;

import static org.junit.jupiter.api.Assertions.assertFalse;
import static org.junit.jupiter.api.Assertions.assertTrue;

class HoaDonTest {

    private static final String BASE_URL =
        "http://localhost/file_ban_2/file_ban_2/file_ban_2/tinhtiendien";

    private static final String LOGIN_URL =
        BASE_URL + "/controller/nhanvien.php?act=login";

    private static final String HOA_DON_URL =
        BASE_URL + "/controller/tiendien.php?act=quanlyhoadon";

    private static final int STEP_DELAY_MS = 500;
    private static final int RESULT_DELAY_MS = 500;

    private WebDriverWait createWait(WebDriver driver) {
        return new WebDriverWait(
            driver,
            Duration.ofSeconds(20)
        );
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

        WebDriverWait wait = createWait(driver);

        driver.get(LOGIN_URL);
        pause(STEP_DELAY_MS);

        WebElement usernameInput = wait.until(
            ExpectedConditions.visibilityOfElementLocated(
                By.name("username")
            )
        );

        usernameInput.sendKeys(username);
        pause(STEP_DELAY_MS);

        driver.findElement(By.name("password")).sendKeys(password);
        pause(STEP_DELAY_MS);

        driver.findElement(By.name("dangnhap")).click();

        wait.until(d ->
            d.getCurrentUrl().contains("tiendien.php")
                && d.getCurrentUrl().contains("act=quanly")
        );

        pause(STEP_DELAY_MS);
    }

    private void openInvoicePage(WebDriver driver) {
        login(driver);

        driver.get(HOA_DON_URL);
        pause(STEP_DELAY_MS);

        System.out.println("========== INVOICE PAGE CHECK ==========");
        System.out.println("Current URL: " + driver.getCurrentUrl());
        System.out.println("Page title: " + driver.getTitle());

        System.out.println(
            "Has mahd input: "
                + !driver.findElements(By.id("mahd")).isEmpty()
        );

        System.out.println(
            "Has searchResults: "
                + !driver.findElements(By.id("searchResults")).isEmpty()
        );

        System.out.println(
            "Body text: "
                + driver.findElement(By.tagName("body")).getText()
        );

        System.out.println("=========================================");

        WebDriverWait wait = createWait(driver);

        wait.until(
            ExpectedConditions.visibilityOfElementLocated(
                By.id("mahd")
            )
        );

        pause(STEP_DELAY_MS);
    }

    // HD01: Check invoice list display.
    @Test
    void testDanhSachHoaDonHienThi() {
        WebDriver driver = new ChromeDriver();

        try {
            driver.manage().window().maximize();
            openInvoicePage(driver);

            WebDriverWait wait = createWait(driver);

            WebElement searchInput = driver.findElement(
                By.id("mahd")
            );

            WebElement invoiceHeading = wait.until(
                ExpectedConditions.visibilityOfElementLocated(
                    By.xpath(
                        "//h2[contains(normalize-space(.), 'Danh sách hóa đơn')]"
                    )
                )
            );

            assertTrue(
                searchInput.isDisplayed(),
                "O tim kiem ma hoa don phai hien thi"
            );

            assertTrue(
                invoiceHeading.isDisplayed(),
                "Tieu de danh sach hoa don phai hien thi"
            );

            List<WebElement> tables = driver.findElements(
                By.cssSelector("table")
            );

            if (!tables.isEmpty()) {
                WebElement invoiceTable = tables.get(0);

                assertTrue(
                    invoiceTable.isDisplayed(),
                    "Bang danh sach hoa don phai hien thi"
                );

                List<WebElement> headers = invoiceTable.findElements(
                    By.cssSelector("thead th")
                );

                assertFalse(
                    headers.isEmpty(),
                    "Bang hoa don phai co cot tieu de"
                );

                assertTrue(
                    invoiceTable.findElements(
                        By.xpath(
                            ".//thead//th[contains(normalize-space(.), 'Mã hóa đơn')]"
                        )
                    ).size() > 0,
                    "Bang phai co cot ma hoa don"
                );

                System.out.println(
                    "HD01 PASS: Danh sach hoa don va cac cot tieu de hien thi."
                );
            } else {
                String pageText = driver.findElement(
                    By.tagName("body")
                ).getText();

                assertTrue(
                    pageText.contains("Không có hóa đơn nào."),
                    "Trang phai hien thi bang hoa don hoac thong bao danh sach trong"
                );

                System.out.println(
                    "HD01 PASS: Trang hien thi thong bao khong co hoa don."
                );
            }

            pause(RESULT_DELAY_MS);

        } finally {
            driver.quit();
        }
    }

    // HD02: Check invoice search by invoice ID.
    @Test
    void testTimKiemHoaDonTheoMa() {
        WebDriver driver = new ChromeDriver();

        try {
            driver.manage().window().maximize();
            openInvoicePage(driver);

            WebDriverWait wait = createWait(driver);

            List<WebElement> invoiceLinks = driver.findElements(
                By.cssSelector(
                    "table tbody tr td:first-child a"
                )
            );

            assertFalse(
                invoiceLinks.isEmpty(),
                "Can co hoa don mau de kiem tra tim kiem"
            );

            String invoiceId = invoiceLinks.get(0).getText().trim();

            assertFalse(
                invoiceId.isBlank(),
                "Ma hoa don mau khong duoc de trong"
            );

            WebElement searchInput = driver.findElement(
                By.id("mahd")
            );

            searchInput.clear();
            searchInput.click();
            searchInput.sendKeys(invoiceId);

            System.out.println(
                "Invoice ID expected: [" + invoiceId + "]"
            );
            System.out.println(
                "Input value immediately: ["
                    + searchInput.getAttribute("value") + "]"
            );

            wait.until(d -> {
                WebElement input = d.findElement(By.id("mahd"));
                String value = input.getAttribute("value");
                return invoiceId.equals(value);
            });

            System.out.println(
                "Input value confirmed: ["
                    + driver.findElement(By.id("mahd"))
                            .getAttribute("value") + "]"
            );

            // Wait for the AJAX result container to receive content.
            wait.until(d -> {
                List<WebElement> resultElements = d.findElements(
                    By.id("searchResults")
                );

                if (resultElements.isEmpty()) {
                    return false;
                }

                String resultText = resultElements.get(0).getText().trim();

                return !resultText.isEmpty()
                    && resultText.contains(invoiceId);
            });

            WebElement results = driver.findElement(
                By.id("searchResults")
            );

            String resultText = results.getText().trim();

            System.out.println("Invoice ID: " + invoiceId);
            System.out.println("Search result: " + resultText);

            assertTrue(
                resultText.contains(invoiceId),
                "Ket qua tim kiem phai chua dung ma hoa don"
            );

            System.out.println(
                "HD02 PASS: Tim thay hoa don theo ma " + invoiceId
            );

            pause(RESULT_DELAY_MS);

        } catch (Exception e) {
            System.out.println(
                "HD02 ERROR: "
                    + e.getClass().getSimpleName()
                    + " - "
                    + e.getMessage()
            );

            System.out.println(
                "Current URL: " + driver.getCurrentUrl()
            );

            List<WebElement> searchInputs = driver.findElements(
                By.id("mahd")
            );

            if (!searchInputs.isEmpty()) {
                System.out.println(
                    "Search input value: "
                        + searchInputs.get(0).getAttribute("value")
                );
            }

            List<WebElement> resultElements = driver.findElements(
                By.id("searchResults")
            );

            if (!resultElements.isEmpty()) {
                System.out.println(
                    "Search results: "
                        + resultElements.get(0).getText()
                );
            }

            throw e;

        } finally {
            driver.quit();
        }
    }
}
