
<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../model/TinhToanDien.php';

class TinhToanDienTest extends TestCase
{
    public function testTinhSanLuongBacDauTien()
    {
        $this->assertEquals(50, tinhSanLuongBac(50, 0, 100));
    }

    public function testSanLuongTaiMoc100()
    {
        $this->assertEquals(100, tinhSanLuongBac(100, 0, 100));
    }

    public function testSanLuongTaiMoc101()
    {
        $this->assertEquals(1, tinhSanLuongBac(101, 101, 150));
    }

    public function testSanLuongTaiMoc150()
    {
        $this->assertEquals(50, tinhSanLuongBac(150, 101, 150));
    }

    public function testSanLuongBacKhongGioiHan()
    {
        $this->assertEquals(1, tinhSanLuongBac(151, 151, null));
    }

    public function testSanLuongBacHaiTai120Kwh()
    {
    $this->assertEquals(20, tinhSanLuongBac(120, 101, 150));
    }
}
