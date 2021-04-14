<?php
/**
 *  @file       system\inc\functions\core\resources.inc.php
 *  
 *  @brief      Set di funzioni per l'incorporamento delle risorse
 *  
 *  @version    5.6.0
 *  @date       dyrr/dyrr/dyrr
 *  
 *  @author     Davide 'Dyrr' Grandi
 *  
 *  @details    Il file contiene il set di per l'incorporamento delle risorse come funzioni, moduli ecc 
 *  
 *  @since      5.6.0
 *  
 *  @todo passare la descrizione del namespace dal file al file specifico con la definizione dei vari namespace della 
 *  documentaizone.
 */     
    
    /**
     *  @namespace  resources
     *  @brief      <b>Risorse per l'incorporamento</b>
     *  
     *  @details    all'intenro di questo namespace si potranno trovare tutto quel set di risorse: classi, funzioni, 
     *  ecc, per l'incorporamento.<br />
     */ 
    namespace resources {
        
        /**
         *  @brief Genera il path per la risorsa richiesta
         *  
         *  @param [in] $file <b>(string)</b> nome del file
         *  @param [in] $path <b>(string)</b> path di riferimento
         *  @return <b>(type)</b> il path completo della risorsa
         *  
         *  @details La funzione genera il path completo di una risorsa in modo darenderne più facile l'inclusione. 
         *  La funzione nasce come funzione <i>"interna"</i> da reciclare all'interno di funzioni più specifiche per il 
         *  tipo di risorse richieste.
         *  @par Esempio
         *  @code{php}
                $file = \resources\file(
                    'core/db/mysqli',
                    '/system/inc/functions/'
                );
                
                //Ritornerà come valore di $file: root/system/inc/functions/core/db/mysqli.inc.php
         *  @endcode 
         *  
         *  @see functions::file
         *  @see modulo::file 
         *  
         *  @since 5.6.0
         */ 
        function file(
            $file, 
            $path
        ) {
            
            //compone e formatta il percorso completo per il file
            $file = ROOT . $path . $file . '.inc.php';
            $file = str_replace('\\','/',$file);
                
            //restituisce il percorso completo del file
            return $file;
            
        }       
        
        /**
         *  @brief Cerica la risorsa completa
         *  
         *  @param [in] $file <b>(string)</b> nome del file
         *  @param [in] $path <b>(string)</b> path di riferimento
         *  
         *  @details La funzione genera carica la risorsa includendola nel file dove la si richiede la dunzione utilizza 
         *  require_once() in maniera da assicurarsi che . 
         *  La funzione nasce come funzione <i>"interna"</i> da reciclare all'interno di funzioni più specifiche per il 
         *  tipo di risorse richieste.
         *  @par Esempio
         *  @code{php}
                \resources\load(
                    'core/db/mysqli',
                    '/system/inc/functions/'
                );
                
                //includerà il file: root/system/inc/functions/core/db/mysqli.inc.php
         *  @endcode 
         *  
         *  @see functions::load
         *  @see modulo::load 
         *  @see https://www.php.net/manual/en/function.require-once.php
         *  
         *  @since 5.6.0
         */         
        function load(
            $file,
            $path
        ) {
            
            //genera il percorso per il file richiesto
            $file = \resources\file(
                $file,
                $path
            );
            
            //SE ESISTE IL FILE
            if(file_exists($file)) {            
            
                //include la risorsa
                require_once($file);
            
            //SE NON ESISTE IL FILE         
            } else {
                
                //mostra un messaggio di errore
                echo "La risorsa " . $file . ' non esiste.';
                
            }
            
        }


    
    }
    
    /**
     *  @namespace  functions
     *  @brief      <b>Risorse per le funzioni</b>
     */     
    namespace functions {

         /**
         * \defgroup resources_path costanti del set di funzioni per il caricamento delle funzioni
         * @{
         */  
        
        /**
         *  @brief  Percorso base delle funzioni
         */     
        define('FUNCTIONS_BASE_PATH','/system/inc/functions/');
        /**@}*/        
		
		/**
         *  @brief Genera il path per il set di funzioni richiesto
         *  
         *  @param [in] $file <b>(string)</b> nome del set di funzioni da caricare
         *  @return <b>(type)</b> il path completo della risorsa
         *  
         *  @details La funzione genera il path completo di una risorsa in modo darenderne più facile l'inclusione. 
         *  La funzione nasce come funzione <i>"interna"</i> da reciclare all'interno di funzioni più specifiche per il 
         *  tipo di risorse richieste.
         *  @par Esempio
         *  @code{php}
                $file = \functions\file(
                    'core/db/mysqli'
                );
                
                //Ritornerà come valore di $file: root/system/inc/functions/core/db/mysqli.inc.php
         *  @endcode 
         *  
         *  @see functions::file
         *  
         *  @since 5.6.0
         */         
		function file($file) {
            
			$file = \resources\file(
                $file, 
                FUNCTIONS_BASE_PATH
            );
                
            //Ritorna il percorso completo del file
			return $file;
            
        }
        
        /**
         *  @brief Cerica il set di funzioni
         *  
         *  @param [in] $file <b>(string)</b> il nome del set di funzioni
         *  
         *  @details La funzione genera carica la risorsa includendola nel file dove la si richiede la dunzione utilizza 
         *  require_once() in maniera da assicurarsi che . 
         *  La funzione nasce come funzione <i>"interna"</i> da reciclare all'interno di funzioni più specifiche per il 
         *  tipo di risorse richieste.
         *  @par Esempio
         *  @code{php}
                \functions\load(
                    'core/db/mysqli',
                );
                
                //includerà il file: root/system/inc/functions/core/db/mysqli.inc.php
         *  @endcode 
         *  
         *  @see resource::load
         *  
         *  @since 5.6.0
         */          
		function load($file) {
            
            $file = \resources\load(
                $file, 
                FUNCTIONS_BASE_PATH
            );
            
        }       
        
    
    }    
    
    /**
     *  @namespace  modulo
     *  @brief      <b>Risorse per i vari moduli della land</b>
     */      
	namespace modulo {

         /**
         * \defgroup resources_path costanti del set di funzioni per il caricamento delle funzioni
         * @{
         */  
        
        /**
         *  @brief  Percorso base del modulo
         */     
        define('MODULO_BASE_PATH','/pages/');
        
        /**
         *  @brief  Percorso del modulo per il sistema di gioco del modulo
         */     
        define('MODULO_GE_PATH','/system/pages/game_engine/' . $GLOBALS['PARAMETERS']['game_engine']['engine'] . '/');		
		
        /**
         *  @brief  Percorso del modulo specifico per la land
         */     
        define('MODULO_LAND_PATH','/system/pages/land/');		
		/**@}*/         
		

		/**
		 *  @brief formattazione del percorso
		 *  
		 *  @param [in] $path <b>(string)</b> Il percorso da formattare
		 *  @return <b>(string)</b> Il percorso formattato
		 *  
		 *  @details La funzione formatta il percorso in maniera che sia valido sia su sistemi lunix che windows e 
		 *  trasforma il __ in separatore di directory per rendere il richiamo dei moduli nei link senza usare caratteri 
		 *  speciali.<br />
		 *  @par Esempio
		 *  @code{php}
				$path = \modulo\pathFormat('root/system/inc/functions\core/db__mysqli.inc.php');
				
				//Ritornerà: root/system/inc/functions/core/db/mysqli.inc.php		 
		 *  @endcode 
		 *  
		 *  @since 5.6.0
		 */		
		function pathFormat($path) {
			
            $path = str_replace('\\','/',$path);
            //converte la combinaizone di caratteri __ nel separatore di directory
            $path = str_replace('__','/',$path);			
			
			return $path;
		}		
		
		/**
		 *  @brief Genera il percorso del modulo
		 *  
		 *  @param [in] $file <b>(string)</b> il nome del modulo
		 *  @return <b>(string)</b> Return description
		 *  
		 *  @details La funzione genera il percorso da cui prelevare il modulo per includerlo cercandolo in sequenza di 
		 *  priorità prima da:
		 *  	- Il percorso in cui sono salvati i moduli customizzati per la land;
		 *  	- Il percorso dove sono salvati i moduli specifici per il sistema di gioco; 
		 *  	- Il percorso dei moduli di default.
		 *  	- Nel caso non fosse presente nesusno di questi moduli caricando un modulo generico per le operaizoni in 
		 *  	caso di errore
		 *  In questo modo sarà possibile creare o modificare dei moduli per la land o per il sistema di gioco senza 
		 *  dover toccare i moduli originari semplicemente creando il modulo sostitutivo nella cartella specifica.
		 *  @par Esempio
		 *  @code{php}
				$modulo = \modulo\file('scheda/oggetti/);
		   
				//Andrà a cercare il modulo in ordine nelle cartelle:
				//Nella cartella specifica per la land    /system/pages/land/scheda/oggetti.inc.php
				//Nella cartella per il sistema di gioco  /system/pages/game_engine/interlock/scheda/oggetti.inc.php
				//Nella cartella del modulo generico      /pages/scheda/oggetti.inc.php
				
		 *  @endcode 
		 *  
		 *  @since 5.6.0
		 */		
		function file($file)
        {

            //genera il percorso per il modulo di default
			$modulo_default = \modulo\pathFormat(
				ROOT . MODULO_BASE_PATH . $file . '.inc.php'
			);

            //genera il percorso per il modulo del sistema di gioco
			$modulo_ge = \modulo\pathFormat(
				ROOT . MODULO_GE_PATH . $file . '.inc.php'
			);     
            
            //genera il percorso per il modulo customizzato della land           
			$modulo_land = \modulo\pathFormat(
				ROOT . MODULO_LAND_PATH . $file . '.inc.php'
			);           
            
            //imposta come modulo il modulo specifico per la land
            $modulo = $modulo_land;
            
            //SE NON ESISTE IL MODULO SPEFICICO PER LA LAND
            if(file_exists($modulo) === false) {            
            
                //imposta come modulo specifico quello per l'engine di gioco
                $modulo = $modulo_ge;          
            
                //SE NON ESISTE IL MODULO SPECIFICO PER L'ENGINE DI GIOCO
                if(file_exists($modulo) === false) {
                    
                    //imposta come modulo quello generico
                    $modulo = $modulo_default;   

                    //SE NON ESISTE IL MODULO GENERICO
                    if(file_exists($modulo) === false) {
                        
                        //imposta il modulo di errore
                        $modulo = ROOT . MODULO_BASE_PATH . 'error/non_trovato.inc.php';
                    
                    }                    
                    
                }
            
            }
            
            return $modulo;
        
        }
        
		/**
		 *  @brief Carica il modulo richiesto
		 *  
		 *  @param [in] $file <b>(string)</b> Il nome del modulo da caricare
		 *  @param [in] $input <b>(array)</b> L'array con i parametri per il modulo
		 *  
		 *  @details La funzione carica il modulo richiesto isolando le variabili utilizzate nella pagina del modulo 
		 *  come variabili locali interne alla funzione. La funzione inoltre permette di passare dei parametri al 
		 *  modulo.
		 *  @par Esempio
		 *  @code{php}
				\modulo\file('scheda/oggetti/);
		 *  @endcode 
		 *  
		 *  @see modulo::file
		 *  
		 *  @since 5.6.0
		 */        
		function load(
            $file, 
            $input = null
        ) {
            
            //recupera come variabili locali le variabili globali con i parametri di configurazione e il vocabolario
            $MESSAGE = $GLOBALS['MESSAGE'];
            $PARAMETERS = $GLOBALS['PARAMETERS'];            
			//genera il percorso del modulo
            $path = \modulo\file($file);
            
            //carica il modulo
			include($path);

        }       
        
    }
