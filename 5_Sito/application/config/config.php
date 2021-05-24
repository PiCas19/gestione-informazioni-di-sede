<?php

/**
 * Configurazione
 * 
 * Per la configurazione ho utilizzato un template MVC che
 * mi è stato dato durante il modulo 133 (php).
 * 
 * Per ulteriori informazioni sulle costanti si prega di @see http://php.net/manual/en/function.define.php 
 * Si si vuole sapere perché uso "define" invece di "const" @see http://stackoverflow.com/q/2447791/1114320
 * 
 * @author Pierpaolo Casati
 * @version 20.01.2021
 */


/**
 * Configurazione di : Error reporting
 * Utile per vedere tutti i piccoli problemi in fase di sviluppo, in produzione solo quelli gravi
 */
error_reporting(E_ALL);
ini_set("display_errors", 0);

/**
 * Configurazione di : URL del progetto
 */
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$documentRoot = $_SERVER['DOCUMENT_ROOT'];
$dir = str_replace('\\','/',getcwd().'/');
$final = $actual_link.str_replace($documentRoot,'',$dir);
$url_login = $actual_link . "/disp_event/gestione_eventi/";
define('URL', $final);
define('URL_LOGIN', $url_login);


/**
 * Costanti per accedere al database gestione_informazione
 */

 define('DBNAME', 'display_info');
 define('DBUSER', 'efof_disp_info');
 define('DBPASS', 'DisplayInfo2021$');
 define('DBHOST', 'efof.myd.infomaniak.com');
 define('DBPORT', '3306');




