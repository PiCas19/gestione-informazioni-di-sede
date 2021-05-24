	//documento pdf
	var pdfDoc = null;
	//numero della pagina del pdf
	var pageNum = 1;
	//permette di fare il rendering della pagina 
	var pageRendering = false;
	//permette di aspettare la pagina successiva per eseguire il rendering
	var pageNumPending = null;
	//scala
	var scale = 0.8;
	//area in cui viene disegnato l'area
	var canvas =  null;
	//contesto grafico
	var ctx = null;
	//url del file pdf
	var url = null;
	//Caricato tramite il tag <script>, crea un collegamento 
	//per accedere alle esportazioni PDF.js.
	var pdfjsLib  = "";
	/**
	 * Permette di visualizzare il file pdf.
	 */
	function onLoadPdf(urlPdf, id, scalePdf = null){
		if(scalePdf != null){
			scale = scalePdf;
		}
		pdfjsLib = window['pdfjs-dist/build/pdf'];
		pdfjsLib.GlobalWorkerOptions.workerSrc = "./application/sources/pdfjs/pdf.worker.js";	  
		pageNum = 1;
		url = urlPdf;
		canvas = document.getElementById('the-canvas-'+id);
		ctx = canvas.getContext('2d');
		/**
		 * Scarica in modo asincrono PDF
		 */
		pdfjsLib.getDocument( url).promise.then(function(pdfDoc_) {
			pdfDoc = pdfDoc_;
			renderPage(pageNum);
		});
	}
	
	/**
	* Ottieni informazioni sulla pagina dal documento, 
	* ridimensiona la tela di conseguenza e visualizza la pagina.
	* @param num Numero della pagina.
	*/
	function renderPage(num) {
		 pageRendering = true;
		 pdfDoc.getPage(num).then(function(page) {
			var viewport = page.getViewport({scale:  scale});
			 canvas.height = viewport.height;
			 canvas.width = viewport.width;
	
			//Rendering della pagina PDF
			var renderContext = {
				canvasContext:  ctx,
				viewport: viewport
			};
			var renderTask = page.render(renderContext);
	
			//Aspettare che il rendering finisca
			renderTask.promise.then(function() {
				 pageRendering = false;
				if ( pageNumPending !== null) {
					//Il nuovo rendering della pagina è in sospeso
					renderPage( pageNumPending);
					 pageNumPending = null;
				}
			});
		});
	}
	
	
	
	/**
	 * Se è in corso il rendering di un'altra pagina, attende che il rendering sia terminato
	 * finito. Altrimenti, esegue immediatamente il rendering.
	 * @param num Numero della pagina
	 */
	function queueRenderPage(num) {
	  if (pageRendering) {
		pageNumPending = num;
	  } else {
		renderPage(num);
	  }
	}
	
	/**
	 * Mostra pagina successiva.
	 */
	function onNextPage() {
		if(pdfDoc != null){
		  if (pageNum >= pdfDoc.numPages) {
			pageNum = 0;
			return;
		  }
		  pageNum++;
		  queueRenderPage(pageNum);
	  	}
	}
	
	
	
	