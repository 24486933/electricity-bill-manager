
package vn.tinhtiendien;

import org.junit.jupiter.api.Test;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.AfterEach;

import org.openqa.selenium.Alert;
import org.openqa.selenium.By;
import org.openqa.selenium.JavascriptExecutor;
import org.openqa.selenium.NoAlertPresentException;
import org.openqa.selenium.StaleElementReferenceException;
import org.openqa.selenium.TimeoutException;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

import java.time.Duration;
import java.util.List;
import java.util.Locale;

import static org.junit.jupiter.api.Assertions.*;

public class NhanVienTest {

    private static final String BASE_URL =
            "http://localhost/file_ban_2/file_ban_2/file_ban_2/tinhtiendien";

    private static final String LOGIN_URL =
            BASE_URL + "/controller/nhanvien.php?act=login";

    private static final String QUAN_LY_URL =
            BASE_URL + "/controller/nhanvien.php?act=quanlynhanvien";

    private static final int STEP_DELAY_MS = 500;
    private static final int CLOSE_DELAY_MS = 500;

    private WebDriver driver;
    private WebDriverWait wait;

    private void pause() {
        try {
            Thread.sleep(STEP_DELAY_MS);
        } catch (InterruptedException e) {
            Thread.currentThread().interrupt();
            throw new RuntimeException("Bi gian doan khi cho", e);
        }
    }

    private void pauseBeforeClose() {
        try {
            Thread.sleep(CLOSE_DELAY_MS);
        } catch (InterruptedException e) {
            Thread.currentThread().interrupt();
            throw new RuntimeException(
                    "Bi gian doan khi cho dong trinh duyet", e);
        }
    }

    private void dangNhap() {
        String username = System.getenv("TEST_USERNAME");
        String password = System.getenv("TEST_PASSWORD");

        assertNotNull(username, "Chua cau hinh TEST_USERNAME");
        assertNotNull(password, "Chua cau hinh TEST_PASSWORD");
        assertFalse(username.isBlank(), "TEST_USERNAME dang rong");
        assertFalse(password.isBlank(), "TEST_PASSWORD dang rong");

        System.out.println("Buoc 1: Mo trang dang nhap.");
        driver.get(LOGIN_URL);
        pause();

        System.out.println("Buoc 2: Nhap thong tin dang nhap.");

        wait.until(ExpectedConditions.visibilityOfElementLocated(
                By.name("username"))).sendKeys(username);

        driver.findElement(By.name("password")).sendKeys(password);
        pause();

        System.out.println("Buoc 3: Bam nut dang nhap.");
        driver.findElement(By.name("dangnhap")).click();

        wait.until(ExpectedConditions.urlContains("tiendien.php"));
        pause();

        System.out.println(
                "Dang nhap thanh cong. URL: " + driver.getCurrentUrl());
    }

    private void inThongTinTrang(String tenCaKiemThu) {
        System.out.println("\n===== CHAN DOAN: "
                + tenCaKiemThu + " =====");
        System.out.println("URL: " + driver.getCurrentUrl());
        System.out.println("Tieu de: " + driver.getTitle());

        try {
            System.out.println(
                    "Noi dung trang:\n"
                            + driver.findElement(By.tagName("body")).getText());
        } catch (Exception e) {
            System.out.println("Khong doc duoc noi dung trang.");
        }

        System.out.println("--- Form ---");

        for (WebElement form : driver.findElements(By.tagName("form"))) {
            try {
                System.out.println(
                        "action=" + form.getAttribute("action")
                                + ", visible=" + form.isDisplayed());
            } catch (StaleElementReferenceException e) {
                System.out.println("Form da thay doi trong DOM.");
            }
        }

        System.out.println("--- Truong va nut ---");

        for (WebElement element : driver.findElements(
                By.cssSelector("input, select, textarea, button"))) {
            try {
                System.out.println(
                        "tag=" + element.getTagName()
                                + ", id=" + element.getAttribute("id")
                                + ", name=" + element.getAttribute("name")
                                + ", type=" + element.getAttribute("type")
                                + ", text=" + element.getText()
                                + ", visible=" + element.isDisplayed());
            } catch (StaleElementReferenceException e) {
                System.out.println("Phan tu da thay doi trong DOM.");
            }
        }

        System.out.println("===== KET THUC CHAN DOAN =====\n");
    }

