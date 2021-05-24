$(document).ready(function() {
	//permette di impostare dei filtri sulla tabella che ha come identificativo "visualizza" 
	$('#visualizza').DataTable({
		"language": {
			"url": "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Italian.json"
		}
	});
	//permette di impostare dei filtri sulla tabella che ha come identificativo "file" 
	$('#file').DataTable({
		"language": {
			"url": "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Italian.json"
		}
	});
} );