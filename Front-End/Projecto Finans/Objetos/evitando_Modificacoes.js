// Object.preventExtensions
const  produto = Object.preventExtensions({
    nome: 'Qualquer', preco: 1.11, tag: 'promoção'
})

console.log('Extensível:', Object.isExtensible(produto))
produto.nome = 'Bravo'
produto.descricao = 'Borracha escolar'
delete produto.tag
console.log(produto)

// Object.seal
const pessoa = { nome: 'Juliana', idade: 35}
Object.seal(pessoa)
console.log('Selado:', Object.isSealed(pessoa))

pessoa.sobrenome = 'Perla'
delete pessoa.nome
pessoa.idade = 29
console.log(pessoa)
