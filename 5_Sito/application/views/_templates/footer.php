		<br>
		<br>
		<!-- footer -->
		<footer class="<?php echo $background; ?> text-white text-center text-lg-start fixed-bottom">
			<!-- Contenitore a griglia  -->
			<div class="container p-2">
				<!-- riga della griglia -->
				<div class="row">
					<?php if(strpos($host, "schermo")): ?>
					<!-- inizio della prima colonna della griglia -->
					<div class="<?php echo $color; ?> col-lg-6 col-md-12 mb-md-0">
						<div class="row">
							<div class="col-auto mt-2">
								<!-- icona della meteo -->
								<img class="mt-2" id="wicon" src="" alt="Weather icon">
							</div>
							<div class="col mt-2">
								<div class="card-block px-2 mt-2">
									<!-- dati della meteo (nome città, descrizione e temperatura) -->
									<strong><p  style="font-size: 25px;"class="temp1 text-uppercase"></p></strong>
									<strong><p class="temp2" style="font-size: 20px;"></p></strong>
									<strong><p class="temp3" style="font-size: 20px;"></p></strong>
								</div>
							</div>
						</div>
					</div>
					<div class="<?php echo $color; ?> col-lg-6 col-md-6 mb-md-0">
						<div id="video" class="row">
						</div>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<!-- Copyright (solo nella views schermo) -->
			<?php if(strpos($host, "schermo")): ?>
			<div class="<?php echo $color;?> bg-light text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)">
                <p  style="font-size: 25px; padding-bottom: 15px;"class="float-center">Implementato da Pierpaolo Casati SAM I4AA (2020/2021)</p>
			</div>
			<?php else: ?>
			<!-- Copyright CPT -->
			<div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
				© 2021 Copyright:
				<a class="text-white" href="https://www.cpttrevano.ti.ch">CPT</a>
			</div>
			<?php endif; ?>
		</footer>
	</body>
</html>
<!-- JavaScript che permette di ricavare il tempo (meteo) -->
<script type="text/javascript" src="<?php  echo URL;?>application/sources/script/weather.js"></script>


