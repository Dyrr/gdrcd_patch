# Introduzione {#mainpage}

# Introduzione {#mainpage}

##1 Indice {#mainpage_1} #
1. [Indice] (#mainpage_1)
2. [Requisiti Minimi] (#mainpage_2) 
3. [Installazione] (#mainpage_3)
	1. [Installazione su GDRCD nuovo](#mainpage_3_1)
	2. [Installazione su GDRCD preesistente](#mainpage_3_2)
4. [Funzionalità introdotte] (#mainpage_4)
	
## 2 Requisiti Minimi {#mainpage_2} #
I requisiti minimi per poter utilizzare questa versione del GDRCD 7 sono:
- GDRCD 5.5 (testato) o versioni inferiori (da testare)
- Uno spazio web su un host che  PHP 7.1 o superiore
- Un database con MySQL 5.1 ao superiore (MySQL 5.6 consigliato);
- Le seguenti estensioni di php abilitate :
	+ mysqli o mysqlind a scelta (PDO consigliata);
	+ openssl;
	+ gdlib.
	
## 3 Installazione {#mainpage_3} #	
	
### 3.1 Installazione su GDRCD nuovo {#mainpage_3_1} #
Per prima cosa bisogna estrarre i file dall'archivio compresso .zip scaricato dal repository o dal sito contenente il 
pacchetto in una cartella del vostro pc controllando che la struttura delle cartelle dello stesso sia stata mantenuta.
Una volta fatta questa verifica i file vanno caricati sullo spazio web che avete scelto. Per effettuare questa 
operazione avrete bisogno di un client FTP come programma per caricare i file sull'host e dei dati di accesso all'ftp 
del vostro spazio web.
Per il client ftp ci sono numerosi ottimi programmi freeware che fanno allo scopo, scegliete quello che vi attira di più.
Per i dati di accesso all'ftp, solitamente l'host li invia nella mail di iscrizione o comunque ha delle guide al riguardo 
per cui vi rimando alla documentaizone dell'host.
Una volta configurato il client ftp, basta caricare i file sull'host che è la stessa cosa di quando copiate dei file da 
una cartella all'altra del vostro pc e verificare per scrupolo che la sturttura delle directory sia rimasta la stessa e 
la patch sarà stata installata automaticamente e non dovrete fare altro.

### 3.1 Installazione su GDRCD nuovo {#mainpage_3_1} #
Nel caso si voglia installare la patch su un GDRCD su cui si è già lavorato	la procedura è molto simile alla precedente.
Gli unici controlli che vanno fatti sono nei file **includes/required.php** e nel file **header.inc.php**.
- Se non avete fatto modifiche a questi file si può semplicemente procedere all'installazione come al punto 
[Installazione su GDRCD nuovo](#mainpage_3_1).
- Se sono state fatte delle modifiche a quei file, cancellate la versione di quei due file presente nella patch e poi 
aprite la vostra versione dei file e fate le seguenti modifiche:

**header.inc.php**
```php
<?php
	//SE SI È IL GESTORE E
	//SE LA PAGINA RICHIESTA È QUELLA DI GESTIONE
	if(    $_SESSION['permessi'] == SUPERUSER 
		&& $_REQUEST['page'] == 'gestione') {
		//aggiorna il file del css
?>
		<link rel="stylesheet" 
		      href="<?php echo csscrush_file('themes/' . $PARAMETERS['themes']['current_theme'] . '/css/source/gdrcd.css'); ?>" 
		      type="text/css" />
<?php
	//IN CASO CONTRARIO
	} else {
		//carica il css preprocessato
?>
		<link rel="stylesheet" href="themes/<?php echo $PARAMETERS['themes']['current_theme']; ?>/gdrcd.css" type="text/css" />
<?php
	}
?>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" crossorigin="anonymous">	 
```
Questro blocco di codice va aggiunto esattamente prima del tag <title>.

**includes/required.php**
```php
	//include il file con la definizione della root
	require_once(__DIR__ . '/../root.inc.php');   
	//include il file con il set di funzioni per le risorse
	require_once ROOT . '/system/inc/functions/core/resources.inc.php';   
   
   //carica il set di funzioni per l'interfacciarsi con il database
	\functions\load('core/db/mysqli');	
    
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
```
Questo blocco di codice va aggiunto alla fine del codice prima del tag di chiusura di php.

## 4 Funzionalità introdotte {#mainpage_4} #		
La patch introduce diversi strumenti rispetto alla vecchia versione del GDRCD, che seppur non visibili subito ad occhio 
nudo forniscono delle utilità molto comode per lo sviluppo modulare del pacchetto anche in vista di una nuova versione 
o lo sviluppo di moduli nuovi.
La patch contiene:
- Una integrazione con il preprocessore di css [csscrush](https://the-echoplex.net/csscrush/) pre avere una gestione dei 
css più organizzata. Attualmente i css del gdrcd non sono stati modificati ma si può trovare nella cartella 
**themes/advanced/css/source/gdrcd.css** e nei vari file presenti in quella sezione i file per iniziare a lavorare con il 
preprocessore.
- Un nuovo gestore delle connessioni e operazioni con il database
- Dei set di funzioni per il caricamento delle risorse (funzioni,monuli,template) per facilizzare la gestione del lavoro. 
I vari set di sunzioni hann l aloro guida specifica nelle varie pagine di questa documentaizone.