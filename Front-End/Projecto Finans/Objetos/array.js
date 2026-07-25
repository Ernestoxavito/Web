//console.log(typeof Array, typeof new  Array)

let aprovado = new Array('Bia', 'Ernesto')
console.log(aprovado)
aprovado.push('Quindala')
console.log(aprovado.length)
console.log(aprovado[2])
delete aprovado[0]
console.log(aprovado)
aprovado.splice(0, 0, 'Front-end')
console.log(aprovado)
console.log(aprovado.length)