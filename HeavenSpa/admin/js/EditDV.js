
const imagess = document.querySelectorAll('.image-container');

imagess.forEach(image => {
    image.addEventListener('mouseenter', () => {
        image.querySelector('.close').style.display = 'block';
    });

    image.addEventListener('mouseleave', () => {
        image.querySelector('.close').style.display = 'none';
    });
});

var removedImages = [];
document.querySelectorAll('.image-container .close').forEach(function (closeButton) {
    closeButton.addEventListener('click', function (e) {
        e.preventDefault();
        var imageContainer = this.parentElement;
        var image = imageContainer.querySelector('img');
        removedImages.push(image.src);
        imageContainer.remove();
        document.getElementById('removedImages').value = removedImages.join(',');
    });
});
document.getElementById("edit_dv").addEventListener("click", function() {
    document.querySelector(".modalBox").classList.remove("hidden");
});
document.querySelector(".fas.fa-times").addEventListener("click", function() {
    document.querySelector(".modalBox").classList.add("hidden");
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

$(document).ready(function() {
    var max_fields = 10; 
    var wrapper    = $(".input_fields_wrap"); 
    var add_button = $("#addMore"); 
    var x = 0; 
    
    $(add_button).click(function(e) { 
        e.preventDefault();
        if(x < max_fields) { 
            x++; 
            $(wrapper).append('<div><input type="text" name="Thoiluong[]" style = "margin: 10px 0px 15px 39px;"/><input type="text" name="gia[]" style = "margin: 10px 0px 15px 78px;"/><lable class="remove_field"> Xóa</lable></div>');
            document.getElementById('bienphp').value= x;
        }
    });

    $(wrapper).on("click",".remove_field", function(e) { 
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })
    
});
