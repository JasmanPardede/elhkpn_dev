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
      <h3><i class="fa fa-flag"></i>Beban Kerja Pemeriksa</h3>
    </div>
    <div class="wrapper-iframe">
        <iframe src="<?php echo $beban_kerja_pemeriksa_url; ?>" width="1100" height="750"  frameborder="0" style="position:relative;"></iframe>
    </div>
  </div>
</div>
