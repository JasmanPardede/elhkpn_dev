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
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <i class="fa <?php echo $icon;?>"></i> <?php echo $title;?>
    <small><?php //echo $title;?></small>
  </h1>
 <?php echo $breadcrumb;?>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <?php echo $content_list; ?>
        <?php echo $content_paging; ?>
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->

<?php echo $content_js; ?>