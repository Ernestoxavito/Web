// coleção dinâmica de pares chave/valor

const produto = new Object
produto.nome = 'Ebook'
produto.categoria = 'HP'
produto.preco = 500

console.log(produto)
delete produto.categoria
delete produto.preco
console.log(produto)

const carro = {
    modelo: 'BMW',
    valor: 100000,
    proprietario: {
        nome: 'Erne',
        idade: 30,
        endereco: {
            Cidade: 'Camama',
            numero: 10
        }
    },
    condutores: [{
        nome: 'Juunior',
        idade: 30
    }, {
        nome: ' Seo',
        idade: 22
    }],
    calcularValorSeguro: function () {
        //...
    }
}
// Acessar os atributos do Objeto
carro.proprietario.endereco.numero = 1000
console.log(carro)