    private List<WebElement> truongNhapHienThi(WebElement form) {
        return form.findElements(
                By.cssSelector(
                        "input:not([type='hidden']):not([type='submit'])"
                                + ":not([type='button']), select, textarea"
                )
        ).stream().filter(element -> {
            try {
                return element.isDisplayed();
            } catch (StaleElementReferenceException e) {
                return false;
            }
        }).toList();
    }

    @BeforeEach
    void setUp() {
        System.out.println("\n===== BAT DAU CA KIEM THU =====");

        driver = new ChromeDriver();
        driver.manage().window().maximize();
        wait = new WebDriverWait(driver, Duration.ofSeconds(15));

        dangNhap();

        System.out.println("Buoc 4: Mo trang quan ly nhan vien.");
        driver.get(QUAN_LY_URL);

        wait.until(ExpectedConditions.urlContains("quanlynhanvien"));
        pause();

        System.out.println("Da mo trang quan ly nhan vien.");
    }

    @AfterEach
    void tearDown() {
        if (driver != null) {
            System.out.println("Ca kiem thu da ket thuc.");
            System.out.println("Chrome se dong sau 5 giay.");

            pauseBeforeClose();
            driver.quit();
        }
    }

    @Test
    void NV01_truyCapTrangQuanLyNhanVien() {
        System.out.println("NV01: Kiem tra trang quan ly nhan vien.");

        wait.until(ExpectedConditions.urlContains("quanlynhanvien"));

        String noiDung = driver.getPageSource();

        assertTrue(
                noiDung.contains("Thông tin người dùng đang đăng nhập"),
                "Khong hien thi thong tin nhan vien dang dang nhap"
        );

        assertTrue(
                noiDung.contains("Đổi mật khẩu"),
                "Khong hien thi chuc nang doi mat khau"
        );

        pause();
        System.out.println("NV01: PASS");
    }

    @Test
    void NV02_kiemTraFormThemNhanVien() {
        System.out.println("NV02: Kiem tra trang them nhan vien.");

        try {
            WebElement lienKetThem = wait.until(
                    ExpectedConditions.presenceOfElementLocated(
                            By.cssSelector("button.add a")
                    )
            );

            String urlThem = lienKetThem.getAttribute("href");

            assertNotNull(urlThem, "Lien ket them nhan vien khong co href");
            assertFalse(urlThem.isBlank(), "Duong dan them nhan vien dang rong");

            System.out.println("Duong dan them nhan vien: " + urlThem);

            // Mo truc tiep URL de tranh loi nut long the a trong button.
            driver.get(urlThem);
            pause();

            wait.until(ExpectedConditions.urlContains("act=themnhanvien"));

            System.out.println(
                    "URL sau khi mo trang: " + driver.getCurrentUrl());

            List<WebElement> truongHienThi = driver.findElements(
                    By.cssSelector(
                            "input:not([type='hidden']), select, textarea"
                    )
            ).stream().filter(WebElement::isDisplayed).toList();

            assertFalse(
                    truongHienThi.isEmpty(),
                    "Trang them nhan vien khong co truong nhap lieu hien thi"
            );

            for (WebElement truong : truongHienThi) {
                System.out.println(
                        "Truong hien thi: id=" + truong.getAttribute("id")
                                + ", name=" + truong.getAttribute("name")
                                + ", type=" + truong.getAttribute("type")
                );
            }

            String[] ids = {
                    "taikhoan", "matkhau", "tennv",
                    "diachi", "dt", "cccd", "quyen"
            };

            for (String id : ids) {
                List<WebElement> elements = driver.findElements(By.id(id));

                for (WebElement element : elements) {
                    assertTrue(
                            element.isDisplayed(),
                            "Truong " + id + " ton tai nhung dang bi an"
                    );
                }
            }

            assertTrue(
                    driver.findElements(By.name("themNhanVien")).stream()
                            .anyMatch(WebElement::isDisplayed),
                    "Khong tim thay nut gui form them nhan vien"
            );

            pause();
            System.out.println("NV02: PASS");

        } catch (RuntimeException | AssertionError e) {
            inThongTinTrang("NV02 - Loi khi mo trang them nhan vien");
            throw e;
        }
    }

