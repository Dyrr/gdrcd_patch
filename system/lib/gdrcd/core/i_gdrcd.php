<?php
/**
 *  @file system\lib\gdrcd\core\i_gdrcd.php
 *  
 *  @brief   Interfaccia della classe contenitore gdrcd
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
     *  @interface  gdrcd::core::i_gdrcd
     *  
     *  @brief      Interfaccia della classe contenitore gdrcd   
     *  
     *  @version    1.0.0
     *  @date       22/09/2016
     *  
     *  @author     Davide 'Dyrr' Grandi
     *  

     */ 
    interface i_gdrcd 
    {
        
        static function getInstance();
     
    }