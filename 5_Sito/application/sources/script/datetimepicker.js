//formato data 
const dateFormat = 'DD.MM.YY HH:mm';
//data attuale
const currentDate = moment(new Date() ,dateFormat);
//controller schermo
var loadUrl = "https://samtinfo.ch/disp_info/schermo/";
//url corrente (cambia in base alla pagina in cui si trova l'utente)
var currentURL = window.location.href;

//identificativo di un informazione o filmato/presentazione
var number  = currentURL.substring(currentURL.length-1, currentURL.length);
//verifico che l'identificativo sia un numero.
//Quando si crea una nuova informazione o filmato/presentazione 
//non viene ancora assegnato un'id a quest'ultimo.
var id = !isNaN(number)?number:-1;

//permette di sapere se ci troviamo nella views informazione
var isViewsInformation = "";

//se siamo nella views informazione devo fare i controlli 
//delle date in base alla tabella Informazione. 
if(currentURL.includes("informazione")){
	isViewsInformation = 1;
}
else{
	isViewsInformation = 0;
}

//vecchia data di inizio
var oldDateStart = $('#oldDateStart');
//vecchia data di fine
var oldDateEnd = $('#oldDateEnd');

//la vecchia data di inizio e di fine è presente solamente nella 
//pagina della modifica di un informazione o di un file.
if(typeof(oldDateStart[0]) != "undefined"  && typeof(oldDateEnd[0]) != "undefined"){
	if(oldDateStart[0].value != " " && oldDateEnd[0].value != " "){
		//stampo la vecchia data di inizio e di fine
		document.getElementById('oldDate').innerHTML = "Vecchia data e orario: " +
		oldDateStart[0].value.substring(0, oldDateStart[0].value.length - 3) + " - " +
		oldDateEnd[0].value.substring(0, oldDateEnd[0].value.length - 3) ;
	}
}


$(function () {
	
	//date-time picker per selezionare la data di inizio
	$('#datetimepicker1').datetimepicker({
		locale: 'it-ch',
		useCurrent: false,
		minDate: currentDate,
		//faccio partire l'ora da 00:00
		viewDate: moment(new Date()).hours(0).minutes(0).seconds(0).milliseconds(0),
		//tooltips in italiano
		tooltips: {
			today: 'Vai a oggi',
			clear: 'Seleziona cancella',
			close: 'Chiudi il picker',
			selectMonth: 'Seleziona Mese',
			selectTime: 'Seleziona ora',
			selectDate: 'Seleziona data',
			prevMonth: 'Scorso Mese',
			nextMonth: 'Prossimo Mese',
			selectYear: 'Seleziona anno',
			prevYear: 'Scorso anno',
			nextYear: 'Prossimo anno',
			selectDecade: 'Seleziona decennio',
			prevDecade: 'Scorso decennio',
			nextDecade: 'Prossimo decennio',
			prevCentury: 'Scorso secolo',
			nextCentury: 'Prossimo secolo',
			incrementHour: 'Incrementa ora',
			pickHour: 'Scegli ora',
			decrementHour:'Decrementa ora',
			incrementMinute: 'Incrementa minuti',
			pickMinute: 'Scegli minuti',
			decrementMinute:'Decrementa minuti',
			incrementSecond: 'Incrementa secondi',
			pickSecond: 'Secgli secondi',
			decrementSecond:'Decrementa secondi'
		},
		//formato data e orario
		format: 'DD.MM.YY HH:mm'
	});
	//date-time picker per selezionare la data di fine
	$('#datetimepicker2').datetimepicker({
		locale: 'it-ch',
		useCurrent: false,
		minDate: currentDate, 
	 	//faccio partire l'ora da 00:00
		viewDate: moment(new Date()).hours(0).minutes(0).seconds(0).milliseconds(0),
		//tooltips in italiano
		tooltips: {
			today: 'Vai a oggi',
			clear: 'Seleziona cancella',
			close: 'Chiudi il picker',
			selectMonth: 'Seleziona Mese',
			selectTime: 'Seleziona ora',
			selectDate: 'Seleziona data',
			prevMonth: 'Scorso Mese',
			nextMonth: 'Prossimo Mese',
			selectYear: 'Seleziona anno',
			prevYear: 'Scorso anno',
			nextYear: 'Prossimo anno',
			selectDecade: 'Seleziona decennio',
			prevDecade: 'Scorso decennio',
			nextDecade: 'Prossimo decennio',
			prevCentury: 'Scorso secolo',
			nextCentury: 'Prossimo secolo',
			incrementHour: 'Incrementa ora',
			pickHour: 'Scegli ora',
			decrementHour:'Decrementa ora',
			incrementMinute: 'Incrementa minuti',
			pickMinute: 'Scegli minuti',
			decrementMinute:'Decrementa minuti',
			incrementSecond: 'Incrementa secondi',
			pickSecond: 'Secgli secondi',
			decrementSecond:'Decrementa secondi'
		},
		//formato data e orario
		format: 'DD.MM.YY HH:mm'
	});
	
});


