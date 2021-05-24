<?php


/**
 * La classe File è il controller principale della pagina File, 
 * cioè la pagina dove l'utente può gestire i 
 * filmati o presentazioni da rendere visibili.
 * 
 * @author Pierpaolo Casati
 * @version 16.02.2021
 */
class File
{
	
	/**
	* Permette di creare l'index della pagina File.
	*/
	public function index()
	{
		session_start();
		if(isset($_SESSION['email'])){
			require_once './application/models/database_model.php';
			require_once "./application/models/file_model.php";
		
			//creo una connessione
			$conn = Database_Model::getConnection();
			$file_model =  new File_Model();
			
			
			//permette di fare il reset degli id della tabella Filmato_Presentazione
			$file_model->resetIdInformazione($conn);
			
			//permette di ricavare le informazioni presenti nella tabella Filmato_Presentazione
			$file = $file_model->getFile($conn);
			
			
			require_once "./application/views/_templates/header.php";
			require_once "./application/views/file/index.php";
			require_once "./application/views/_templates/footer.php";
		}
		else{
			header("Location: ".URL_LOGIN);
			exit;
		}
	}
	
	/**
	 * Permette di visualizzare la pagina dove 
	 * l'utente può caricare un file.
	 */
	public function getViewsAddFile(){
		session_start();
		if(isset($_SESSION['email'])){
			require_once "./application/views/_templates/header.php";
			require_once "./application/views/file/aggiungi.php";
			require_once "./application/views/_templates/footer.php";
		}
		else{
			header("Location: ".URL_LOGIN);
			exit;
		}
	}
	
	/**
	 * Permette di caricare il file.
	 */
	public function upload(){
		
		//faccio partire le sessioni
		session_start();
		//percorso dove sono presenti i filmati e le presentazioni
		$path_dir = "./application/sources/filmati-presentazioni/";  
		if (!empty($_FILES)) {
			//file temporaneo appena caricato nel dropzone
			$temp_file = $_FILES['file']['tmp_name'];  
			//nome del file
			$file_name = $_FILES['file']['name']; 
			//percorso del file 
			$path = $path_dir . $file_name[0]; 
			//memorizzo il percorso del file
			$_SESSION["path"] = $path;
			
			move_uploaded_file($temp_file[0],  $path);
			
		}
	}
	
	/**
	 * Permette di accettare il file da rendere visibile.
	 */
	public function acceptedFile(){
		session_start();
		if(isset($_SESSION['email'])){
			
			//se clicco il pulsante Carica
			if(isset($_POST['accepted'])){
				//carica nella cartella il file
				$this->getViewsAddFile();  
			}
			
			//se clicco il pulsante Elimina
			if(isset($_POST['remove'])){
				if(isset($_SESSION["path"])){
					//elimina file
					$this->deleteFile($_SESSION["path"]); 
					//distruggo la sessione path  
					unset($_SESSION["path"]);
					header("Location: ".URL."file/getViewsAddFile");  
				}
				else{
					//carica il file
					header("Location: ".URL."file");   
				}
			}
		}
		else{
			header("Location: ".URL_LOGIN);
			exit;
		}
	}
		
	
	/**
	 * Permette di impostare o modificare la data di inizio e di fine 
	 * per la visualizzazione del file.
	 * @param id Identificativo del file, valore di default null
	 */
	public function addOrModifyDateEndStartFile($id = null){
		require_once "./application/models/database_model.php";
		require_once "./application/models/file_model.php";
		
		//faccio partire le sessioni
		session_start();
		
		if(isset($_SESSION['email'])){
		
		
			$isErrorName = $isErrorDate = false;
			
			$overlap = $nome = $start = $end =  "";
			
			if($_SERVER["REQUEST_METHOD"] == "POST") {
				
				if(isset($_POST['overlap'])){
					$overlap = $this->test_input($_POST['overlap']);
					if($overlap == 1){   
						$_SESSION['overlap'] = "Esiste già un file nel periodo di tempo selezionato.";
					}
				}
				
				//l'utente deve caricare il file
				if(isset($_SESSION['path'])) {
					$nome = $this->test_input( substr($_SESSION['path'], 44));
				}
				else{
				    if(is_null($id)){
						$isErrorName = true;  
				    }
				}
				
						
				
				//se l'utente deve inserire la data di inizio e di fine
				if(isset($_POST["start"]) && isset($_POST["end"])){
					
					$start = $this->test_input($_POST["start"]);
					$end = $this->test_input($_POST["end"]);
				}
				else{
					//se l'utente ha solo inserito la data di inizio
					//vuole dire che è stato attivato l'opzione giornata intera.
					if(isset($_POST["start"])){
						$start = $this->test_input($_POST["start"]);
						//data e orario di fine
						$end = date_format(date_create($start), "d.m.Y 23:59");
					}
					else{
						$_SESSION['errorDate'] = "Per favore compila questo campo";
					}
				}	
				
				//verifico che l'utente abbia attivato o disabilitato l'opzione giornata intera
				if(isset($_POST['giornata_intera'])){
					$giornata_intera = $this->test_input($_POST['giornata_intera']);
				}
				else{
					$giornata_intera = "off";
				}			
				
				//se non ho impostato la giornata intera
				if($giornata_intera != "on"){
					//ricavo le date in base al formato del date-time picker
					//nel database il formato della data deve essere Y-m-d H:i:s
					$startDate = DateTime::createFromFormat("d.m.y H:i", $start)->format("Y-m-d H:i:s");
					$endDate = DateTime::createFromFormat("d.m.y H:i", $end)->format("Y-m-d H:i:s");
					
					$origin =  new DateTime($startDate);
					$target = new DateTime($endDate);
					
					//la data di fine deve essere successiva alla data di inizio
					if($origin  > $target){
						$isErrorDate = true;
						$_SESSION['errorDate'] = "La data di fine deve essere successiva alla data di inizio";
					}
				}
				else{
					//ricavo le date in base al formato del date-time picker
					//nel database il formato della data deve essere Y-m-d H:i:s
					$startDate = date_format(date_create($start), "Y-m-d 00:00:00");
					$endDate = date_format(date_create($end), "Y-m-d 23:59:00"); 	
				}
				
				
				if(!$isErrorName && !$isErrorDate && $overlap == 0){
					//eseguo una connessione al database gestione_informazione
					$conn = Database_Model::getConnection();
					$file =  new File_Model();
					if(is_null($id)){
						//inserisco una nuova record nella tabella Filmato_Presentazione
						$file->insertFile($conn, $nome, $startDate , $endDate);
						//distruggo la sessione path
						unset($_SESSION['path']);
					}
					else{
						
						//modifico una determinata record della tabella Filmato_Presentazione
						$file->modifyFile($conn,$id, $startDate , $endDate);
					}
					header("Location: ".URL."file");
				}
				else{
					if(is_null($id)){
						$this->getViewsAddFile();
					}
					else{
						$this->getViewsModifiy($id);
					}
					
				}		
			}
		}
		else{
			header("Location: ".URL_LOGIN);
			exit;
		}
	}
	
	
	/**
	 * Permette di tornare nella views gestione filmati/presentazioni
	 */
	public function exitAddViewsFile(){
		//faccio partire le sessioni
		session_start();
		//elimino il file 
		$this->deleteFile($_SESSION["path"]);
		//distruggo la sessione path
		unset($_SESSION["path"]);
		//distruggo le sessioni di errore
		unset($_SESSION['overlap']);
		unset($_SESSION['errorDate']);
		header("Location: ".URL."file");  
	}
	
