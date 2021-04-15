<?php 
/**
 *  @file system\lib\gdrcd\core\gdrcd.php
 *  
 *  @brief Classe contenitore gdrcd
 *  
 *  @version 1.0.0
 *  @date    dyrr/dyrr/dyrr
 *  
 *  @author  Davide 'Dyrr' Grandi
 */ 
    
    /** 
     * @namespace       gdrcd::core
     * @brief           <b>Risorse per le funzioni base del CMS</b>
    **/ 
    namespace gdrcd\core;
    
    /** 
     *  @class      gdrcd::core::gdrcd
     *  
     *  @author     Davide 'Dyrr' Grandi
     *  
     *  @version    1.0.0
     *  @date       22/09/2016
     *  
     *  @brief      Classe contenitore del CMS
     *  
     *  @details    La classe è un semplice contenitore usato per indicizzare e richiamare con ordine le funzioni delle 
     *              altre classi utilizzate dal CMS.     
     */ 
    class gdrcd 
    implements 
        \gdrcd\core\i_gdrcd
    {
        
        private static $instance = null;
        
        /* @brief Attributo nel quale verranno iniettate le altre classi */
        public static $class = null;
        
        private function __construct()
        {
        
            self::$class = new \stdClass();
        
        }

        private function __clone() {}

        public static function getInstance()
        {
        
            if (!isset(self::$instance)) {
                
                self::$instance = new gdrcd();

            }
            
            return self::$instance;
        
        }
    
    }