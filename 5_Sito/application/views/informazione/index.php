<div class="container mt-3 mb-5">
	<h2>Gestione informazione</h2>
	<a class="btn btn-success mt-5 mb-3" href="<?php echo URL;?>informazione/getViewsAddInformation">AGGIUNGI INFORMAZIONE</a>
	<!-- La seguente tabella permette di visualizzare tutte le informazioni che sono state inserite -->
	<table id="visualizza" class="responsive-table mt-3 table table-striped table-hover">
		<thead>
			<tr>
				<th scope="col">Titolo</th>
				<th scope="col-md-6 col-xs-6">Descrizione</th>
				<th scope="col-md-5 col-xs-5">Data inizio</th>
				<th scope="col-md-4 col-xs-4">Data fine</th>
				<th scope="col-md-3 col-xs-3">Modifica</th>
				<th scope="col-md-2 col-xs-2">Elimina</th>
			</tr>
		</thread>
		<tbody>
			<?php while($row = $informazioni->fetch(PDO::FETCH_ASSOC)): ?>
			<?php $url = URL ."informazione/deleteInformazione/". $row['id']; ?>
				<tr>
					<td scope="row"><?php echo $row['titolo']; ?></td>
					<td scope="row"><?php echo $row['descrizione']; ?></td>
					<td scope="row"><?php echo date('d-m-Y H:i', strtotime($row['data_inizio'])); ?></td>
					<td scope="row"><?php echo date('d-m-Y H:i', strtotime($row['data_fine'])); ?></td>
					<!-- pulsante per modificare l'informazione -->
					<td><a class="btn btn-info text-white d-flex justify-content-center" href="<?php echo URL ?>informazione/getViewsModifiy/<?php echo $row['id'];  ?>">Modifica</a></td>
					<!-- pulsante per eliminare l'informazione -->
					<td><a class='btn btn-danger text-white d-flex justify-content-center' data-information="<?php echo "Sei sicuro di eliminare l'informazione <b>{$row['titolo']}</b>?"; ?>" data-url="<?php echo $url; ?>" data-bs-toggle="modal" data-bs-target="#deleteModal">Elimina<a></td>
				</tr>
			<?php endwhile; ?>	
		</tbody>
	</table>
</div>
<!-- Modal per confermare l'eliminazione di un informazione -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<!-- il popup si troverà al centro della pagina -->
	<div class="modal-dialog modal-dialog-centered">
		<!-- contenuto del popup -->
		<div class="modal-content">
			<!-- intestazione del popup -->
			<div class="modal-header btn-danger text-white">
				<h5 class="modal-title" id="exampleModalLabel">Conferma</h5>
				<!-- icona x per chiudere il popup -->
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<!-- corpo del popup -->
			<div class="modal-body">
				<!-- messaggio popup -->
				<p id="message"></p>
			</div>
			<!-- piè di pagina popup -->
			<div class="modal-footer">
				<!-- pulsante per chiudere il popup -->
				<button type="button" class="btn border-danger text-danger"  data-bs-dismiss="modal">Annulla</button>
				<!-- pulsante per confermare l'eliminazione -->
				<a class='btn btn-danger' id="confirm">Elimina</a>
			</div>
		</div>
	</div>
</div>
<!-- JavaScript che permette di creare i popup -->
<script type="text/javascript" src="<?php  echo URL;?>application/sources/script/popup.js"></script>
<!-- JavaScript che permette dei filtri sulle tabelle -->
<script type="text/javascript" src="<?php  echo URL;?>application/sources/script/table.js"></script>