
package vn.tinhtiendien;

import java.time.Duration;
import java.util.List;

import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.StaleElementReferenceException;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.Select;
import org.openqa.selenium.support.ui.WebDriverWait;

import static org.junit.jupiter.api.Assertions.assertTrue;

class GiaDienTest {

    private static final String BASE_URL =
        "http://localhost/file_ban_2/file_ban_2/file_ban_2/tinhtiendien";

    private static final String LOGIN_URL =
        BASE_URL + "/controller/nhanvien.php?act=login";

    private static final String GIA_DIEN_URL =
        BASE_URL + "/controller/giadien.php?act=quanlygiadien";

    private static final By SELECT_NGAY = By.id("ngay");
    private static final By FORM_NGAY = By.cssSelector("form#formNgay");

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

        System.out.println("Login completed.");
    }

    private void openGiaDienPage(WebDriver driver) {
        login(driver);

        driver.get(GIA_DIEN_URL);
        pause(STEP_DELAY_MS);

        createWait(driver).until(
            ExpectedConditions.visibilityOfElementLocated(SELECT_NGAY)
        );

        System.out.println("Electricity price page opened.");
        pause(STEP_DELAY_MS);
    }

    // GD01: Check electricity price list display.
    @Test
    void testGD01_HienThiDanhSachGiaDien() {
        WebDriver driver = new ChromeDriver();

        try {
            driver.manage().window().maximize();
            openGiaDienPage(driver);

            WebDriverWait wait = createWait(driver);

            WebElement selectNgay = wait.until(
                ExpectedConditions.visibilityOfElementLocated(SELECT_NGAY)
            );

            assertTrue(
                selectNgay.isDisplayed(),
                "Bo chon ngay ap dung phai hien thi"
            );

            pause(STEP_DELAY_MS);

            WebElement headingGiaDien = wait.until(
                ExpectedConditions.presenceOfElementLocated(
                    By.xpath("//h4[contains(., 'gi')]")
                )
            );

            WebElement bangGiaDien = wait.until(
                ExpectedConditions.presenceOfElementLocated(
                    By.xpath(
                        "//h4[contains(., 'gi')]/following::table[1]"
                    )
                )
            );

            assertTrue(
                headingGiaDien.isDisplayed(),
                "Tieu de bang gia dien phai hien thi"
            );

            assertTrue(
                bangGiaDien.findElements(By.cssSelector("tr")).size() >= 1,
                "Bang gia dien phai co hang tieu de hoac du lieu"
            );

            pause(STEP_DELAY_MS);

            WebElement bangTatCaGia = wait.until(
                ExpectedConditions.presenceOfElementLocated(
                    By.xpath(
                        "//h4[contains(., 'Danh')]/following::table[1]"
                    )
                )
            );

            assertTrue(
                bangTatCaGia.findElements(By.cssSelector("tr")).size() >= 2,
                "Danh sach tat ca gia dien phai co tieu de va it nhat mot dong du lieu"
            );

            assertTrue(
                bangTatCaGia.findElements(
                    By.cssSelector("tr:first-child th")
                ).size() == 5,
                "Bang tat ca gia dien phai co 5 cot"
            );

            System.out.println(
                "GD01 PASS: Electricity price lists are displayed correctly."
            );

            pause(RESULT_DELAY_MS);

        } finally {
            driver.quit();
        }
    }

    // GD02: Check filtering electricity prices by effective date.
    @Test
    void testGD02_LocBangGiaTheoNgay() {
        WebDriver driver = new ChromeDriver();

        try {
            driver.manage().window().maximize();
            openGiaDienPage(driver);

            WebDriverWait wait = createWait(driver);

            // Read the available options before selecting a date.
            List<String> dateOptions = wait.until(d -> {
                try {
                    WebElement selectElement = d.findElement(SELECT_NGAY);

                    if (!selectElement.isDisplayed()) {
                        return null;
                    }

                    Select currentSelect = new Select(selectElement);

                    List<WebElement> options = currentSelect.getOptions();

                    if (options.size() <= 1) {
                        return null;
                    }

                    return options.stream()
                        .map(WebElement::getText)
                        .toList();

                } catch (StaleElementReferenceException e) {
                    return null;
                }
            });

            assertTrue(
                dateOptions.size() > 1,
                "Danh sach ngay ap dung phai co it nhat mot ngay de chon"
            );

            String selectedDate = dateOptions.get(1);

            System.out.println("Available dates: " + dateOptions);
            System.out.println("Selecting date: " + selectedDate);

            pause(STEP_DELAY_MS);

            // Re-find the select element immediately before selecting.
            wait.until(d -> {
                try {
                    WebElement selectElement = d.findElement(SELECT_NGAY);

                    if (!selectElement.isDisplayed()) {
                        return false;
                    }

                    Select currentSelect = new Select(selectElement);
                    List<WebElement> options = currentSelect.getOptions();

                    if (options.size() <= 1) {
                        return false;
                    }

                    currentSelect.selectByIndex(1);
                    return true;

                } catch (StaleElementReferenceException e) {
                    return false;
                }
            });

            pause(STEP_DELAY_MS);

            // Wait for the page to settle and verify the table exists.
            wait.until(d -> {
                try {
                    return d.findElements(By.cssSelector("table")).size() >= 1;
                } catch (StaleElementReferenceException e) {
                    return false;
                }
            });

            wait.until(
                ExpectedConditions.presenceOfElementLocated(FORM_NGAY)
            );

            assertTrue(
                driver.findElements(By.cssSelector("table")).size() >= 1,
                "Sau khi chon ngay, trang phai hien thi bang gia dien"
            );

            System.out.println(
                "GD02 PASS: Electricity prices are displayed after selecting a date."
            );

            pause(RESULT_DELAY_MS);

        } finally {
            driver.quit();
        }
    }
}
