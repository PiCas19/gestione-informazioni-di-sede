<?php


/**
 * La classe Informazione è il controller principale della pagina Informazione, 
 * cioè la pagina dove l'utente può gestire le informazioni di sede.
 * 
 * @author Pierpaolo Casati
 * @version 10.02.2021
 */
class Informazione
{
    
    
    /**
    * Permette di creare l'index della pagina Informazione.
    */
    public function index()
    {
        
        session_start();
        //se sono imposte le sessioni degli errori
        if(isset($_SESSION['errorDescription']) 
            || isset($_SESSION['errorName']) 
            || isset($_SESSION['errorDate'])
            || isset($_SESSION['overlap'])){
            //distruggo le sessioni degli errori
            unset($_SESSION['errorDescription']);
            unset($_SESSION['errorName']);
            unset($_SESSION['errorDate']);
            unset($_SESSION['overlap']);
        }
        
        if(isset($_SESSION['email'])){
            require_once './application/models/database_model.php';
            require_once "./application/models/informazione_model.php";
        
            //creo una connessione
            $conn = Database_Model::getConnection();
            $informazione =  new Informazione_Model();
            
            //permette di fare il reset degli id della tabella Informazione
            $informazione->resetIdInformazione($conn);
            
            //permette di ricavare le informazioni presenti nella tabella Informazione
            $informazioni = $informazione->getInformazioni($conn);
                    
            require_once "./application/views/_templates/header.php";
            require_once "./application/views/informazione/index.php";
            require_once "./application/views/_templates/footer.php";
        }
        else{
            header("Location: ".URL_LOGIN);
            exit;
        }
    }
    
    /**
    * Permette di eliminare una determinata informazione.
    * @param id Identificativo dell'informazione.
    */
    public function deleteInformazione($id){
        require_once './application/models/database_model.php';
        require_once "./application/models/informazione_model.php";
        
        //creo una connessione
        $conn = Database_Model::getConnection();
        $informazione =  new Informazione_Model();
        
        //permette di eliminare una determinata record della tabella Informazione
        $informazione->deleteInformazioneById($conn, $id);
        
        header("Location: ".URL);
    }
    
    /**
     * Permette di aggiungere o di modificare un informazione.
     * @param id Identificativo dell'informazione, default valore nullo.
     */
    public function addOrModifyInformation($id = null){
        
        session_start();
        if(isset($_SESSION['email'])){
            require_once "./application/models/database_model.php";
            require_once "./application/models/informazione_model.php";
            
            $title = $description = $start = $end = $color = "";
            
            $startDate  = $endDate = "";
            
            $giornata_intera = "";
            
            $overlap = $isErrorName = $isErrorDate = $isErrorDescription = false;
            
            
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                
                
                if(isset($_POST['overlap'])){
                    $overlap = $this->test_input($_POST['overlap']);
                    if($overlap == 1){    
                        $_SESSION['overlap'] = "Esiste già un informazione nel periodo di tempo selezionato.";
                    }
                }
                
                //titolo dell'informazione deve essere obbligatorio
                if(isset($_POST["title"])) {
                    $title = $this->test_input($_POST["title"]);
                }
                else{
                   $isErrorName = true;
                   $_SESSION['errorName'] = "Per favore compila questo campo";
                }
                                
                // se viene inserito una descrizione
                if(isset($_POST["description"])) {
                    if(strlen($_POST["description"]) > 0 && strlen($_POST["description"]) <= 121){
                        $description = $this->test_input($_POST["description"]);    
                    }
                    else{
                        $isErrorDescription = true;
                        $_SESSION['errorDescription'] = "La descrizione deve avere al massimo 121 e al minimo 1 carattere";
                    }
                }
                else{
                    $isErrorDescription = true;
                    $_SESSION['errorDescription'] = "Per favore compila questo campo";
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
                        $isErrorDate = true;
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
                 
                
                //verifico che l'utente abbia selezionato il colore del testo
                if(isset($_POST['colorTextInformation'])){
                    $color = $_POST['colorTextInformation'];
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
      
                if(!$isErrorName && !$isErrorDate && !$isErrorDescription && $overlap == 0){
                    //eseguo una connessione al database gestione_informazione
                    $conn = Database_Model::getConnection();
                    $informazione =  new Informazione_Model();
                    //se l'utente si trova nella views aggiungi
                    if(is_null($id)){
                        //aggiungi informazione
                        $informazione->insertInformazione($conn, $title,  $startDate , $endDate, $description, $color); 
                   
                    }
                    else{    
                        //modifica l'informazione
                       $informazione->modifyInformazione($conn, $id, $title, $startDate , $endDate, $description, $color);   
                    }   
                    header("Location: ".URL);
                }
                else{
                    //se l'utente si trova nella views aggiungi
                    if(is_null($id)){
                        //ritorna nella views aggiungi
                        $this->getViewsAddInformation();
                    }
                    else{
                        //ritorna nella views modifica
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
     * Permette di visualizzare la views modifica dove 
     * l'utente può modificare una determinata informazione.
     * @param id Identificativo dell'informazione.
     */
    public function getViewsModifiy($id){
        session_start();
        if(isset($_SESSION['email'])){
            require_once './application/models/database_model.php';
            require_once "./application/models/informazione_model.php";
        
            //creo una connessione
            $conn = Database_Model::getConnection();
            $informazione =  new Informazione_Model();
            
            //dati di una determinata informazione
            $row = $informazione->getInformazioniById($conn, $id);
            
        
            require_once "./application/views/_templates/header.php";
            require_once "./application/views/informazione/modifica.php";
            require_once "./application/views/_templates/footer.php";
        }
        else{
            header("Location: http://samtinfo.ch/disp_event/gestione_eventi/");
            exit;
        }
    }
    
    
    /**
     * Permette di visualizzare la pagina dove 
     * l'utente può aggiungere un informazione.
     */
    public function getViewsAddInformation(){
        session_start();
        if(isset($_SESSION['email'])){
            require_once "./application/views/_templates/header.php";
            require_once "./application/views/informazione/aggiungi.php";
            require_once "./application/views/_templates/footer.php";
        }
        else{
            header("Location: ".URL_LOGIN);
            exit;
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
