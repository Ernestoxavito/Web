const pessoa = {
    nome: 'Seo',
    idade: 23,
    peso: 50
}

console.log(Object.keys(pessoa))
console.log(Object.values(pessoa))
//Função que percorre o índice do Array
Object.entries(pessoa).forEach(([chave, valor]) => {
    console.log(`${chave}: ${valor}`)
})