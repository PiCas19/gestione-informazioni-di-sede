//elemento dove viene scritto nome della località
var nome = document.querySelector('.temp1');
//elemento dove viene scritto descrizione
var desc = document.querySelector('.temp2');
//elemento dove viene scritto temperatura
var temp = document.querySelector('.temp3');
//elemento dove viene aggiunta l'icona della meteo
var img = document.getElementById("wicon");

/**
 * Permette di avviare il meteo.
 */
function startWeather() {
	//permette di ricavare le risorse della meteo con l'API OpenWeatherMap
	fetch('https://api.openweathermap.org/data/2.5/weather?q=Canobbio&units=metric&lang=it&appid=6feca9dfdd0408127de155919920e155')
	//ricavo le risorse in formato JSON
	.then(response => response.json())
	.then(data => {
		
		//nome della località
		var nameValue = data['name'];
		//temperatura
		var tempValue = Math.ceil(data['main']['temp']) + " &#8451;";
		//descrizione della meteo
		var descValue = data['weather'][0]['description'];
		//id dell'icona meteo
		var imgValue = data['weather'][0]['icon'];
		
		//setto ogni elemento il valore ricavato dall'API
		nome.innerHTML = nameValue;
		desc.innerHTML = descValue;
		temp.innerHTML = tempValue;
		
		//aggiungo l'immagine della meto
		img.setAttribute("src", "https://samtinfo.ch/disp_info/application/sources/img/weather-icons/"+imgValue+".png");
		
	
		
		
	})
	
	.catch(err => alert("Wrong city name!"))
}

