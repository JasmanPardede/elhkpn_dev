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
 * @package Views/lhkpn
*/
?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Daftar LHKPN
            <?php if($this->session->userdata('IS_PN') == '1') { ?>
                <?php echo $this->session->userdata('NAMA'); ?>
             <?php } ?>
            <small>
                daftar LHKPN
                <?php if($this->session->userdata('IS_PN') == '1') { ?>
                    <?php echo $this->session->userdata('NAMA'); ?>
                 <?php } ?>
            </small>
          </h1>
          <?php echo $breadcrumb;?>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <!-- <h3 class="box-title">Bordered Table</h3> -->
                  
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <?php if($this->session->userdata('IS_KPK') != '1') { ?>
                            <button class="btn btn-sm btn-default" id="btn-add" href="index.php/efill/lhkpn/addlhkpn"><i class="fa fa-plus"></i> Pengisian LHKPN</button>
                            <!-- <button class="btn btn-sm btn-default" id="btnImportExcel" href="index.php/efill/lhkpn/importexcel"><i class="fa fa-file-excel-o"></i> Import Excel</button> -->
                        <?php } ?>
                    </div>
                    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                        <form method="post" class='form-horizontal' id="ajaxFormCari" action="index.php/efill/lhkpn/">
                    <?php if($this->session->userdata('ID_ROLE') !== '5') : ?>
                            <div class="box-body">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Tahun :</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="year-picker form-control" name="CARI[TAHUN]" placeholder="TAHUN" value="<?php echo @$CARI['TAHUN'];?>" id="CARI_TAHUN">
                                            <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                                        </div>
                                        <div class="col-sm-3">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Jenis Laporan :</label>
                                        <div class="col-sm-5">
                                            <select class="form-control" name="CARI[JENIS]">
                                                <option value="">-pilih Jenis-</option>
                                                <option value="1" <?php if(@$CARI['JENIS'] == 1){ echo 'selected';};?>>Khusus, Calon</option>
                                                <option value="2" <?php if(@$CARI['JENIS'] == 2){ echo 'selected';};?>>Khusus, Awal menjabat</option>
                                                <option value="3" <?php if(@$CARI['JENIS'] == 3){ echo 'selected';};?>>Khusus, Akhir menjabat</option>
                                                <option value="4" <?php if(@$CARI['JENIS'] == 4){ echo 'selected';};?>>Periodik tahunan</option>
                                            </select>
                                            <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                                        </div>
                                        <div class="col-sm-3">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Status Laporan :</label>
                                        <div class="col-sm-5">
                                            <select class="form-control" name="CARI[STATUS]">
                                                <option value="">-pilih Status-</option>
                                                <option value="0" <?php if(@$CARI['STATUS'] == '0'){ echo 'selected';};?>>Draft</option>
                                                <option value="1" <?php if(@$CARI['STATUS'] == 1){ echo 'selected';};?>>Masuk</option>
                                                <option value="2" <?php if(@$CARI['STATUS'] == 2){ echo 'selected';};?>>Perlu Perbaikan</option>
                                                <option value="3" <?php if(@$CARI['STATUS'] == 3){ echo 'selected';};?>>Terverifikasi Lengkap</option>
                                                <option value="4" <?php if(@$CARI['STATUS'] == 4){ echo 'selected';};?>>Diumumkan</option>
												<option value="5" <?php if(@$CARI['STATUS'] == 5){ echo 'selected';};?>>Terverifikasi Tidak Lengkap</option>
												<option value="6" <?php if(@$CARI['STATUS'] == 6){ echo 'selected';};?>>Diumumkan Tidak Lengkap</option>
												<option value="7" <?php if(@$CARI['STATUS'] == 7){ echo 'selected';};?>>Ditolak</option>
                                            </select>
                                            <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                                        </div>
                                        <div class="col-sm-3">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Entri Via :</label>
                                        <div class="col-sm-5">
                                            <select class="form-control" name="CARI[VIA]">
                                                <option value="">-pilih Via-</option>
                                                <option value="0" <?php if(@$CARI['VIA'] == '0'){ echo 'selected';};?>>WL, Online</option>
                                                <option value="1" <?php if(@$CARI['VIA'] == 1){ echo 'selected';};?>>Entry Hard Copy</option>
                                                <!-- <option value="2" <?php if(@$CARI['VIA'] == 2){ echo 'selected';};?>>Import Excel</option> -->
                                            </select>
                                            <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                                        </div>
                                        <div class="col-sm-3">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Lembaga :</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" name="CARI[LEMBAGA]" placeholder="LEMBAGA" value="<?php echo @$CARI['LEMBAGA'];?>" id="CARI_LEMBAGA">
                                            <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Cari :</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="CARI[NAMA]" placeholder="Nama" value="<?php echo @$CARI['NAMA'];?>" id="CARI_NAMA">
                                            <!-- <button type="button" class="btn btn-sm btn-default">...</button> -->
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                            <button type="button" id="btn-clear" class="btn btn-sm btn-default"> Clear</button>
                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                               
                              <!-- <br>
                              Jenis Entry <select name="CARI[JENIS_ENTRY]" id="CARI_JENIS_ENTRY">
                                        <option>-- Jenis Entry --</option>
                                        <option value="1">Form</option>
                                        <option value="2">Excel</option>
                                    </select>
                              <br>
                              Diinput Oleh <select name="CARI[DIINPUT_OLEH]" id="CARI_DIINPUT_OLEH">
                                        <option>-- Diinput Oleh --</option>
                                        <option value="1">Data Entry</option>
                                        <option value="2">PN</option>
                                    </select>
                              <br> -->
                    <?php endif; ?>
                        </form>
                    </div>
                    
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                        
                    </div>


                  

