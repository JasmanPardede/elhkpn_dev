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
 * @package Views/efill/penugasan
*/
?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <i class="fa <?php echo $icon;?>"></i> <?php echo $title;?>
            <small><?php echo $title;?></small>
          </h1>
         <?php echo $breadcrumb;?>
        </section>

		
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                    <!--<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <button type="button" id="btnPrintEXCEL"
                            class="btn btn-sm btn-default">
                            <i class="fa fa-file-excel-o"></i>
                        </button>
                    </div>-->
                  <!-- <h3 class="box-title">Bordered Table</h3> -->
                  <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <!-- <button class="btn btn-sm btn-default" id="btn-add" href="<?php echo $urlEdit;?>"><i class="fa fa-plus"></i> Tambah Data</button> -->
                  </div>
                    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                        <form method="post" class='form-horizontal' id="ajaxFormCari" action="<?php echo $thisPageUrl;?>">
                            <input type="hidden" name="id" value="<?php echo @$id; ?>" />
                            <div class="box-body">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Status :</label>
                                        <div class="col-sm-5">
                                            <select name="CARI[STATUS]" class="form-control" id="CARI_STATUS">
                                                <option value="-99">-- Status --</option>
                                                <option value="1" <?php echo @$CARI['STATUS']==1?'selected':'';?>>Belum Ditugaskan</option>
                                                <option value="2" <?php echo @$CARI['STATUS']==2?'selected':'';?>>Sudah Ditugaskan</option>
                                                <option value="3" <?php echo @$CARI['STATUS']==3?'selected':'';?>>Sedang/Sudah dientry</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Petugas :</label>
                                        <div class="col-sm-5">
                                            <!-- <select name="CARI[PETUGAS]" id="CARI_PETUGAS">
                                            </select> -->
                                            <input type="text" class="form-control" name="CARI[PETUGAS]" id="CARI_PETUGAS" placeholder="Petugas" value="<?php echo @$CARI['PETUGAS'];?>">
                                        </div>
                                        <div class="col-sm-3">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Tahun Terima :</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="CARI[TAHUN]" class="form-control input-sm year-picker" id="CARI_TAHUN" value="<?=@$CARI['TAHUN']?>" placeholder="Tahun Terima">
