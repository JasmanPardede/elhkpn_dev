
<section class="content-header">
  <div class="panel panel-default">
    <div class="panel-heading"><strong>VERIFIKASI DATA INDIVIDUAL</strong></div>
    </div>
    <?php echo $breadcrumb; ?>
</section>
<section class="content">
 <div class="row">
   <div class="col-md-12">
     <div class="panel panel-default">
         <div class="panel-body" >
            <?php
            $j_add_pn = $daftar_pn ? $daftar_pn : 0;
            $j_up_jpn = $daftar_pn_perubahan ? $daftar_pn_perubahan : 0;
            $j_calon_pn = $daftar_calon_pn ? $daftar_calon_pn : 0;
            $wl = $daftar_wl ? $daftar_wl : 0;
            $nwl = $daftar_nwl ? $daftar_nwl : 0;
            $tab1 = NULL;
            $tab2 = NULL;
            $tab3 = NULL;
            $tab4 = NULL;
            $tab5 = NULL;
            $cls_act1 = NULL;
            $cls_act2 = NULL;
            $cls_act3 = NULL;
            $cls_act4 = NULL;
            $cls_act5 = NULL;
            $clr_font1 = ' style="color: wheat"';
            $clr_font2 = ' style="color: wheat"';
            $clr_font3 = ' style="color: wheat"';
            $clr_font4 = ' style="color: wheat"';
            $clr_font5 = ' style="color: wheat"';
            if ($j_up_jpn > 0):
                $tab1 = 'data-toggle="tab"';
                $clr_font1 = '';
            endif;
            if ($j_add_pn > 0):
                $tab2 = 'data-toggle="tab"';
                $clr_font2 = '';
            endif;
            if ($j_calon_pn > 0):
                $tab3 = 'data-toggle="tab"';
                $clr_font3 = '';
            endif;
            if ($wl > 0):
                $tab4 = 'data-toggle="tab"';
                $clr_font4 = '';
            endif;
            if ($nwl > 0):
                $tab5 = 'data-toggle="tab"';
                $clr_font5 = '';
            endif;

            if ($j_add_pn > 0):
                $cls_act2 = "active";
            elseif ($j_up_jpn > 0):
                $cls_act1 = "active";
            elseif ($j_calon_pn > 0):
                $cls_act3 = "active";
            elseif ($wl > 0):
                $cls_act4 = "active";
            elseif ($nwl > 0):
                $cls_act5 = "active";
            endif;
			if ($j_up_jpn==0 && $j_add_pn==0 && $j_calon_pn==0 && $wl==0 && $nwl==0)
			{
			?>
			<!--
				<button class="btn btn-sm btn-primary btn-add"  id="btn-add" href="index.php/mailbox/sent/addmail"><i class="fa fa-envelope"></i> Kirim Email</button>
				-->
			<?php
			}
            ?>
            <section class="content" style="background:#ffffff">
                <div class="row" >
                    <div class="col-md-12">

                        <form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo $thisPageUrl; ?>">
                            <div class="box-body">

                                <div class="col-md-6">
                                    <!--
                    <div class="row">
                                    <?php if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2): ?>
                                                        <div class="form-group">
                                <label class="col-sm-4 control-label">Instansi:</label>
                                <div id="inpCariInstansiPlaceHolder" class="col-sm-6">
                                                                        <input type='text' class="input-sm form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI' value='' placeholder="-- Pilih Instasi --">
                                </div>
                            </div>
                                    <?php endif; ?>
                    </div>
                                    -->
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <!--
                                        <?php if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 3): ?>
                                                    <div class="form-group">
                                                            <label class="col-sm-4 control-label">Unit Kerja:</label>
                                                            <div id="inpCariUnitKerjaPlaceHolder" class="col-sm-6">
                                                                    <input type='text' class="input-sm form-control" name='CARI[UNIT_KERJA]' style="border:none;padding:6px 0px;" id='CARI_UNIT_KERJA' value='' placeholder="-- Pilih Unit Kerja --">
                                                            </div>
                                                    </div>
                                        <?php endif; ?>
                                        -->
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="nav-tabs-custom" >
                        <ul class="nav nav-tabs">
                            <li class="<?= $cls_act3 ?>">
                                <a href="#tab_3" <?= $tab3 . $clr_font3 ?> >Penambahan Calon PN/WL (<b><?php echo $j_calon_pn; ?></b>)</a></li>
                            <li class="<?= $cls_act2 ?>">
                                <a href="#tab_2" <?= $tab2 . $clr_font2 ?> >Penambahan PN/WL (<b><?php echo $j_add_pn; ?></b>)</a></li>
                            <li class="<?= $cls_act1 ?>">
                                <a href="#tab_1" <?= $tab1 . $clr_font1 ?> >PN/WL Online (<b><?php echo $j_up_jpn; ?></b>)</a></li>
                            <li class="<?= $cls_act4 ?>">
                                <a href="#tab_4" <?= $tab4 . $clr_font4 ?> >Wajib Lapor (<b><?php echo $wl; ?></b>)</a></li>
                            <li class="<?= $cls_act5 ?>">
                                <a href="#tab_5" <?= $tab5 . $clr_font5 ?> >Non Wajib Lapor (<b><?php echo $nwl; ?></b>)</a></li>
                        </ul>
                        <div class="tab-content" >
                            <div class="tab-pane  <?= $cls_act2 ?>" id="tab_2">
                                <b>Penambahan PN/WL</b>
                                <div class="box" >
                                    <div class="col-md-12">
                                        <form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo $thisPageUrl; ?>">
                                            <div class="box-body">
                                                <div class="col-md-5">
                                                    <div class="row">
                                                        <?php if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 31): ?>
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">Instansi:</label>
                                                                <div id="inpCariInstansiPlaceHolder" class="col-sm-6">
                                                                    <input type='text' class="input-sm form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI' value='' placeholder="-- Pilih Instasi --">
                                                                </div>
                                                            </div>  
                                                            <div class="form-group" style="margin-bottom: 14px;">
                                                                <label class="col-sm-4 control-label">Unit Kerja:</label>
                                                                <div id="inpCariUnitKerjaPlaceHolder" class="col-sm-6">
                                                                    <input type='text' class="input-sm form-control" name='CARI[UNIT_KERJA]' style="border:none;padding:6px 0px;" id='CARI_UNIT_KERJA' value='' placeholder="-- Pilih Unit Kerja --">
                                                                </div>
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label">Jenis Pelaporan:</label>
                                                            <div id="inpJenisLaporanPlaceHolder" class="col-sm-6">
                                                                <select class="form-control input-sm col-6" name="CARI[JENIS]" id="CARI_JENIS">
                                                                    <option value="2">ALL</option>
                                                                    <option value="1">ONLINE</option>
                                                                    <option value="0">OFFLINE</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <?php else: ?>
                                                            <div class="form-group" style="margin-bottom: 14px;">
                                                                <label class="col-sm-4 control-label">Unit Kerja:</label>
                                                                <div id="inpCariUnitKerjaPlaceHolder" class="col-sm-6">
                                                                        <input type='text' class="input-sm form-control" name='CARI[UNIT_KERJA]' style="border:none;padding:6px 0px;" id='CARI_UNIT_KERJA' value='' placeholder="-- Pilih Unit Kerja --">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">Jenis Pelaporan:</label>
                                                                <div id="inpJenisLaporanPlaceHolder" class="col-sm-6">
                                                                    <select class="form-control input-sm col-6" name="CARI[JENIS]" id="CARI_JENIS">
                                                                        <option value="2">ALL</option>
                                                                        <option value="1">ONLINE</option>
                                                                        <option value="0">OFFLINE</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="row">
                                                        <?php endif; ?>
                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label">Cari:</label>
                                                            <div class="col-sm-4" style="padding:0 0 0 15px;margin:0">
                                                                <input type="text" class="form-control input-sm" placeholder="Kata Kunci" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="inp_dt_completeNEW_cari"/>
                                                            </div>
                                                            <div class="col-sm-4" style="padding:0 0 0 3px; margin:0">
                                                                <button type="button" id="btnCari" class="btn btn-sm btn-default" onclick="submitCari();"><i class="fa fa-search"></i></button>
                                                                <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="clearPencarian();">Clear</button>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                <!-- pull ini -->
                                                <a class="btn-cetak btn btn-default btn-sm btn-same" style="background-color: #34ac75;">
                                                    <span class="logo-mini">
                                                        <img style="width:20px;" src="<?php echo base_url(); ?>img/icon/excel.png" >
                                                    </span> Print to Excel 
                                                </a>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="box-body" >
                                        <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                            <thead>
                                                <tr>
                                                    <th width="10px">No</th>
                                                    <th width="120px">NIK</th>
                                                    <th width="180px">NAMA</th>
                                                    <th>JABATAN </th>
                                                    <th width="80px">AKSI</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div><!-- /.box-body -->

                                </div>
                            </div><!-- /.tab-pane -->

                            <div  class="tab-pane <?php echo $cls_act1; ?>" id="tab_1">
                                <b>Perubahan Jabatan</b>
                                <div class="box">
                                    <div class="col-md-12">
                                        <form method="post" class='form-horizontal' id="ajaxFormCariPerubahan" action="<?php echo $thisPageUrl; ?>">
                                            <div class="box-body">
                                                <div class="col-md-5">
                                                    <div class="row">
                                                        <?php if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 31): ?>
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">Instansi:</label>
                                                                <div id="inpCariInstansiPerubahanPlaceHolder" class="col-sm-6">
                                                                    <input type='text' class="input-sm form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI_PERUBAHAN' value='' placeholder="-- Pilih Instasi --">
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="form-group" style="margin-bottom: 14px;">
                                                            <label class="col-sm-4 control-label">Unit Kerja:</label>
                                                            <div id="inpCariUnitKerjaPerubahanPlaceHolder" class="col-sm-6">
                                                                <input type='text' class="input-sm form-control" name='CARI[UNIT_KERJA]' style="border:none;padding:6px 0px;" id='CARI_UNIT_KERJA_PERUBAHAN' value='' placeholder="-- Pilih Unit Kerja --">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label">Cari:</label>
                                                            <div class="col-sm-4" style="padding:0 0 0 5px;margin:0">
                                                                <input type="text" class="form-control input-sm" placeholder="Kata Kunci" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="inp_dt_completeUPDATE_cari"/>
                                                            </div>
                                                            <div class="col-sm-4" style="padding:0 0 0 3px; margin:0">
                                                                <button type="button" id="btnCari" class="btn btn-sm btn-default" onclick="submitCariPerubahan();"><i class="fa fa-search"></i></button>
                                                                <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="clearPencarianPerubahan();">Clear</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- pull ini -->
                                                <a class="btn-cetak-perubahan btn btn-default btn-sm btn-same" style="background-color: #34ac75;">
                                                    <span class="logo-mini">
                                                        <img style="width:20px;" src="<?php echo base_url(); ?>img/icon/excel.png" >
                                                    </span> Print to Excel 
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="box-body">
                                        <table id="dt_completeUPDATE" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                            <thead>
                                                <tr>
                                                    <th width="10px">No</th>
                                                    <th width="120px">NIK</th>
                                                    <th width="180px">NAMA</th>
                                                    <th>JABATAN </th>
                                                    <th width="80px">AKSI</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div><!-- /.box-body -->

                                </div>
                            </div>

                            <div class="tab-pane  <?= $cls_act3 ?>" id="tab_3">
                                <b>Penambahan Calon PN/WL</b>
                                <div class="box">
                                    <div class="col-md-12">
                                    <form method="post" class='form-horizontal' id="ajaxFormCariCalonPN" action="<?php echo $thisPageUrl; ?>">
                                        <div class="box-body">

                                            <div class="col-md-5">
                                                <div class="row">
                                                    <?php if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 31): ?>
                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label">Instansi:</label>
                                                            <div id="inpCariInstansiCalonPlaceHolder" class="col-sm-6">
                                                                <input type='text' class="input-sm form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI_CALON' value='' placeholder="-- Pilih Instasi --">
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Cari :</label>
                                                        <div class="col-sm-4" style="padding:0 0 0 5px;margin:0">
                                                            <input type="text" class="form-control input-sm" placeholder="Kata Kunci" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="inp_dt_completeCalonPN_cari"/>
                                                        </div>
                                                    <div class="col-sm-4" style="padding:0 0 0 3px; margin:0">
                                                    <button type="button" id="btnCari" class="btn btn-sm btn-default" onclick="submitCariCalonPN();"><i class="fa fa-search"></i></button>
                                                    <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="clearPencarianCalonPN();">Clear</button>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="row">
                                                    <div class="form-group" style="padding-top:5px">
                                                        <label class="col-sm-4 control-label">Jenis Pelaporan:</label>
                                                        <div id="inpJenisLaporanCalonPlaceHolder" class="col-sm-6">
                                                            <select class="form-control" name="CARI[JENIS]" id="CARI_JENIS_CALON">
                                                                <option value="2">ALL</option>
                                                                <option value="1">ONLINE</option>
                                                                <option value="0">OFFLINE</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>>

                                            
                                        </div>
                                    </form>
                                </div>
                                    <div class="box-body">
                                        <table id="dt_completeCalonPN" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                            <thead>
                                                <tr>
                                                    <th width="10px">No</th>
                                                    <th width="120px">NIK</th>
                                                    <th width="180px">NAMA</th>
                                                    <th>JABATAN </th>
                                                    <th width="80px">AKSI</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div><!-- /.box-body -->
                                </div>
                            </div><!-- /.tab-pane -->

                            <div class="tab-pane  <?= $cls_act4 ?>" id="tab_4">
                                <b>Wajib Lapor</b>
                                <div class="box">
                                    <div class="col-md-12">
                                        <form method="post" class='form-horizontal' id="ajaxFormCariWL" action="<?php echo $thisPageUrl; ?>">
                                            <div class="box-body">
                                                <div class="col-md-5">
                                                    <div class="row">
                                                        <?php if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 31): ?>
                                                            <div class="form-group"  >
                                                                <label class="col-sm-4 control-label">Instansi:</label>
                                                                <div id="inpCariInstansiWLPlaceHolder" class="col-sm-6">
                                                                    <input type='text' class="input-sm form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI_WL' value='' placeholder="-- Pilih Instasi --">
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label class="col-sm-2 control-label">Cari :</label>
                                                            <div class="col-sm-4" style="padding:0 0 0 5px;margin:0">
                                                                <input type="text" class="form-control input-sm" placeholder="Kata Kunci" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="inp_dt_WajibLapor_cari"/>
                                                            </div>
                                                            <div class="col-sm-4" style="padding:0 0 0 3px; margin:0">
                                                                <button type="button" id="btnCari" class="btn btn-sm btn-default" onclick="submitCariWL();"><i class="fa fa-search"></i></button>
                                                                <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="clearPencarianWL();">Clear</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a class="btn-cetak-wl btn btn-default btn-sm btn-same" style="background-color: #34ac75;">
                                                    <span class="logo-mini">
                                                        <img style="width:20px;" src="<?php echo base_url(); ?>img/icon/excel.png" >
                                                    </span> Print to Excel 
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="box-body">
                                        <table id="dt_WajibLapor" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                            <thead>
                                                <tr>
                                                    <th width="10px"><input type="checkbox" onClick="chk_all(this);" /></th>
                                                    <th width="10px">No</th>
                                                    <th width="120px">NIK</th>
                                                    <th width="180px">NAMA</th>
                                                    <th>JABATAN </th>
                                                    <th width="80px">AKSI</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                        <div id="veriftommbolwl" name="veriftommbolwl">
                                        <button style="margin-top: 10px;" type="button" class="btn btn-warning" onclick="verif_all();"title="Verifikasi"><i class="fa fa-plus-square"></i> Verifikasi Semua</button>
                                        </div>
                                        <div id="canceltommbolwl" name="canceltommbolwl">
                                        <button style="margin-top: 10px;" type="button" class="btn btn-warning" onclick="cancel_all();"title="Cancel Verifikasi"><i class="fa fa-minus-square"></i> Cancel Verifikasi Semua</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane  <?= $cls_act5 ?>" id="tab_5">
                                <b>Non Wajib Lapor</b>
                                <div class="box">
                                    <div class="col-md-12">
                                        <form method="post" class='form-horizontal' id="ajaxFormCariNONWL" action="<?php echo $thisPageUrl; ?>">
                                            <div class="box-body">
                                                <div class="col-md-5">
                                                    <div class="row">
                                                        <?php if ($this->session->userdata('ID_ROLE') == 1 || $this->session->userdata('ID_ROLE') == 2 || $this->session->userdata('ID_ROLE') == 31): ?>
                                                            <div class="form-group"  >
                                                                <label class="col-sm-4 control-label">Instansi:</label>
                                                                <div id="inpCariInstansiNonWLPlaceHolder" class="col-sm-6">
                                                                    <input type='text' class="input-sm form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI_NONWL' value='' placeholder="-- Pilih Instasi --">
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label class="col-sm-2 control-label">Cari :</label>
                                                            <div class="col-sm-4" style="padding:0 0 0 5px;margin:0">
                                                                <input type="text" class="form-control input-sm" placeholder="Kata Kunci" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="inp_dt_NonWajibLapor_cari"/>
                                                            </div>
                                                            <div class="col-sm-4" style="padding:0 0 0 3px; margin:0">
                                                                <button type="button" id="btnCari" class="btn btn-sm btn-default" onclick="submitCariNONWL();"><i class="fa fa-search"></i></button>
                                                                <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="clearPencarianNONWL();">Clear</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a class="btn-cetak-nwl btn btn-default btn-sm btn-same" style="background-color: #34ac75;">
                                                    <span class="logo-mini">
                                                        <img style="width:20px;" src="<?php echo base_url(); ?>img/icon/excel.png" >
                                                    </span> Print to Excel 
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="box-body">
                                        <table id="dt_NonWajibLapor" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                            <thead>
                                                <tr>
                                                    <th width="10"><input type="checkbox" onClick="chkNonWajibLapor_all(this);" /></th>
                                                    <th width="10px">No</th>
                                                    <th width="120px">NIK</th>
                                                    <th width="180px">NAMA</th>
                                                    <th>JABATAN </th>
                                                    <th>ALASAN </th>
                                                    <th width="80px">AKSI</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                        <div id="veriftommbolnwl" name="veriftommbolnwl">
                                        <button style="margin-top: 10px;" type="button" class="btn btn-warning" onclick="verifNonWajibLapor_all();"title="Verifikasi"><i class="fa fa-plus-square"></i> Verifikasi Semua</button>
                                        </div>
                                        <div id="canceltommbolnwl" name="canceltommbolnwl">
                                        <button style="margin-top: 10px;" type="button" class="btn btn-warning" onclick="cancelNonWajibLapor_all();"title="Cancel Verifikasi"><i class="fa fa-minus-square"></i> Cancel Verifikasi Semua</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div><!-- /.tab-content -->
                    </div><!-- nav-tabs-custom -->
                    <!-- <div id="resultdiv"></div> -->
                </div>
                </section>
            </div>
          </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
