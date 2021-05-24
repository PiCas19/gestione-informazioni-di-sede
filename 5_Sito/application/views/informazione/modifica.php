<div class="container mb-3 mt-3">
	<h2 class="d-flex justify-content-center mb-4">Modifica informazione</h2>
	<div class="d-flex justify-content-center">
		<!-- form per modificare le informazioni. La classe needs-validation controlla se gli input sono validi. -->
		<form action="<?php  echo URL?>informazione/addOrModifyInformation/<?php echo $row['id'];?>" class="needs-validation" method="post" novalidate>
			<div class="form-group row">
				<label for="nameInformation"><b>Titolo informazione</b></label>
				<!-- input nome dell'informazione -->
				<div class="col-sm-6">
					<input type="text" class="form-control" id="nameInformation" placeholder="Titolo informazione"  value="<?php echo $row['titolo']; ?>" name="title" required>
					<!-- Errore durante la compilazione del campo -->
					<?php if(isset($_SESSION['errorName'])):?>
						<br>
						<div class="text-danger"><?php echo $_SESSION['errorName']; ?></div>
					<?php endif; ?>
					<!-- messaggio quando il valore del campo è valido -->
					<div class="valid-feedback">Valido.</div>
					<!-- messaggio quando il valore del campo non è valido -->
					<div class="invalid-feedback">Per favore compila questo campo.</div>
				</div>
			</div>
			<br>
			<div class="form-group row">
				<label for="description"><b>Descrizione informazione</b></label>
				<!-- input descrizione dell'informazione che corrisponde ad un text area -->
				<div class="col-sm-8">
					<textarea class="form-control" id="description" placeholder="Descrizione informazione" name="description" required><?php echo $row['descrizione']; ?></textarea>
					<!-- Errore durante la compilazione del campo -->
					<?php if(isset($_SESSION['errorDescription'])):?>
						<br>
						<div class="text-danger"><?php echo $_SESSION['errorDescription']; ?></div>
					<?php endif; ?>
					<!-- messaggio quando il valore del campo è valido -->
					<div class="valid-feedback">Valido.</div>
					<!-- messaggio quando il valore del campo non è valido -->
					<div class="invalid-feedback">Per favore compila questo campo.</div>
				</div>
			</div>
			<br>
			<div class="form-group row">
				<label for="color"><b>Colore testo</b></label>
				<!-- input per selezionare il colore del testo -->
				<div class="col-sm-4">
					<input type="color" id="colorTextInformation" class="form-control" name="colorTextInformation" value="<?php echo $row['coloreTesto']; ?>">
				</div>
			</div>
		   <br>
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
			<label id="oldDate"></label>
			<br>
			<br>
			<div class="form-group row">
				<label for="description"><b>Data e orario di inizio</b></label>
				<div class="col-sm-6">
					<!-- input per selezionare la data e orario di inizio dell'informazione -->
					<div class="input-group date" id="datetimepicker1" data-target-input="nearest">
						<input type="text" name="start"  class="form-control datetimepicker-input" data-target="#datetimepicker1"  required/>
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
					<!-- input per selezionare la data e orario di fine dell'informazione -->
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
			<!-- Input che contiene il valore della vecchia data e orario di inizio visualizzazione dell'informazione -->
			<input  type="hidden" id="oldDateStart" value="<?php echo $row['data_inizio']; ?>">
			<!-- Input che contiene il valore della vecchia data e orario di fine visualizzazione dell'informazione -->
			<input  type="hidden" id="oldDateEnd" value="<?php echo $row['data_fine']; ?>">
			<!-- Input che contiene un valore booleano se l'informazione corrente è sovrapposto in base alla data ad un altra informazione -->
			<input type="hidden" id="overlap" name="overlap"/>
			<button type="submit" name="modify" class="btn btn-primary">Modifica</button>
			<a class="btn btn-secondary" href="<?php echo URL ?>">Esci</a>
		</form>
		<br>
	</div>
</div>
<!-- JavaScript che controlla che sono validi i valori inseriti negli input del form -->
<script type="text/javascript" src="<?php  echo URL;?>application/sources/script/checkInput.js"></script>
<!-- JavaScript che permette di creare i date-time picker -->
<script type="text/javascript" src="<?php  echo URL;?>application/sources/script/datetimepicker.js"></script>