<!--                                             <select name="CARI[TAHUN]" class="form-control" id="CARI_TAHUN">
                                                <option value="99">-- Pilih Tahun --</option>
                                                <?php
                                                $tahun=date('Y');
                                                if (@$CARI['TAHUN'] != '') {
                                                    $tahun = $CARI['TAHUN'];
                                                }
                                                for ($i=date('Y'); $i>date('Y')-10; $i--) {
                                                    $selected = $i==$tahun?'selected':'';
                                                    ?>
                                                    <option value="<?php echo $i;?>" <?php echo $selected;?>><?php echo $i;?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select> -->
                                        </div>
                                        <div class="col-sm-3">
                                            
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">No Agenda :</label>
                                        <div class="col-sm-5">
                                           <input type="text" class="form-control input-sm" placeholder="xxxx/x/xxxxxxxxx/xxx" name="CARI[KODE]" value="<?php echo @$CARI['KODE'];?>" id="CARI_KODE"/>
                                        </div>
                                        <div class="col-sm-3">
                                            
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Cari :</label>
                                        <div class="col-sm-5">
                                            <select name="CARI[BY]" id="CARI_BY" class="form-control input-sm" style="width: 75px; float: left;">
                                                <option <?php echo (@$CARI['BY'] == 'NIK') ? 'selected' : ''; ?> value="NIK">NIK</option>
                                                <option <?php echo (@$CARI['BY'] == 'NAMA') ? 'selected' : ''; ?> value="NAMA">Nama</option>
                                                <!-- <option <?php echo (@$CARI['BY'] == 'PN') ? 'selected' : ''; ?> value="PN">PN</option> -->
                                            </select>
                                            <input type="text" style="width: 190px;" class="form-control input-sm pull-right" placeholder="Search" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT'];?>" id="CARI_TEXT"/>
                                        </div>                                       
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-3 col-sm-offset-4">
                                        <button type="submit" class="btn btn-sm btn-default" id="btn-cari"><i class="fa fa-search"></i></button>
                                        <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#CARI_KODE').val(''); $('#CARI_TEXT').val(''); $('#CARI_BULAN').val(''); $('#CARI_TAHUN').val(''); $('#CARI_STATUS').val(''); $('#CARI_PETUGAS').select2('val', ''); $('#CARI_BY').val(''); $('#ajaxFormCari').trigger('submit');">Clear</button>
                                    </div>
                                </div>                                        
                            </div>
                        </form>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">

                <strong class="pull-right"><span id="jml">0</span> Dokumen yang dipilih!</strong>
                <table id="dt_completeNEW" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <thead>
                        <tr>
                            <th width="30" class="showWhenEditable"><input type="checkbox" onClick="chk_all(this);" /></th>
                            <th width="30">No.</th>
                            <th>Tgl Terima</th>
                            <th width="300">PN / WL</th>
                            <th>Jenis Dokumen</th>
                            <th>Agenda</th>
<!--							<th>Penugasan</th>-->
                            <!-- <th>Username</th>
                            <th>Tanggal Penugasan</th>
							<th>Due Date</th>
                            <th>Keterangan</th> -->
							<th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($total_rows) { ?>
                            <?php

                                $i = 0 + $offset;
                                $start = $i + 1;
                                $aId = @explode(',', $id);
                                foreach ($items as $item) {
                                    $agenda = $item->TAHUN_PELAPORAN.'/'.($item->JENIS_LAPORAN == '4' ? 'R' : 'K').'/'.$item->NIK.'/'.$item->ID_LHKPN;
                            ?>
                            <tr>
                                <td class="showWhenEditable">
                                <?php if (@$item->IS_READ != '1') {
                                    ?>
                                    <?php echo (in_array($item->ID_PENERIMAAN, $aId) ? '<input class="chk" type="checkbox" checked="checked" value="'.$item->ID_PENERIMAAN.'" onclick="chk(this);" style="display: none;" />' : '<input class="chk" type="checkbox" value="'.$item->ID_PENERIMAAN.'" onclick="chk(this);" />') ?>
                                    <?php
                                } ?>
                                </td>
                                <td><?php echo ++$i; ?>.</td>
                                <td>
                                    <?php echo date('d/m/Y',strtotime($item->TANGGAL_PENERIMAAN)); ?> <br>
                                    Oleh : <?php echo $item->user_penerima; ?>
                                </td>
                                <td>
                                    <table style="background: transparent;">
                                        <tbody>
                                            <tr>
                                                <th width="60px" valign='top'>NIK</th>
                                                <td valign='top'><span style="margin-right: 10px;">:</span></td>
                                                <td class="nik" valign='top'><?php echo $item->NIK; ?></td>
                                            </tr>
                                            <tr>
                                                <th valign='top'>NAMA</th>
                                                <td valign='top'><span style="margin-right: 10px;">:</span></td>
                                                <td class="nama" valign='top'><?php echo $item->NAMA; ?></td>
                                                <td class="agenda" style="display: none;"><?php echo $item->TAHUN_PELAPORAN.'/'.($item->JENIS_LAPORAN == '4' ? 'R' : 'K').'/'.$item->NIK.'/'.$item->ID_LHKPN ?></td>
                                                <td class="lhkpn" style="display: none;"><?php echo substr(md5($item->ID_LHKPN),5,8); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <table style="background: transparent;">
                                        <tbody>
                                            <?php if ($item->JENIS_LAPORAN <> '4') { ?>
                                            <tr>
                                                <th width="110px" valign='top'>
                                                    LHKPN Khusus
                                                </th>
                                                <td valign='top'>
                                                    <span style="margin-right: 10px;">:</span>
                                                    <?php
                                                        if ($item->JENIS_LAPORAN == '1') {
                                                            echo 'Calon Penyelenggara Negara';
                                                        } else if ($item->JENIS_LAPORAN == '2') {
                                                            echo 'Awal Menjabat';
                                                        } else if ($item->JENIS_LAPORAN == '3') {
                                                            echo 'Akhir Menjabat';
                                                        } else {}
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php } else { ?>
                                            <tr>
                                                <th width="110px" valign='top'>LHKPN Periodik </th>
                                                <td valign='top'>
                                                    <span style="margin-right: 10px;">:</span>
                                                    <?php
                                                        if ($item->TAHUN_PELAPORAN !== '0') {
                                                            echo $item->TAHUN_PELAPORAN;
                                                         } else {
                                                            echo '-';
                                                         }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <tr>
                                                <th valign='top'>Jenis Dokumen</th>
                                                <td valign='top'><span style="margin-right: 10px;">:</span> <?php echo $item->JENIS_DOKUMEN ?></td>
                                            </tr>
                                            <tr>
                                                <th valign='top'>Melalui</th>
                                                <td valign='top'><span style="margin-right: 10px;">:</span> <?php echo $item->MELALUI ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <?php //echo $item->JENIS_DOKUMEN; ?>
                                    <?php //echo $item->MELALUI; ?>
                                    <?php //echo substr(md5($item->ID_PENERIMAAN),5,8); ?>
                                </td>
                                <td class="text-center">
                                    <a href="index.php/efill/lhkpnoffline/tracking/show/<?php echo substr(md5($item->ID_LHKPN),5,8);?>" class="btn-tracking"><?php echo $agenda; ?></a>
                                </td>
    <!--                            <td>-->
    <!--                                <table style="background: transparent;">-->
    <!--                                    <tbody>-->
    <!--                                        <tr>-->
    <!--                                            <th width="70px" valign='top'>Petugas</th>-->
    <!--                                            <td><span style="margin-right: 10px;" valign='top'>:</span></td>-->
    <!--                                            <td valign='top'>--><?php //echo $item->USERNAME; ?><!--</td>-->
    <!--                                        </tr>-->
    <!--                                        <tr>-->
    <!--                                            <th valign='top'>Tanggal Penugasan</th>-->
    <!--                                            <td valign='top'><span style="margin-right: 10px;">:</span></td>-->
    <!--                                            <td valign='top'>-->
    <!--                                                --><?php //
    //                                                if($item->ID_TUGAS){
    //                                                    echo date('d-m-Y',strtotime($item->TANGGAL_PENUGASAN));
    //                                                }
    //                                                ?>
    <!--                                            </td>-->
    <!--                                        </tr>-->
    <!--                                    </tbody>-->
    <!--                                </table>-->
    <!--                            </td>-->
                               <!-- <td><?php// echo $item->NAMA; ?> (<?php //echo $item->NIK; ?>)<br> -->
                                    <?php 
                                    // switch ($item->JENIS_LAPORAN) {
                                    //     case '1':
                                    //         echo 'Calon Penyelenggara Negara ('.date('d-m-Y',strtotime($item->TANGGAL_PELAPORAN)).')';
                                    //         break;
                                    //     case '2':
                                    //         echo 'Awal Menjabat ('.date('d-m-Y',strtotime($item->TANGGAL_PELAPORAN)).')';
                                    //         break;
                                    //     case '3':
                                    //         echo 'Akhir Menjabat ('.date('d-m-Y',strtotime($item->TANGGAL_PELAPORAN)).')';
                                    //         break;
                                    //     case '4':
                                    //         echo 'Sedang Menjabat ('.$item->TAHUN_PELAPORAN.')';
                                    //         break;
                                        
                                    //     default:
                                    //         break;
                                    // }
                                    ?>
                                <!-- </td> -->
                                <!-- <td><?php //echo date('d-m-Y', strtotime($item->TANGGAL_PENERIMAAN)); ?></td>
                                <td></td>
                                <td>
                                
                                </td>
                                <td> -->
                                <?php 
                                // if($item->ID_TUGAS){
                                //     echo date('d-m-Y',strtotime($item->DUE_DATE)); 
                                // }
                                ?>
                                <!-- </td> -->
                               <!--  <td><?php// echo $item->KETERANGAN; ?></td> -->
                                <td><?php 
                                switch ($item->STAT) {
                                    case '1':
                                        echo 'Belum Ditugaskan';
                                        break;
                                    case '2':
                                        echo 'Sudah Ditugaskan</br><strong>Petugas :</strong> '.$item->USERNAME;
                                        break;
                                    case '3':
                                        echo 'Selesai';
                                        break;
                                }
                                ?></td>
                                <!-- <td width="120" nowrap>
                                    <?php
                                    if($item->ID_TUGAS && @$item->IS_READ != '1'){
                                    ?>
                                    <button type="button" class="btn btn-sm btn-default btn-detail"
                                    href="<?php echo $urlEdit.'/'.$item->ID_TUGAS.'/detail'; ?>" title="Preview"><i
                                    class="fa fa-search-plus"></i></button>
                                    <button type="button" class="btn btn-sm btn-default btn-edit" href="<?php echo $urlEdit.'/'.$item->ID_TUGAS; ?>" title="Edit"><i
                                    class="fa fa-pencil"></i></button>
                                    <button type="button" class="btn btn-sm btn-default btn-delete" href="<?php echo $urlEdit.'/'.$item->ID_TUGAS.'/delete'; ?>" title="Delete"><i
                                    class="fa fa-trash" style="color:red;"></i></button>
                                    <?php
                                    }
                                    ?>
                                </td> -->
                            </tr>
                            <?php
                                    $end = $i;
                                }
                            ?>
                        <?php } else { ?>
                            <!-- <tr id="not-found">
                                <td colspan="7" align="center"><strong>Data not Found</strong></td>
                            </tr> -->
                        <?php } ?>
                    </tbody>
<!--                    <tfoot class="showWhenEditable">-->
<!--                        <tr>-->
<!--                           <td colspan="6">-->
<!--                               <div id="wrapperEditCek" class="form-horizontal">-->
<!--                                   <input type="hidden" readonly="readonly" id="slcted">-->
<!--                                   <button type="button" class="btn btn-sm btn-default btn-edit_check" href="--><?php //echo $urlEdit.'/editcheck'; ?><!--" title="Edit Penugasan">Edit Penugasan</button>-->
<!--                               </div>-->
<!--                           </td> -->
<!--                        </tr>-->
<!--                    </tfoot>-->
                </table>
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
                    <div class="col-sm-6 text-right">
                        <div class="dataTables_paginate paging_bootstrap">
                            <?php echo $pagination; ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <button style="margin-top: 10px;" type="button" class="btn btn-warning" onclick="f_bast();"><i class="fa fa-plus"></i> Daftar Penugasan</button>
                <table id="con-bast" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <thead>
                    <tr>
                        <th width="30">No.</th>
                        <th width="100px">NIK</th>
                        <th>Nama</th>
                        <th width="200px" align="center">No Agenda</th>
                        <th width="100px">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1; if(isset($item_selected)){ foreach(@$item_selected as $row): ?>
                        <tr>
                            <td align="center"><?php echo $i; ?></td>
                            <td><?php echo $row->NIK?></td>
                            <td><?php echo $row->NAMA?></td>
                            <td><a href="index.php/efill/lhkpnoffline/tracking/show/<?php echo substr(md5($row->ID_LHKPN),5,8);?>" onclick="return tracking(this)"><?php echo $item->TAHUN_PELAPORAN.'/'.($item->JENIS_LAPORAN == '4' ? 'R' : 'K').'/'.$item->NIK.'/'.$item->ID_LHKPN ?></a></td>
                            <td align="center"><button type="button" class="btn btn-danger" onClick="f_batal(this);" data-id="<?php echo $row->ID_PENERIMAAN ?>" ><i class="fa fa-trash"></i></button> </td>
                        </tr>
                        <?php $i++; endforeach; } ?>
                    <?php if(@$id == ''){ ?>
                        <!-- <tr id="not-found">
                            <td colspan="5" align="center"><strong>Data not Found</strong></td>
                        </tr> -->
                    <?php } ?>
                    </tbody>
                </table>

                <button type="button" class="btn btn-sm btn-success btn-edit_check" href="<?php echo $urlEdit.'/editcheck'; ?>" title="Edit Penugasan"><i class="fa fa-edit"></i> Penugasan</button>

                <form method="post" name="ajaxFormPenugasan" id="ajaxFormPenugasan" action="index.php/efill/lhkpnoffline/save/penugasan">
                    <input type="hidden" name="id" value="<?php echo @$id; ?>" />
                    <div id="wrapperFormPenugasan" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-4 control-label"><?php echo @$CARI['STATUS']==2?'Ganti ':'';?> Petugas <font color='red'>*</font>:</label>
                            <div class="col-sm-4">
                                <input type="text" name="PETUGAS" id="PETUGAS" required placeholder="Petugas" style="width: 200px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Tanggal Penugasan <font color='red'>*</font>:</label>
                            <div class="col-sm-4">
                                <input type="text" name="TANGGAL_PENUGASAN" value="<?php echo date('d/m/Y');?>" required placeholder='DD/MM/YYYY' class="date-picker form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Due Date <font color='red'>*</font>:</label>
                            <div class="col-sm-4">
                                <input type="text" name="DUE_DATE" value="<?php echo date('d/m/Y');?>" required placeholder='DD/MM/YYYY' class="date-picker form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Keterangan:</label>
                            <div class="col-sm-4">
                                <textarea name="KETERANGAN" class='form-control'></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label"></label>
                            <div class="col-sm-8">
                                <input type="hidden" name="status" value="<?php echo @$CARI['STATUS'];?>">
                                <input type="hidden" name="act" value="doinsert">
                                <input type="submit" name="" value="Simpan" class="btn btn-sm btn-primary">
                                <input type="reset" name="" value="Batal" class="btn btn-sm btn-danger">
                            </div>
                        </div>
                    </div>
                </form>				
			</div><!-- /.box-body -->
             <!--    <div class="box-footer clearfix">
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
                    <div class="col-sm-6 text-right">
                        <div class="dataTables_paginate paging_bootstrap">
                            <?php echo $pagination; ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div> -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

<script language="javascript">
    var idChk = [];
    var jml = 0;

    function chk_all(ele)
    {
        if($(ele).is(':checked')){
            $('.chk').prop('checked', true);
        }else{
            $('.chk').prop('checked', false);
        }

        $('.chk:visible').each(function(){
            chk(this);
        });
    }

    function chk(ele){
        var val = $(ele).val();
        idChk.push(val);
    }

    function f_bast(){
        $('.chk').each(function(){
            var val = $(this).val();
            if($(this).is(':checked') && $(this).is(':visible')){
                jml++;
                $(this).hide();
                var table = $(this).closest('tr');
                var nik = $('.nik', table).text();
                var nama = $('.nama', table).text();
                var agenda = $('.agenda', table).text();
                var lhkpn = $('.lhkpn', table).text();

                $('#con-bast tbody').append('<tr><td align="center">'+jml+'</td><td>'+nik+'</td><td>'+nama+'</td><td><a href="index.php/efill/lhkpnoffline/tracking/show/'+lhkpn+'" onclick="return tracking(this);">'+agenda+'</a></td><td align="center"><button class="btn btn-danger" data-id="'+val+'" type="button" onClick="f_batal(this);"> <i class="fa fa-trash"></i></button></td></tr>');
            }
        })

        

        count();
    }

    function f_batal(ele) {
        var id = $(ele).attr('data-id');
        var index = idChk.indexOf(id);    // <-- Not supported in <IE9
        if (index !== -1) {
            idChk.splice(index, 1);
        }

        $(ele).closest('tr').remove();
        $('.chk[value="'+id+'"]').show();
        $('.chk[value="'+id+'"]').prop('checked', false);

        count();
    }

    function count(){
        var jml = parseInt($('.chk:checked').length);
        $('#jml').text(jml);
        if(jml > 0){
            $('#con-bast #not-found').hide();
        }else{
            $('#con-bast #not-found').show();
        }

        var tmo = $('#ajaxFormCari input[name="id"]').val(idChk.join());
    }

    function tracking(ele)
    {
        url = $(ele).attr('href');
        $.post(url, function (html) {
            OpenModalBox('Detail Tracking LHKPN', html, '', 'large');
        });
        return false;
    }

    $(document).ready(function () {
        var tmo = $('#ajaxFormCari input[name="id"]').val();
        if(tmo != '') {
            idChk = tmo.split(',');
        }

        count();
<?php
// echo 'alert("'.@$CARI['STATUS'].'");';
    echo '$("#wrapperFormPenugasan").show();';
    echo '$(".showWhenEditable").show();';
// if(@$CARI['STATUS']==1 || @$CARI['STATUS']==2){
//     echo '$("#wrapperFormPenugasan").show();';
//     echo '$(".showWhenEditable").show();';
// }else{
//     echo '$("#wrapperFormPenugasan").hide();';
//     echo '$(".showWhenEditable").hide();';
// }
?>

$('.year-picker').datepicker({
    orientation: "left",
    format: 'yyyy',
    viewMode: "years",
    minViewMode: "years",
    autoclose: true
});   

$('#ajaxFormPenugasan').submit(function(){
    $('#ajaxFormPenugasan input[name="id"]').val(idChk.join());

    if(idChk.length != 0) {
        var formObj = $(this);
        var formURL = formObj.attr("action");
        var formData = new FormData(this);
        $.ajax({
            url : formURL,
            type : 'POST',
            data : formData,
            mimeType : "multipart/form-data",
            contentType : false,
            cache : false,
            processData : false,
            success : function(data, textStatus, jqXHR) {
                msg = {
                   success : 'Data Berhasil Disimpan!',
                   error : 'Data Gagal Disimpan!'
                };
                if (data == 0) {
                    alertify.error(msg.error);
                } else {
                    alertify.success(msg.success);
                }
                url = 'index.php/efill/lhkpnoffline/index/penugasan/';
                ng.LoadAjaxContent(url);
            },
            error : function(jqXHR, textStatus, errorThrown) {
                alertify.error(msg.error + "\n" + jqXHR.statusText);
            }
        });
        return false;
    }else{
        alertify.error('Silahkan Pilih Penerimaan');
        return false;
    }
    e.preventDefault(); //Prevent Default action.
});

        $('#CARI_STATUS').change(function(){
            if($(this).val()==1){
               $('#CARI_PETUGAS').val('-99');
            }
        });

        $('#CEK_ALL').change(function(){
            $('.CEK_PENERIMAAN').prop('checked', $(this).prop("checked"));
        });

        $(".pagination").find("a").click(function () {
            var url = $(this).attr('href');
            window.location.hash = url;
            ng.LoadAjaxContentPost(url, $('#ajaxFormCari'));
            return false;
        }); 

		$(".breadcrumb").find("a").click(function () {
			var url = $(this).attr('href');
			window.location.hash = url;
			ng.LoadAjaxContent(url);
			return false;
		});	
		
		$("#ajaxFormCari").submit(function (e) {
			var url = $(this).attr('action');
			ng.LoadAjaxContentPost(url, $(this));
			return false;
		});

        $(".btn-detail").click(function () {
           url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Detail Penugasan', html, '');
            });            
            return false;
        })

        $('.btn-tracking').click(function(e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Detail Tracking LHKPN', html, '', 'large');
            });
            return false;
        });

        $("#btn-add").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah Penugasan', html, '', 'large');
                // ng.formProcess($("#ajaxFormAdd"), 'add', '<?php echo $thisPageUrl;?>');
            });            
            return false;              
        });
        // ctrl + a
        $(document).on('keydown', function(e){
            if(e.ctrlKey && e.which === 65 || e.which === 97){
                e.preventDefault();
                $('#btn-add').trigger('click');
                return false;
            }
        });

        $('.btn-edit').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Edit Penugasan', html, '', 'standart');
                // ng.formProcess($("#ajaxFormEdit"), 'edit', '<?php echo $thisPageUrl;?>');
            });            
            return false;
        });

        $('.btn-edit_check').click(function (e) {
            url = $(this).attr('href');
            data = { CEK_PENERIMAAN : idChk.join() };
            $.post(url,data, function (html) {
                OpenModalBox('Edit Beberapa Panugasan', html, '', 'standart');
                // ng.formProcess($("#ajaxFormEdit"), 'edit', '<?php echo $thisPageUrl;?>');
            });            
            return false;
        });

        $('.btn-delete').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Delete Penugasan', html, '', 'standart');
                // ng.formProcess($("#ajaxFormDelete"), 'delete', '<?php echo $thisPageUrl;?>');
            });            
            return false;
        });

        $('.date-picker').datepicker({
                orientation: "left",
                format: 'dd/mm/yyyy',
                autoclose: true
        });

        $('#PETUGAS').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/share/reff/getUser/'.$role)?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term
                    };
                },
                results: function (data, page) {
                    return { results: data.item };
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?=base_url('index.php/share/reff/getUser/'.$role)?>/"+id, {
                        dataType: "json"
                    }).done(function(data) { callback(data[0]); });
                }
            },
            formatResult: function (state) {
                if(state.id == '0'){
                    return '-- Pilih Petugas --';
                }else{
                    return '<strong>'+state.role+'</strong> : '+state.name;
                }
            },
            formatSelection:  function (state) {
                if(state.id == '0'){
                    return '-- Pilih Petugas --';
                }else{
                    return '<strong>'+state.role+'</strong> : '+state.name;
                }
            }
        });

        $('#CARI_PETUGAS').select2({
            minimumInputLength: 0,
            ajax: {
                url: "<?=base_url('index.php/share/reff/getUser/'.$role)?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term
                    };
                },
                results: function (data, page) {
                    return { results: data.item };
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?=base_url('index.php/share/reff/getUser/'.$role)?>/"+id, {
                        dataType: "json"
                    }).done(function(data) { callback(data[0]); });
                }
            },
            formatResult: function (state) {
                if(state.id == '0'){
                    return '-- Pilih Petugas --';
                }else{
                    return '<strong>'+state.role+'</strong> : '+state.name;
                }
            },
            formatSelection:  function (state) {
                if(state.id == '0'){
                    return '-- Pilih Petugas --';
                }else{
                    return '<strong>'+state.role+'</strong> : '+state.name;
                }
            }
        });
        $('#btnPrintEXCEL').click(function() {
            var url = '<?php echo $linkCetak;?>/excel';
            ng.exportTo('excel', url);
        });

        $('#CARI_KODE').blur(function (e) {
            var jumlah = substr_count($(this).val(),'/');
            if (jumlah != '3') 
            {
                alertify.error('Nomor agenda tidak valid!!, <br/>Tidak melakukan filter berdasar agenda!!');
            };
        });

    });

    function substr_count(string,substring,start,length)
    {
         var c = 0;
         if(start) { string = string.substr(start); }
         if(length) { string = string.substr(0,length); }
         for (var i=0;i<string.length;i++)
         {
          if(substring == string.substr(i,substring.length))
          c++;
         }
         return c;
    }
    
</script>

<style>
    td .btn {
        margin: 0px;
    }
</style>
