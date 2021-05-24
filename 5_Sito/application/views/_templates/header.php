<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="description" content="Pagina web di gestione informazioni di sede">
		<meta name="keywords" content="HTML,CSS,PHP,JavaScript, AJAX">
		<meta name="author" content="Pierpaolo Casati">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<title>Informazioni sede</title>
				
		<!-- Libreria bootstrap  -->
		<link rel="stylesheet" href="<?php echo URL; ?>application/sources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- Libreria dropzone -->
		<link href="<?php echo URL; ?>application/sources/dropzone/dropzone.css" rel="stylesheet">
		<!-- Libreria font-awesome -->
		<link rel="stylesheet" href="<?php echo URL; ?>application/sources/fontawesome/css/all.min.css">
		<!-- Libreria font-awesome  v4.1.0 -->
		<link rel="stylesheet" href="<?php echo URL; ?>application/sources/fontawesome-4.1.0/css/font-awesome.min.css" >
		<!-- Stile tabelle -->
		<link href="<?php echo URL; ?>application/sources/css/table.css" rel="stylesheet">
		<!-- Stile main  -->
		<link rel="stylesheet" href="<?php echo URL; ?>application/sources/css/main.css" rel="stylesheet">
		<!-- Libreria tempus dominus -->
		<link rel="stylesheet" href="<?php echo URL; ?>application/sources/tempus-dominus/css/tempusdominus-bootstrap-4.min.css">
		<!-- Libreria DataTables Bootstrap -->
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap5.min.css"/>
	

		<!-- JavaScript bootstrap  -->
		<script type="text/javascript" src="<?php echo URL; ?>application/sources/bootstrap/js/bootstrap.min.js"></script>
		<!-- JavaScript dropzone -->
		<script  type="text/javascript" src="<?php echo URL; ?>application/sources/dropzone/dropzone.js"></script>
		<!-- Libreria JQuery -->
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<!-- JavaScript tempus dominus language -->
		<script type="text/javascript" src="https://momentjs.com/downloads/moment-with-locales.js"></script>
		<!-- JavaScript tempus dominus -->
		<script type="text/javascript" src="<?php echo URL; ?>application/sources/tempus-dominus/js/tempusdominus-bootstrap-4.min.js"></script>		
		<!-- JavaScript DataTables -->	
		<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
		<!-- JavaScript Bootstrap DataTables -->		
		<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap5.min.js"></script>
		<!-- Libreria pdfjs -->		
		<script type="text/javascript" src="<?php echo URL; ?>application/sources/pdfjs/pdf.js"></script>
	</head>
	<?php 
		//url attuale, cioè l'url cambia in base dove mi trovo nel sito
		$host =(isset($_SERVER['HTTPS'])&& $_SERVER['HTTPS'] === 'on' ? "https://" : "http://").$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	?>
	<?php if(strpos($host, "schermo")): ?>
	<body onload="startTime();startWeather();">
	<?php else: ?>
	<body>
	<?php endif; ?>
		<!-- navbar -->
		<?php  
		
		$background = "";
		$color = "";
		if(strpos($host, "schermo")){
			$background = "bg-light";
			$color = "text-dark";
		}
		else{
			$background = "bg-primary";
			$color = "text-white";
		}
		?>
		<nav class="navbar navbar-expand-sm navbar-dark <?php echo $background; ?> sticky-top">
			<!-- contenuto navbar -->
			<div class="container-fluid">
				<!-- logo navbar (solo nella views schermo) -->
				<?php if(strpos($host, "schermo")): ?>
				<a class="navbar-brand" style="padding-left:50px; padding-top:20px" href="#">
					<img width="200px" height="40px" src="<?php echo URL;?>application/sources/img/logo_TI.png">
					<img width="150px" height="100px" class="mx-5" src="<?php echo URL;?>application/sources/img/logo.png" >
				</a>
				<?php endif; ?>
				<!-- icona del menu hamburger (modalità mobile) -->
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<?php 
				//i link della navbar vengono solamente visualizzati nella pagina gestione informazioni e filmati/presentazioni
				if(!strpos($host, "informazione/") && !strpos($host, "file/") && !strpos($host, "schermo") ): 
				?>
				<!-- link delle pagine -->
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav me-auto mb-2 mb-lg-0">
						<li class="nav-item">
							<a class="nav-link active" href="<?php echo URL_LOGIN; ?>home/index">Gestione CPT</a>
						</li>
						<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="<?php  echo URL;?>">Gestione informazione</a>
						</li>
						<li class="nav-item">
							<a class="nav-link active" href="<?php echo URL; ?>file">Gestione filmati/presentazioni</a>
						</li>
						<li class="nav-item">
							<a class="nav-link active" href="<?php echo URL_LOGIN; ?>home/logout">Esci</a>
						</li>
					</ul>
				</div>
				<?php endif; ?>
				<!-- orario e data (solo nella views schermo) -->
				<?php if(strpos($host, "schermo")): ?>
				<div class="navbar-collapse collapse justify-content-center order-2" id="collapsingNavbar">
					<ul class="navbar-nav ms-auto mb-2 mb-lg-0">
						<li class="nav-item">
							<strong><div  class="nav-link active text-dark" style="font-size: 30px; margin-right: 50px;" id="txt"></div></strong>
						</li>
					</ul>
				</div>
				<div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
					<ul class="navbar-nav">
						<li class="nav-item">
							<div style="font-size: 50px;margin-left:200px;" class="nav-link active text-dark">Informazioni di sede</div>
						</li>
					</ul>
				</div>
				<?php endif; ?>
			</div>
		</nav>
		<!-- JavaScript che permette di creare l'orologio -->
		<script type="text/javascript" src="<?php  echo URL;?>application/sources/script/clock.js"></script>