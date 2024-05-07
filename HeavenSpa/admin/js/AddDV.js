function addTimePrice() {
    const container = document.querySelector('.time-price-container');
    const row = document.createElement('div');
    row.classList.add('time-price-row');

    const inputTime = document.createElement('input');
    inputTime.type = 'text';
    inputTime.name = 'thoiGian[]';
    inputTime.placeholder = 'Thời gian';
    inputTime.required = true;

    const inputPrice = document.createElement('input');
    inputPrice.type = 'text';
    inputPrice.name = 'giaTien[]';
    inputPrice.placeholder = 'Giá tiền';
    inputPrice.required = true;

    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.textContent = 'Xóa';
    removeButton.onclick = function() {
        removeTimePrice(this);
    };

    row.appendChild(inputTime);
    row.appendChild(inputPrice);
    row.appendChild(removeButton);

    container.appendChild(row);
}

function removeTimePrice(button) {
    const row = button.parentElement;
    const container = row.parentElement;
    container.removeChild(row);
}
function showInput(element) {
    var input = document.getElementById('nhomkhacdiv');
    if (element.value == 'nhomkhac') {
        input.style.display = 'block';
    } else {
        input.style.display = 'none';
    }
}
$(document).ready(function() {
    $('#NhomDV').change(function() {
        if ($(this).val() == 'nhomkhac') {
            $('#nhomkhacdiv').css('display', 'block');
        } else {
            $('#nhomkhacdiv').css('display', 'none');
        }
    });
});
function updateFileList() {
    var input = document.getElementById('image');
    var output = document.getElementById('fileList');

    output.innerHTML = '<ul>';
    for (var i = 0; i < input.files.length; ++i) {
        output.innerHTML += '<li>' + input.files.item(i).name + '</li>';
    }
    output.innerHTML += '</ul>';
}