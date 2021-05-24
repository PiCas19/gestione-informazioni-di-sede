<?php
/**
 * La classe File_Model è il model che mi 
 * permette di eseguire delle query sulla tabella Filmato_Presentazione.
 * 
 * @author Pierpaolo Casati
 * @version 20.02.2021
 */
class File_Model {
	
	public function __construct(){
	
	}
	
	
	
	/*
	 * Permette di effettuare il reset degli id, 
	 * in modo che non ci siano dei "buchi" di numerazione.
	 * @parma conn Connessione al database.
	 */ 
	public function resetIdInformazione($conn){
		$sth = $conn->prepare("set @num := 0");
		$sth->execute();
		$sth = $conn->prepare("update Filmato_Presentazione set id = @num := (@num+1)");
		$sth->execute();
		$sth = $conn->prepare("alter table Filmato_Presentazione auto_increment = 1");
		$sth->execute();
	}
	
	
	/**
	 * Permette di ricavare tutte le informazioni dalla tabella Filmato_Presentazione.
	 * @param conn Connessione al database.
	 * @return Statement della query eseguita.
	 */
	public function getFile($conn){
		//preparo lo statement che mi ricava tutte le informazioni 
		//dalla tabella Filmato_Presentazione.
		$sth = $conn->prepare("select * from Filmato_Presentazione");
		$sth->execute();
		return $sth; 
	}
	
	/**
	 * Permette di ricavare i dati di una determinato file.
	 * @param conn Connessione al database.
	 * @param id Identificativo di un file.
	 * @return Una sola record della query.
	 */
	public function getFileById($conn, $id){
		//preparo lo statement che mi ricava tutti i dati di una determinata informazione 			
		$sth = $conn->prepare("select * from Filmato_Presentazione where id = :id");
		$sth->bindParam(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		//voglio solo 1 record
		$result = $sth->fetch(PDO::FETCH_ASSOC);
		return $result;
	}
	
	
	/**
	 * Permette di inserire una nuova record nella tabella Filmato_Presentazione.
	 * @param conn Connessione al database.
	 * @param name Nome del file con l'estensione.
	 * @param start Data e ora di inizio.
	 * @param end Data e ora di fine.
	 */
	public function insertFile($conn, $name, $start, $end){
		
		//Preparo lo statement che permette di inserire una nuova record nella tabella Filmato_Presentazione
		$sth = $conn->prepare('insert into Filmato_Presentazione (nome, data_inizio, data_fine) values (:nome, :data_inizio, :data_fine)');
		//Inserisco i dati
		$sth->bindParam(':nome', $name, PDO::PARAM_STR);
		$sth->bindParam(':data_inizio', $start, PDO::PARAM_STR);
		$sth->bindParam(':data_fine', $end, PDO::PARAM_STR);
		$sth->execute();
	}
	
	/**
	 * Permette di modificare una determinata record della tabella Filmato_Presentazione.
	 * @param conn Connessione al database.
	 * @param id Identificativo del filmato.
	 * @param start Data e ora di inizio.
	 * @param end Data e ora di fine.
	 */
	public function modifyFile($conn, $id, $start, $end){
		//Preparo la statement che mi permette di aggiornare una determinata record della tabella Filmato_Presentazione
		$sth = $conn->prepare('update Filmato_Presentazione set data_inizio = :data_inizio, data_fine = :data_fine where id = :id');
		//Inserisco i dati
		$sth->bindParam(':id', $id, PDO::PARAM_INT);
		$sth->bindParam(':data_inizio', $start, PDO::PARAM_STR);
		$sth->bindParam(':data_fine', $end, PDO::PARAM_STR);
		$sth->execute();
	}
	
	
	
	/**
	 * Permette di eliminare una determinata record dalla tabella Filmato_Presentazione.
	 * @param conn Connessione al database.
	 * @param id Identificativo del filmato.
	 */
	public function deleteFileById($conn, $id){
		//Preparo lo statement che permette di eliminare
		//una determinata record dalla tabella Filmato_Presentazione
		$sth = $conn->prepare('delete from Filmato_Presentazione where id = :id');
		//Inserisco i dati
		$sth->bindParam(':id', $id, PDO::PARAM_INT);
		$sth->execute();
	}
	
	
	/**
	 * Permette di ricavare il nome di una determinata record dalla tabella Filmato_Presentazione.
	 * @param conn Connessione al database.
	 * @param id Identificativo del filmato.
	 * @return Una sola record della query.
	 */
	public function getFileNameById($conn, $id){
		//preparo lo statement che mi ricava tutti i dati di una determinata informazione 			
		$sth = $conn->prepare("select nome from Filmato_Presentazione where id = :id");
		$sth->bindParam(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		//voglio solo 1 record
		$result = $sth->fetch(PDO::FETCH_ASSOC);
		return $result;
	}
	

	/**
	 * Permette di ricavare i dati di un file filtrando in base alla data.
	 * @param conn Connessione al database.
	 * @param date Data attuale.
	 * @return Statement della query eseguita.
	 */
	public function getFileFilterByDate($conn, $date){
		//preparo lo statement che mi ricava tutte le informazioni 
		$sth = $conn->prepare("select * from Filmato_Presentazione where data_inizio >= :data_inizio or data_fine >= :data_fine limit 1");
		$sth->bindParam(':data_inizio', $date, PDO::PARAM_STR);
		$sth->bindParam(':data_fine', $date, PDO::PARAM_STR);
		$sth->execute();
		return $sth; 
	}
	
	
	/**
	 * Permette di ricavare i dati di un file filtrando 
	 * in base alla data di inizio.
	 * @param conn Connessione al database.
	 * @param startDate Data di inizio.
	 * @return Se esiste già un file nel intervallo di tempo = true.
	 */
	public function getFileFilterByDateStartEnd($conn, $startDate, $endDate){
		//preparo lo statement che mi ricava tutte le informazioni filtrando per la data di inizio e di fine
		$sth = $conn->prepare('select * from Filmato_Presentazione where data_inizio between :data_inizio and :data_fine or
		data_fine between :data_inizio and :data_fine or data_inizio < :data_inizio and data_fine > :data_fine');
		$sth->bindParam(':data_inizio', $startDate, PDO::PARAM_STR);
		$sth->bindParam(':data_fine', $endDate, PDO::PARAM_STR);
		$sth->execute();
		//voglio solo 1 record
		$result = $sth->fetch(PDO::FETCH_ASSOC);
		return $result;	
	}
	

	
	
}
	