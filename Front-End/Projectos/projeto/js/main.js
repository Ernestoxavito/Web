// Declarando Variaveis
var toggleModal = document.querySelectorAll(".toggle-modal");
var toggleMenu = document.querySelectorAll(".toggle-menu");
var menuMobile = document.querySelector(".navBar-mob");
var btnMenuMobImg = document.querySelector(".btn-menu-mob ion-icon");

//Abrindo e Fechado Menu Mobile
for (m = 0; m < toggleMenu.length; m++) {
    toggleMenu[m].addEventListener("click", function () { 
        var overlay = document.querySelector(".overlay");

        overlay.classList.toggle("open");
        menuMobile.classList.toggle("menu-is-close");
        menuMobile.classList.toggle("menu-is-open");

        var icon = btnMenuMobImg.getAttribute("name"); 
            
        if (icon === "menu-outline") {
            btnMenuMobImg.setAttribute("name", "close-outline");
        } else{
            btnMenuMobImg.setAttribute("name", "menu-outline");
        }
    });
}

//Page Preloader
window.addEventListener("load", function () {
    var pagePreloder = document.querySelector(".preloader");
    pagePreloder.classList.add("fade-out");

    setTimeout(function () {
        pagePreloder.style.display = "none";
    }, 2000);
});

// Botões com link
function linkCadastro(link) {
    location.href = "../src/pages/cadastro.html"
}

function linkLogin(Link) {
    location.href = "../src/pages/login.html"
}