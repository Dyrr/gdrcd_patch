<?php
/**
 *  @file system\lib\gdrcd\core\i_gdrcd_msg.php
 *  
 *  @brief   Interfaccia con i messaggi comuni alle varie librerie
 *  
 *  @version 1.0.0
 *  @date    dyrr/dyrr/dyrr
 *  
 *  @author  Davide 'Dyrr' Grandi
 */
    
    /**
     *  @namespace  gdrcd::core
     *  @brief      <b>librerie basilari del vendor</b>
     */         
    namespace gdrcd\core;
    
    /** 
     *  @interface  gdrcd::core::i_gdrcd_msg
     *  
     *  @brief      Interfaccia con i messaggi comuni alle varie librerie    
     *  
     *  @version    1.0.0
     *  @date       22/09/2016
     *  
     *  @author     Davide 'Dyrr' Grandi
     */ 
    interface i_gdrcd_msg 
    {
        
        const ERROR_NOTE_NO_PERMESSI = 'Non hai i permessi per visualizzare la nota';
    
    }