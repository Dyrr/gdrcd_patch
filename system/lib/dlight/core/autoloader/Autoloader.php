<?php
/**
 *  @file       system\lib\dlight\core\autoloader\Autoloader.php
 *  
 *  @brief      Classe per l'autoload delle classi e tratti
 *  
 *  @version 1.0.0
 *  @date    dyrr/dyrr/dyrr
 *  
 *  @author  Davide 'Dyrr' Grandi
 */
    
    /**
     *  @namespace  dlight::core::autoloader
     *  @brief      <b>Risorse per l'autoloading</b>
     */     
    namespace dlight\core\autoloader;
    
    /** 
     *  @class      dlight::core::autoloader::Autoloader
     *  
     *  @author     Davide 'Dyrr' Grandi
     *  
     *  @version    1.0.1
     *  @date       14/01/2016
     *  
     *  @brief      Classe per l'autoload delle risorse (class,interfac,trait)
     *  
     *  @details    La classe esegue l'autoload della risorsa, class,interface o trait, richiesto nel momento in cui la 
     *  risorsa è richiesta e non è presente.
     *  
     *  @snippet system/inc/common.php autoloader_example
     *
     *  @todo Inserire la descrizione dettagliata dei vari metodi della classe
     */
    class Autoloader
    {
        
        /* @brief Attributo per l'elenco dei vendor che possono utilizzare questa classe per l'autoloading */
        protected $vendor = array('dlight');
        
        /* @brief Attributo per l'elenco dei vendor che possono utilizzare questa classe per l'autoloading */
        protected $prefix = array();        
        
        /* @brief elenco delle directory base in cui cercare le classi */
        protected $base_path = array();
        
        /* @brief Attributo con l'elenco delle classi caricate da questo autoloadre */
        protected $loaded = array();
        
        
        /**
         *  @brief          Costruttore della classe
         *  
         *  @prepend [in]   $class <b>(bool)</b> true registra la funzione dell'autoloader all'inizio della coda delle 
         *  funzioni di autoloading, false registra la funzione alla fine della coda.
         *  
         *  @details        Il costruttore registra la funzione di autoloading self::Loader per utilizzarla per 
         *  l'autoloading delle risorse<br />
         *  Il metodo controlla la versione di php in esecuzione per scegliere quale funzione usare per registrare 
         *  l'autoload e che parametri usare eventualmente.
         */         
        public function __construct($prepend=false)
        {
            
            //SE LA VERSIONE DI PHP USATA È MINORE DELLA 5.1.2
            if (version_compare(PHP_VERSION, '5.1.2') < 0) {
                
                //registra l'autoloading con la funzione __autoload()
                __autoload(array($this, 'Loader'));

            //SE LA VERSIONE DI PHP USATA È MINORE DELLA 5.3.0          
            } elseif (version_compare(PHP_VERSION, '5.3.0') < 0) {
            
                //registra l'autoloading con la funzione spl_autoload_register()
                spl_autoload_register(array($this, 'Loader'),false);
            
            //SE LA VERSIONE DI PHP USATA È MAGGIORE O UGUALE ALLA 5.3.0            
            } else {
                
                //valida il parametro per decidere se aggiungere l'autoload all'inizio o alla fine dell'elenco degli autoloader
                $prepend = ($prepend === false) ? false : true;
                
                //registra l'autoloading con la funzione spl_autoload_register()                
                spl_autoload_register(array($this, 'Loader'),false,$prepend);               
            
            }

        }
    
        /**
         *  @brief                  Metodo per l'autoload di classi, interfacce e tratti
         *  
         *  @param [in] $class      <b>(string)</b> nome della classe completo di namespace
         */     
        public function Loader($class)
        {
            
            //SE LA CLASSE NON È GIA STATA DEFINITA
            if (!class_exists($class)) {    
                
                $resource = array();
                //trasforma in un array la richiesta di nuovo oggetto (es: new \classes\gdrcd\system\security\Session();)
                $namespace = explode('\\',$class);
                
                //nome qualificato del namespace (es: dyrr\system\security)
                $resource['fullName'] = implode('/',$namespace);                    
                
                //nome del vendor/creatore (es: dyrr)
                $resource['vendor'] = $namespace[0];                
                
                //nome della classe (es: security)
                $resource['shortName'] = end($namespace);               
                
                //SE IL VENDOR HA UNA CARTELLA BASE DIVERSA DAL SUO NOME STESSO INDICATA NEI PREFISSI
                if(array_key_exists($namespace[0],$this->prefix) === true) {
                    
                    //esegue la sostituzione
                    $namespace[0] = $prefix[$namespace[0]];
                
                }
                
                //nome qualificato del namespace (es: dyrr\system\security)
                $resource['path'] = implode('/',$namespace);                
            
                //SE L'AUTORE È PRESENTE NELLA LISTA DEGLI AUTORI
                if(in_array($resource['vendor'],$this->vendor)) {
                
                    //imposta a false il controllo se la classe è gia stata caricata durante il ciclo
                    $loaded = false;
                    //definisce il valore iniziale dell'elenco dei path per il messaggio di errore.
                    $file_list = '';
                    
                    //CICLA L'ELENCO DEI PATH IN CUI CERCARE LA CLASSE
                    foreach($this->base_path as $value) {
                        
                        $file =  $value . $resource['fullName'] . '.php';
                        
                        //Se esiste il file
                        if(file_exists($file) && $loaded === false) {

                            //include la risorsa
                            require_once($file);
                            
                            //inserisce la risorsa in un elenco per verificare le risorse caricate.
                            $this->loaded[] = $resource;
                            
                            //registra che la classe è stata caricata.
                            $loaded = true;
                    
                        }

                        //aggiunge il path all'elenco dei path in cui è stata fatta la ricerca per l'eventuale errore
                        $file_list .= $file . ' / ';
                    
                    }
                    
                    //SE NON È STATA TROVATO IL FILE RICHIESTO IN NESUSNO DEI PATH
                    if($loaded === false) {
                        
                        //rimuove il ' / ' alla fine dell'elenco dei path
                        $file_list = substr($file_list,0,-3);
                        //Genera un errore di tipo utente.
                        $error = 'il file con la classe ' . $file_list . ' non esiste.';
                        trigger_error($error,E_USER_ERROR);
                        
                    }
                        
                }
                    
            }
    
        }

        /**
         *  @brief      Ritorna la lista delle risorse caricate da questo autoloader specifico
         *  
         *  @return     <b>(array)</b> un array contenente i dati delle risorse
         */     
        public function loadedComponents()
        {
            
            return $this->loaded;
            
        }
        
        /**
         *  @brief      Aggiunge un autore/vendor alla lista di quelli supportati per l'autoloading
         *  
         *  @param [in] $vendor <b>(string)</b> il nome dell'autore/vendor
         *  
         *  @details    Il metodo aggiunge all'array contenente la lista degli autori supportati da questo autoloader 
         *  cercando la classe se rileva l'autore/vendor come primo pezzo namespace della classe.
         */     
        public function addVendor($vendor)
        {
            
            if(in_array($vendor,$this->vendor) === false)
                $this->vendor[] = $vendor;
            
        }

        /**
         *  @brief      Aggiunge una cartella base diversa da quella del suo stesso nome ad un vendor
         *  
         *  @param [in] $vendor <b>(string)</b> il nome dell'autore/vendor
         *  
         *  @details    Il metodo aggiunge una cartella base diversa da quella con il suo stesso nome da un Vendor/Autore 
         *  utile per esempio per indicare un unico namespace per delle classi di uno stesso autore che non fanno uso di 
         *  un namespace
         */     
        public function addPrefix($name,$prefix)
        {
            
            if(array_key_exists($prefix,$this->prefix) === false)
                $this->prefix[$name] = $prefix;
            
        }       
        
        
        /**
         *  @brief      Aggiunge un percorso dove cercare in sequenza la risorsa da caricare
         *  
         *  @param [in] $path <b>(string)</b> il percorso aggiuntivo in cui cercare la risorsa
         *  @param [in] $prepend <b>(bool)</b> il  selettore se accorare o mettere in cima all'elenco il nuovo path
         *  
         *  @details    Il metodo aggiunge un percorso dove iniziare a cercare la risorsa selezionata. Il metodo è utile 
         *  per aggiungere un percorso alternativo dove usare le nuove versioni di una risorsa senza sovrascrivere quelle 
         *  gia esistenti.
         */         
        public function addPath($path,$prepend=false)
        {
            
            //SE IL PATH AGGIUNTO NON È IN ELENCO
            if(in_array($path,$this->base_path) === false) {
                
                //SE IL PATH DEVE ESSERE AGGIUNTO ALLA FINE DELLA CODA
                if($prepend === false) {
                
                    //aggiunge il path in coda all'array
                    array_push($this->base_path,$path);
                    
                //SE IL PATH DEVE ESSERE AGGIUNTO ALL'INIZIO DELLA CODA             
                } else {
                    
                    //aggiunge il path all'inizio all'array                 
                    array_unshift($this->base_path,$path);                  
                
                }
                
            }
            
        }       

    }