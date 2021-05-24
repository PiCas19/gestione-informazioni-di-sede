# Script per la creazione del database.
# @version 05.02.2021
# @author Matthias Iannarella
# @author Marco Lorusso
# @author Pierpaolo Casati

# Elimina il database se esiste
drop database if exists gestionecpt;

# Crea il database
create database gestionecpt;

# Utilizza il database appena creato
USE gestionecpt;

############# CREAZIONE TABELLE #############

# Tabella utente, serve a gestire gli accessi al sito.
DROP TABLE IF EXISTS Utente;
CREATE TABLE Utente(
	id INT AUTO_INCREMENT NOT NULL,
	email VARCHAR(50) UNIQUE NOT NULL,
	nome VARCHAR(50) NOT NULL,
	cognome VARCHAR(50) NOT NULL,
    passwd VARCHAR(255) NOT NULL,
    tipo enum('amministratore','responsabile','limitato') NOT NULL,
	ultimo_accesso DATE NOT NULL,
	PRIMARY KEY (id)
);

################ Marco ################

# Tabella evento, serve a gestire tutti gli eventi scolastici.
DROP TABLE IF EXISTS Evento;
CREATE TABLE Evento(
	id int AUTO_INCREMENT,
    nome VARCHAR(30) NOT NULL,
    descrizione VARCHAR(50),
    data_inizio datetime,
    data_fine datetime,
    giornata_intera boolean,
    aula VARCHAR(20) NOT NULL,
    osservazione TEXT,
	PRIMARY KEY (id)
);

################ Pierpaolo ################

# Tabella informazioni, serve a gestire tutte le informazioni della sede.
DROP TABLE IF EXISTS Informazione;
CREATE TABLE Informazione ( 
	id int NOT NULL AUTO_INCREMENT,
	titolo VARCHAR(30) NOT NULL,
	data_inizio datetime,
	data_fine datetime,
	giornata_intera boolean,
	descrizione TEXT NOT NULL,
	coloreTesto CHAR(10), 
	PRIMARY KEY (id)
);

# Tabella Filmato_Presentazione, serve a gestire tutti i filmati o presentazioni della sede.
DROP TABLE IF EXISTS Filmato_Presentazione;
CREATE TABLE Filmato_Presentazione (
	id int NOT NULL AUTO_INCREMENT,
	nome VARCHAR(256) NOT NULL,
	data_inizio datetime,
	data_fine datetime,
	PRIMARY KEY (id)
)


################ Matthias ################

# Tabella Categoria
CREATE TABLE Categoria(
	id INT AUTO_INCREMENT NOT NULL,
	nome VARCHAR(30) UNIQUE NOT NULL,
	colore VARCHAR(30) NOT NULL,
	archiviato boolean NOT NULL DEFAULT FALSE,
	PRIMARY KEY (id)
);

# Tabella Documento
CREATE TABLE Documento(
	id INT AUTO_INCREMENT NOT NULL,
	nome VARCHAR(30) NOT NULL,
	data_aggiunta DATE NOT NULL,
	archiviato boolean NOT NULL DEFAULT FALSE,
	percorso MEDIUMBLOB NOT NULL,
	ultima_modifica DATE NOT NULL,
	grandezza DOUBLE NOT NULL,
	visualizzazioni INT NOT NULL,
	id_categoria INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (id_categoria) REFERENCES Categoria(id)
);

# Tabella Iscrizione
CREATE TABLE Iscrizione(
	id INT AUTO_INCREMENT NOT NULL,
	nome VARCHAR(50) NOT NULL,
	cognome VARCHAR(50) NOT NULL,
	email VARCHAR(50) NOT NULL,
	id_documento INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (id_documento) REFERENCES Documento(id)
);

# Tabella Statistica
CREATE TABLE Statistica(
	id INT AUTO_INCREMENT NOT NULL,
    data_inizio DATE NOT NULL,
	data_termine DATE NOT NULL,
	data_creazione DATE NOT NULL,
	archiviato boolean NOT NULL DEFAULT FALSE,
    id_documento INT NOT NULL,
    PRIMARY KEY (id),
	FOREIGN KEY (id_documento) REFERENCES Documento(id)
);

# Tabella Log
CREATE TABLE Log(
	id INT AUTO_INCREMENT NOT NULL,
	azione VARCHAR(50) NOT NULL,
	data DATE NOT NULL,
	orario DATE NOT NULL,
	archiviato boolean NOT NULL DEFAULT FALSE,
	id_utente INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (id_utente) REFERENCES Utente(id)
);
                   