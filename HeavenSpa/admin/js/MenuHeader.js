document.getElementById("userInfo").addEventListener("click", function() {
    document.querySelector(".modalBox").classList.remove("hidden");
});
document.querySelector(".fa.fa-times").addEventListener("click", function() {
    document.querySelector(".modalBox").classList.add("hidden");
});