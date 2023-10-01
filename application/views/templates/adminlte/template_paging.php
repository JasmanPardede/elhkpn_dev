<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/**
 * View
 *
 * @author Gunaones - SKELETON-2015 - PT.Mitreka Solusi Indonesia 
 * @package Views/template
*/
?>
<div class="box-footer clearfix">
    <?php
        if($total_rows){
    ?>
    <div class="col-sm-6">
        <div class="dataTables_info" id="datatable-1_info">Showing <?php echo  $start; ?> to <?php echo  $end; ?>
            of <?php echo  $total_rows; ?> entries
        </div>
    </div>
    <?php
        }
    ?>
    <div class="col-sm-12 text-right">
        <div class="dataTables_paginate paging_bootstrap">
            <?php echo $pagination; ?>
        </div>
    </div>
    <div class="clearfix"></div>
</div>