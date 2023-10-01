<?php  ?>
<style media="screen">
  .title h3 {
    margin-top:0;
    padding-bottom: 20px;
    border-bottom: 3px solid #d2d6de;
  }
  .title .fa {
    margin-right: 5px;
  }
</style>

<div class="laporan-content">
  <div class="container-fluid">
    <div class="title">
      <h3><i class="fa fa-bar-chart"></i>Outlier Harta/Penerimaan PN (StDev)</h3>
    </div>
    <div class="wrapper-iframe">        
        <iframe src="<?php echo $outlier_url; ?>" width="1100" height="900"  frameborder="0" style="position:relative;"></iframe>
    </div>
<!--    <p>
      <?php echo $outlier_url; ?><br>
      Lorem ipsum dolor sit amet, consectetur adipisicing elit,
      sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
      Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
      Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
      Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
    </p>-->
  </div>
</div>