<!-- </div> -->
<!-- </div>
</div> -->
</section><!-- /.content --> <!-- /container -->
<script type="text/javascript">

    var tblDaftarIndividual = {

        tableId: 'dt_completeNEW',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarIndividual'},
        conf: {
            "cShowSearch": false,
            "bJQueryUI": true,
            "bProcessing": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "bAutoWidth": false,
            "sAjaxSource": "<?php echo base_url('ereg/all_pn/load_data_daftar_pn_individu/0'); ?>",
            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {
                var passData = getRecordDtTbl(sSource, aoData, oSettings);

                passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI").val()});
                passData.push({"name": "CARI[JENIS]", "value": $("#CARI_JENIS").val()});
                passData.push({"name": "CARI[TEXT]", "value": $("#inp_dt_completeNEW_cari").val()});
                passData.push({"name": "CARI[UNIT_KERJA]", "value": $("#CARI_UNIT_KERJA").val()})

                $.getJSON(sSource, passData, function(json) {
                    fnCallback(json);
                });
            },
            "aoColumns": [
                {"mDataProp": "NO_URUT", bSearchable: false},
                {"mDataProp": "NIK", bSearchable: true},
                {"mDataProp": "NAMA", bSearchable: true},
                {
                    "mDataProp": function(source, type, val) {
                        var arr_showed_string = [];
                        if (isObjectAttributeExists(source, 'NAMA_JABATAN') && !isEmpty(source.NAMA_JABATAN)) {
                            arr_showed_string.push(source.NAMA_JABATAN);
                        }
                        if (isObjectAttributeExists(source, 'SUB_UNIT_KERJA') && !isEmpty(source.SUB_UNIT_KERJA)) {
                            arr_showed_string.push(source.SUB_UNIT_KERJA);
                        }
                        if (isObjectAttributeExists(source, 'UK_NAMA') && !isEmpty(source.UK_NAMA)) {
                            arr_showed_string.push(source.UK_NAMA);
                        }
                        if (isObjectAttributeExists(source, 'NAMA_LEMBAGA') && !isEmpty(source.NAMA_LEMBAGA)) {
                            arr_showed_string.push(source.NAMA_LEMBAGA);
                        }

                        return  arr_showed_string.join(' - ');
                    },
                    bSearchable: true
                },
                {
                    "mDataProp": function(source, type, val) {

                        var btnDetail = '<button type="button" class="btn btn-sm btn-info btn-detail" href="index.php/ereg/all_pn/detailpn_penambahan/' + source.ID_PN + '/' + source.ID + '/0" title="Preview" onclick="onButton.detail.click(this);"><i class="fa fa-search-plus"></i></button>';

                        // var BtnHapus = '<button onclick="btnDeleteOnClick(this);" type="button" class="btn btn-sm btn-danger btn-delete"  href="index.php/ereg/all_pn/delete_daftar_pn_individual/' + source.ID_PN + '/' + source.ID + '/0"" title="Cancel"><i class="fa fa-close" style="color:white;"></i></button>';
                        var BtnHapus = '<button onclick="btnDeleteOnClick(this);" type="button" class="btn btn-sm btn-danger btn-delete"  href="index.php/ereg/all_pn/delete_daftar_pn_individual/' + source.ID_PN + '/0"" title="Cancel"><i class="fa fa-close" style="color:white;"></i></button>';

                        var BtnApprove = '<button  class="btn btn-sm btn-success" onclick="app_vi_add(' + source.ID_PN + ',' + source.ID + ',' + source.IS_APLIKASI + ',' + source.ID_USER + ',0)" title="approve"><i class="fa fa-check"></i></button>';
                        var disable = '', btnApprove = '';
                        if (source.IS_ACTIVE == 1) {
                            disable = 'disabled';
                        }

                        if (isVerifikasi) {
                            btnApprove = '<button ' + disable + ' class="btn btn-sm btn-success" onclick="approve(' + source.ID_PN + ')"  title="approve"><i class="fa fa-check"></i></button>';
                        }

                        return (btnDetail + " " + BtnApprove + " " + BtnHapus).toString();
                    },
                    bSortable: false,
                    bSearchable: false
                }
            ],
            "fnRowCallback": function(nRow, aData) {
                return nRow;
            }
        }
    };

    var tblDaftarIndividualPerubahan = {
        tableId: 'dt_completeUPDATE',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarIndividualPerubahan'},
        conf: {
            "cShowSearch": false,
            "bJQueryUI": true,
            "bProcessing": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "bAutoWidth": false,
            "sAjaxSource": "<?php echo base_url('ereg/all_pn/load_data_daftar_pn_individu/1'); ?>",
            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {
                var passData = getRecordDtTbl(sSource, aoData, oSettings);

                passData.push({"name": "CARI[UNIT_KERJA]", "value": $("#CARI_UNIT_KERJA_PERUBAHAN").val()})
                passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI_PERUBAHAN").val()});
                passData.push({"name": "CARI[TEXT]", "value": $("#inp_dt_completeUPDATE_cari").val()});

                $.getJSON(sSource, passData, function(json) {
                    fnCallback(json);
                });
            },
            "aoColumns": [
                {"mDataProp": "NO_URUT", bSearchable: false},
                {"mDataProp": "NIK", bSearchable: true},
                {"mDataProp": "NAMA", bSearchable: true},
                {
                    "mDataProp": function(source, type, val) {
                        var arr_showed_string = [];
                        if (isObjectAttributeExists(source, 'DESKRIPSI_JABATAN') && !isEmpty(source.DESKRIPSI_JABATAN)) {
                            arr_showed_string.push(source.DESKRIPSI_JABATAN);
                        }
                        if (isObjectAttributeExists(source, 'SUB_UNIT_KERJA') && !isEmpty(source.SUB_UNIT_KERJA)) {
                            arr_showed_string.push(source.SUB_UNIT_KERJA);
                        }
                        if (isObjectAttributeExists(source, 'UK_NAMA') && !isEmpty(source.UK_NAMA)) {
                            arr_showed_string.push(source.UK_NAMA);
                        }
                        if (isObjectAttributeExists(source, 'NAMA_LEMBAGA') && !isEmpty(source.NAMA_LEMBAGA)) {
                            arr_showed_string.push(source.NAMA_LEMBAGA);
                        }
                        return  arr_showed_string.join(' - ');
                    },
                    bSearchable: true
                },
                {
                    "mDataProp": function(source, type, val) {
                        var btnDetail = '<button type="button" class="btn btn-sm btn-info btn-detail" href="index.php/ereg/all_pn/detailpn_perubahan/' + source.ID_PN + '/' + source.ID + '" title="Preview" onclick="onButton.detail.click(this);"><i class="fa fa-search-plus"></i></button>';

                        var BtnHapus = '<button onclick="btnDeleteOnClick(this);" type="button" class="btn btn-sm btn-danger btn-delete"  href="index.php/ereg/all_pn/delete_vi/' + source.ID_PN + '/' + source.ID + '"" title="Cancel"><i class="fa fa-close" style="color:white;"></i></button>';

                        var BtnApprove = '<button onclick="app_vi_up(' + source.ID_PN + ',' + source.ID + ',' + source.ID_USER + ')" class="btn btn-sm btn-success"  title="approve"><i class="fa fa-check"></i></button>';

                        return (btnDetail + " " + BtnApprove + " " + BtnHapus).toString();
                    },
                    bSortable: false,
                    bSearchable: false
                }
            ],
            "fnRowCallback": function(nRow, aData) {
                return nRow;
            }
        }
    };

    var tblDaftarIndividualCalonPN = {
        tableId: 'dt_completeCalonPN',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarIndividualCalonPN'},
        conf: {
            "cShowSearch": false,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "bAutoWidth": false,
            "sAjaxSource": "<?php echo base_url('ereg/all_pn/load_data_daftar_pn_individu/6'); ?>",
            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

                var passData = getRecordDtTbl(sSource, aoData, oSettings);

                passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI_CALON").val()});
                //                    passData.push({"name": "CARI[UNIT_KERJA]", "value": $("#CARI_UNIT_KERJA").val()})
                passData.push({"name": "CARI[JENIS]", "value": $("#CARI_JENIS_CALON").val()});
                passData.push({"name": "CARI[TEXT]", "value": $("#inp_dt_completeCalonPN_cari").val()});

                $.getJSON(sSource, passData, function(json) {
                    fnCallback(json);
                });
            },
            "aoColumns": [
                /*{
                "mDataProp": function (source, type, val) {
                return "";
                },
                bSearchable: false
                },*/
                {"mDataProp": "NO_URUT", bSearchable: false},
                {"mDataProp": "NIK", bSearchable: true},
                {"mDataProp": "NAMA", bSearchable: true},
                {
                    "mDataProp": function(source, type, val) {
                        var arr_showed_string = [];
                        if (isObjectAttributeExists(source, 'NAMA_JABATAN') && !isEmpty(source.NAMA_JABATAN)) {
                            arr_showed_string.push(source.NAMA_JABATAN);
                        }
                        if (isObjectAttributeExists(source, 'SUB_UNIT_KERJA') && !isEmpty(source.SUB_UNIT_KERJA)) {
                            arr_showed_string.push(source.SUB_UNIT_KERJA);
                        }
                        if (isObjectAttributeExists(source, 'UK_NAMA') && !isEmpty(source.UK_NAMA)) {
                            arr_showed_string.push(source.UK_NAMA);
                        }
                        if (isObjectAttributeExists(source, 'NAMA_LEMBAGA') && !isEmpty(source.NAMA_LEMBAGA)) {
                            arr_showed_string.push(source.NAMA_LEMBAGA);
                        }

                        return  arr_showed_string.join(' - ');
                    },
                    bSearchable: true
                },
                {
                    "mDataProp": function(source, type, val) {

                        var btnDetail = '<button type="button" class="btn btn-sm btn-info btn-detail" href="index.php/ereg/all_pn/detailpn_penambahan/' + source.ID_PN + '/' + source.ID + '/1" title="Preview" onclick="onButton.detail.click(this);"><i class="fa fa-search-plus"></i></button>';

                        // var BtnHapus = '<button onclick="btnDeleteOnClick(this);" type="button" class="btn btn-sm btn-danger btn-delete"  href="index.php/ereg/all_pn/delete_daftar_pn_individual/' + source.ID_PN + '/' + source.ID + '/0"" title="Cancel"><i class="fa fa-close" style="color:white;"></i></button>';
                        var BtnHapus = '<button onclick="btnDeleteOnClick(this);" type="button" class="btn btn-sm btn-danger btn-delete"  href="index.php/ereg/all_pn/delete_daftar_pn_individual/' + source.ID_PN + '/1"" title="Cancel"><i class="fa fa-close" style="color:white;"></i></button>';

                        var BtnApprove = '<button  class="btn btn-sm btn-success" onclick="app_vi_add(' + source.ID_PN + ',' + source.ID + ',' + source.IS_APLIKASI + ',' + source.ID_USER + ',1)" title="approve"><i class="fa fa-check"></i></button>';
                        var disable = '', btnApprove = '';
                        if (source.IS_ACTIVE == 1) {
                            disable = 'disabled';
                        }

                        if (isVerifikasi) {
                            btnApprove = '<button ' + disable + ' class="btn btn-sm btn-success" onclick="approve(' + source.ID_PN + ')" title="approve"><i class="fa fa-check"></i></button>';
                        }

                        return (btnDetail + " " + BtnApprove + " " + BtnHapus).toString();
                    },
                    bSortable: false,
                    bSearchable: false
                }
            ],
            "fnRowCallback": function(nRow, aData) {
                return nRow;
            }
        }
    };

    var tblDaftarWajibLapor = {
        tableId: 'dt_WajibLapor',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarWajibLapor'},
        conf: {
            "cShowSearch": false,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "bAutoWidth": false,
            "sAjaxSource": "<?php echo base_url('ereg/all_pn/load_data_daftar_wl/4'); ?>",
            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

                var passData = getRecordDtTbl(sSource, aoData, oSettings);
                // if ($('#CARI_INSTANSI').val() === "" || $('#CARI_INSTANSI').val() === "1081") {
                //     passData.push({"name": "CARI[INSTANSI]", "value": "1081"});
                //     passData.push({"name": "CARI[UNIT_KERJA]", "value": "590"})
                // }
                // else {
                //     passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI").val()});
                //     passData.push({"name": "CARI[UNIT_KERJA]", "value": $("#CARI_UNIT_KERJA").val()})
                // }
                passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI_WL").val()});
                passData.push({"name": "CARI[TEXT]", "value": $("#inp_dt_WajibLapor_cari").val()});

                $.getJSON(sSource, passData, function(json) {
                    fnCallback(json);
                });
            },
            "aoColumns": [
                {
                    "mDataProp": function(source, type, val) {
                        var arr_showed_string = [];
                        if (isObjectAttributeExists(source, 'ID_PN') && !isEmpty(source.ID_PN)) {
                            arr_showed_string.push(source.ID_PN);
                        }
                        if (isObjectAttributeExists(source, 'ID') && !isEmpty(source.ID)) {
                            arr_showed_string.push(source.ID);
                        }
                        arr_showed_string.join(',');
                        var btxchk = '<input class="chk" type="checkbox" onclick="chk(this);" value="' + arr_showed_string + '"  name="chk1">';
                        return (btxchk).toString();
                    },
                    bSortable: false,
                    bSearchable: false
                },
                {"mDataProp": "NO_URUT", bSearchable: false},
                {"mDataProp": "NIK", bSearchable: true},
                {"mDataProp": "NAMA", bSearchable: true},
                {
                    "mDataProp": function(source, type, val) {
                        var arr_showed_string = [];
                        if (isObjectAttributeExists(source, 'NAMA_JABATAN') && !isEmpty(source.NAMA_JABATAN)) {
                            arr_showed_string.push(source.NAMA_JABATAN);
                        }
                        if (isObjectAttributeExists(source, 'SUB_UNIT_KERJA') && !isEmpty(source.SUB_UNIT_KERJA)) {
                            arr_showed_string.push(source.SUB_UNIT_KERJA);
                        }
                        if (isObjectAttributeExists(source, 'UK_NAMA') && !isEmpty(source.UK_NAMA)) {
                            arr_showed_string.push(source.UK_NAMA);
                        }
                        if (isObjectAttributeExists(source, 'NAMA_LEMBAGA') && !isEmpty(source.NAMA_LEMBAGA)) {
                            arr_showed_string.push(source.NAMA_LEMBAGA);
                        }

                        return  arr_showed_string.join(' - ');
                    },
                    bSearchable: true
                },
                {
                    "mDataProp": function(source, type, val) {

                        var btnDetail = '<button type="button" class="btn btn-sm btn-info btn-detail" href="index.php/ereg/all_pn/detailpn_wl/' + source.ID_PN + '/' + source.ID + '" title="Preview" onclick="onButton.detail.click(this);"><i class="fa fa-search-plus"></i></button>';

                        var Btncancel = '<button type="button" class="btn btn-sm btn-danger"  onclick="Cancel_VerNon_wlon(' + source.ID_PN + ',' + source.ID + ')" title="Cancel "><i class="fa fa-close"></i></button>';

                        var BtnApprove = '<button onclick="app_vi_nonact(' + source.ID_PN + ',' + source.ID + ')" class="btn btn-sm btn-success" title="approve"><i class="fa fa-check"></i></button>';


                        return (btnDetail + " " + BtnApprove + " " + Btncancel).toString();
                    },
                    bSortable: false,
                    bSearchable: false
                }
            ],
            "fnRowCallback": function(nRow, aData) {
                return nRow;
            }
        }
    };

    var tblDaftarNonWajibLapor = {
        tableId: 'dt_NonWajibLapor',
        reloadFn: {tableReload: true, tableCollectionName: 'tblDaftarNonWajibLapor'},
        conf: {
            "cShowSearch": false,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "bAutoWidth": false,
            "sAjaxSource": "<?php echo base_url('ereg/all_pn/load_data_daftar_nwl/5'); ?>",
            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

                var passData = getRecordDtTbl(sSource, aoData, oSettings);
                
                passData.push({"name": "CARI[INSTANSI]", "value": $("#CARI_INSTANSI_NONWL").val()});
                passData.push({"name": "CARI[TEXT]", "value": $("#inp_dt_NonWajibLapor_cari").val()});


                $.getJSON(sSource, passData, function(json) {
                    fnCallback(json);
                });
            },
            "aoColumns": [
            {
                    "mDataProp": function(source, type, val) {
                        var arr_showed = [];
                        if (isObjectAttributeExists(source, 'ID_PN') && !isEmpty(source.ID_PN)) {
                            arr_showed.push(source.ID_PN);
                        }
                        if (isObjectAttributeExists(source, 'ID') && !isEmpty(source.ID)) {
                            arr_showed.push(source.ID);
                        }
                        arr_showed.join(',');
                        var btchk = '<input class="chk2" type="checkbox" onclick="chkNonWajibLapor(this);" value="' + arr_showed + '" name="chk2">';
                        return (btchk).toString();
                    },
                    bSortable: false,
                    bSearchable: false
                },
                {"mDataProp": "NO_URUT", bSearchable: false},
                {"mDataProp": "NIK", bSearchable: true},
                {"mDataProp": "NAMA", bSearchable: true},
                {
                    "mDataProp": function(source, type, val) {
                        var arr_showed_string = [];
                        if (isObjectAttributeExists(source, 'NAMA_JABATAN') && !isEmpty(source.NAMA_JABATAN)) {
                            arr_showed_string.push(source.NAMA_JABATAN);
                        }
                        if (isObjectAttributeExists(source, 'SUB_UNIT_KERJA') && !isEmpty(source.SUB_UNIT_KERJA)) {
                            arr_showed_string.push(source.SUB_UNIT_KERJA);
                        }
                        if (isObjectAttributeExists(source, 'UK_NAMA') && !isEmpty(source.UK_NAMA)) {
                            arr_showed_string.push(source.UK_NAMA);
                        }
                        if (isObjectAttributeExists(source, 'NAMA_LEMBAGA') && !isEmpty(source.NAMA_LEMBAGA)) {
                            arr_showed_string.push(source.NAMA_LEMBAGA);
                        }

                        return  arr_showed_string.join(' - ');
                    },
                    bSearchable: true
                },
                {"mDataProp": "ALASAN_NON_WL", bSearchable: true},
                {
                    "mDataProp": function(source, type, val) {

                        var btnDetail = '<button type="button" class="btn btn-sm btn-info btn-detail" href="index.php/ereg/all_pn/detailpn_nonwl/' + source.ID_PN + '/' + source.ID + '" title="Preview" onclick="onButton.detail.click(this);"><i class="fa fa-search-plus"></i></button>';

                        var Btncancel = '<button type="button" class="btn btn-sm btn-danger"  onclick="Cancel_VerNon_wloff(' + source.ID_PN + ',' + source.ID + ')" title="Cancel "><i class="fa fa-close"></i></button>';

                        var BtnApprove = '<button onclick="app_vi_nonact_new(' + source.ID_PN + ',' + source.ID + ')" class="btn btn-sm btn-success" title="approve"><i class="fa fa-check"></i></button>';


                        return (btnDetail + " " + BtnApprove + " " + Btncancel).toString();
                    },
                    bSortable: false,
                    bSearchable: false
                }
            ],
            "fnRowCallback": function(nRow, aData) {
                return nRow;
            }
        }
    };

    function app_vi_add(idpn, idj, is_aplikasi, iduser, iscln) {

        confirm('Apakah Anda Yakin ?', function() {
            $('#loader_area').show();
            var server_url = 'index.php/ereg/All_pn/app_vi_add/' + idpn + '/' + idj + '/' + iscln;
            $.ajax({
                url: server_url,
                type: "POST",
                data: {"idtemp": idpn},
                success: function(response) {
                    if (response == '0') {
                        alertify.error("Gagal.");
                        $('#loader_area').hide();
                    } else {
                        $('#loader_area').hide();
                        if (is_aplikasi == '1' || is_aplikasi == 1){
                            send_mail_to(idpn, idj, response, iduser, 'add');
                        }else{
                            $.get(location.href.split('#')[1], function(html) {
                                $('#ajax-content').html(html);
                                CloseModalBox();
                                $('#loader_area').hide();
                            });
                        }
                    }
                    $('#loader_area').hide();
                },
                cache: false,
                contentType: false,
                processData: false
            });
            //$('#loader_area').hide();
        });
        $('#loader_area').hide();
    }

    function app_vi_up(idpn, idj, iduser) {
        confirm('Apakah Anda Yakin ?', function() {
            $('#loader_area').show();
            server_url = 'index.php/ereg/All_pn/app_vi_up/' + idpn + '/' + idj;
            $.ajax({
                url: server_url,
                type: "POST",
                data: {"idtemp": idpn},
                success: function(response) {

                    if (response == '0') {
                        alertify.error("gagal");
                        $('#loader_area').hide();
                    } else {
                        $('#loader_area').hide();
                        send_mail_to(idpn, idj, response, iduser, 'up');
                    }

                },
                cache: false,
                contentType: false,
                processData: false

            });
            //$('#loader_area').hide();
        });
        $('#loader_area').hide();
    }

    function app_vi_nonact(idpn, idj) {
        confirm('Apakah Anda Yakin ?', function() {
            $('#loader_area').show();
            server_url = 'index.php/ereg/All_pn/app_vi_nonact/' + idpn + '/' + idj;
            $.ajax({
                url: server_url,
                type: "POST",
                data: {"idtemp": idpn},
                success: function(htmldata) {

                    if (htmldata.status == '0') {
        //                                alertify.error(msg.error);
                        alertify.error("Gagal.");
                        $('#loader_area').hide();
                    } else {
                    //                                alertify.success(msg.success);
                        $.get(location.href.split('#')[1], function(html) {
                            $('#ajax-content').html(html);
                            CloseModalBox();
                            $('#loader_area').hide();
                        })
                    }

                },
                cache: false,
                contentType: false,
                processData: false

            });
            //$('#loader_area').hide();
        });
        $('#loader_area').hide();
    }

    function app_vi_nonact_verif_all(idpn, idj) {
            $('#loader_area').show();
            server_url = 'index.php/ereg/All_pn/app_vi_nonact/' + idpn + '/' + idj;
            $.ajax({
                url: server_url,
                type: "POST",
                data: {"idtemp": idpn},
                success: function(htmldata) {
                    if (htmldata.status == '0') {
                        alertify.error("Gagal.");
                        $('#loader_area').hide();
                    }
                    else {
                        $.get(location.href.split('#')[1], function(html)
                        {
                            $('#ajax-content').html(html);
                            CloseModalBox();
                            $('#loader_area').hide();
                        });

                            $('#loader_area').hide();
                    }
                },
                cache: false,
                contentType: false,
                processData: false

            });
        $('#loader_area').hide();
    }

    function Cancel_VerNon_wlon_all(idpn, idj) {
            $('#loader_area').show();
            server_url = 'index.php/ereg/All_pn/Cancel_VerNon_wlon/' + idpn + '/' + idj;
            $.ajax({
                url: server_url,
                type: "POST",
                data: {"idtemp": idpn},
                success: function(htmldata) {
                    if (htmldata.status == '0') {
                        alertify.error("Gagal.");
                        $('#loader_area').hide();
                    }
                    else {
                        $.get(location.href.split('#')[1], function(html)
                        {
                            $('#ajax-content').html(html);
                            CloseModalBox();
                            $('#loader_area').hide();
                        });

                            $('#loader_area').hide();
                            $("#veriftommbolwl").empty();
                            $("#canceltommbolwl").empty();
                    }
                },
                cache: false,
                contentType: false,
                processData: false

            });
        $('#loader_area').hide();
    }

    function app_vi_nonact_new(idpn, idj) {
        confirm('Apakah Anda Yakin ?', function() {
            $('#loader_area').show();
            server_url = 'index.php/ereg/All_pn/app_vi_nonact_new/' + idpn + '/' + idj;
            $.ajax({
                url: server_url,
                type: "POST",
                data: {"idtemp": idpn},
                success: function(htmldata) {

                    if (htmldata.status == '0') {
                        //                                alertify.error(msg.error);
                        alertify.error("Gagal.");
                        $('#loader_area').hide();
                    } else {
                        //                                alertify.success(msg.success);
                        $.get(location.href.split('#')[1], function(html) {
                            $('#ajax-content').html(html);
                            CloseModalBox();
                            $('#loader_area').hide();
                        })
                    }
                },
                cache: false,
                contentType: false,
                processData: false

            });
            //$('#loader_area').hide();
        });
        $('#loader_area').hide();
    }

    function app_vi_nonact_new_all(idpn, idj) {
            $('#loader_area').show();
            server_url = 'index.php/ereg/All_pn/app_vi_nonact_new/' + idpn + '/' + idj;
            $.ajax({
                url: server_url,
                type: "POST",
                data: {"idtemp": idpn},
                success: function(htmldata) {

                    if (htmldata.status == '0') {
                        //                                alertify.error(msg.error);
                        alertify.error("Gagal.");
                        $('#loader_area').hide();
                    } else {
                        //                                alertify.success(msg.success);
                        $.get(location.href.split('#')[1], function(html) {
                            $('#ajax-content').html(html);
                            CloseModalBox();
                            $('#loader_area').hide();
                        })
                    }
                },
                cache: false,
                contentType: false,
                processData: false

            });
            //$('#loader_area').hide();
        $('#loader_area').hide();
    }

    function Cancel_VerNon_wloff_all(idpn, idj) {
            $('#loader_area').show();
            server_url = 'index.php/ereg/All_pn/Cancel_VerNon_wloff/' + idpn + '/' + idj;
            $.ajax({
                url: server_url,
                type: "POST",
                data: {"idtemp": idpn},
                success: function(htmldata) {

                    if (htmldata.status == '0') {
                    //                                alertify.error(msg.error);
                        alertify.error("Gagal.");
                        $('#loader_area').hide();
                    } else {
                    //                                alertify.success(msg.success);
                        $.get(location.href.split('#')[1], function(html) {
                            $('#ajax-content').html(html);
                            CloseModalBox();
                            $('#loader_area').hide();
                            $("#veriftommbolnwl").empty();
                            $("#canceltommbolnwl").empty();
                        })
                    }
                },
                cache: false,
                contentType: false,
                processData: false

            });
            //$('#loader_area').hide();
        $('#loader_area').hide();
    }

    function delete_vi(idpn, idj) {
        confirm('Apakah Anda Yakin Akan Menghapusnya ?', function() {
            $('#loader_area').show();
            server_url = 'index.php/ereg/All_pn/delete_vi/' + idpn + '/' + idj;
            $.ajax({
                url: server_url,
                type: "POST",
                data: {"idtemp": idpn},
                success: function(htmldata) {

                    if (htmldata.status == '0') {
                        alertify.error(msg.error);
                        $('#loader_area').hide();
                    } else {
                        $.get(location.href.split('#')[1], function(html) {
                            $('#ajax-content').html(html);
                            CloseModalBox();
                            $('#loader_area').hide();
                        })
                    }

                },
                cache: false,
                contentType: false,
                processData: false

            });
            //$('#loader_area').hide();
        });
        $('#loader_area').hide();
    }

    function Cancel_VerNon(idpn, idj) {
        $('#loader_area').show();
        server_url = 'index.php/ereg/All_pn/Cancel_VerNon/' + idpn + '/' + idj;
        $.ajax({
            url: server_url,
            type: "POST",
            data: {"idtemp": idpn},
            success: function(htmldata) {

                if (htmldata.status == 0) {
                    alertify.error(msg.error);
                    $('#loader_area').hide();
                } else {
                    $.get(location.href.split('#')[1], function(html) {
                        $('#ajax-content').html(html);
                        CloseModalBox();
                        $('#loader_area').hide();
                    })
                }

            },
            cache: false,
            contentType: false,
            processData: false

        });
    }

    function Cancel_VerNon_wloff(idpn, idj) {
        confirm('Apakah Anda Yakin ?', function() {
        $('#loader_area').show();
        server_url = 'index.php/ereg/All_pn/Cancel_VerNon_wloff/' + idpn + '/' + idj;
        $.ajax({
            url: server_url,
            type: "POST",
            data: {"idtemp": idpn},
            success: function(htmldata) {

                if (htmldata.status == 0) {
                    alertify.error(msg.error);
                    $('#loader_area').hide();
                } else {
                    $.get(location.href.split('#')[1], function(html) {
                        $('#ajax-content').html(html);
                        CloseModalBox();
                        $('#loader_area').hide();
                    })
                }

            },
            cache: false,
            contentType: false,
            processData: false

            });
        });
    }

    function Cancel_VerNon_wlon(idpn, idj) {
        confirm('Apakah Anda Yakin ?', function () {
        $('#loader_area').show();
        server_url = 'index.php/ereg/All_pn/Cancel_VerNon_wlon/' + idpn + '/' + idj;
        $.ajax({
            url: server_url,
            type: "POST",
            data: {"idtemp": idpn},
            success: function(htmldata) {

                if (htmldata.status == 0) {
                    alertify.error(msg.error);
                    $('#loader_area').hide();
                } else {
                    $.get(location.href.split('#')[1], function(html) {
                        $('#ajax-content').html(html);
                        CloseModalBox();
                        $('#loader_area').hide();
                    })
                }

            },
            cache: false,
            contentType: false,
            processData: false

            });
        });
    }

    function send_mail_to(idpn, idj, content, iduser, actionMethod) {

        if (!isDefined(actionMethod)) {
            actionMethod = 'add';
        }

            //            var request_url = 'index.php/ereg/All_pn/send_mail_for_approval_verification/' + idpn + '/' + idj + '/' + content + '/' + actionMethod;
        var request_url = 'index.php/ereg/All_pn/aktivasiakun/' + iduser;

        $.ajax({
            url: request_url,
            type: "POST",
            data: {content: content},
            success: function(response) {
                $.get(location.href.split('#')[1], function(html) {
                    $('#ajax-content').html(html);
                    CloseModalBox();
                    $('#loader_area').hide();
                });

            },
        });
    }

    function chk_all(ele){


        if($(ele).is(':checked')){
            $('.chk').prop('checked', true);

        }else{
            $('.chk').prop('checked', false);
        }
        cekTombol('chk1');
    }

    function chk(ele){
        var val = $(ele).val();
        //idChk.push(val);
        cekTombol('chk1');
    }

    function verif_all(){
        confirm('Apakah Anda Yakin Memverifikasi Semua Data Yang Dipilih?', function() {
            $('.chk').each(function(){
                var val = $(this).val();
                var res = val.split(",");
                if($(this).is(':checked')){
                    app_vi_nonact_verif_all(res[0],res[1]);
                }
            })
            count();
        });
    }

    function cancel_all(){
        confirm('Apakah Anda Yakin Membatalkan Semua Data Yang Dipilih?', function() {
            $('.chk').each(function(){
                var val = $(this).val();
                var res = val.split(",");
                if($(this).is(':checked')){
                    Cancel_VerNon_wlon_all(res[0],res[1]);
                }
            })
            count();
        });
    }

    function chkNonWajibLapor_all(ele){
            if($(ele).is(':checked')){
                    $('.chk2').prop('checked', true);
            }else{
                    $('.chk2').prop('checked', false);
            }
            cekTombol('chk2');
    }

    function chkNonWajibLapor(ele){
            var val = $(ele).val();
            cekTombol('chk2');
    }

    function verifNonWajibLapor_all(){
        confirm('Apakah Anda Yakin Memverifikasi Semua Data Yang Dipilih?', function() {
                $('.chk2').each(function(){
                        var val = $(this).val();
                        var res = val.split(",");
                        if($(this).is(':checked')){
                                app_vi_nonact_new_all(res[0],res[1]);
                        }
                })
                count();
        });
    }
    function cancelNonWajibLapor_all(){
        confirm('Apakah Anda Yakin Membatalkan Semua Data Yang Dipilih?', function() {
                $('.chk2').each(function(){
                        var val = $(this).val();
                        var res = val.split(",");
                        if($(this).is(':checked')){
                                Cancel_VerNon_wloff_all(res[0],res[1]);
                        }
                })
                count();
        });
    }

    function cekTombol(chkboxName) {
            var checkboxes = document.getElementsByName(chkboxName);
            var checkboxesChecked = [];
            for (var i=0; i<checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                    checkboxesChecked.push(checkboxes[i]);
                    }
            }
            // Return the array if it is non-empty, or null
            if (checkboxesChecked.length === 0 )
            {
                    if(chkboxName ==='chk1'){
                        $("#veriftommbolwl").empty();
                        $("#canceltommbolwl").empty();
                    } else {
                        $("#veriftommbolnwl").empty();
                        $("#canceltommbolnwl").empty();
                    }
            }
            else{
                    if(chkboxName ==='chk1'){
                    $("#veriftommbolwl").html("<button style=\"margin-top: 10px;\" type=\"button\" class=\"btn btn-warning\" onclick=\"verif_all();\"title=\"Verifikasi\"><i class=\"fa fa-plus-square\"></i> Verifikasi Semua</button>");
                    $("#canceltommbolwl").html("<button style=\"margin-top: 10px;\" type=\"button\" class=\"btn btn-warning\" onclick=\"cancel_all();\"title=\"Cancel Verifikasi\"><i class=\"fa fa-minus-square\"></i> Cancel Verifikasi Semua</button>");
                    }
                    else if(chkboxName ==='chk2'){
                    $("#veriftommbolnwl").html("<button style=\"margin-top: 10px;\" type=\"button\" class=\"btn btn-warning\" onclick=\"verifNonWajibLapor_all();\"title=\"Verifikasi\"><i class=\"fa fa-plus-square\"></i> Verifikasi Semua</button>");
                    $("#canceltommbolnwl").html("<button style=\"margin-top: 10px;\" type=\"button\" class=\"btn btn-warning\" onclick=\"cancelNonWajibLapor_all();\"title=\"Cancel Verifikasi\"><i class=\"fa fa-minus-square\"></i> Cancel Verifikasi Semua</button>");
                    }
            }
    }


    <?php
    $vVer = $view_form == 'verifikasi' ? TRUE : FALSE;
    if (!$vVer) {
        $style = 'style="color:red";';
        $btn = 'btn-default';
    } else {
        $style = '';
        $btn = 'btn-primary';
    }

    echo "var btnStyle = '" . $style . "', btnClass = '" . $btn . "', isVerifikasi = " . ($vVer ? "true" : "false") . "; ";
    ?>

    var btnDeleteOnClick = function(self) {
        url = $(self).attr('href');
        $('#loader_area').show();
        $.post(url, function(html) {
            OpenModalBox('Batal Verifikasi Data Individual', html, '', 'standart');
        });
        return false;
    };

    var onButton = {
        go: function(obj, size) {

            if (!isDefined(size)) {
                size = 'large';
            }

            var url = $(obj).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Wajib Lapor', html, '', 'size');
            });
            return false;
        },
        detail: {
            click: function(self) {
                onButton.go(self);
            }}
    };
    $('.btn-add').click(function(e) {
        url = $(this).attr('href');
        $('#loader_area').show();
        $.post(url, function(html) {
            OpenModalBox('Kirim Email', html, '', 'large');
        });
        return false;
    });
    function initiateCariInstansi() {

	$("#CARI_INSTANSI").remove();
	$("#inpCariInstansiPlaceHolder").empty();
        var LEMBAGA = '1081';
	<?php if ($this->session->userdata('ID_ROLE')=="1" || $this->session->userdata('ID_ROLE')=="2" || $this->session->userdata('ID_ROLE') == "7" || $this->session->userdata('ID_ROLE') == "10" || $this->session->userdata('ID_ROLE') == "13" || $this->session->userdata('ID_ROLE') == "14" || $this->session->userdata('ID_ROLE') == "18"): ?>
		$("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='3122' placeholder=\"KOMISI PEMBERANTASAN KORUPSI (KPK)\">");
//		initiateSelect2CariUnitKerja(3122);
	<?php else: ?>
		$("#inpCariInstansiPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI' value='' placeholder=\"-- Pilih Instasi --\">");
                LEMBAGA = '<?php echo $default_instansi; ?>';
        <?php endif; ?>
            initiateSelect2CariUnitKerja(LEMBAGA);
	var cari_instansi_cfg = {
		minimumInputLength: 0,
		data: [],
		ajax: {
			url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
			dataType: 'json',
			quietMillis: 250,
			data: function (term, page) {
				return {
					q: term
				};
			},
			results: function (data, page) {
				return {results: data.item};
			},
			cache: true
		},
		initSelection: function (element, callback) {
			var id = $('#CARI_INSTANSI').val();
			if (id !== "") {
				$.ajax("<?php echo base_url('index.php/share/reff/getLembaga') ?>/" + id, {
					dataType: "json"
				}).done(function (data) {
					callback(data[0]);
				});
			}
		},
		formatResult: function (state) {
			return state.name;
		},
		formatSelection: function (state) {
			return state.name;
		}
	};

	var iins = null;
	$.ajax({
		url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
		dataType: "json",
		async: false,
	}).done(function (data) {
		if (!isEmpty(data.item)) {
			cari_instansi_cfg.data = [{
					id: data.item[0].id,
					name: data.item[0].name
				}];

			iins = data.item[0].id;

			$('#CARI_INSTANSI').select2(cari_instansi_cfg);

			if (iins != null) {
				$("#CARI_INSTANSI").val(iins).trigger("change");
//				initiateSelect2CariUnitKerja(iins);
			}
		}
	});

	};
    var initiateCariInstansiCalon = function() {

        $("#CARI_INSTANSI_CALON").remove();

        $("#inpCariInstansiCalonPlaceHolder").empty();
        <?php if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2" || $this->session->userdata('ID_ROLE') == "31"): ?>
        $("#inpCariInstansiCalonPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI_CALON' value='1081' placeholder=\"KOMISI PEMBERANTASAN KORUPSI (KPK)\">");
        // initiateSelect2CariUnitKerja(1081);
        <?php else: ?>
                    $("#inpCariInstansiCalonPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI_CALON' value='' placeholder=\"-- Pilih Instasi --\">");
        <?php endif; ?>
        var cari_instansi_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $('#CARI_INSTANSI_CALON').val();
                if (id !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getLembaga') ?>/" + id, {
                        dataType: "json"
                    }).done(function(data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }
        };

        var iins = null;
        $.ajax({
            url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
            dataType: "json",
            async: false,
        }).done(function(data) {
            if (!isEmpty(data.item)) {
                cari_instansi_cfg.data = [{
                        id: data.item[0].id,
                        name: data.item[0].name
                    }];

                iins = data.item[0].id;

                $('#CARI_INSTANSI_CALON').select2(cari_instansi_cfg);

                if (iins != null) {
                    $("#CARI_INSTANSI").val(iins).trigger("change");
                    initiateSelect2CariUnitKerja(iins);
                }
            }
        });

    };

    var initiateCariInstansiPerubahan = function() {

        $("#CARI_INSTANSI_PERUBAHAN").remove();

        $("#inpCariInstansiPerubahanPlaceHolder").empty();
        <?php if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2" || $this->session->userdata('ID_ROLE') == "31"): ?>
        $("#inpCariInstansiPerubahanPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI_PERUBAHAN' value='1081' placeholder=\"KOMISI PEMBERANTASAN KORUPSI (KPK)\">");
            initiateSelect2CariUnitKerjaPerubahan(1081);
        <?php else: ?>
                    $("#inpCariInstansiPerubahanPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI_PERUBAHAN' value='' placeholder=\"-- Pilih Instasi --\">");
        <?php endif; ?>
        var cari_instansi_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $('#CARI_INSTANSI_PERUBAHAN').val();
                if (id !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getLembaga') ?>/" + id, {
                        dataType: "json"
                    }).done(function(data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }
        };

        var iins = null;
        $.ajax({
            url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
            dataType: "json",
            async: false,
        }).done(function(data) {
            if (!isEmpty(data.item)) {
                cari_instansi_cfg.data = [{
                        id: data.item[0].id,
                        name: data.item[0].name
                    }];

                iins = data.item[0].id;

                $('#CARI_INSTANSI_PERUBAHAN').select2(cari_instansi_cfg);

                if (iins != null) {
                    $("#CARI_INSTANSI_PERUBAHAN").val(iins).trigger("change");
                    initiateSelect2CariUnitKerjaPerubahan(iins);
                }
            }
        });

    };

    var initiateCariInstansiWL = function() {

        $("#CARI_INSTANSI_WL").remove();

        $("#inpCariInstansiWLPlaceHolder").empty();
        <?php if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2" || $this->session->userdata('ID_ROLE') == "31"): ?>
        $("#inpCariInstansiWLPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI_WL' value='1081' placeholder=\"KOMISI PEMBERANTASAN KORUPSI (KPK)\">");
        // initiateSelect2CariUnitKerja(1081);
        <?php else: ?>
                    $("#inpCariInstansiWLPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI_WL' value='' placeholder=\"-- Pilih Instasi --\">");
        <?php endif; ?>
        var cari_instansi_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $('#CARI_INSTANSI_WL').val();
                if (id !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getLembaga') ?>/" + id, {
                        dataType: "json"
                    }).done(function(data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }
        };

        var iins = null;
        $.ajax({
            url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
            dataType: "json",
            async: false,
        }).done(function(data) {
            if (!isEmpty(data.item)) {
                cari_instansi_cfg.data = [{
                        id: data.item[0].id,
                        name: data.item[0].name
                    }];

                iins = data.item[0].id;

                $('#CARI_INSTANSI_WL').select2(cari_instansi_cfg);

            }
        });

    };
    var initiateCariInstansiNONWL = function() {

        $("#CARI_INSTANSI_NONWL").remove();

        $("#inpCariInstansiNonWLPlaceHolder").empty();
        <?php if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2" || $this->session->userdata('ID_ROLE') == "31"): ?>
        $("#inpCariInstansiNonWLPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI_NONWL' value='1081' placeholder=\"KOMISI PEMBERANTASAN KORUPSI (KPK)\">");
        // initiateSelect2CariUnitKerja(1081);
        <?php else: ?>
                    $("#inpCariInstansiNonWLPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[INSTANSI]' style=\"border:none;padding:6px 0px;\" id='CARI_INSTANSI_NONWL' value='' placeholder=\"-- Pilih Instasi --\">");
        <?php endif; ?>
        var cari_instansi_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $('#CARI_INSTANSI_NONWL').val();
                if (id !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getLembaga') ?>/" + id, {
                        dataType: "json"
                    }).done(function(data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }
        };

        var iins = null;
        $.ajax({
            url: "<?php echo base_url('index.php/share/reff/getLembaga') ?>",
            dataType: "json",
            async: false,
        }).done(function(data) {
            if (!isEmpty(data.item)) {
                cari_instansi_cfg.data = [{
                        id: data.item[0].id,
                        name: data.item[0].name
                    }];

                iins = data.item[0].id;

                $('#CARI_INSTANSI_NONWL').select2(cari_instansi_cfg);
            }
        });

    };

    
    var initiateSelect2CariUnitKerja = function (LEMBAGA) {

	$("#CARI_UNIT_KERJA").remove();
	$("#inpCariUnitKerjaPlaceHolder").empty();
        var set_default_to_null = '';
	if (LEMBAGA !== 1081) {
	$("#inpCariUnitKerjaPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[UNIT_KERJA]' style=\"border:none;padding:6px 0px;\" id='CARI_UNIT_KERJA' value='' placeholder=\"-- Pilih Unit Kerja --\">");
	}
	else{
            $("#inpCariUnitKerjaPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[UNIT_KERJA]' style=\"border:none;padding:6px 0px;\" id='CARI_UNIT_KERJA' value='' placeholder=\"DEPUTI BIDANG PENCEGAHAN DAN MONITORING\">");
		<?php
			$set_default_null = "pencegahan";
		?>

	}
	var cari_unit_kerja_cfg = {
		minimumInputLength: 0,
		data: [],
		ajax: {
			url: "<?php echo base_url('index.php/share/reff/getUnitKerja'); ?>/" + LEMBAGA + "?setdefault_to_null=<?php echo $set_default_null; ?>",
			dataType: 'json',
			quietMillis: 250,
			data: function (term, page) {
				return {
					q: term
				};
			},
			results: function (data, page) {
				return {results: data.item};
			},
			cache: true
		},
		initSelection: function (element, callback) {
			var UNIT_KERJA = $('#CARI_UNIT_KERJA').val();
			if (UNIT_KERJA !== "") {
				$.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA + "/" + UNIT_KERJA + "?setdefault_to_null=<?php echo $set_default_null; ?>", {
					dataType: "json"
				}).done(function (data) {
					callback(data[0]);
				});
			}
		},
		formatResult: function (state) {
			return state.name;
		},
		formatSelection: function (state) {
			return state.name;
		}
	};

	var dsuk = null;
	if (isDefined(LEMBAGA)) {
		var __UNIT_KERJA = $('#CARI_UNIT_KERJA').val();

		$.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA + "/" + __UNIT_KERJA + "?setdefault_to_null=<?php echo $set_default_null; ?>", {
			dataType: "json"
		}).success(function (data) {
//                    console.log(data, data.item, !isEmpty(data.item));
			if (!isEmpty(data.item)) {
				cari_unit_kerja_cfg.data = [{
						id: data.item[0].id,
						name: data.item[0].name
					}];

				dsuk = data.item[0].id;

				$('#CARI_UNIT_KERJA').select2(cari_unit_kerja_cfg).on("change", function (e) {
					reloadTableDoubleTime(gtblAllPnNonAktif);
				});

				if (dsuk != null) {
					$("#CARI_UNIT_KERJA").val(dsuk).trigger("change");
				}
			}

		});
	}
	};

    var initiateSelect2CariUnitKerjaPerubahan = function(LEMBAGA) {

        $("#CARI_UNIT_KERJA_PERUBAHAN").remove();
        $("#inpCariUnitKerjaPerubahanPlaceHolder").empty();
        var set_default_null = "PENCEGAHAN";

        if (LEMBAGA !== 1081) {
            $("#inpCariUnitKerjaPerubahanPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[UNIT_KERJA]' style=\"border:none;padding:6px 0px;\" id='CARI_UNIT_KERJA_PERUBAHAN' value='' placeholder=\"-- Pilih Unit Kerja --\">");
        }
        else {
            $("#inpCariUnitKerjaPerubahanPlaceHolder").html("<input type='text' class=\"input-sm form-control\" name='CARI[UNIT_KERJA]' style=\"border:none;padding:6px 0px;\" id='CARI_UNIT_KERJA_PERUBAHAN' value='' placeholder=\"DEPUTI BIDANG PENCEGAHAN DAN MONITORING\">");
            set_default_null = "PENCEGAHAN";
        }
        var cari_unit_kerja_cfg = {
            minimumInputLength: 0,
            data: [],
            ajax: {
                url: "<?php echo base_url('index.php/share/reff/getUnitKerja'); ?>/" + LEMBAGA + "?setdefault_to_null="+set_default_null,
                dataType: 'json',
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var UNIT_KERJA = $('#CARI_UNIT_KERJA_PERUBAHAN').val();
                if (UNIT_KERJA !== "") {
                    $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA + "/" + UNIT_KERJA + "?setdefault_to_null="+set_default_null, {
                        dataType: "json"
                    }).done(function(data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }
        };

        var dsuk = null;
        if (isDefined(LEMBAGA)) {
            var __UNIT_KERJA = $('#CARI_UNIT_KERJA_PERUBAHAN').val();

            $.ajax("<?php echo base_url('index.php/share/reff/getUnitKerja') ?>/" + LEMBAGA + "/" + __UNIT_KERJA + "?setdefault_to_null=<?php echo $set_default_null; ?>", {
                dataType: "json"
            }).success(function(data) {
                //                    console.log(data, data.item, !isEmpty(data.item));
                if (!isEmpty(data.item)) {
                    cari_unit_kerja_cfg.data = [{
                            id: data.item[0].id,
                            name: data.item[0].name
                        }];

                    dsuk = data.item[0].id;

                    $('#CARI_UNIT_KERJA_PERUBAHAN').select2(cari_unit_kerja_cfg).on("change", function(e) {
                            // gtblDaftarIndividual.fnClearTable(0);
                            // gtblDaftarIndividual.fnDraw();
                            // reloadTableDoubleTime(gtblDaftarIndividual);
                            $("#ajaxFormCariPerubahan").trigger('submit');
                                        });

                    if (dsuk != null) {
                        $("#CARI_UNIT_KERJA_PERUBAHAN").val(dsuk).trigger("change");
                    }

                }

            });
        }
    };

    var clearPencarian = function() {
        $('#inp_dt_completeNEW_cari').val('');
        $("#ajaxFormCari").trigger('submit');
    };
    var clearPencarianCalonPN = function() {
        $('#inp_dt_completeCalonPN_cari').val('');
        $("#ajaxFormCariCalonPN").trigger('submit');
    };
    var submitCariPerubahan = function() {
        $("#ajaxFormCariPerubahan").trigger('submit');
    };
    var clearPencarianPerubahan = function() {
        $('#inp_dt_completeUPDATE_cari').val('');
        $("#ajaxFormCariPerubahan").trigger('submit');
    };
    var submitCariWL = function() {
        $("#ajaxFormCariWL").trigger('submit');
    };
    var clearPencarianWL = function() {
        $('#inp_dt_WajibLapor_cari').val('');
        $("#ajaxFormCariWL").trigger('submit');
    };
    var submitCariNONWL = function() {
        $("#ajaxFormCariNONWL").trigger('submit');
    };
    var clearPencarianNONWL = function() {
        $('#inp_dt_NonWajibLapor_cari').val('');
        $("#ajaxFormCariNONWL").trigger('submit');
    };
    var submitCari = function() {
        $("#ajaxFormCari").trigger('submit');
        // reloadTableDoubleTime(gtblDaftarIndividual);
    };
   
    var submitCariCalonPN = function() {
        $("#ajaxFormCariCalonPN").trigger('submit');
    };
    

    $(document).ready(function() {
        $('.select2').select2();
        $('.btn-delete').click(function(e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Batal Verifikasi Data Individual', html, '','standart');
            });
            return false;
        });

        //pull yg ini aja
        $('.btn-cetak').click(function(e) {
            e.preventDefault();

            var unit_kerja = '';
            var instansi = '';
            var jenis = '';
            var text = '';
            var tipe = '0';

            unit_kerja = $('#CARI_UNIT_KERJA').val();
            instansi = $('#CARI_INSTANSI').val();
            jenis = $('#CARI_JENIS').val();
        
            text = $('#inp_dt_completeNEW_cari').val();
            if(unit_kerja == ''){unit_kerja = 'ALL';}
            if(instansi == ''){instansi = 'ALL';}
            if(text == ''){text = 'ALL';}
            var url = '<?php echo site_url("/index.php/ereg/Cetak/export"); ?>/' + tipe +'/' + text +'/' + jenis +'/' + instansi +'/'+ unit_kerja;
            window.location.href = url;
            return;
        });
        $('.btn-cetak-perubahan').click(function(e) {
            e.preventDefault();
            var unit_kerja = '';
            var instansi = '';
            var text = '';
            var tipe = '1';
            var jenis = '2';
            unit_kerja = $('#CARI_UNIT_KERJA_PERUBAHAN').val();
            instansi = $('#CARI_INSTANSI_PERUBAHAN').val();
            text = $('#inp_dt_completeUPDATE_cari').val();
            if(unit_kerja == ''){unit_kerja = 'ALL';}
            if(instansi == ''){instansi = 'ALL';}
            if(text == ''){text = 'ALL';}
            var url = '<?php echo site_url("/index.php/ereg/Cetak/export"); ?>/'  + tipe +'/' + text +'/' + jenis +'/' + instansi +'/'+ unit_kerja;
            window.location.href = url;
            return;
        });
        $('.btn-cetak-wl').click(function(e) {
            e.preventDefault();
            var unit_kerja = '';
            var instansi = '';
            var text = '';
            var tipe = '4';
            var jenis = '2';
            instansi = $('#CARI_INSTANSI_WL').val();
            text = $('#inp_dt_WajibLapor_cari').val();
            if(unit_kerja == ''){unit_kerja = 'ALL';}
            if(instansi == ''){instansi = 'ALL';}
            if(text == ''){text = 'ALL';}
            var url = '<?php echo site_url("/index.php/ereg/Cetak/export"); ?>/'  + tipe +'/' + text +'/' + jenis +'/' + instansi +'/'+ unit_kerja;
            window.location.href = url;
            return;
        });
        $('.btn-cetak-nwl').click(function(e) {
            e.preventDefault();
            var unit_kerja = '';
            var instansi = '';
            var text = '';
            var tipe = '5';
            var jenis = '2';
            instansi = $('#CARI_INSTANSI_NONWL').val();
            text = $('#inp_dt_NonWajibLapor_cari').val();
            if(unit_kerja == ''){unit_kerja = 'ALL';}
            if(instansi == ''){instansi = 'ALL';}
            if(text == ''){text = 'ALL';}
            var url = '<?php echo site_url("/index.php/ereg/Cetak/export"); ?>/'  + tipe +'/' + text +'/' + jenis +'/' + instansi +'/'+ unit_kerja;
            window.location.href = url;
            return;
        });
        
        //pull sampe sini
        $("#ajaxFormCari").submit(function(e) {
            reloadTableDoubleTime(gtblDaftarIndividual);
            return false;
        });
        $("#ajaxFormCariPerubahan").submit(function(e) {
            reloadTableDoubleTime(gtblDaftarIndividualPerubahan);
            return false;
        });
        $("#ajaxFormCariWL").submit(function(e) {
            reloadTableDoubleTime(gtblWajibLapor);
            return false;
        });
        $("#ajaxFormCariNONWL").submit(function(e) {
            reloadTableDoubleTime(gtblNonWajibLapor);
            return false;
        });
        $("#ajaxFormCariCalonPN").submit(function(e) {
            reloadTableDoubleTime(gtblDaftarIndividualCalonPN);
            return false;
        });
        
        initiateCariInstansi();
        initiateCariInstansiCalon();
        initiateCariInstansiPerubahan();
        initiateCariInstansiWL();
        initiateCariInstansiNONWL();
        // $('#CARI_IS_CALON').change(function() {
        //     $("#ajaxFormCari").trigger('submit');
        //     $("#ajaxFormCariCalonPN").trigger('submit');
        //     $("#ajaxFormCariPerubahan").trigger('submit');
        //     $("#ajaxFormCariWL").trigger('submit');
        //     $("#ajaxFormCariNONWL").trigger('submit');
        // });
        
        
        $('#CARI_INSTANSI_CALON').change(function() {
            $("#ajaxFormCariCalonPN").trigger('submit');
        });
        $('#CARI_INSTANSI_PERUBAHAN').change(function() {
            initiateSelect2CariUnitKerjaPerubahan($(this).val());
            $("#ajaxFormCariPerubahan").trigger('submit');
        });
        $('#CARI_INSTANSI_WL').change(function() {
            $("#ajaxFormCariWL").trigger('submit');
        });
        $('#CARI_INSTANSI_NONWL').change(function() {
            $("#ajaxFormCariNONWL").trigger('submit');
        });
        $('#CARI_JENIS').change(function() {
            $("#ajaxFormCari").trigger('submit');
            // $("#ajaxFormCariCalonPN").trigger('submit');
            // $("#ajaxFormCariWL").trigger('submit');
            // $("#ajaxFormCariNONWL").trigger('submit');
        });
        $('#CARI_JENIS_CALON').change(function() {
            $("#ajaxFormCariCalonPN").trigger('submit');
        });
        $('#CARI_INSTANSI').change(function() {
            initiateSelect2CariUnitKerja($(this).val());
            $("#ajaxFormCari").trigger('submit');
        });
        <?php if ($this->session->userdata('ID_ROLE') == "1" || $this->session->userdata('ID_ROLE') == "2" || $this->session->userdata('ID_ROLE') == "31"): ?>
                        initiateSelect2CariUnitKerja(1081);
                        initiateSelect2CariUnitKerjaPerubahan(1081);
        <?php else: ?>
                        initiateSelect2CariUnitKerja(<?php echo $this->session->userdata('INST_SATKERKD'); ?>);
                        initiateSelect2CariUnitKerjaPerubahan(<?php echo $this->session->userdata('INST_SATKERKD'); ?>);
        <?php endif; ?>
        // $('#CARI_INSTANSI_TAMBAH').change(function(event) {
        //     // initiateSelect2CariUnitKerja($(this).val());
        //     $("#ajaxFormCari").trigger('submit');
        // });
        
        var gtblDaftarIndividual = initDtTbl(tblDaftarIndividual);
        var gtblDaftarIndividualPerubahan = initDtTbl(tblDaftarIndividualPerubahan);
        var gtblDaftarIndividualCalonPN = initDtTbl(tblDaftarIndividualCalonPN);
        var gtblWajibLapor = initDtTbl(tblDaftarWajibLapor);
        var gtblNonWajibLapor = initDtTbl(tblDaftarNonWajibLapor);
        cekTombol('chk1');
        cekTombol('chk2');
    });




</script>