	/**
	 * Permette di eliminare un determinato file dalla 
	 * tabella della views gestione filmato/presentazione.
	 * @param id Identificativo del file.
	 */
	public function removeFile($id){
		
		require_once './application/models/database_model.php';
		require_once "./application/models/file_model.php";
		
		//creo una connessione
		$conn = Database_Model::getConnection();
		$file =  new File_Model();
		
		$row = $file->getFileNameById($conn, $id);
				
		$path = "./application/sources/filmati-presentazioni/" . $row['nome'];
		$this->deleteFile($path);
		
		//permette di eliminare una determinata record della tabella Filmato_Presentazione
		$file->deleteFileById($conn, $id);
		
		header("Location: ".URL."file");
		
	}
	
	/**
	 * Permette di visualizzare la views modifica dove 
	 * l'utente può modificare la data di inizio e fine di un filmato.
	 * @param id Identificativo del file.
	 */
	public function getViewsModifiy($id){
		session_start();
		if(isset($_SESSION['email'])){
			require_once './application/models/database_model.php';
			require_once "./application/models/file_model.php";
		
			//creo una connessione
			$conn = Database_Model::getConnection();
			$file =  new File_Model();
			
			//dati di un determinato file
			$row = $file->getFileById($conn, $id);
			require_once "./application/views/_templates/header.php";
			require_once "./application/views/file/modifica.php";
			require_once "./application/views/_templates/footer.php";
		}
		else{
			header("Location: ".URL_LOGIN);
			exit;
		}
	}
	
	
	/**
	 * Permette di eliminare un determinato file di una specifica cartella
	 * @param ext Nome del file con il suo formato.
	 */
	private function deleteFile($ext){
		
		//nome del file
		$fileExt = substr($ext, 44);
		
		//Percorso directory root
		$rootDirectory = "./application/sources/";
		//cambio directory
		chdir($rootDirectory);
		
		//directory dove sono presenti filmati e presentazioni
		$dirpath = "filmati-presentazioni";
		$files = glob($dirpath . '/*');
		
		foreach ($files as $file) {
			if(strpos($file, $fileExt) != 0){
				//elimino file
				unlink($file);
			}   
		}
		
	}
	
	   
	/**        
	* Permette di controllare il valore inserito nei input.
	* @param data Valore inserito nel input da controllare.
	*/ 
	private function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	
	
}