<?php

function tinhSanLuongBac($ketqua, $tuSoKw, $denSoKw)
{
    if ($ketqua < $tuSoKw) {
        return 0;
    }

    if ($denSoKw === null) {
        return $ketqua - $tuSoKw + ($tuSoKw != 0 ? 1 : 0);
    }

    if ($ketqua < $denSoKw) {
        return $ketqua - $tuSoKw + ($tuSoKw != 0 ? 1 : 0);
    }

    return $denSoKw - $tuSoKw + ($tuSoKw != 0 ? 1 : 0);
}