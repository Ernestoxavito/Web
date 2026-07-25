
//Estrutura de repitição While
 
var x = 12
/**
while(x <= 10) {
    document.write( x + "<br>")
    
    x++
}*/
/** 
do {
   document.write( x + "<br>") 
} while (x <= 10);
*/
/***  
for(var x = 100; x >= 1; x-=5){
    document.write( x + "<br>")
}**/
var lista_frutas = Array()
lista_frutas[0] = "Banana"
lista_frutas[1] = "Maçã"
lista_frutas[2] = "Morango"
lista_frutas[3] = "Uva"
lista_frutas[4] = "Abacate"
lista_frutas[5] = "Pera"

var y = 0

while ( y < lista_frutas.length) {
    document.write(lista_frutas[y] + "<br>")
    y++
}