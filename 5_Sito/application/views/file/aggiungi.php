<!-- contenitore -->
<div class="container mb-3 mt-3">
	<h2 class="d-flex justify-content-center">Inserisci informazione</h2>
	<!-- sezione dove carico i filmati e le presentazioni -->
	<div class="d-flex align-items-start py-3 justify-content-center mb-4"> 
		<div class="pl-sm-2 pl-2"> 
			<?php if(!isset($_SESSION['path'])) : ?>
				<b>Carica presentazione/filmati</b>
				<p>Tipo di file accettato meno di 200 MB</p>
				<p>Tipo di formato accettati .mp4 e .pdf.</p>
			<?php endif; ?>
			<br>
			<!-- se viene caricato un file e viene cliccato il pulsante "Carica" viene mostrato il nome del file al posto del dropzone -->
			<?php if(isset($_SESSION['path'])) : ?>			
				<img src="<?php echo URL; ?>application/sources/img/<?php echo (substr($_SESSION['path'], -4) == ".pdf"?"pdf.png":"mp4.png") ?>" width="100" height="100">
				<p><?php echo substr($_SESSION['path'], 44);  ?><p>
				<br>
			<?php else: ?>
				<!-- dropzone -->
				<form action="<?php echo URL; ?>file/upload" class="dropzone" method="post"></form>
				<br>
			<?php endif; ?>
			<!-- form per accettare il file -->
			<form action="<?php echo URL; ?>file/acceptedFile" method="post">
				<!-- pulsante per accettare il file -->
				<button type="submit" name="accepted" class="btn btn-primary">Carica</button>
				<!-- pulsante per rimuovere il file -->
				<?php if(isset($_SESSION['path'])) : ?>
					<button type="submit" name="remove" class="btn btn-danger">Elimina</button>
				<?php else: ?>
					<a class="btn btn-secondary" href="<?php echo URL ?>file/exitAddViewsFile">Esci</a>
				<?php endif; ?>
			</form>
		</div>
	</div>
	<!-- Attivo il form per impostare la data solo se l'utente ha caricato il file -->
	<?php if(isset($_SESSION['path'])) : ?>
		<div class="fileCenter d-flex justify-content-center">
			<form action="<?php echo URL; ?>file/addOrModifyDateEndStartFile" class="needs-validation" method="post" novalidate>
				<div class="form-group row">
					<div class="col-sm-5">
						<!-- checkbox per attivare l'opzione giornata intera -->
						<div class="form-check form-switch">
							  <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="giornata_intera">
							  <label class="form-check-label" for="flexSwitchCheckDefault">Giornata intera</label>
						</div>
					</div>
				</div>
				<br>
				<div class="form-group row">
					<label for="description"><b>Data e orario di inizio</b></label>
					<div class="col-sm-6">
						<!-- input per selezionare la data e orario di inizio del file -->
						<div class="input-group date" id="datetimepicker1" data-target-input="nearest">
							<input type="text" name="start"  class="form-control datetimepicker-input" data-target="#datetimepicker1" required/>
							<div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
								<div class="input-group-text" style="height: 38px"><i class="fa fa-calendar"></i></div>
							</div>
							<!-- messaggio quando il valore del campo è valido -->
							<div class="valid-feedback">Valido.</div>
							<!-- messaggio quando il valore del campo non è valido -->
							<div class="invalid-feedback">Per favore compila questo campo.</div>
						</div>
					</div>
				</div>
				<br>
				<div class="form-group row">
					<label for="description"><b>Data e orario di fine</b></label>
					<div class="col-sm-6">
						<!-- input per selezionare la data e orario di fine del file -->
						<div class="input-group date" id="datetimepicker2" data-target-input="nearest">
							<input type="text" name="end" class="form-control datetimepicker-input" data-target="#datetimepicker2" required/>
							<div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
								<div class="input-group-text" style="height: 38px"><i class="fa fa-calendar"></i></div>
							</div>
							<!-- messaggio quando il valore del campo è valido -->
							<div class="valid-feedback">Valido.</div>
							<!-- messaggio quando il valore del campo non è valido -->
							<div class="invalid-feedback">Per favore compila questo campo.</div>
						</div>
					</div>
				</div>
				<!-- Errore durante la compilazione del campo -->
				<?php if(isset($_SESSION['errorDate'])):?>
					<br>
					<div class="text-danger"><?php echo $_SESSION['errorDate']; ?></div>
				<?php endif; ?>
				<!-- Errore durante la compilazione del campo (errore di sovrapposizione di informazioni) -->
				<?php if(isset($_SESSION['overlap'])):?>
					<br>
					<div class="text-danger"><?php echo $_SESSION['overlap']; ?></div>
				<?php endif; ?>
				<br>
				<!-- Input che contiene un valore booleano se il file corrente è sovrapposto in base alla data ad un altro file -->
				<input type="hidden" id="overlap" name="overlap"/>
				<button type="submit" name="add" class="btn btn-primary">Inserisci</button>
				<a class="btn btn-secondary" href="<?php echo URL ?>file/exitAddViewsFile">Esci</a>
			</form>
		</div>
	<?php endif; ?>
</div>
<!-- JavaScript che controlla che sono validi i valori inseriti negli input del form -->
<script type="text/javascript" src="<?php  echo URL;?>application/sources/script/checkInput.js"></script>
<!-- JavaScript che permette di creare i date-time picker -->
<script type="text/javascript" src="<?php  echo URL;?>application/sources/script/datetimepicker.js"></script>