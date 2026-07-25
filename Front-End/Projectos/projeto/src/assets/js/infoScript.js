// Declarando Variaveis
var toggleModal = document.querySelectorAll(".toggle-modal");
var toggleMenu = document.querySelectorAll(".toggle-menu");
var menuMobile = document.querySelector(".navBar-mob");
var btnMenuMobImg = document.querySelector(".btn-menu-mob ion-icon");


// Mostrar conteudo
function mostrarConteudo(num) {
    var texto = document.getElementById("conteudo" + num);
    var btn = document.getElementsByClassName("btn")[num - 1];
    var outrosTextos = document.getElementsByClassName("content");
    var outrosBtns = document.getElementsByClassName("btn");

    for (var i = 0; i < outrosTextos.length; i++) {
        if (outrosTextos[i].style.display === "block") {
            outrosTextos[i].style.display = "none";
        }
    }

    for (var i = 0; i < outrosBtns.length; i++) {
        outrosBtns[i].classList.remove("ativo");
    }

    if (texto.style.display === "block") {
        texto.style.display = "none";
        btn.classList.remove("ativo");
    } else {
        texto.style.display = "block";
        btn.classList.add("ativo");
    }
}

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
