<?php
/**
 *  @file       includes/template.inc.php
 *  
 *  @brief      Set di funzioni per la gestione dei template
 *  
 *  @version    5.6.0
 *  @date       dyrr/dyrr/dyrr
 *  
 *  @author     Davide 'Dyrr' Grandi
 *  
 *  @details    Set di piccole funzioni per l'utilizzo di un sistema di template base, separato dal file principale 
 *  delle funzioni per avere un po' più di ordine
 *  
 */     
    
    /**
     *  @namespace  template
     *  @brief      <b>Risorse per la gestione dei template</b>
     */     
    namespace template;

         /**
         * \defgroup resources_path costanti del set di funzioni per il caricamento dei templates
         * @{
         */  
        
        /**
         *  @brief  Percorso del tema comune
         */     
        define('TEMPLATE_BASE_PATH','/themes/_common/template/');
        
        /**
         *  @brief  Percorso del tema impostato
         */     
        define('TEMPLATE_THEME_PATH','/themes/' . $GLOBALS['PARAMETERS']['themes']['current_theme'] . '/template/');		
		/**@}*/     
    
	
	/**
     *  @brief Generazione del nome completo del template
     *  
     *  @param [in] $template <b>(string)</b> il nome del template da caricare
     *  
     *  @return <b>(string)</b> Il nome completo del template
     *  
     *  @details La funzione ritorna il nome completo del template da caricare dopo aver controllato la sua esistenza 
     *  prima nel tema scelto e poi nel tema comune. In questa maniera quando si crea un nuovo tema, basta creare solo 
     *  le pagine che differiscono dal tema comune.
     *  
	 *  @par Esempio
	 *  @code{php} 
			$template = '\template\file('sheda/index');
			require($template);
	 *  @endcode 
	 *  
	 *  @since 5.6.0
	 *  
	 *  @todo sistemare la gestione dell'errore in caso di mancanza di esistenza del template
     */ 
    function file($template)
    {
		//genera il nome completo del template per il tema corrente
        $theme_template = ROOT . TEMPLATE_THEME_PATH . $template . '.php';
        $theme_template = str_replace('\\','/',$theme_template);
        
        //genera il nome completo per il tmeplate comune
        $common_template = ROOT . TEMPLATE_BASE_PATH . $template . '.php';
        $common_template = str_replace('\\','/',$common_template);
		
		//assegna il nome del template da controllare
        $template = $theme_template;
        
        //SE IL TEMPLATE DEL TEMA NON ESISTE
        if(file_exists($template) === false) {
            
            //assegna il nuovo nome da controllare
            $template = $common_template;
            
            //SE IL TEMPLATE COMUNE NON ESISTE
            if(file_exists($template) === false) {

                echo $common_template . "\<br />\n";
                echo $theme_template . "\<br />\n"; 				
				//assegna il nome del template di errore
                $template = $common_template = ROOT . '/themes/_common/template/not_found.php';
                
            }
        
        }
        
        //ritorna il nome del template
        return $template;
    }

    /**
     *  @brief Inizio del buffer dell'output da salvare
     *  
     *  @param [in] $template <b>(str)</b> il nome del blocco
     *  
     *  @details Semplice funzione da mettere all'inizio del pezzo del template che si vuole salvare
     */    
    function start($template)
    {
    
        ob_start();
    
    }

    /**
     *  @brief Fine del buffer dell'output da salvare
     *  
     *  @param [in] $template <b>(str)</b> il nome del blocco
     *  
     *  @details Semplice funzione da mettere alla fine del pezzo del template che si vuole salvare
     */     
    function end($template)
    {
            
        $GLOBALS['OUT'][$template] = ob_get_clean();
            
    }
    
	function mode($mode='html')
	{
		
		$GLOBALS['TAG']['render']['mode'] = $mode;	
	
	}    
	
	function render($template,$mode='html') {
		
		//IMPOSTA LA MODALITÀ DI VISUALIZZAZIONE DELL'OUTPUT
		switch(strtoupper($mode)) {
			
			//output in formato json
			case 'JSON' :
			
				\template\renderJSON($GLOBALS['TAG']['page']);
				
			break;
			
			//output in formato XML
			case 'XML' :
			
			break;
			
			//output in caso di php semplice o di debug
			case 'PHP' :
			case 'DEBUG' :
			
				\template\renderJSON($template);
			
			break;
			
			//nessun output
			case 'NULL' :
			
			break;
			
			//altri tipi di output;
			case 'CSS' :
			
				\template\renderJSON($template);
				
			break;				
			
			//altri tipi di output;
			case 'OTHER' :
			
				\template\renderJSON($template);
				
			break;
			
			//normale output HTML
			default :
			
				\template\renderHTML($template);
				
			break;	
			
		}
		
	}
	
	
	/**
     *  @brief      stampa della struttura della pagina
     *  
     *  @details    La funzione stampa a video la struttura della pagina ricostruita riordinatamente in base al tipo 
     *  di richiesta 
     *       
     *  @snippet    system\lib\dlight\core\template\Template.php is_ajax_example
     */      
    function renderJSON($data) {
        
		header('Content-Type: application/json;charset=utf-8');	

		echo json_encode($data,true);
    
    }   

   /**
     *  @brief      stampa della struttura della pagina
     *  
     *  @details    La funzione stampa a video la struttura della pagina ricostruita riordinatamente in base al tipo 
     *  di richiesta 
     *       
     *  @snippet    system\lib\dlight\core\template\Template.php is_ajax_example
     */      
    function renderHTML($template) {
        
        $html = '';
		
		//SE LA PAGINA È STATA RICHIESTA TRAMITE CHIAMATA AJAX
        if(\template\is_ajax() === true) { 
        
            //stampa solo il contenuto del modulo
            $html .= isset($template['content'])       ? $template['content']       : null;          
        
        //SE LA PAGINA È STATA RICHIESTA NORMALMENTE
        } else {
            
            //stampa tutta la pagina HTML
			$html .= isset($template['header'])        ? $template['header']        : null;   
			$html .= isset($template['layout_top'])    ? $template['layout_top']    : null;   
			$html .= isset($template['content'])       ? $template['content']       : null;   
			$html .= isset($template['layout_bottom']) ? $template['layout_bottom'] : null;   
			$html .= isset($template['footer'])        ? $template['footer']        : null;   
        
        }

		return $html;
    
    }
    
    /**
     *  @brief      Identifica  la modalirà di richiesta della pagina
     *  
     *  @return     $ajax <b>(bool)</b> true se la pagine è stata richiesta tramite ajax false in caso contrario
     *  
     *  @details    Il metodo identifica se la pagina è stata richiesta tramite chiamata ajax o meno, restituendo 
     *  1 nel primo caso e 0 nel secondo.
     *  
     *  @snippet    system\lib\dlight\core\template\Template.php is_ajax_example
     */     
    function is_ajax()
    {
        
        $ajax = (   isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
                 && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') 
            ? true 
            : false;
        
        return $ajax;
    
    }

    /**
     *  @brief          Filtra automaticamente l'output
     *  
     *  @param [in]     $item <b>array</b> reference all'array contenente i dati dell'output
     *  
     *  @details        Il metodo passa tutti gli elementi dell'array $var attraverso la funzione htmlentities()
     *  per assicurarsi che tutti i dati passati per l'output del template siano sicuri.
     *  Le funzione è anche impostata come paramtri per evitare che le entità html non siano codificate più di una 
     *  volta.
    */      
    function __autoFilter(&$item, $key)
    {

        //SELEZIONA LE AZIONI DA ESEGUIRE IN BASE AL TIPO DI VARIABILE
        switch(gettype($item)) {
            
            case 'boolean' :
            case 'integer' :
            break;
        
            default :
            
                //$item = $this->replace_n($item);
                $item = htmlentities($item, ENT_QUOTES|ENT_SUBSTITUTE, "UTF-8", false);             
            
            break;
        
        }
    
    }

    /**
     *  @brief Filtraggio recursivo dei dati
     *  
     *  @param [in] $TAG <b>(array)</b> l'array dei dati da filtrare
     *  
     *  @return <b>(array)</b> l'array con i dati filtrati
     *  
     *  @details La funzione filtra ricorsivamente l'array dei dati passato filtrando in automatico gli indici dell'array 
     *  che risultano come stringhe per la stampa a video.
     *  
     *  @see template::autofilter();
     */ 
    function filterOut($TAG)
    {
        
        array_walk_recursive($TAG,'\template\__autofilter');
        
        return $TAG;
    
    }
    
    /**
     *  @brief Include un template
     *  
     *  @param [in] $template <b>(str)</b> il nome del template da caricare
     *  @param [in] $TAG <b>(array)</b> l'array con i dati da passare al template
     *  @param [in] $filter <b>(bool)</b> l'opzione per filtrare o meno in automatico l'array dei dati
     *  
     *  @details La funzione carica un template includendolo all'intenro della funzione. In questo modo avrà accesso 
     *  diretto come dati solo all'array passato. Questo è utile specie in caso di template che vengano inclusi 
     *  ricorsivamente, permettendo di passare di volta in volta solo i dati necessari.<br />
     *  La funzione inoltre effettua gia un controllo sull'esistenza del template e un filtraggio dei dati passati.
     */ 
    function load($template,$TAG,$filter=false)
    {
        
        //genera il nome completo del template da caricare
        $template = \template\file($template);
            
        //SE È STATO SCELTO DI FILTRARE IN AUTOMATICO L'ARRAY DEI DATI
        if($filter === true) {           
        
            //effettua il filtraggio dei dati passati al template
            $TAG = \template\filterOut($TAG);
            
        }
        
        //include il template
        require $template;
        
    }