//permette di sapere se bisogna disattivare il campo "data di fine" 
var disableEndDate = false;

//data di inizio e di fine
var endDate = "";
var startDate = "";


//Viene eseguito una funzione quando cambia lo stato del checkbox
//che permette di impostare la giornata intera come orario.
$("#flexSwitchCheckDefault").change(function () {
	//se il checkbox è attivo
	if($(this).prop("checked") == true){
		//Disabilito l'orario e l'utente ha solo la possibilità di modificare la data.
		disableEndDate = true;
		//L'ora di inizio è alle 00:00.
		$('#datetimepicker1').data("datetimepicker").format("DD.MM.YYYY 00:00");
		//disattivo date-time picker che permette di selezionare la data di fine
		$('#datetimepicker2').data("datetimepicker").disable();	
		
		if($('[name="start"]')[0].value != " "){
			//stampo l'orario nel campo "data inizio"
			printEndDateForFullDay()
		}
		
		//ogni volta che modifico il campo "data di inizio" 
		//che permette di selezionare la data di inizio
		$('#datetimepicker1').on("input",function(date){
			if(disableEndDate){
				//stampo l'orario nel campo "data fine"
				printEndDateForFullDay();
				//verifico le date non vanno a sovrapporsi a d'altre 
				//date di un'altra informazione o file.
				$.ajax({
					type: "POST",
					url: loadUrl+"verifyDate",
					data:{
						data_inizio: date.target.value,
						data_fine: $('[name="end"]')[0].value,
						isInformation: isViewsInformation,
						id: id,
						disableEndDate: 0
					},
					success: function(result) {
						$('#overlap')[0].value = result;		
					}
				})
			}
		});
			
	}
	else{
		disableEndDate = false;
		$('#datetimepicker1').data("datetimepicker").format("DD.MM.YY HH:mm");
		//attivo date-time picker che permette di selezionare la data di fine
		$('#datetimepicker2').data("datetimepicker").enable();	
		$('#datetimepicker2').data("datetimepicker").format("DD.MM.YY HH:mm");
			
	}
	
});


/**
 * Permette di stampare nell'input la data di fine 
 * se è attivo l'opzione giornata intera.
 */
function printEndDateForFullDay(){
	//ottengo il valore del campo "data di inizio" 
	var inputStartDate = $('[name="start"]')[0].value;
	//l'utente deve selezionare la data di inizio
	if(inputStartDate != ""){
		//Creo la data di fine in base alla data di inizio.
		//l'orario della data di fine deve essere "23:59".
		endDate = inputStartDate.replace(inputStartDate.substring(inputStartDate.length-5, inputStartDate.length), "23:59");
		$('[name="end"]')[0].value = endDate;
	}
}

if(!disableEndDate){
	//se l'utente modifica il campo "data di fine"
	$('#datetimepicker2').on("input",function(date){
		$.ajax({
			type: "POST",
			url: loadUrl+"verifyDate",
			data:{
				data_inizio: $('[name="start"]')[0].value,
				data_fine: date.target.value,
				isInformation: isViewsInformation,
				id: id,
				disableEndDate: 1
			},
			success: function(result) {	
				$('#overlap')[0].value = result;		
			}
		})
	});
}














