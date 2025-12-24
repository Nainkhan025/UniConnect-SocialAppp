
document.addEventListener("DOMContentLoaded", function () {

    const image = document.getElementById("postImage");
    const lightbox = document.getElementById("lightbox");
    const lightboxImg = document.getElementById("lightboxImg");

    if (image) {
        image.addEventListener("click", function () {
            lightboxImg.src = this.src;
            lightbox.style.display = "flex";
        });
    }

    lightbox.addEventListener("click", function () {
        lightbox.style.display = "none";
        lightboxImg.src = "";
    });

});

