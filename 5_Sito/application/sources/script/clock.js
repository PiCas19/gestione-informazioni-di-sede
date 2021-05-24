	//permette di far partire l'orologio
	function startTime() {
	  //data di oggi
	  var today = new Date();
	  //giorni
	  var dd = today.getUTCDate();
	  //mesi
	  var mm = today.getMonth()+1; 
	  //anni
	  var yyyy = today.getFullYear();
	  	  
	  //ore 
	  var h = today.getHours();
	  //minuti
	  var m = today.getMinutes();
	  //secondi
	  var s = today.getSeconds();
	  //permette di aggiungere lo zero se i secondi, i minuti 
	  //le ore sono minori di 10 (ore, minuti o secondi).
	  h = (h < 10) ? "0" + h : h;
      m = (m < 10) ? "0" + m : m;
      s = (s < 10) ? "0" + s : s;
	  
	  //premette di aggiungere gli zeri ai mesi e ai giorni
	  //dd = (dd < 10) ? "0" + dd : dd;
	  mm= (mm < 10) ? "0" + mm : mm;
	  //stampo la data e l'ora
	  var ajax_load = document.getElementById('txt').innerHTML =
	  dd + "/" + mm + "/" +  yyyy + "&nbsp&nbsp" + h + ":" + m + ":" + s;
	  var t = setTimeout(startTime, 500);
    }

	
	
	
	
	
	