<?php
/**
 *  @file       includes\required.php
 *  
 *  @brief      Il file con i componenti comuni dei vari entry point del CMS
 *  
 *  @version    5.6.0
 *  @date       dyrr/dyrr/dyrr
 *  
 *  @author     Davide 'Dyrr' Grandi
 *  
 *  @details    Il file include i componenti comuni necessari ai vari punti di ingresso 
 *  
 *  @since      5.6.0
 */  	
	
	//include il file con la definizione della root
	require_once(__DIR__ . '/../root.inc.php');
	//include il file con la definizione delle costanti
	require_once(ROOT . '/includes/constant_values.inc.php');
	//include il file con i parametri di configurazione
	require_once(ROOT . '/config.inc.php');
	//include il file del vocabolario
	require_once(ROOT . '/vocabulary/' . $PARAMETERS['languages']['set'] . '.vocabulary.php');
	//include il file con le funzioni base
	require_once(ROOT . '/includes/functions.inc.php');
	//include il file con il set di funzioni per le risorse
	require_once ROOT . '/system/inc/functions/core/resources.inc.php';
	
    //carica il set di funzioni per l'interfacciarsi con il database
	\functions\load('core/db/mysqli');	
    //carica gli helpers
	\functions\load('core/helpers');	
	
	//Eseguo la connessione al database
    //per questioni di retrocompatibilità ho lasciato anche la vechcia connesiuone al database.
    $db = \gdrcd\db\connect(
        $PARAMETERS['database']['username'],
        $PARAMETERS['database']['password'],
        $PARAMETERS['database']['url'],
        $PARAMETERS['database']['database_name'],
        $PARAMETERS['database']['collation']
    );	
	
    //include il preprocessore dei css
    require_once ROOT . '/system/lib/csscrush/CssCrush.php';
    
    //opzioni per il processore dei css
    $settings= array(
        'minify' => true,
        'output_dir' =>  ROOT . '/themes/' . $PARAMETERS['themes']['current_theme'],
        'versioning' => true,
        'formatter' => 'block'
    );  
    //imposta le opzioni per il processore dei css      
    csscrush_set('options',$settings); 

	//INCLUSIONE DI UN AUTOLOADER STANDARD QUALORA QUALCUNO VOLESSE CIMENTARSI NELL'USO DELLA PROGRAMMAZIONE AD OGGETTI
    /// [autoloader_example]        
    //include l'autoloader
    require_once(ROOT . '/system/lib/dlight/core/autoloader/Autoloader.php');
    //istanzia l'autoloader
    $autoloader = new \dlight\core\autoloader\Autoloader();
    
    //aggiunge i path in cui cercare le risorse
    $autoloader->addPath(ROOT . '/system/lib/');
    
    //aggiunge nell'array degli autori che sfruttano la classe phpbrowscap
    $autoloader->addVendor('gdrcd'); 
    $autoloader->addVendor('erusev'); 

	
	/// [autoloader_example]        
    
    //INCLUSIONE DI UNA CLASSE CONTENITORE UTILE PER L'INIEZIONE DELLE DIPENDENZE SE QUALCUNO VOLESSE CIMENTARSI NELL'USO 
    //DELLA PROGRAMMAZIONE AD OGGETTI
    //avvia la classe contenitore
    \gdrcd\core\gdrcd::getInstance();
    //imposta un alias più comodo per il contenitore delle classi
    $gdrcd = \gdrcd\core\gdrcd::$class;	
