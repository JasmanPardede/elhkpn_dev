 <header>
        <div class="header-content">
            <div class="header-content-inner">
                <div class="container-fluid hello">
                    <div class="row bubble">
                        <div class="col-lg-8 col-lg-offset-2  bubble-inside">
                           <h2>Selamat Datang di Aplikasi e-lhkpn</h2>
                           <p>
                              <?php if(isset($pengumuman)): echo $pengumuman; EndIf; ?>
                           </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>


  <?php

    $button = array();
    $button[0] = 'btn-reg';
    $button[1] = 'btn-filing';
    $button[2] = 'btn-announ';
    $button[3] = 'btn-verification';
    $button[4] = 'btn-report';
    $button[5] = 'btn-audit';
    $button[6] = 'btn-admin';


   // echo $this->session->userdata('ID_USER');

    //echo $this->session->userdata('ID_PN');

  ?>

	<section id="shortcut">
        <div class="container" style="width:100%;">
            <div class="row" >
                 <div class="col-lg-12">
                    <ul id="ul-main-menu">
                       <?php // if($this->session->userdata('ID_PN')): ?>
                       <?php if($this->session->userdata('ID_ROLE') == '5'): ?> 
                            <li style="margin-top:5px;"><a style="font-size:13px; width:200px;" class="btn btn-filing btn-lg btn-default" href="<?php echo base_url('portal/filing');?>">E-Filing</a></li>
                            <!--<li style="margin-top:5px;"><a style="font-size:13px; width:200px;" class="btn btn-announ btn-lg btn-default" href="<?php echo base_url('portal/announcement');?>">E-Announcement</a></li>-->
                       <?php Else: ?>
                        <?php foreach($shortcut as $s): ?>
                             <li style="margin-top:5px;"><a style="font-size:13px; width:200px;" class="btn <?php echo $s['button'];?> btn-lg btn-default" href="<?php echo $s['link'];?>?mn=<?php echo $s['nama'];?>"><?php echo $s['nama'];?></a></li>
                        <?php EndForeach; ?>
                       <?php EndIf; ?>
                    </ul>
                 </div>
                 <div class="col-lg-12" style="text-align:center; margin-top:10px;">
                    <a class="btn btn-inverse" id="btn-down" style="border: 1px solid #e7e7e7; border-radius:50%;">
                        <i class="fa fa-angle-down fa-3x"></i>
                    </a>
                 </div>
            </div>
        </div>
    </section>

	<section id="info">
        <div class="container" id="notice-wrapper">
            <div class="row">
                 <div class="col-lg-8 col-lg-offset-2">
                    <img src="<?php echo base_url();?>portal-assets/img/kpk-icon.png"/>
                    <h2>Pengumuman Terbaru</h2>
                    <?php if(isset($pengumuman)): echo $pengumuman; EndIf; ?>
                 </div>
            </div>
        </div>
    </section>



    <div class="remodal" data-remodal-id="modal-notice">
      <button data-remodal-action="close" class="remodal-close"></button>
      <div id="notice">
          <h2>Pengumuman Terbaru</h2>
          <?php if(isset($pengumuman)): echo $pengumuman; EndIf; ?>
      </div>
    </div>

	<script type="text/javascript">
	  $(document).ready(function(){
		$("#shortcut").sticky({topSpacing:93});
        var options = { hashTracking: false };
        var inst = $('[data-remodal-id=modal-notice').remodal(options);
        inst.open();
        var s = $('#shortcut').height();
        s+=230;
        $('#btn-down').click(function(){
            $('html, body').animate({
                scrollTop: s
            }, 2000);
        });
	  });
	</script>
