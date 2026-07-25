
var altura = 0
var largura = 0
function ajustaTamanhoPalcoJogo(){
    var  altura = window.innerHeight
    var largura = window.innerWidth
    console.log(altura, largura)
}

ajustaTamanhoPalcoJogo()

function posicaoRandomica() {
    var posicaoX = Math.floor(Math.random() * largura)
    var posicaoY = Math.floor(Math.random() * altura)
    
    console.log(posicaoX, posicaoY)
    
    //criar o elemento html...
    var mosca = document.createElement('img')
    mosca.src = 'imagens/mosca.png'
    mosca.className = 'mosquito1'
    mosca.style.left = posicaoX + 'px'
    mosca.style.top = posicaoY + 'px'
    mosca.style.position = 'absolute'
    document.body.appendChild(mosca)  
} 