<!--                   <div class="box-tools">
                    <div class="input-group">

                    
                  </div> -->
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    <!-- <br>Ditampilkan di sisi user Data Entry -->
                <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <thead>
                        <tr>
                            <th width="30">No.</th>
                            <th>No. Agenda</th>
                            <th>PN</th>
                            <th>Tgl Lapor</th>
                            <th>Jabatan</th>
                            <!-- <th class="hidden-xs hidden-sm">Eselon</th> -->
                            <!-- <th class="hidden-xs hidden-sm">Unit Kerja</th> -->
                            <!-- <th class="hidden-xs hidden-sm">Jenis</th> -->
                            <!-- <th class="hidden-xs hidden-sm">Status</th> -->
                            <th>Status Laporan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 0 + $offset;
                            $start = $i + 1;

                            $aJenis = ['1' => 'Calon Penyelenggara Negara', '2' => 'Awal Menjabat', '3' => 'Akhir Menjabat', '4' => 'Sedang Menjabat'];
                            $aStatus = ['0' => 'Draft', '1' => 'Masuk', '2' => 'Perlu Perbaikan', '3' => 'Terverifikasi Lengkap', '4' => 'Diumumkan', '5' => 'Terverifikasi Tidak Lengkap', '6' => 'Diumumkan Tidak Lengkap', '7' => 'Ditolak'];
                            $abStatus = ['1' => 'Salah entry', '2' => 'Tidak lulus'];
                            foreach ($items as $item) {
                        $agenda = date('Y', strtotime($item->TGL_LAPOR)) . '/' . ($item->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $item->NIK . '/' . $item->ID_LHKPN;
                        ?>
                        <tr>
                            <td><?php echo ++$i; ?>.</td>
                            <td>
                                <a href="index.php/efill/lhkpnoffline/tracking/show/<?php echo substr(md5($item->ID_LHKPN), 5, 8) ?>"
                                   class="btn-tracking"><?php echo $agenda; ?></a>
                            </td>
                            <td><a href="index.php/efill/lhkpn/getInfoPn/<?php echo $item->ID_PN; ?>"
                                   onClick="return getPn(this);"><?php echo $item->NAMA; ?></a></td>
                            <td><?php echo date('d/m/Y', strtotime($item->TGL_LAPOR)); ?>
                                <?php if ($item->ENTRY_VIA == '0') {
                                    echo "&nbsp; <img src='" . base_url() . "img/online.png' title='Via Online'/>";
                                } else if ($item->ENTRY_VIA == '1') {
                                    echo "&nbsp; <img src='" . base_url() . "img/hard-copy.png' title='Via Hardcopy'/>";
                                    if ($this->session->userdata('IS_PN') != '1') {
                                        echo (!empty($item->USERNAME_ENTRI)) ? "<p>Ditugaskan oleh <b>" . $item->USERNAME_KOORD_ENTRY . "</b> ke <b>" . $item->USERNAME_ENTRI . "</b></p>" : '';
                                    }
                                } else if ($item->ENTRY_VIA == '2') {
                                    echo "&nbsp; <img src='" . base_url() . "img/excel-icon.png' title='Via Excel'/>";
                                    if ($this->session->userdata('IS_PN') != '1') {
                                        echo (!empty($item->USERNAME_ENTRI)) ? "<p>Ditugaskan oleh <b>" . $item->USERNAME_KOORD_ENTRY . "</b> ke <b>" . $item->USERNAME_ENTRI . "</b></p>" : '';
                                    }
                                }
                                ?>
                            </td>
                            <!-- <td><?php echo $item->JABATAN; ?> - <?php echo $item->INST_NAMA; ?></td> -->
                            <td>
                                <?php
                                if ($item->NAMA_JABATAN) {
                                    $j = explode(',', $item->NAMA_JABATAN);
                                    echo '<ul>';
                                    foreach ($j as $ja) {
                                        $jb = explode(':58:', $ja);
                                        $idjb = $jb[0];
                                        $statakhirjb = @$jb[1];
                                        $statakhirjbtext = @$jb[2];
                                        $statmutasijb = @$jb[3];
                                        if (@$jb[4] != '') {
                                            echo '<li>' . @$jb[4] . '</li>';
                                        }
                                    }
                                    echo '</ul>';
                                }
                                ?>
                            </td>
                            <!-- <td class="hidden-xs hidden-sm"><?php echo $item->ESELON; ?></td> -->
                            <!-- <td class="hidden-xs hidden-sm"><?php echo $item->UNIT_KERJA; ?></td> -->
                            <!-- <td class="hidden-xs hidden-sm"><?php echo $item->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus'; ?></td> -->
                            <!--                             <td class="hidden-xs hidden-sm"><?php
                            echo($item->IS_SUBMITED == '1' ? 'Diterima <small>' . date('d/m/Y', strtotime($item->SUBMITED_DATE)) . '</small></br>' : '');
                            echo($item->IS_VERIFIED == '1' ? 'Verifikasi <small>' . date('d/m/Y', strtotime($item->VERIFIED_DATE)) . '</small></br>' : '');
                            echo($item->IS_PUBLISHED == '1' ? 'Announcement <small>' . date('d/m/Y', strtotime($item->PUBLISHED_DATE)) . '</small></br>' : '');
                            ?>
                            </td> -->
                            <td class="text-center">
                                <?php
                                if ($item->STATUS == '2' && $item->ALASAN == '1' && $item->ENTRY_VIA != '0' && $isPN) {
                                    echo 'Sedang Diverifikasi';
                                } else {
                                    echo $aStatus[$item->STATUS];
                                    if ($item->STATUS == '2') {
                                        echo '(' . @$abStatus[@$item->ALASAN] . ')';
                                    }
                                }
                                ?>
                            </td>
                            <td width="120" nowrap="">
                                <input type="hidden" class="key" value="<?php echo substr(md5($item->ID_LHKPN),5,8);?>">
                                <?php // echo $item->ID_LHKPN;?>
                                <!-- <button type="button" class="btn btn-sm btn-default btn-detail" href="index.php/efill/lhkpn/detaillhkpn/<?php echo substr(md5($item->ID_LHKPN),5,8); ?>" title="Preview"><i class="fa fa-search-plus"></i></button> -->
                                <?php if($item->STATUS == '0' || $item->STATUS == '2'){ ?>
                                    <button type="button" class="btn btn-sm btn-success btn-edit" href="index.php/efill/lhkpn/entry/<?php echo substr(md5($item->ID_LHKPN),5,8);?>/edit" title="Proses"><i class="fa fa-pencil"></i></button>
                                    <?php if($item->ENTRY_VIA != '1'){ ?>
                                    <button type="button" class="btn btn-sm btn-danger btn-delete" href="index.php/efill/lhkpn/deletelhkpn/<?php echo substr(md5($item->ID_LHKPN),5,8);?>" title="Delete"><i class="fa fa-trash"></i></button>
                                    <?php } ?>
                                <?php }else{ ?>
                                <!-- <button type="button" class="btn btn-sm btn-default btn-detail" href="index.php/efill/lhkpn_view/detail/lhkpn/<?php echo substr(md5($item->ID_LHKPN),5,8); ?>" title="Preview"><i class="fa fa-search-plus"></i></button> -->
                                <button type="button" class="btn btn-sm btn-info btn-edit" href="index.php/efill/lhkpn/entry/<?php echo substr(md5($item->ID_LHKPN),5,8);?>/view" title="Preview"><i class="fa fa-search-plus"></i></button>
                                <?php } if ($this->session->userdata('IS_PN') !== '1') { ?>
                                    <!--<button type="button" class="btn btn-sm btn-warning btnGenPDF" href="index.php/efill/lhkpn_view/genpdf"><i class="fa fa-print"></i> Draft TBN</button>-->
                                    <!--<?php if($item->STATUS == '1') { ?>
                                        <button type="button" class="btn btn-sm btn-warning btnTTS" href="index.php/efill/lhkpn_view/tandaterima"><i class="fa fa-print"></i> TTS</button>
                                    <?php } else if($item->STATUS == '3') { ?>
                                        <button type="button" class="btn btn-sm btn-warning btnTT" href="index.php/efill/lhkpn_view/tandaterima"><i class="fa fa-print"></i> TT</button>
                                    <?php } ?>-->
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                                $end = $i;
                            }
                        ?>
                        <?php
                        // echo (count($items) == 0 ? '<tr><td colspan="7" class="items-null">Data tidak ditemukan!</td></tr>' : '');
                        ?>
                    </tbody>
                </table>
<!-- <br>Ditampilkan di sisi user PN</div>
<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
    <thead>
        <tr>
            <th width="30">No.</th>
            <th>Tgl Lapor</th>
            <th>Jenis</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $i = 0 + $offset;
            $start = $i + 1;
            foreach ($items as $item) {
        ?>
        <tr>
            <td><?php echo ++$i; ?></td>
            <td><?php echo date('d-m-Y',strtotime($item->TGL_LAPOR)); ?></td>
            <td><?php echo $item->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus'; ?></td>
            <td>Khusus(Calon Penyelenggara Negara, Awal Menjabat, Akhir Menjabat, tgl Pelaporan), Periodik (Sedang Menjabat, Tahun Pelaporan), </td>
<td><?php 
                    echo ($item->IS_SUBMITED == '1' ? 'Diterima <small>'.date('d-m-Y', strtotime($item->SUBMITED_DATE)).'</small></br>' : '');
                    echo ($item->IS_VERIFIED == '1' ? 'Verifikasi <small>'.date('d-m-Y', strtotime($item->VERIFIED_DATE)).'</small></br>' : '');
                    echo ($item->IS_PUBLISHED == '1' ? 'Annauncement <small>'.date('d-m-Y', strtotime($item->PUBLISHED_DATE)).'</small></br>' : '');
                ?>
            </td>
            <td width="120" nowrap="">
                <button type="button" class="btn btn-sm btn-default btn-detail"
                href="index.php/efill/lhkpn/detaillhkpn/<?php echo substr(md5($item->ID_LHKPN),5,8); ?>" title="Preview"><i
                class="fa fa-search-plus"></i></button>
                <button type="button" class="btn btn-sm btn-default btn-edit" href="index.php/efill/lhkpn/editlhkpn/<?php echo substr(md5($item->ID_LHKPN),5,8);?>" title="Edit"><i
                class="fa fa-pencil"></i></button>
                <button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/efill/lhkpn/deletelhkpn/<?php echo substr(md5($item->ID_LHKPN),5,8);?>" title="Delete"><i
                class="fa fa-trash" style="color:red;"></i></button>
            </td>
        </tr>
        <?php
                $end = $i;
            }
        ?>
    </tbody>
</table> -->

                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php
                        if($total_rows){
                    ?>
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="datatable-1_info">Showing <?php echo $start; ?> to <?php echo $end; ?>
                            of <?php echo $total_rows; ?> entries
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                    <div class="col-sm-6 text-right">
                        <div class="dataTables_paginate paging_bootstrap">
                            <?php echo $pagination; ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

<script type="text/javascript">
        jQuery(document).ready(function() {
            $('.year-picker').datepicker({
                orientation: "left",
                format: 'yyyy',
                viewMode: "years",
                minViewMode: "years",
                autoclose: true
            });

            $('.date-picker').datepicker({
                orientation: "left",
                format: 'dd/mm/yyyy',
                autoclose: true
            });    
        });
</script>

<script language="javascript">
    $(document).ready(function() {
        $(".pagination").find("a").click(function() {
            var url = $(this).attr('href');
            window.location.hash = url;
            ng.LoadAjaxContentPost(url, $('#ajaxFormCari'));
            return false;
        });
        $("#ajaxFormCari").submit(function(e) {
            var url = $(this).attr('action');
            ng.LoadAjaxContentPost(url, $(this));
            return false;
        });
        $('#btn-clear').click(function(event) {
            $('#ajaxFormCari').find('input:text').val('');
            $('#ajaxFormCari').find('select').val('');
            $('#ajaxFormCari').trigger('submit');
        });
        $(".btn-detail").click(function() {
            url = $(this).attr('href');
            ng.LoadAjaxContent(url, '');
            return false;
            $.post(url, function(html) {
                OpenModalBox('Detail Lhkpn', html, '', 'large');
            });
            return false;
        })
        $("#btn-add").click(function() {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Pengisian LHKPN', html, '', 'standart');
                // ng.formProcess($("#ajaxFormAdd"), 'add', 'index.php/efill/lhkpn');
            });
            return false;
        });
        $('.btn-tracking').click(function(e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Detail Tracking LHKPN', html, '', 'large');
            });
            return false;
        });
        $("#btnImportExcel").click(function() {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Import Excel', html, '');
                ng.formProcess($("#ajaxFormAdd"), 'add', 'index.php/efill/lhkpn');
            });
            return false;
        });
        $('.btn-edit').click(function(e) {
            var url = $(this).attr('href');
            ng.LoadAjaxContent(url, '');
            return false;
        });
        $('.btn-delete').click(function(e) {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Delete Lhkpn', html, '', 'standart');
                // ng.formProcess($("#ajaxFormDelete"), 'delete', 'index.php/efill/lhkpn');
            });
            return false;
        });
        $('.btnGenPDF').click(function(){
            key = $(this).parents('td').children('.key').val();
            url = $(this).attr('href')+'/'+key;
            ng.exportTo('pdf', url, 'Cetak Draft TBN LHKPN');
        });

        $('.btnTTS').click(function(){
            key = $(this).parents('td').children('.key').val();
            url = $(this).attr('href')+'/'+key+'/tts';
            ng.exportTo('pdf', url, 'Cetak Tanda Terima Sementara');
        });

        $('.btnTT').click(function(){
            key = $(this).parents('td').children('.key').val();
            url = $(this).attr('href')+'/'+key+'/tt';
            ng.exportTo('pdf', url, 'Cetak Tanda Terima');
        });
    });

    function getPn(ele){
        var url = $(ele).attr('href');
        $.get(url, function(html){
            OpenModalBox('Detail PN', html, '', 'standart');
        });

        return false;
    }
    //DataTables
    $(function() {
        $('#dt_completeNEW').dataTable({
            "bPaginate": false,
            "bLengthChange": true,
            "bFilter": false,
            "bSort": true,
            "bInfo": false,
            "bAutoWidth": true,
            "scrollY": '50vh',
            "scrollCollapse": true,
        });
    });
</script>

<style>
    td .btn {
        margin: 0px;
    }
</style>


