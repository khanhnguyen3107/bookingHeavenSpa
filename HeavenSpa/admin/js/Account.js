document.getElementById("username").addEventListener("click", function() {
    document.querySelector(".modalBox").classList.remove("hidden");
});
document.querySelector(".fas.fa-times").addEventListener("click", function() {
    document.querySelector(".modalBox").classList.add("hidden");
});
document.getElementById("edit_button").addEventListener("click", function() {
    document.querySelector(".modalBoxs").classList.remove("hidden");
});
document.querySelector(".fas.fa-times").addEventListener("click", function() {
    document.querySelector(".modalBoxs").classList.add("hidden");
});