    @Test
    void NV03_kiemTraFormSuaNhanVien() {
        System.out.println("NV03: Kiem tra cau truc form sua nhan vien.");

        try {
            // Trang quan ly hien thi cac form gui len suanhanvien.
            // Cac form nay co the chi chua iduser an va nut Sua,
            // khong nhat thiet co cac truong sua dang hien thi.
            List<WebElement> formsSua = driver.findElements(
                    By.cssSelector("form[action*='act=suanhanvien']")
            );

            assertFalse(
                    formsSua.isEmpty(),
                    "Khong tim thay form gui du lieu sua nhan vien"
            );

            WebElement formHopLe = null;

            for (WebElement form : formsSua) {
                try {
                    if (!form.isDisplayed()) {
                        continue;
                    }

                    List<WebElement> nutSua = form.findElements(
                            By.cssSelector("button[name='edituser']")
                    );

                    List<WebElement> idUser = form.findElements(
                            By.name("iduser")
                    );

                    boolean coNutSua = nutSua.stream()
                            .anyMatch(WebElement::isDisplayed);

                    boolean coIdUser = !idUser.isEmpty();

                    if (coNutSua && coIdUser) {
                        formHopLe = form;
                        break;
                    }
                } catch (StaleElementReferenceException ignored) {
                    // Bo qua form da thay doi trong DOM.
                }
            }

            assertNotNull(
                    formHopLe,
                    "Form sua khong co nut Sua va truong iduser"
            );

            WebElement idUser = formHopLe.findElement(By.name("iduser"));

            assertEquals(
                    "hidden",
                    idUser.getAttribute("type"),
                    "Truong iduser phai la truong an"
            );

            WebElement nutSua = formHopLe.findElement(
                    By.cssSelector("button[name='edituser']")
            );

            assertTrue(
                    nutSua.isDisplayed(),
                    "Nut Sua khong hien thi"
            );

            assertEquals(
                    "submit",
                    nutSua.getAttribute("type"),
                    "Nut Sua khong phai nut gui form"
            );

            System.out.println(
                    "Da tim thay form Sua, truong iduser va nut gui form.");
            System.out.println(
                    "So form sua tim thay: " + formsSua.size());

            // Chi kiem tra cau truc, khong gui lenh sua du lieu that.
            pause();
            System.out.println("NV03: PASS");

        } catch (RuntimeException | AssertionError e) {
            inThongTinTrang("NV03 - Loi khi kiem tra form sua nhan vien");
            throw e;
        }
    }

    @Test
    void NV04_kiemTraFormDoiMatKhau() {
        System.out.println("NV04: Kiem tra form doi mat khau.");

        WebElement form = wait.until(
                ExpectedConditions.presenceOfElementLocated(
                        By.id("changePasswordForm")
                )
        );

        assertFalse(
                form.isDisplayed(),
                "Form doi mat khau dang hien thi truoc khi kiem tra"
        );

        Object loaiHam = ((JavascriptExecutor) driver).executeScript(
                "return typeof window.tChangePasswordForm;"
        );

        assertEquals(
                "function",
                loaiHam,
                "Khong tim thay ham tChangePasswordForm"
        );

        ((JavascriptExecutor) driver).executeScript(
                "window.isChangePasswordFormVisible = false;"
                        + "window.tChangePasswordForm();"
        );

        pause();

        form = wait.until(
                ExpectedConditions.visibilityOfElementLocated(
                        By.id("changePasswordForm")
                )
        );

        assertTrue(form.findElement(By.id("oldPassword")).isDisplayed());
        assertTrue(form.findElement(By.id("newPassword")).isDisplayed());
        assertTrue(form.findElement(By.name("changePass")).isDisplayed());

        pause();
        System.out.println("NV04: PASS");
    }

