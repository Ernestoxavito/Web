const nome = 'OBS'
const valor = 12

const obj4 = {[nome]: valor}
console.log(obj4)
// Escrever função dentro de um objecto

const obj5 = {
    funcao1() {
        obj5.nome1 = 'olá'
    }
}
console.log(obj5.funcao1())