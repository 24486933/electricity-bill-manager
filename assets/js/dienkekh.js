
var selectedRadio = null;

function showDienKe(makh) {
    var dienke_row = document.getElementById('dienke_row_' + makh);
    var button = document.getElementById('button_' + makh);

    if (!dienke_row || !button) {
        console.error('Khong tim thay hang dien ke hoac nut xem:', makh);
        return;
    }

    var currentDisplay = window.getComputedStyle(dienke_row).display;

    // Neu hang dien ke dang mo thi dong lai
    if (currentDisplay !== 'none') {
        if (
            selectedRadio &&
            selectedRadio.closest('tr') &&
            selectedRadio.closest('tr').id === 'dienke_row_' + makh
        ) {
            selectedRadio.checked = false;
            selectedRadio = null;
            localStorage.removeItem('selected_id_madk');
        }

        dienke_row.style.display = 'none';
        button.innerHTML = 'Xem';
        return;
    }

    // Dong cac hang dien ke khac dang mo
    var allRows = document.querySelectorAll('tr[id^="dienke_row_"]');

    allRows.forEach(function (row) {
        if (window.getComputedStyle(row).display !== 'none') {
            if (
                selectedRadio &&
                selectedRadio.closest('tr') &&
                selectedRadio.closest('tr').id === row.id
            ) {
                selectedRadio.checked = false;
                selectedRadio = null;
                localStorage.removeItem('selected_id_madk');
            }

            row.style.display = 'none';

            var currentMakh = row.id.substring('dienke_row_'.length);
            var currentButton = document.getElementById('button_' + currentMakh);

            if (currentButton) {
                currentButton.innerHTML = 'Xem';
            }
        }
    });

    // Mo hang dien ke duoc chon
    dienke_row.style.display = 'table-row';
    button.innerHTML = 'Đóng';

    // Chi bo chon radio neu radio thuc su ton tai
    if (selectedRadio) {
        selectedRadio.checked = false;
        selectedRadio = null;
    }

    localStorage.removeItem('selected_id_madk');
}

window.onload = function () {
    var radios = document.getElementsByName('selected_id');

    for (var i = 0; i < radios.length; i++) {
        radios[i].addEventListener('click', function () {
            if (selectedRadio === this && this.checked) {
                this.checked = false;
                selectedRadio = null;
                localStorage.removeItem('selected_id_madk');
            } else {
                selectedRadio = this;

                var row = this.closest('tr');
                var firstCell = row
                    ? row.querySelector('td:first-child')
                    : null;

                if (firstCell) {
                    var madk = firstCell.innerText.trim();
                    localStorage.setItem('selected_id_madk', madk);
                }
            }
        });
    }
};

function kiemTraChon() {
    if (!selectedRadio) {
        alert('Vui lòng chọn một điện kế để lập hóa đơn.');
        return false;
    }

    lapHoaDon();
    return true;
}

function lapHoaDon() {
    var selectedMadk = localStorage.getItem('selected_id_madk');
    var form = document.getElementById('hoadon');

    if (!form) {
        console.error('Khong tim thay form hoa don.');
        return;
    }

    if (!selectedMadk) {
        alert('Vui lòng chọn một điện kế để lập hóa đơn.');
        return;
    }

    var url = form.getAttribute('action');
    var xhr = new XMLHttpRequest();

    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
            } else {
                console.error('Loi khi lap hoa don:', xhr.status, xhr.responseText);
            }
        }
    };

    var data = JSON.stringify({
        selected_madk: selectedMadk
    });

    xhr.send(data);
}
