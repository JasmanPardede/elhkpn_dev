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
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Views/user
 */
?>
<!-- Content Header (Page header) -->
<script src="<?php echo base_url('plugins/chartjs/Chart.min.js'); ?>"></script>

<section class="content-header">
    <h1>Executive Summary
        <small></small>
    </h1>
    <?php echo $breadcrumb;?>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-6 con">
            <?php echo $komposisi; ?>
        </div>
        <div class="col-md-6 con">
            <?php echo $kepatuhan; ?>
        </div>
    </div>
</section><!-- /.content -->