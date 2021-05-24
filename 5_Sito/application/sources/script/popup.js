/* popup di eliminazione */
var deleteModal = document.getElementById('deleteModal')

/* paragrafo nel quale viene scritto il messaggio del popup  */
var descriptionModal = document.getElementById('message')


/* quando l'utente clicca sul pulsante "Elimina" compare il popup di conferma */
deleteModal.addEventListener('show.bs.modal', function (event) {


  /* quando l'utente clicca sul pulsante "Elimina" */
  var button = event.relatedTarget
  
  /* setto il messaggio del popup */
  descriptionModal.innerHTML = button.getAttribute('data-information');
  
  
  /* ottengo l'URL  */
  var url = button.getAttribute('data-url');
  
  
  /* l'url deve essere definito nell'attributo data-url del pulsante "Elimina" */
  if (typeof url !== 'undefined') {
  	/* Aggiungo la proprietà href al pulsante "Sì, conferma". */
  	/* Quando verrà il pulsante verrà richiamato il metodo deleteInformazione del controller Home. */
    $("#confirm").attr("href", url);
  }
})
