<?php
	/**
	 * La classe Informazione_Model è il model che mi 
	 * permette di eseguire delle query sulla tabella Informazione
	 * @author Pierpaolo Casati
	 * @version 26.01.2021
	 */
	class Informazione_Model {
		
		public function __construct(){
		
		}
		
		/**
		 * Permette di inserire una nuova record nella tabella Informazione.
		 * @param conn Connessione al database.
		 * @param titolo Titolo dell'informazione.
		 * @param start Data e ora di inizio.
		 * @param end Data e ora di fine.
		 * @param descrizione Descrizione dell'informazione.
		 * @param color Colore del testo.
		 */
		public function insertInformazione($conn, $titolo, $start, $end, $descrizione, $color){
			
			//Preparo lo statement che permette di inserire una nuova informazione
			$sth = $conn->prepare('insert into Informazione (titolo, data_inizio, data_fine, descrizione, coloreTesto) values (:titolo, :data_inizio, :data_fine, :descrizione, :coloreTesto)');
			//Inserisco i dati
			$sth->bindParam(':titolo', $titolo, PDO::PARAM_STR);
			$sth->bindParam(':data_inizio', $start, PDO::PARAM_STR);
			$sth->bindParam(':data_fine', $end, PDO::PARAM_STR);
			$sth->bindParam(':descrizione', $descrizione, PDO::PARAM_STR);
			$sth->bindParam(':coloreTesto', $color, PDO::PARAM_LOB);
			$sth->execute();
		}
		
		/**
		 * Permette di modificare una determinata informazione.
		 * @param conn Connessione al database.
		 * @param id Identificativo dell'informazione.
		 * @param titolo Titolo dell'informazione.
		 * @param start Data e ora di inizio.
		 * @param end Data e ora di fine.
		 * @param descrizione Descrizione dell'informazione.
		 * @param color Colore del testo.
		 */
		public function modifyInformazione($conn, $id, $titolo, $start, $end, $descrizione, $color){
			//Preparo la statement che mi permette di aggiornare una determinata informazione
			$sth = $conn->prepare('update Informazione set titolo = :titolo, descrizione = :descrizione, data_inizio = :data_inizio, data_fine = :data_fine, coloreTesto = :coloreTesto where id = :id');
			//Inserisco i dati
			$sth->bindParam(':id', $id, PDO::PARAM_INT);
			$sth->bindParam(':titolo', $titolo, PDO::PARAM_STR);
			$sth->bindParam(':data_inizio', $start, PDO::PARAM_STR);
			$sth->bindParam(':data_fine', $end, PDO::PARAM_STR);
			$sth->bindParam(':descrizione', $descrizione, PDO::PARAM_STR);
			$sth->bindParam(':coloreTesto', $color, PDO::PARAM_STR);
			$sth->execute();
		}
		

		/**
		 * Permette di eliminare una determinata informazione.
		 * @param conn Connessione al database.
		 * @param id Identificativo dell'informazione.
		 */
		public function deleteInformazioneById($conn, $id){
			//Preparo lo statement che permette di eliminare una determinata informazione
			$sth = $conn->prepare('delete from Informazione where id = :id');
			//Inserisco i dati
			$sth->bindParam(':id', $id, PDO::PARAM_INT);
			$sth->execute();
		}
		
		
		/*
		 * Permette di effettuare il reset degli id, 
		 * in modo che non ci siano dei "buchi" di numerazione.
		 * @parma conn Connessione al database.
		 */ 
		public function resetIdInformazione($conn){
			$sth = $conn->prepare("set @num := 0");
			$sth->execute();
			$sth = $conn->prepare("update Informazione set id = @num := (@num+1)");
			$sth->execute();
			$sth = $conn->prepare("alter table Informazione auto_increment = 1");
			$sth->execute();
		}
		
		
		/**
		 * Permette di ricavare tutte le informazioni dalla tabella Informazione.
		 * @param conn Connessione al database.
		 * @return Statement della query eseguita.
		 */
		public function getInformazioni($conn){
			//preparo lo statement che mi ricava tutte le informazioni 
			$sth = $conn->prepare("select * from Informazione");
			$sth->execute();
			return $sth; 
		}
		
		/**
		 * Permette di ricavare i dati di una determinata informazione.
		 * @param conn Connessione al database.
		 * @param id Identificativo dell'informazione.
		 * @return Una sola record della query.
		 */
		public function getInformazioniById($conn, $id){
			//preparo lo statement che mi ricava tutti i dati di una determinata informazione 			
			$sth = $conn->prepare("select * from Informazione where id = :id");
			$sth->bindParam(':id', $id, PDO::PARAM_INT);
			$sth->execute();
			//voglio solo 1 record
			$result = $sth->fetch(PDO::FETCH_ASSOC);
			return $result;
		}
		
		/**
		 * Permette di ricavare i dati di un informazione filtrando in base alla data.
		 * @param conn Connessione al database.
		 * @param date Data attuale.
		 * @return Statement della query eseguita.
		 */
		public function getInformazioniFilterByDate($conn, $date){
			//preparo lo statement che mi ricava tutte le informazioni 
			$sth = $conn->prepare("select * from Informazione where data_inizio >= :data_inizio or data_fine >= :data_fine limit 1");
			$sth->bindParam(':data_inizio', $date, PDO::PARAM_STR);
			$sth->bindParam(':data_fine', $date, PDO::PARAM_STR);
			$sth->execute();
			return $sth; 
		}
		
		/**
		 * Permette di ricavare i dati di un informazione filtrando 
		 * in base alla data di inizio e di fine.
		 * @param conn Connessione al database.
		 * @param startDate Data di inizio.
		 * @param endDate Data di fine.
		 * @return Se esiste già un informazione nel intervallo di tempo = true.
		 */
		public function getInformazioniFilterByDateStartEnd($conn, $startDate, $endDate){
			//preparo lo statement che mi ricava tutte le informazioni filtrando per la data di inizio e di fine
			$sth = $conn->prepare('select * from Informazione where data_inizio between :data_inizio and :data_fine or
			data_fine between :data_inizio and :data_fine or data_inizio < :data_inizio and data_fine > :data_fine');
			$sth->bindParam(':data_inizio', $startDate, PDO::PARAM_STR);
			$sth->bindParam(':data_fine', $endDate, PDO::PARAM_STR);
			$sth->execute();
			//voglio solo 1 record
			$result = $sth->fetch(PDO::FETCH_ASSOC);
			return $result;	
		}
				
		
	}

?>