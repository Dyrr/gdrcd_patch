<?php
/**
 *  @file       system/inc/functions/core/log.inc.php
 *  
 *  @brief      Set di funzioni la gestione dei log
 *  
 *  @version    5.6.0
 *  @date       dyrr/dyrr/dyrr
 *  
 *  @author     Davide 'Dyrr' Grandi
 *  
 *  @details    Set di funzioni base: 
 *  - Inserimento; 
 *  - modifica; 
 *  - eliminazione; 
 *  - visualizzazione; 
 *  - oscuramento.
 *  Dei log degli eventi
 *  
 *  @since		5.6.0
 *  
 */     
    
    /**
     *  @namespace  log
     *  @brief      <b>Risorse per la gestione log eventi</b>
     */  	
	namespace log {

		/**
		 *  @brief Inserisce un evento nel log
		 *  
		 *  @param [in] $input <b>(array)</b> L'array con i dati del log da inserire
		 *  @param [in] $input['nome_interessato'] <b>(str)</b> Il nome del pg interessato
		 *  @param [in] $input['autore'] <b>(str)</b> il nome dell'autore del log
		 *  @param [in] $input['data_evento'] <b>(string)</b> la data dell'evento
		 *  @param [in] $input['codice_evento'] <b>(int)</b> Il codice dell'evento segnaltato
		 *  @param [in] $input['descrizione_evento'] <b>(string)</b> La descrizione dell'evento
		 *  @param [in] $input['descrizione_evento'] <b>(str)</b> L'ID univoco del tipo del messaggio
		 *  
		 *  @details Inserisce un evento nella tabella dei log.
		 */		
		function insert($input)
		{
			
			$query = "INSERT INTO log 
					    (
						    nome_interessato, 
						    autore, 
						    data_evento, 
						    codice_evento, 
						    descrizione_evento 
						) 
					 VALUES 
					    (
							?,
							?,
							NOW(),
							?,
							?
						)";
			$param = array();
			$param[] = array(					
				'type' 	=> PARAM_STR,
				'value' => $input['interessato']
			);			
			$param[] = array(					
				'type' 	=> PARAM_STR,
				'value' => isset($input['autore']) ? $input['autore'] : $_SESSION['login']
			);		
			$param[] = array(					
				'type' 	=> PARAM_INT,
				'value' => isset($input['codice']) ? $input['codice'] : GENERIC
			);				
			$param[] = array(					
				'type' 	=> PARAM_STR,
				'value' => $input['txt']
			);			
			\gdrcd\db\stmt($query,$param);			
	
		}
		
		
		/**
		 *  @brief Inserisce un evento nel log
		 *  
		 *  @param [in] $pg <b>(str)</b> Il nome del pg di cui si chiede il log
		 *  @param [in] $type <b>(int|array)</b> il tipo o tipi di log di cui si chiede l'elenco 
		 *  @param [in] $min <b>(int)</b> il numero di log di partenza
		 *  @param [in] $max <b>(int)</b> il numero di log da recuperare 
		 *  
	     *  @return <b>(bool|array)</b> False in caso non ci siano dati o l'array associativo contenente la lista dei log richiesti
		 *  
		 *  @details La funzione recupera la lista dei log secondo le opzioni richieste per tipologia di log e .
		 */		
		function get($input)
		{
			
			$param = array();	
				
				$query  =  "SELECT 
								*
							FROM log 
							WHERE 1 = 1 ";
			
			//SE È STATO IMPOSTATO IL NOME DI UN PG o IP
			if(isset($input['pg'])) {
				
				//imposta la query per i log di quello specifico pg o ip
				$query .=	"AND (   nome_interessato = ? 
								  OR autore = ?) ";
				
				$param[] = array(					
					'type' 	=> PARAM_STR,
					'value' => $input['pg']
				);					
			
				$param[] = array(					
					'type' 	=> PARAM_STR,
					'value' => $input['pg']
				);				
			
			}
		
			//SE È STATO RICHIESTO UNA O PIÙ TIPOLOGIE DI LOG
			if(isset($input['type'])) {
				
				//SE È STATO RICHIESTO UN SINGOLO TIPO DI LOG
				if(is_int($input['type'])) {
				
					//imposta la query per i log di quello specifico tipo
					$query .=	"AND codice_evento = ? ";
					
					$param[] = array(					
						'type' 	=> PARAM_INT,
						'value' => $input['type']
					);

				}
				
				//SE SONO STATE RICHIESTE PIÙ TIPOLOGIE DI LOG 
				if(is_array($input['type'])) {
					
					//imposta il testo temporaneo.
					$temp = '';
					
					//imposta la query per la tipologia di log richeste
					$query .= "AND codice_evento in(";
					
					
					foreach($input['type'] as $v) {
						
						$temp .= '?,';

						$param[] = array(					
							'type' 	=> PARAM_INT,
							'value' => $v
						);						
						
					}
					
					$temp = substr($temp,-1);
					
					$query .= $temp . ') ';
					
				}
			
			}

			//SE È STATA IMPOSTATA UNA DATA INIZIALE
			if(isset($input['begin'])) {
				
				$query .= " AND data_evento > ? ";
				
				$param[] = array(					
					'type' 	=> PARAM_STR,
					'value' => $input['begin']
				);					
				
			}
			
			//SE È STATA IMPOSTATA UNA DATA FINALE			
			if(isset($input['end'])) {
				
				$query .= " AND data_evento < ? ";
				
				$param[] = array(					
					'type' 	=> PARAM_STR,
					'value' => $input['end']
				);					
				
			}			
			
			
			//SE È STATO RICHIESTO UN INTERVALLO DI LOG
			if(    isset($input['min'])
				&& isset($input['max'])) {
				
				$query .= "limit(?,?)";
			
			
				$param[] = array(					
					'type' 	=> PARAM_INT,
					'value' => $input['min']
				);	
				$param[] = array(					
					'type' 	=> PARAM_INT,
					'value' => $input['max']
				);
				
				
			}
			
			if(count($param) == 0) {
				
				$result = \gdrcd\db\stmt($query);
			
			} else {
				
				$result = \gdrcd\db\stmt($query,$param);				
				
			}
			
			if($result['info']['num_rows'] == 0) {
				
				$data = false;
				
			} else {
				
				$data = $result['data'];
				
			}
			
			return $data;
		
		}
	}    
