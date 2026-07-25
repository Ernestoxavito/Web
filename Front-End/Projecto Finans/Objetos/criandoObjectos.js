

// Funções construtoras
function produto(nome, preco, desc) {
    this.nome = nome
    this.getPrecoComDesconto = () => {
        return preco * (1 - desc)
    }
}

const p1 = new produto('Hp', 2000, 1.12)
const p2 = new produto('Dell', 1000, 0.22)
console.log(p1.getPrecoComDesconto(), p2.getPrecoComDesconto())
//Função factory
function criarFuncionario(nome, salario, faltas) {
    return {
        nome,
        salario,
        faltas,
        getSalario() {
            return (salarioBase / 30) * (30 - faltas)
        }
    }

}

const f1 = criarFuncionario('Olá', 3000, 5)
const f2 = criarFuncionario('oii', 3000, 4)
console.log(f1.getSalario(), f2.getSalario())
// Object.create
const filha = Object.create(null)
filha.nome = 'Ernesto Qundala'
console.log(filha)
// JSON
const fromJSON = JSON.parse('Olá mundo')
console.log(fromJSON. info)