<div class="box-header with-border portlet-header title-pribadi">
    <h5 class="">"Harta Bergerak berupa perabotan rumah Tangga, barang elektronik, perhiasan &amp; logam/batu mulia, barang seni/antik, persediaan dan harta bergerak lainnya"</h5>
</div>
<br>&nbsp;&nbsp;&nbsp;<button class="btn btn-info aksi-hide" id="klarifikasiDataHBL" href="index.php/eaudit/klarifikasi/update_harta_bergerak_lain/<?php echo $new_id_lhkpn;?>/0/new"><span class="fa fa-plus"></span> Tambah</button><br><br>
<div class="box-body" id="wrapperHartaBergerak2">
<div id="tombolTetapHb2"></div><br><br>
    <table id="TableHBL" class="table borderless">
        <thead class="table-header">
            <tr>
                <th width="15px"><input type="checkbox" onClick="chk_all_hb2(this);" /></th>
                <th width="15px">NO</th>
                <th width="80px">STATUS</th>
                <th>URAIAN</th>
                <th>KEPEMILIKAN</th>
                <th>NILAI PEROLEHAN</th>
                <th>NILAI PELAPORAN/ KLARIFIKASI</th>
                <th class="aksi-hide">AKSI</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $survey = 'ok';
            $i = 0;
            $totalHartaBergerak2 = 0;
            $stat = ['1'=>'Tetap','2'=>'Ubah','3'=>'Baru'];
            if ($lhkpn_harta_bergerak_lain) {
            foreach ($lhkpn_harta_bergerak_lain as $hartabergerak2) {
                $totalHartaBergerak2 += str_replace('.', '', $hartabergerak2->NILAI_PELAPORAN);
                if ($i%2 == 0) {
                    $bgcolor = 'bgcolor="#f9f9f9"';
                }
                else{
                    $bgcolor = '';
                }
                if ($hartabergerak2->IS_PELEPASAN == '1') {
                    $style_pelepasan = 'style="background-color: rgb(128, 128, 128); color: rgb(255, 255, 255);"';
                }else{
                    $style_pelepasan = null;
                }
                ?>
                <tr <?php echo $bgcolor; ?> <?= $style_pelepasan ?>  >
                    <td><?php if($hartabergerak2->STATUS==3 && $hartabergerak2->ID_HARTA!=''){ ?>
                        <input class="chk-hb2" type="checkbox" onclick="chkHb2(this);" value="<?= $hartabergerak2->ID ?>" name="chk-hb2"><?php }else{ ?><input type="checkbox" disabled="true" value="" name="chk-hb2"> <?php } ?></td>
                    <td><?php echo ++$i; ?>.</td>
                    <td><?php 
                        $data_label = array();
                        $data_label[1] = '<label class="label label-primary">Tetap</label>';
                        $data_label[2] = '<label class="label label-success">Ubah</label>';
                        $data_label[3] = '<label class="label label-success">Baru</label>';
                        if ($hartabergerak2->IS_PELEPASAN == '1') {
                            echo '<label class="label label-danger pelepasan">Lepas</label>';
                        } else {
                            if($hartabergerak2->STATUS==3 && $hartabergerak2->ID_HARTA!=''){
                                echo '<label class="label label-warning">Lama</label>';
                            }else{
                                echo $data_label[$hartabergerak2->STATUS];
                            }
                            
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        $uraian = "
					<table class='table-child table-condensed'>
						 <tr>
						    <td><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $hartabergerak2->HBL_NAMA . "</td>
						 </tr>
						 <tr>
						    <td><b>Jumlah</b></td>
                            <td>:</td>
                            <td>" . $hartabergerak2->JUMLAH . "</td>
						 </tr>
						 <tr>
						    <td><b>Satuan</b></td>
                            <td>:</td>
                            <td>" . $hartabergerak2->SATUAN . "</td>
						 </tr>
						 <tr>
						    <td><b>Ket Lainnya</b></td>
                            <td>:</td>
                            <td>" . $hartabergerak2->KETERANGAN . "</td>
						 </tr>
					</table>
				";
                       echo $uraian;
                        ?>
                    </td>
                    
                    <td>
                        <div class="col-sm-12"><b>Asal Usul Harta : </b>
                            <?php
                            $exp = explode(',', $hartabergerak2->ASAL_USUL);
                            $text = '';
                            foreach ($exp as $key) {
                                foreach ($asalusul as $au) {
                                    if ($au->ID_ASAL_USUL == $key) {
                                        $text .= ($au->IS_OTHER === '1' ? '<font>' . $au->ASAL_USUL . '</font>' : $au->ASAL_USUL) . '&nbsp;,&nbsp;&nbsp;';
                                    }
                                }
                            }
                            $rinci = substr($text, 0, -19);
                            echo $rinci;
                            ?>
                        </div>
                        
                    </td>
                   
                    <td>
                        <div align="right">Rp. <?php echo number_format($hartabergerak2->NILAI_PEROLEHAN, 0, '', '.'); ?></div>
                    </td>
                    <td>
                        <div align="right">
                            NP: Rp. <?php echo number_format($hartabergerak2->NILAI_PELAPORAN_OLD, 0, '', '.'); ?>/<br>
                            NK: Rp. <?php echo number_format($hartabergerak2->NILAI_PELAPORAN, 0, '', '.'); ?>    
                        </div>
                    </td>
                    <td align="center" class="aksi-hide">
                    <?php
                        $id_ht = '<input type="hidden" class="ID_HARTA_TABLE" value="' . $hartabergerak2->ID_HARTA . '" />';
                        $id_ht .= '<input type="hidden" class="STATUS_TABLE" value="' . $hartabergerak2->STATUS . '" />'; 
                        $nilai = 0;
                        $key = $key ? $key : 0;
                        $nilai = $hartabergerak2->$key;
                        if ($t_lhkpn->IS_COPY == '0') { // KONDISI LAPORAN PERTAMA
                            ?>
                            <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_bergerak_lain/<?php echo $hartabergerak2->ID_LHKPN; ?>/<?php echo $hartabergerak2->ID ? $hartabergerak2->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-danger delete-action"  id_harta="<?= $hartabergerak2->ID  ?>" href="#" title="Hapus Data"  ><i class="fa fa-trash"></i></button>
                            <?php
                        } else { // KONDISI KEDUA , KETIGA dst
                            if ($hartabergerak2->IS_PELEPASAN == '0') { // JIKA BUKAN PELEPASAN
                                if ($hartabergerak2->STATUS == '1') { // JIKA TETAP
                                //  dump('JIKA TETAP');
                                    ?>
                                        <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_bergerak_lain/<?php echo $hartabergerak2->ID_LHKPN; ?>/<?php echo $hartabergerak2->ID ? $hartabergerak2->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/pelepasan/<?php echo $hartabergerak2->ID_LHKPN; ?>/<?php echo $hartabergerak2->ID ? $hartabergerak2->ID : 0; ?>/t_hb2" title="Pelepasan"  onclick="onButton.go(this, 'large', true);"><i class="fa fa-upload"></i></button>
                                        <?php
                                } else { // JIKA BUKAN TETAP & LEPAS
                                    if ($hartabergerak2->ID_HARTA) { // JIKA DI AMBIL DARI HASIL COPY
                                        //  dump('JIKA DI AMBIL DARI HASIL COPY');
                                         ?>
                                        <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_bergerak_lain/<?php echo $hartabergerak2->ID_LHKPN; ?>/<?php echo $hartabergerak2->ID ? $hartabergerak2->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/pelepasan/<?php echo $hartabergerak2->ID_LHKPN; ?>/<?php echo $hartabergerak2->ID ? $hartabergerak2->ID : 0; ?>/t_hb2" title="Pelepasan"  onclick="onButton.go(this, 'large', true);"><i class="fa fa-upload"></i></button>
                                        <?php
                                        if ($hartabergerak2->STATUS == '3') {
                                            ?>
                                            <button type="button" class="btn btn-primary penetapan-action"  id_lhkpn='<?= $hartabergerak2->ID_LHKPN; ?>' id_harta="<?= $hartabergerak2->ID  ?>" href="#" title="Tetap"  ><i class="fa fa-link"></i></button>
                                            <?php
                                        }
                                    } else { // JIKA BIKIN BARU KETIKA LAPORAN KE 2,3, dst
                                //  dump('JIKA BIKIN BARU KETIKA LAPORAN KE 2,3, dst');
                                        ?>
                                        <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_bergerak_lain/<?php echo $hartabergerak2->ID_LHKPN; ?>/<?php echo $hartabergerak2->ID ? $hartabergerak2->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-danger delete-action"  id_harta="<?= $hartabergerak2->ID  ?>" href="#" title="Hapus Data"  ><i class="fa fa-trash"></i></button>
                                        <?php
                                    }
                                }
                            } else { // JIKA DATA DITETAPKAN
                                         ?>
                                        <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_bergerak_lain/<?php echo $hartabergerak2->ID_LHKPN; ?>/<?php echo $hartabergerak2->ID ? $hartabergerak2->ID : 0; ?>/edit" title="Undo" onclick="onButton.go(this, 'large', true);"><i class="fa fa-repeat"></i></button>                                       
                                        <?php
                            }
                        }
                        echo $id_ht;
                        ?>
                        <!--<button type="button" class="btn btn-primary" href="index.php/eaudit/klarifikasi/update_harta_bergerak_lain_lain/<?php echo $hartabergerak2->ID; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/soft_delete/<?php echo $hartabergerak2->ID; ?>/harta_bergerak_lain" title="Hapus Data" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>-->
                    </td>
                   
                    	                                    
                </tr>
                <?php if ($hartabergerak2->KET_PEMERIKSAAN != ''): ?>
                    <tr <?php echo $bgcolor; ?>>
                        <td></td>
                        <td></td>
                        <td <?php echo $is_preview ? 'colspan="5"' : 'colspan="6"'; ?>><strong>Keterangan Pemeriksaan: </strong><?php echo $hartabergerak2->KET_PEMERIKSAAN; ?></td>
                        <!-- <td></td> -->
                    </tr>
                <?php endif ?>
<?php }} ?>
        </tbody>
        <tfoot class="table-footer">
            <tr>
                <td <?php echo $is_preview ? 'colspan="6"' : 'colspan="7"'; ?>><b>Sub Total/Total</b></td>
                <td class="text-right"><b>Rp. <?php echo number_format($totalHartaBergerak2, 0, '', '.'); ?></b></td>
                <!-- <td></td> -->
            </tr>
        </tfoot>
    </table> 
</div>
<script type="text/javascript">
    $(document).ready(function() {
        // $('.table').dataTable({});
        $("#klarifikasiDataHBL").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Input Klarifikasi Data Harta Bergerak Lainnya', html, null, 'large');
            });            
            return false;
        });
    });

    // PENETAPAN ACTION
    $("#TableHBL tbody").on('click', '.penetapan-action', function (event) {
        var id = $(this).attr('id_harta');
        confirm("Apakah anda yakin akan melakukan penetapan harta ini ? ", function () {
            $.ajax({
                url: base_url + 'eaudit/klarifikasi/do_check_penetapan/' + id +'/t_hb2',
                async: false,
                dataType: 'JSON',
                success: function (data) {
                    var rs = eval(data);
                    if (rs.result.LUAS_TANAH == 0 && rs.result.LUAS_BANGUNAN == 0){
                        notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan luas tanah dan luas bangunan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                    }else if (rs.result.NILAI_PELAPORAN == 0){
                        notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan nilai pelaporan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                    }else{
                        do_delete('eaudit/klarifikasi/do_penetapan/' + id + '/t_hb2', 'Data Harta Tidak Bergerak Sudah Tetap ');
                        //   $('#TableTanah').DataTable().ajax.reload(); 
                        var url = location.href.split('#')[1];
                        url = url.split('?')[0] + '?upperli=li3&bottomli=lii2';
                        window.location.hash = url;
                        ng.LoadAjaxContent(url);
                        $('.modal-open').css("overflow", "scroll");
                        $('.modal-backdrop').remove();
                        return false;
                    }
                }
                });
            });
        });

    // DELETE ACTION
    $("#TableHBL tbody").on('click', '.delete-action', function (event) {
        var id = $(this).attr('id_harta');
        confirm("Apakah anda yakin menghapus data ? ", function () {
            do_delete('eaudit/klarifikasi/soft_delete/' + id + '/harta_bergerak_lain', 'Data Harta Bergerak Berhasil dihapus ');
            $("#modal-success").removeClass("modal-lg"); 
            $("#modal-warning").removeClass("modal-lg"); 
            return false;
                
            });
        });

    function tetapkan_hb2_all(){
        var jml_cek = $("#jml_check").val();
        confirm("Apakah anda yakin akan melakukan penetapan semua harta yang dipilih ? ", function () {
            $('.chk-hb2:checkbox:checked').each(function(index){
                var id = $(this).val();
                if($(this).is(':checked')){
                    ajax_hb2_penetapan(id, jml_cek, index);
                }
            })
        });
    }

    function ajax_hb2_penetapan(id, jml_cek, index){
        $('#loader_area').show();
        $.ajax({
            url: base_url + 'eaudit/klarifikasi/do_check_penetapan/' + id +'/t_hb2',
            async: true,
            dataType: 'JSON',
            success: function (data) {
                var rs = eval(data);
                if (rs.result.LUAS_TANAH == 0 && rs.result.LUAS_BANGUNAN == 0){
                    notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan luas tanah dan luas bangunan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                }else if (rs.result.NILAI_PELAPORAN == 0){
                    notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan nilai pelaporan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                }else{
                    do_delete('eaudit/klarifikasi/do_penetapan/' + id + '/t_hb2', 'Data Harta Tidak Bergerak Sudah Tetap ');

                    var url = location.href.split('#')[1];
                    url = url.split('?')[0] + '?upperli=li3&bottomli=lii2';
                    window.location.hash = url;

                    if(jml_cek == 1){
                        ng.LoadAjaxContent(url);
                    }else if(jml_cek > 1 && jml_cek == index+1){
                        var delayInMilliseconds = 10;
                        setTimeout(function() {
                            ng.LoadAjaxContent(url);
                        }, delayInMilliseconds);

                    }
                    
                    $('.modal-open').css("overflow", "scroll");
                    $('.modal-backdrop').remove();
                    return false;
                }
            }
        });
    }
    
    function chk_all_hb2(ele){
        if($(ele).is(':checked')){
            $('.chk-hb2').prop('checked', true);
        }else{
            $('.chk-hb2').prop('checked', false);
        }
        cekTombolHb2("chk-hb2");
    }

    function chkHb2(ele){
        var val = $(ele).val();
        cekTombolHb2("chk-hb2");
    }

    function cekTombolHb2(chkboxName){
        var checkboxes = document.getElementsByName(chkboxName);
            var checkboxesChecked = [];
            for (var i=0; i<checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                      checkboxesChecked.push(checkboxes[i]);
                    }
            }
            
            var jml_chk = checkboxesChecked.length;
            if (jml_chk === 0 ){
                $("#tombolTetapHb2").empty();
            } else{
                $("#tombolTetapHb2").html("<button style=\"margin-top: 10px;\" type=\"button\" class=\"btn btn-warning\" onclick=\"tetapkan_hb2_all();\"title=\"Tetap\"><input type=\"hidden\" name=\"jml_check\" id=\"jml_check\"><i class=\"fa fa-plus-square\"></i> Tetapkan Semua</button>");
                $("#jml_check").val(jml_chk);
            }
    }
</script>
