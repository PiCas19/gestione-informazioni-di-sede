//url del controller
var loadUrl = "https://samtinfo.ch/disp_info/schermo/";

//configurazione ajax
$.ajaxSetup ({
	//svuoto la cache
	 cache: false
})

//stato di visualizzazione dell'informazione o filmato/presentazione
let status = false;
//permette di mantenere il vecchio risultato di una chiamata Ajax.
let oldResult = null;
//id del informazioni di ogni chiamata Ajax.
let id = null;
//controllo che permette di sapere se sullo schermo ci sono dei video
let isVideo = false;

//ogni 500 millisecondi eseguo una richiesta POST al metodo getViewsSchermo
setInterval(function(){ 
	$.ajax({
		type: "POST",
		url: loadUrl+"getViewsSchermo",
		success: function(result) {	
			//console.log(result);
			$.each(JSON.parse(result), function(index, value){	
				//se non è un filmato/presentazione devo stampare l'informazione
				if(value['video_presentazione']){
					isVideo = true;
				}
				else{
					//se c'é un video sullo schermo devo toglierlo
					if(isVideo){
						//imposto tutte le variabili al loro valore di partenza
						status = false;
						oldResult = null;
						//elimino tutti gli elementi presenti nella parte centrale
						$("#reload").empty();
						isVideo = false;
					}
				}
				
				//il vecchio risultato deve essere differente da quello attuale.
				if(oldResult != result){
					//id del informazioni viene modificato ogni 500 millisecondi
					id = value['id'];
					//l'informazione deve essere visibile
					status = true;
				}
			});
			//se il risultato è vuoto, ovvero non ci sono delle informazioni
			if(JSON.parse(result) == ""){
				//imposto tutte le variabili al loro valore di partenza
				status = false;
				oldResult = null;
				//elimino tutti glie elementi presenti nella parte centrale
				$("#reload").empty();
			}
			else{	
				//controllo se l'informazione attuale deve essere eliminata
				$.each(JSON.parse(oldResult), function(index, value){
					//controllo che l'id vecchio non corrisponde a quello attuale
					if(id != value['id']){
						//elimino l'informazione attuale
						$("#"+value['id']).remove();
						//imposto tutte le variabili al loro valore di partenza
						status = false
						oldResult = null
					}
				});				
			}
				
			$.each(JSON.parse(result), function(index, value){						
				if(status){
					//modifico il vecchio risultato con il nuovo risultato				
					oldResult = result;
					//se è un informazione
					if(!value['video_presentazione']){
													
						//stampo l'informazione
						$("#reload").html(
							"<div id='"+value['id']+"' class='col-sm-12 my-2 mt-5'>"+
							"<div class='card'>" +
							"<div class='card-header'>" +
							"<h5 style='font-size:40px;' class='card-title text-center'>"+value['titolo']+"</h5>"+
							"</div>" +
							"<p style='color:"+value['coloreTesto']+";font-size:35px;' class='descrizione mx-3 card-text text-justify'>"+value['descrizione']+"</p>"+
							"</div>"+
							"</div>"
						);
					}
					else{
						if(value["isVideo"]){
							//stampo il filmato
							$("#reload").html(
								"<video class='d-flex justify-content-center mt-3' width='100%' height='100%' autoplay loop>"+
								"<source src='./application/sources/filmati-presentazioni/"+value['nome']+"' type='video/mp4'>"+
								"</video>"
							);
						}
						else{
							isVideo = true;
							//stampo il filmato presentazione
							$("#reload").html(
								"<div id='"+value['id']+"'>"+
								"<canvas id='the-canvas-"+value['id']+"' style='border: 1px solid black;direction: ltr;margin-left:20%;'></canvas>"+
								"</div>"
							);
							onLoadPdf("./application/sources/filmati-presentazioni/"+value['nome'], value['id'], 0.8);
						}
					}
					//modifico lo stato dell'informazione attuale						
					status = false;
				}
				//se non è un video devo cambiare le slide
				if(isVideo){
					onNextPage();
				}
			})
		}
	});
}, 500);



//stato di visualizzazione filmato/presentazione
let statusVideo = false;
//permette di mantenere il vecchio risultato di una chiamata Ajax.
let oldResultVideo = null;
//id del filmato/presentazione di ogni chiamata Ajax.
let videoId = null;
//permette di sapere se l'immagine della CPT è visibile sullo schermo
let isCPT = true;

//ogni 500 millisecondi eseguo una richiesta POST al metodo getViewsOnlyVideoPresentation
setInterval(function(){ 
	$.ajax({
		type: "POST",
		url: loadUrl+"getViewsOnlyVideoPresentation",
		success: function(result) {	
			$.each(JSON.parse(result), function(index, value){	
				//se non è un filmato/presentazione devo stampare l'informazione
				if(value['video_presentazione']){
					isVideo = true;
				}
				
				//il vecchio risultato deve essere differente da quello attuale.
				if(oldResultVideo != result){
					//id del filmato/presentazione viene modificato ogni 500 millisecondi
					videoId = value['id'];
					//il filmato/presentazione deve essere visibile
					statusVideo = true;
				}
			});
			
			//se il risultato è vuoto, ovvero non ci dei filmati/presentazioni
			if(JSON.parse(result) == ""){
				//imposto tutte le variabili al loro valore di partenza
				statusVideo = false;
				oldResultVideo = null;
				//stampo l'immagine della CPT
				$("#video").html(
					"<div class='col'>" +
					"</div>" +
					"<div class='col'>" +
					"</div>" +
					"<div class='col'>" +
					"<div class='card-block'>" +
					"<div class='card-body'>" + 
					"<img src='./application/sources/img/cpt.jpg' alt='CPT' width='250px' height='150px'>" +
					"</div>" +
					"</div>" + 
					"</div>"
				);
				
				isCPT = true;
			}
			else{
				//controllo se il filmato/presentazione attuale deve essere eliminata
				$.each(JSON.parse(oldResultVideo), function(index, value){
					//controllo che l'id vecchio non corrisponde a quello attuale
					if(videoId != value['id']){
						//elimino il filmato/presentazione attuale
						$("#"+value['id']).remove();
						//imposto tutte le variabili al loro valore di partenza
						statusVideo = false
						oldResultVideo = null
					}
				});				
			}
				
			$.each(JSON.parse(result), function(index, value){						
				if(statusVideo){
					//modifico il vecchio risultato con il nuovo risultato				
					oldResultVideo = result;
					
					//se è presente l'immagine della CPT la elimino
					if(isCPT){
						//elimino ogni elemento della schermo.
						$('#video').empty();
						isCPT = false;
					}
					
					if(value["isVideo"]){
						//stampo il filmato
						$("#video").html(
							"<video class='d-flex justify-content-center width='450px' height='250px' autoplay loop>"+
							"<source src='./application/sources/filmati-presentazioni/"+value['nome']+"' type='video/mp4'>"+
							"</video>"
						);
					}
					else{
						//stampo il filmato presentazione
						$("#video").html(
							"<div id='"+value['id']+"'>"+
							"<canvas id='the-canvas-"+value['id']+"' style='border: 1px solid black;direction: ltr;margin-left:20%;'></canvas>"+
							"</div>"
						);
						onLoadPdf("./application/sources/filmati-presentazioni/"+value['nome'], value['id'],0.4);
					}
					//modifico lo stato dell'informazione attuale										
					statusVideo = false;
				}
				//se non è un video devo cambiare le slide
				if(isVideo){
					onNextPage();
				}
			})
		}
	});
}, 500);





	