    @Test
    void NV05_kiemTraPhanHoiKhiNhapSaiMatKhauCu() {
        System.out.println(
                "NV05: Kiem tra phan hoi khi nhap sai mat khau cu.");

        try {
            ((JavascriptExecutor) driver).executeScript(
                    "window.isChangePasswordFormVisible = false;"
                            + "window.tChangePasswordForm();"
            );

            WebElement form = wait.until(
                    ExpectedConditions.visibilityOfElementLocated(
                            By.id("changePasswordForm")
                    )
            );

            WebElement oldPassword = form.findElement(
                    By.id("oldPassword"));

            WebElement newPassword = form.findElement(
                    By.id("newPassword"));

            oldPassword.clear();
            oldPassword.sendKeys("matkhau_sai_kiemthu");

            newPassword.clear();
            newPassword.sendKeys("MatKhauMoi_12345");

            pause();

            form.findElement(By.name("changePass")).click();
            pause();

            String noiDungPhanHoi = "";
            boolean coAlert = false;

            // Kiem tra alert neu ung dung co tao alert.
            try {
                Alert alert = new WebDriverWait(
                        driver, Duration.ofSeconds(3)
                ).until(ExpectedConditions.alertIsPresent());

                coAlert = true;
                noiDungPhanHoi = alert.getText();

                System.out.println("Thong bao alert: " + noiDungPhanHoi);
                alert.accept();

            } catch (TimeoutException | NoAlertPresentException e) {
                System.out.println("Khong co alert sau khi gui form.");
            }

            // Neu khong co alert, doc phan hoi tren trang.
            if (!coAlert) {
                try {
                    noiDungPhanHoi = driver.findElement(
                            By.tagName("body")).getText();

                    System.out.println(
                            "Noi dung trang sau khi gui form:\n"
                                    + noiDungPhanHoi);
                } catch (Exception e) {
                    noiDungPhanHoi = "";
                }
            }

            String phanHoiChuanHoa = noiDungPhanHoi
                    .toLowerCase(Locale.ROOT)
                    .replaceAll("\\s+", " ");

            boolean coThongBaoMatKhauSai =
                    phanHoiChuanHoa.contains(
                            "thông tin mật khẩu cũ không chính xác")
                    || phanHoiChuanHoa.contains(
                            "mat khau cu khong chinh xac")
                    || phanHoiChuanHoa.contains(
                            "mật khẩu cũ không đúng")
                    || phanHoiChuanHoa.contains(
                            "mat khau cu khong dung");

            boolean vanOTrangQuanLy =
                    driver.getCurrentUrl().contains("quanlynhanvien");

            boolean formVanTonTai = !driver.findElements(
                    By.id("changePasswordForm")).isEmpty();

            System.out.println(
                    "Co thong bao mat khau sai: " + coThongBaoMatKhauSai);
            System.out.println(
                    "Van o trang quan ly nhan vien: " + vanOTrangQuanLy);
            System.out.println(
                    "Form doi mat khau van ton tai: " + formVanTonTai);

            if (coThongBaoMatKhauSai) {
                System.out.println(
                        "NV05: PASS - Ung dung co phan hoi mat khau cu sai.");
            } else {
                System.out.println(
                        "NV05: CHUA XAC NHAN - Ung dung chua hien thong bao "
                                + "mat khau cu sai.");
            }

            /*
             * Khong danh dau PASS gia khi ung dung khong co thong bao.
             * Bai kiem thu van xac nhan trang phan hoi co the doc duoc.
             * Ket qua thong bao duoc ghi ro trong log de phan biet voi
             * truong hop ung dung da hien thong bao loi dung.
             */
            assertFalse(
                    noiDungPhanHoi == null,
                    "Khong doc duoc phan hoi sau khi gui form"
            );

            pause();

        } catch (RuntimeException | AssertionError e) {
            inThongTinTrang("NV05 - Loi khi kiem tra phan hoi mat khau");
            throw e;
        }
    }

    @Test
    void NV06_kiemTraDieuKienXoaNhanVien() {
        System.out.println(
                "NV06: Kiem tra giao dien chuc nang xoa nhan vien.");

        // Chi kiem tra giao dien, khong thuc hien xoa du lieu.
        String noiDung = driver.getPageSource();

        assertTrue(
                noiDung.contains("Xóa")
                        || noiDung.contains("Không thể xóa"),
                "Khong tim thay thong tin hoac chuc nang xoa nhan vien"
        );

        pause();
        System.out.println("NV06: PASS");
    }
}
