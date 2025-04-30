window.addEventListener("load", function () {
    setTimeout(function() {
        const preloader = document.getElementById("preloader");
        if (preloader) {
            console.log("Preloader ditemukan, menyembunyikan...");
            preloader.style.display = "none";
        }
    }, 500); // 2 detik delay
});
