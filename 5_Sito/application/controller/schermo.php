<?php

/**
 * La classe Schermo è il controller principale della pagina Schermo, 
 * cioè la pagina dove vengono visualizzate le varie informazioni e filmati/presentazioni.
 * 
 * @author Pierpaolo Casati
 * @version 24.02.2021
 */
class Schermo
{
	/**
     * Array delle informazioni.
     */
	private $arrayInformazione;
    
    /**
     * Array che contiene i files.
     */
	private $arrayFile;
    
	
	/**
	* Permette di creare l'index della pagina Informazione.
	*/
	public function index()
	{		
		require_once "./application/views/_templates/header.php";
		require_once "./application/views/schermo/index.php";
		require_once "./application/views/_templates/footer.php";
	}
	
	/**
	 * Premette di preparare i filmati o presentazioni da rendere visibili.
	 * @return Array di filmati/presentazioni da rendere visibile.
	 */
	private function createArrayVideoPresentation(){
		require_once './application/models/database_model.php';
		require_once "./application/models/file_model.php";
		
		//creo una connessione
		$conn = Database_Model::getConnection();
		$file =  new File_Model();
			
		//data attuale
		$now = Date('Y-m-d H:i:s');
		
		//ricavo tutti i dati dei file filtrando in base alla data.
		$files = $file->getFileFilterByDate($conn, $now);
		
		//array che contiene tutti file
		$fileArray = array();
		
		
		while($row = $files->fetch(PDO::FETCH_ASSOC)){	
			if($now > $row['data_inizio'] && $now < $row['data_fine']){
				array_push(
					$fileArray, 
					[
						"id" => $row['id'],
						"nome" => $row['nome'],
						"video_presentazione" => true,
						"isVideo" => (substr($row['nome'], -4) == ".mp4"?true:false)
					]
				);
			}
		}
		
		return $fileArray;
		
	}
	
	/**
	 * Permette di preparare le informazioni da rendere visibili.
	 * @return Array di informazioni da rendere visibile.
	 */
	private function createArrayInformation(){
		require_once './application/models/database_model.php';
		require_once "./application/models/informazione_model.php";
	
		//creo una connessione
		$conn = Database_Model::getConnection();
		$informazione =  new Informazione_Model();
		
		//data attuale
		$now = Date('Y-m-d H:i:s');
		
		//ricavo tutti i dati delle informazioni filtrando in base alla data.
		$informazioni = $informazione->getInformazioniFilterByDate($conn, $now);
		
		//array che contiene tutte le informazioni
		$info = array();
		
	
		while($row = $informazioni->fetch(PDO::FETCH_ASSOC)){	
			if($now > $row['data_inizio'] && $now < $row['data_fine']){
				array_push(
					$info, 
					[
						"id" => $row['id'],
						"titolo" => $row['titolo'],
						"descrizione" => $row['descrizione'],
						"video_presentazione" => false,
						"coloreTesto" => $row['coloreTesto']
					]
				);
			}
		}
		return $info;	
	}
	
	/**
	 * Permette di stampare sullo schermo (nella parte centrale) le informazioni o i filmati/presentazioni.
	 * Questo metodo viene richiamato da un metodo AJAX ogni 500 millisecondi.
	 */
	public function getViewsSchermo(){
		$this->arrayInformazione = $this->createArrayInformation();		
		$this->arrayFile = $this->createArrayVideoPresentation();	
		$content = "";
		//Se ci sono dei testi da rendere visibili 
		if($this->arrayInformazione){
			$content = $this->arrayInformazione;
		}
		else{
			if($this->arrayFile){
				$content = $this->arrayFile;
			}
		}
		echo json_encode($content);	
	}
    
    /**
     * Permette di stampare sullo schermo (nel riquadro in basso a destra) solamente i filmati/presentazioni.
     * Questo metodo viene richiamato da un metodo AJAX ogni 500 millisecondi.
     */
    public function getViewsOnlyVideoPresentation(){
        $this->arrayInformazione = $this->createArrayInformation();		
        $this->arrayFile = $this->createArrayVideoPresentation();	
        $content = "";
        //Se ci sono dei testi da rendere visibili e dei video/filmati
        if($this->arrayInformazione && $this->arrayFile){
            $content = $this->arrayFile;
        }
        echo json_encode($content);	
    }
	
	/**
	 * Permette di verificare la data di inizio e di fine selezionata 
	 * dall'utente in modo da non creare una sovrapposizione.
	 */
	public function verifyDate(){
		require_once './application/models/database_model.php';
		require_once "./application/models/informazione_model.php";
		require_once "./application/models/file_model.php";
		
		$result = $startDate = $endDate =  "";
			
		$informazione =  new Informazione_Model();
		$file = new File_Model();
		//creo una connessione
		$conn = Database_Model::getConnection();
		
		//premette di sapere se è stato attivato l'opzione giornata intera
		$disableEndDate = $_POST['disableEndDate'];
		
		if($disableEndDate == 0){
			$startDate = date_format(date_create($_POST["data_inizio"]), "Y-m-d H:i:s");
			$endDate = date_format(date_create($_POST["data_fine"]), "Y-m-d H:i:s");
		}
		else{
			//data di inizio selezionata dall'utente
			$startDate = DateTime::createFromFormat("d.m.y H:i", $_POST["data_inizio"])->format("Y-m-d H:i:s");
			//data di fine selezionata dall'utente
			$endDate = DateTime::createFromFormat("d.m.y H:i", $_POST["data_fine"])->format("Y-m-d H:i:s");
		}
		//permette di sapere se ci troviamo nella views informazione
		$isViewsInformation =  $_POST['isInformation'];
		
		//identificativo del informazione o del file
		$id = $_POST['id'];
		
		//Se mi trovo nella views informazione controllo le date della tabella 
		//informazione altrimenti controllo le data della tabella Filmato_Presentazione
		if($isViewsInformation == 1){
			//risultato della query che verifica se esiste un informazione con la possibile data di inizio e di fine
			$result = $informazione->getInformazioniFilterByDateStartEnd($conn, $startDate, $endDate);
		}
		else {
			//risultato della query che verifica se esiste un file con la possibile data di inizio e di fine
			$result = $file->getFileFilterByDateStartEnd($conn, $startDate, $endDate);
		}
		if($result && $result['id'] != $id){
			echo 1;
		}
		else{
			echo 0;
		}
		

	}
	
	
}