<?php
/**
 *  @file       system\inc\functions\core\helpers.inc.php
 *  
 *  @brief      Set di funzioni per la semplificazione di alcune operazioni
 *  
 *  @version    5.6.0
 *  @date       dyrr/dyrr/dyrr
 *  
 *  @author     Davide 'Dyrr' Grandi
 *  
 *  @details    Il file contiene il set di funzioni usato per la semplificazione di alcuni tipii di  operazioni come la 
 *  formattazione e stampa a video di alcuni contenuti
 *  
 *  @since      5.6.0
 */     
    
    /**
     *  @brief Stampa a video una variabile filtrata in uscita
     *  
     *  @param [in] $data <b>(mixed)</b> la variabile in entrata da filtrare
     *  
     *  @details La funzione stampa a video una variabile filtrandola nel caso si rilevi che questa sia una stringa
     *  @par Esempio
     *  @code{php} 
            <?php
                $data = '<script tyle="javascript">alert(\'XSS\');</script>';
                
                out($data);
            ?>
            
            //genererà come output
            &lt;script tyle=&quot;javascript&quot;&gt;alert(&#039;XSS&#039;);&lt;/script&gt;
     *  @endcode
     *  
     *  @todo vedere se fare qualche filtragigo nel caso la variabile risulti diversa da questi tre tipi di variabile
     *  
     *  @since 5.6.0
     */     
    function out($data)
    {

        //SELEZIONA LE AZIONI DA ESEGUIRE IN BASE AL TIPO DI VARIABILE
        switch(gettype($data)) {
            
            case 'boolean' :
            case 'integer' :
            break;
        
            default :
            
                $data = htmlentities($data, ENT_QUOTES|ENT_SUBSTITUTE, "UTF-8", false);             
            
            break;
        
        }
        
        echo $data;
    
    }
    
    /**
     *  @brief Stampa a video un attributo data di un tag HTML
     *  
     *  @param [in] $attr <b>(string)</b> Il nome dell'attributo data da creare
     *  @param [in] $data <b>(string|int)</b> Il valore dell'attributo
     *  @param [in] $regex <b>(bool)</b> Opszione per il controllo del valore dell'attributo tramite regex
     *  
     *  @details La funzione analizza il valore dell'attributo data indicato dell'invio dei dati alla funzione e in base 
     *  al valore decide se stampare a video l'attributo, rendendo più intuitivo la stampa a video degli attributi di 
     *  quel tipo nei template.<br />
     *  La funzione di default ha impostato una regex di controllo del valore dell'attributo che accetta come caratteri 
     *  validi solo lettere numeri e i caratteri - _ #.<br/>
     *  La funzione ha una regola specifica per l'attibuto delay nel caso questo abbia valore 0 impostandone il valore a 
     *  none.
     *  @par Esempio
     *  @code{php} 
            <?php
                $v = array(
                    'function' => 'main',
                    'target'   => '',
                    'delay'    => 0
                );
            ?>
            <a href="test.php>" 
               <?php data('function',$v['funzione']); ?>
               <?php data('target',$v['target']); ?> 
               <?php data('delay',$v['delay']); ?> 
            >test</a>
            
            //genererà come output
            <a href="test.php" data-function="main" data-delay="none">test</a>
     *  @endcode
     *  
     *  @since 5.6.0 
     */ 
    function data(
        $attr,
        $value,
        $regex = true)
    {
            
        //SE È STATO INSERITO UN VALORE PER L'ATTRIBUTO
        if(trim($value) != '') {
            
            //inizializza la variabile di controllo del valore dell'attributo
            $matchess = array();
            
            //SE È STATO IMPOSTATO IL CONTROLLO DEL VALORE DELL'ATTRIBUTO TRAMITE REGEX
            if($regex === true) {
            
                //ritrasforma le entità HTML in caratteri
                $value = html_entity_decode ($value, ENT_QUOTES|ENT_HTML5, "UTF-8");                
                //imposta il pattern di controllo
                $pattern = "#[^\p{L}0-9\#_-]#siu";
                //controlla il valore
                preg_match($pattern,$value,$matches);
                
            }
            
            //SE IL VALORE SODDISFA IL PATTERN DI CONTROLLO
            if(count($matches) == 0) {
                
                //trasforma in entità HTML il valore
                $value = htmlentities($value, ENT_QUOTES|ENT_SUBSTITUTE, "UTF-8", false); 
                //trasforma in entità HTML l'attributo
                $attr = htmlentities($attr, ENT_QUOTES|ENT_SUBSTITUTE, "UTF-8", false);             
                
                //genera loutput
                $output = 'data-' . $attr . '="' . $value . '"';
            
            //SE IL VALORE NON SODDISFA IL PATTERN DI CONTROLLO
            } else {
                
                //genera un output vuoto
                $output = '';

            }
            
        //SE NON È STATO INSERITO UN VALORE PER L'ATTRIBUTO        
        } else {
            
            //genera un output vuoto            
            $output = '';
        
        }
        
        //SE L'ATTRIBUTO INDICATO È IL CASO SPECIFICO DELAY
        if($attr == 'delay') {
            
            //SE IL VALORE È 0
            if($value == 0) {
                
                //genera loutput
                $output = 'data-delay="none"';
            
            //SE IL VALORE NON È 0            
            } else {
                
                //genera un output vuoto 
                $output = '';
                
            }
        
        
        }
        
        //SE L'OUTPUT NON È VUOTO
        if($output != '') {

            //stampa a video l'output
            echo $output;
            
        }
    
    }   
    
    /**
     *  @brief Stampa a video una variabile rimuovendo i filtri in uscita
     *  
     *  @param [in] $data <b>(mixed)</b> la variabile in entrata da filtrare
     *  
     *  @details La funzione stampa a video una variabile filtrandola nel caso si rilevi che questa sia una stringa
     *  @par Esempio
     *  @code{php} 
            <?php
                $data = '<script tyle="javascript">alert(\'XSS\');</script>';
                
                rawout($data);
            ?>
            
            //genererà come output
            <script tyle="javascript">alert('XSS');</script>
     *  @endcode
     *  
     *  @since 5.6.0
     */         
    function rawout($data)
    {
        
        //SELEZIONA LE AZIONI DA ESEGUIRE IN BASE AL TIPO DI VARIABILE
        switch(gettype($data)) {                
            
            case 'boolean' :
            case 'integer' :
            break;
        
            default :
            
                $data = html_entity_decode ($data, ENT_QUOTES|ENT_HTML5, "UTF-8");              
            
            break;
        
        }
        
        echo $data;
        
    }
    
    /**
     *  @brief Stampa a video una variabile trasformandola in un url solo con caratteri validi
     *  
     *  @param [in] $data <b>(mixed)</b> la variabile in entrata da filtrare
     *  
     *  @details La funzione stampa a video una variabile filtrandola nel caso si rilevi che questa sia una stringa
     *  @par Esempio
     *  @code{php} 
            <?php
                $data = '<script tyle="javascript">alert(\'XSS\');</script>';
                
                url($data);
            ?>
            
            //genererà come output
            %3Cscript+tyle%3D%22javascript%22%3Ealert%28%27XSS%27%29%3B%3C%2Fscript%3E
     *  @endcode
     *  
     *  @since 5.6.0
     */    
    function url($data)
    {
        
        echo processURL($data);
        
    }
    
    function processURL($data) 
	{
        //SELEZIONA LE AZIONI DA ESEGUIRE IN BASE AL TIPO DI VARIABILE
        switch(gettype($data)) {                
            
            case 'boolean' :
            case 'integer' :
            break;
        
            default :
            
                //rimuove eventuali htmlentities precedenti;
                $data = html_entity_decode ($data, ENT_QUOTES|ENT_HTML5, "UTF-8"); 
                //effettua l'encode dei caratteri per l'url
                $data = urlencode($data);
                //converte i caratteri speciali in entità
                $data = htmlentities($data, ENT_QUOTES|ENT_SUBSTITUTE, "UTF-8", false);             
            
            break;
        
        }	
	
		return $data;
	
	
	}
	
	
	/**
     *  @brief formatta un nome
     *  
     *  @param [in] $pg <b>(string)</b> Il nome da formattare
     *  
     *  @return <b>(string)</b> Il nome formattato
     *  
     *  @details La funzione formatta un nome rimuovendo eventuali spazi prima e dopo il nome e mettendo l'iniziale in 
     *  maiuscolo
     *  
     *  @since 5.6.0
     */    
    function nome($pg)
    {
        
        return ucwords(trim($pg));
    
    }
	
	/**
	 *  @brief Helper per ls generazione dei link
	 *  
	 *  @param [in] $modulo <b>(string)</b> il modulo da eseguire usando '/' come separatore per le cartelle
	 *  @param [in] $azione <b>(string)</b> l'operazione da far eseguire al modulo
	 *  @param [in] $parametri <b>(array/string)</b> array o stringa con i parametri per la querystring
	 *  @return <b>(string)</b> l'url nel formato richiesto
	 *  
	 *  @details Il metodo genera l'url nel formato utilizzato del CMS (cartella/modulo//azione/querystring) 
	 *  tramite i valori passati alla funzione.<br />
	 *  Il metodo in particolare permette di passare i parametri per la querystring in diversi formati per essere 
	 *  Il più flessibile possibile per le varie esigenze. I parametri possono essere passari come:
	 *   - array nel formato array('nome_parametro_1' => 'valore','nome_parametro_2' => 'valore');
	 *   - stringa nel formato nome_parametro_1=valore,nome_parametro_2=valore;
	 *   - stringa con la querystring tradizioneale ?nome_parametro_1=valore&amp;nome_parametro_2=valore.
	 *  Il metodo individuerà automaticamente la modalità con cui è passata la lista dei parametri e la formatterà 
	 *  per la generazione dell'url completo.
	 *  
	 *  @see system/lib/helpers/_alias.php per ulteriori informazioni sugli helpers
	 */		
	function landURL(
		$modulo,
		$azione=null,
		$parametri=null)
	{
		
		//inizializza la variabile contenente l'url per evitare warning da php in fase di concatenazione
		$url = 'main.php';
		
		//SE È STATO PASSATO UN MODULO ALLA FUNZIONE
		if($modulo !== null && $modulo != '') {
			
			$modulo = processURL($modulo);
			
			//aggiunge il modulo all'url
			$url .= '?page=' . str_replace('/','__',$modulo);				
		
		}
		
		//SE È STATA PASSATA UNA AZIONE ALL'URL
		if($azione !== null && $azione != '') {
			
			$azione = processURL($azione);
			
			//aggiunge l'azione da eseguire all'url
			$url .= '&op=' . $azione; 
			
		}
		
		//SE CI SONO PARAMETRI
		if($parametri !== null && $parametri != '') {
			
			//SE I PARAMETRI SONO PASSATI COME ARRAY
			if(is_array($parametri)) {
				
				//CICLA L'ELENCO DEI PARAMETRI
				foreach($parametri as  $k => $v) {
					
					$k = processURL($k);
					$v = processURL($v);
					
					//genera la querystring per i rimanenti parametri
					$url .= '&' . $k . '=' . $v;
					
				}
			
			//SE I PARAMETRI SONO PASSATI COME STRINGA
			} else {
			
				//trasforma l'elenco dei parametri in un array
				$temp = explode(',',$parametri);

				
				//CICLA L'ELENCO DEI PARAMETRI
				foreach($temp as $v) {
					
					$temp1 = explode('=',$v);
					
					$k = processURL($temp1[0]);
					$v = processURL($temp1[1]);					
					
					//genera la querystring per i rimanenti parametri
					$url .= '&' . $k . '=' . $v;
					
				}

			}
			
		}
			
		echo trim($url);			
			
			
	}