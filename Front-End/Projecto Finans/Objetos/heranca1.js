/*
const ferrari = {
    modelo: 'F40',
    velMax: 300
}

const toyota = {
    modelo: 'V8',
    velMax: 100
}

console.log(ferrari.__proto__ === Object.prototype)
console.log(toyota.__proto__ === Object.prototype)
*/

// Cadeia de protótipos

/*const avo = {attr1: 'A'}
const pai = {__proto__:avo, attr2: 'B'}
const filho = {__proto__: pai, attr3: 'C'}
console.log(filho.attr1, filho.attr2)

const carro= {
    velAtua: 0,
    velMax: 200,
    acelerarMais(delta) {
        if (this.velAtua + delta <= this.velMax) {
            this.velAtua += delta
        } else {
            this.velAtua = this.velMax
        }
    },
    status() {
        return `${this.velAtua}Km/h de ${this.velMax}Km/h`
    }
}

const ferrari = {
    modelo: 'F40',
    velMax: 250
}

const volvo = {
    modelo: 'V8',
    status() {
        return `${this.modelo}: ${super.status()}`
    }
}

Object.setPrototypeOf(ferrari, carro)
Object.setPrototypeOf(volvo, carro)

console.log(ferrari)
console.log(volvo)***/

// Herança 3

/** const pai = { nome: 'Pedro', corCabelo: 'preto'}

const filha1 = Object.create(pai)
filha1.nome = 'Ana'
console.log(filha1.corCabelo)

const filha2 = Object.create(pai, {
    nome: { value: 'bela', writable: false, enumerable: true}
})***/
//Herança 6

function Aula(nome, videoID) {
    this.nome = nome
    this.videoID = videoID
}

const aula1 = new Aula('Bem vindo', 1234)
const aula2 = new Aula('Até Breve', 123)
console.log(aula1, aula2)


