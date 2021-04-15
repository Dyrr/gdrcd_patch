<?php
/**
 *  @file       themes\discord\home\ambientazione.php
 *  
 *  @brief      File con la descrizione dell'ambientaizone della land.
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
 ?>
<div class="pagina_ambientazione">
    <?php
    $strInnerPage == "";
    if($_REQUEST['page'] == 'user_ambientazione') {
        include('pages/user_ambientazione.inc.php');
    } else {
        if($_REQUEST['page'] == 'user_razze') {
            include('pages/user_razze.inc.php');
        } else {
            include('pages/user_regolamento.inc.php');
        }
    } ?>
    <!-- Link di ritorno alla homepage -->
    <div class="link_back">
        <a href="index.php">
            <?php echo gdrcd_filter_out($PARAMETERS['info']['homepage_name']); ?>
        </a>
    </div>
</div>