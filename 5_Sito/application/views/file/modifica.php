<div class="container mb-3 mt-3">
	<h2 class="d-flex justify-content-center">Modifica data del filmato/presentazione</h2>
	<div class="d-flex align-items-start py-3 justify-content-center mb-4"> 
		<!-- form per modificare le informazioni. La classe needs-validation controlla se gli input sono validi. -->
		<form action="<?php  echo URL?>file/addOrModifyDateEndStartFile/<?php echo $id;?>" class="needs-validation" method="post" novalidate>
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
			<!-- Input che contiene il valore della vecchia data e orario di inizio visualizzazione del file -->
			<input  type="hidden" id="oldDateStart" value="<?php echo $row['data_inizio']; ?>">
			<!-- Input che contiene il valore della vecchia data e orario di fine visualizzazione del file -->
			<input  type="hidden" id="oldDateEnd" value="<?php echo $row['data_fine']; ?>">
			<!-- Input che contiene un valore booleano se il file corrente è sovrapposto in base alla data ad un altro file -->
			<input type="hidden" id="overlap" name="overlap"/>
			<button type="submit" name="modify" class="btn btn-primary">Modifica</button>
			<a class="btn btn-secondary" href="<?php echo URL ?>file/exitAddViewsFile">Esci</a>
		</form>
		<br>
	</div>
</div>
<!-- JavaScript che controlla che sono validi i valori inseriti negli input del form -->
<script type="text/javascript" src="<?php  echo URL;?>application/sources/script/checkInput.js"></script>
<!-- JavaScript che permette di creare i date-time picker -->
<script type="text/javascript" src="<?php  echo URL;?>application/sources/script/datetimepicker.js"></script>