/* Disabilita l'invio di moduli se ci sono campi non validi */
(function() {
  'use strict';
  window.addEventListener('load', function() {
	/* Ottieni i moduli a cui vuoi aggiungere gli stili di convalida */
	var forms = document.getElementsByClassName('needs-validation');
	/* Esegui il loop su di loro e impedisci la presentazione */
	var validation = Array.prototype.filter.call(forms, function(form) {
	  /* Quando viene cliccato il pulsante "Inserisci" 
	     viene controllato che i dati inseriti nel form sono validi */
	  form.addEventListener('submit', function() {
		if (form.checkValidity() === false) {
		  event.preventDefault();
		  event.stopPropagation();
		}
		form.classList.add('was-validated');
	  }, false);
	});
  }, false);
})();