document.getElementById("makh").addEventListener("input", function () {
    var input = this.value;
    if (input.trim() !== '') {
        searchInvoice(input, 'makh');
    } else {
        document.getElementById("searchResults").innerHTML = '';
    }
});

document.getElementById("nameKH").addEventListener("input", function () {
    var input = this.value;
    if (input.trim() !== '') {
        searchInvoice(input, 'nameKH');
    } else {
        document.getElementById("searchResults").innerHTML = '';
    }
});

document.getElementById("makh").addEventListener("keydown", function (event) {
    if (event.keyCode === 13) { // mã phím của phím enter
        event.preventDefault(); // chặn hành động mặc định của phím enter
        var input = this.value;
        if (input.trim() !== '') {
            searchInvoice(input);
        }
    }
});
document.getElementById("nameKH").addEventListener("keydown", function (event) {
    if (event.keyCode === 13) { // mã phím của phím enter
        event.preventDefault(); // chặn hành động mặc định của phím enter
        var input = this.value;
        if (input.trim() !== '') {
            searchInvoice(input);
        }
    }
});
function searchInvoice(input, type) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            document.getElementById("searchResults").innerHTML = this.responseText;
        }
    };
    xhr.open("POST", "../controller/dienke.php?act=kqsearch", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    if (type === 'makh') {
        xhr.send("makh=" + input);
    } else if (type === 'nameKH') {
        xhr.send("nameKH=" + input);
    }
}