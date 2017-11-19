// -------------confirmation lien avant de quitter site-------------------------
var liens = document.querySelectorAll('a.external');
for(var i = 0; i < liens.length; i++){
	var lien = liens[i];
	lien.addEventListener('click',function(event){
		var reponse = window.confirm('voulez-vous vraiment quitter?');
		if(reponse === false){
			event.preventDefault();
		}
	})
}
// -------------Ajoute au survole une couleur sur les pragraphes----------------
// var ps = document.querySelectorAll('p');
//
// for(i = 0; i < ps.length; i++){
// 	var p = ps[i];
// 	var rougit = function(){
// 		this.classList.add('red');
// 	}
// 	var noircie = function(){
// 		this.classList.remove('red');
// 	}
// 	p.addEventListener('mouseover', rougit)
// 	p.addEventListener('mouseout', noircie)
// }
// -------------clignote la couleur quand on clique sur un des paragraphe-------
// var ps = document.querySelectorAll('p');
//
// for(i = 0; i < ps.length; i++){
// 	var p = ps[i];
// 	var rougit = function(){
// 		this.classList.toggle('red');
// 	}
// 	p.addEventListener('mouseover', rougit)
// }
// ----------------------------------------
// var ps = document.querySelectorAll('p');
//
// for(i = 0; i < ps.length; i++){
// 	var p = ps[i];
// 	var rougit = function(){
// 		this.classList.toggle('red');
// 	}
// 	p.addEventListener('click', rougit)
// }
// ---------------------------------------
// var ps = document.querySelectorAll('p');
//
// for(i = 0; i < ps.length; i++){
// 	var p = ps[i];
// 	var rougit = function(){
// 		p.classList.toggle('red');
// 		console.log('rougit', this)
// 	}
// 	var demo = function(){
// 		console.log('demo', this)
// 	}
// 	p.addEventListener('click', rougit)
// 	demo();
// }
