<div class="box-header with-border portlet-header title-pribadi">
    <h5 class="">"Harta Bergerak berupa alat transportasi dan mesin"</h5>
</div>
<br>&nbsp;&nbsp;&nbsp;<button class="btn btn-info aksi-hide" id="klarifikasiDataHartaBergerak" href="index.php/eaudit/klarifikasi/update_harta_bergerak/<?php echo $new_id_lhkpn;?>/<?php echo $hartabergerak->ID ? $hartabergerak->ID : 0; ?>/new"><span class="fa fa-plus"></span> Tambah</button><br><br>
<div class="box-body" id="wrapperHartaBergerak">
<div id="tombolTetapHb"></div><br><br>
    <table class="table borderless" id="TableHartaBergerak">
        <thead class="table-header">
            <tr>
                <th width="15px"><input type="checkbox" onClick="chk_all_hb(this);" /></th>
                <th width="15px">NO</th>
                <th width="90px">STATUS</th>
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
            $totalHartaBergerak = 0;
            $list_harta = [
                '2' => 'MOBIL',
                '3' => 'MOTOR',
                '4' => 'KAPAL LAUT/PERAHU',
                '5' => 'PESAWAT TERBANG',
                '6' => 'Lainnya'
            ];
            $list_jenis_bukti = [
                '3' => 'BPKB/STNK',
                '4' => 'LAINNYA'
            ];
            $stat = ['1'=>'Tetap','2'=>'Ubah','3'=>'Baru'];
            if ($lhkpn_harta_bergerak) {
            foreach ($lhkpn_harta_bergerak as $hartabergerak) {

                ////////////////atas nama & pasangan anak//////////////////
                $get_atas_nama = $hartabergerak->ATAS_NAMA;
                $atas_nama = '';  
                $get_atas_nama = check_atas_nama($get_atas_nama);
                if(strstr($get_atas_nama, "5")){    
                    $atas_nama = '<b>'.substr($get_atas_nama,2).'</b>';
                }               
                if(strstr($get_atas_nama, "1")){
                    $atas_nama = '<b>PN YANG BERSANGKUTAN</b>';
                }        
                if(strstr($get_atas_nama, "2")){
                    
                    $pasangan_array = explode(',', $hartabergerak->PASANGAN_ANAK);
                    $get_list_pasangan = '';
                    $loop_first_pasangan = 0;
                    foreach($pasangan_array as $ps){
                        $sql_pasangan_anak = "SELECT NAMA FROM t_lhkpn_keluarga WHERE ID_KELUARGA = '$ps'";
                        $data_pasangan_anak = $this->db->query($sql_pasangan_anak)->result_array();
                        if($loop_first_pasangan==0){
                            $get_list_pasangan = $data_pasangan_anak[0]['NAMA'];
                        }else{
                            $get_list_pasangan = $get_list_pasangan.',<br> '.$data_pasangan_anak[0]['NAMA'];
                        }
                        $loop_first_pasangan++;
                    }
                    $show_pasangan = $get_list_pasangan;
                    if($atas_nama==''){
                        $atas_nama = $atas_nama.'<b>PASANGAN/ANAK</b> ('.$show_pasangan.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>PASANGAN/ANAK</b> ('.$show_pasangan.')';
                    }
                }
                if(strstr($get_atas_nama, "3")){
                    if($atas_nama==''){
                        $atas_nama = $atas_nama.'<b>LAINNYA</b> ('.$hartabergerak->KET_LAINNYA.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>LAINNYA</b> ('.$hartabergerak->KET_LAINNYA.')' ;
                    }
                }
                ////////////////atas nama & pasangan anak//////////////////






                if ($i%2 == 0) {
                    $bgcolor = 'bgcolor="#f9f9f9"';
                }
                else{
                    $bgcolor = '';
                }
                if ($hartabergerak->IS_PELEPASAN == '1') {
                    $style_pelepasan = 'style="background-color: rgb(128, 128, 128); color: rgb(255, 255, 255);"';
                }else{
                    $style_pelepasan = null;
                }
                $totalHartaBergerak += $hartabergerak->NILAI_PELAPORAN;
                ?>
                <tr <?php echo $bgcolor; ?> <?= $style_pelepasan ?>  >
                    <td><?php if($hartabergerak->STATUS==3 && $hartabergerak->ID_HARTA!=''){ ?>
                        <input class="chk-hb" type="checkbox" onclick="chkHb(this);" value="<?= $hartabergerak->ID ?>" name="chk-hb">
                    <?php }else{ ?> <input type="checkbox" disabled="true" value="" name="chk-hb"> <?php } ?></td>
                    <td><?php echo ++$i; ?>.</td>
                    <td><?php 
                            $data_label = array();
                            $data_label[1] = '<label class="label label-primary">Tetap</label>';
                            $data_label[2] = '<label class="label label-success">Ubah</label>';
                            $data_label[3] = '<label class="label label-success">Baru</label>';
                            if ($hartabergerak->IS_PELEPASAN == '1') {
                                echo '<label class="label label-danger pelepasan">Lepas</label>';
                            } else {
                                if($hartabergerak->STATUS==3 && $hartabergerak->ID_HARTA!=''){
                                    echo '<label class="label label-warning">Lama</label>';
                                }else{
                                    echo $data_label[$hartabergerak->STATUS];
                                }
                                
                            }
                        ?>
                    </td>
                    <td><b>Jenis : </b><?php echo $list_harta[$hartabergerak->KODE_JENIS] ?><br>
                        <b>Merek : </b> <?php echo $hartabergerak->MEREK; ?><br>
                        <b>Model : </b> <?php echo $hartabergerak->MODEL; ?><br>
                        <b>Tahun Pembuatan : </b> <?php echo $hartabergerak->TAHUN_PEMBUATAN; ?><br>
                        <b>No Pol / Registrasi : </b> <?php echo $hartabergerak->NOPOL_REGISTRASI; ?><br>
                    </td>

                    <td>
                        <?php
                       
                        if ($hartabergerak->JENIS_BUKTI == '8') {
                            echo 'Bukti Lain';
                        } else {
                            echo '<b>Jenis bukti : </b>'.$list_jenis_bukti[$hartabergerak->JENIS_BUKTI];
                        }
                        ?><br/><b>a.n : </b><?php echo $atas_nama; ?><br>
                        <b>Asal Usul Harta : </b><br>
                        <?php
                        $exp = explode(',', $hartabergerak->ASAL_USUL);
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
                        <br>
                        <b>Pemanfaatan : </b>
                        <br />
                        <?php
//                        echo $pemanfaatan2[$hartabergerak->PEMANFAATAN];
                        echo map_data_pemanfaatan($hartabergerak->PEMANFAATAN, 2);
                        ?>
                        <br>
                    </td>

                    <td>
                        <div align="right">
                            Rp. <?php echo number_format($hartabergerak->NILAI_PEROLEHAN, 0, '', '.'); ?>
                        </div>
                    </td>
                    <td>
                        <div align="right">
                            NP: Rp. <?php echo number_format($hartabergerak->NILAI_PELAPORAN_OLD, 0, '', '.'); ?>/<br>
                            NK: Rp. <?php echo number_format($hartabergerak->NILAI_PELAPORAN, 0, '', '.'); ?>
                        </div>
                    </td>
                    <td align="center" class="aksi-hide">
                        <?php
                        $id_ht = '<input type="hidden" class="ID_HARTA_TABLE" value="' . $hartabergerak->ID_HARTA . '" />';
                        $id_ht .= '<input type="hidden" class="STATUS_TABLE" value="' . $hartabergerak->STATUS . '" />'; 
                        $nilai = 0;
                        $key = $key ? $key : 0;
                        $nilai = $hartabergerak->$key;
                        if ($t_lhkpn->IS_COPY == '0') { // KONDISI LAPORAN PERTAMA
                            ?>
                            <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_bergerak/<?php echo $hartabergerak->ID_LHKPN; ?>/<?php echo $hartabergerak->ID ? $hartabergerak->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-danger delete-action"  id_harta="<?= $hartabergerak->ID  ?>" href="#" title="Hapus Data"  ><i class="fa fa-trash"></i></button>
                            <?php
                        } else { // KONDISI KEDUA , KETIGA dst
                            if ($hartabergerak->IS_PELEPASAN == '0') { // JIKA BUKAN PELEPASAN
                                if ($hartabergerak->STATUS == '1') { // JIKA TETAP
                                //  dump('JIKA TETAP');
                                    ?>
                                        <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_bergerak/<?php echo $hartabergerak->ID_LHKPN; ?>/<?php echo $hartabergerak->ID ? $hartabergerak->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/pelepasan/<?php echo $hartabergerak->ID_LHKPN; ?>/<?php echo $hartabergerak->ID ? $hartabergerak->ID : 0; ?>/t_hb" title="Pelepasan"  onclick="onButton.go(this, 'large', true);"><i class="fa fa-upload"></i></button>
                                        <?php
                                } else { // JIKA BUKAN TETAP & LEPAS
                                    if ($hartabergerak->ID_HARTA) { // JIKA DI AMBIL DARI HASIL COPY
                                        //  dump('JIKA DI AMBIL DARI HASIL COPY');
                                         ?>
                                        <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_bergerak/<?php echo $hartabergerak->ID_LHKPN; ?>/<?php echo $hartabergerak->ID ? $hartabergerak->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/pelepasan/<?php echo $hartabergerak->ID_LHKPN; ?>/<?php echo $hartabergerak->ID ? $hartabergerak->ID : 0; ?>/t_hb" title="Pelepasan"  onclick="onButton.go(this, 'large', true);"><i class="fa fa-upload"></i></button>
                                        <?php
                                        if ($hartabergerak->STATUS == '3') {
                                            ?>
                                            <button type="button" class="btn btn-primary penetapan-action"  id_lhkpn='<?= $hartabergerak->ID_LHKPN; ?>' id_harta="<?= $hartabergerak->ID  ?>" href="#" title="Tetap"  ><i class="fa fa-link"></i></button>
                                            <?php
                                        }
                                    } else { // JIKA BIKIN BARU KETIKA LAPORAN KE 2,3, dst
                                //  dump('JIKA BIKIN BARU KETIKA LAPORAN KE 2,3, dst');
                                        ?>
                                        <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_bergerak/<?php echo $hartabergerak->ID_LHKPN; ?>/<?php echo $hartabergerak->ID ? $hartabergerak->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-danger delete-action"  id_harta="<?= $hartabergerak->ID  ?>" href="#" title="Hapus Data"  ><i class="fa fa-trash"></i></button>
                                        <?php
                                    }
                                }
                            } else { // JIKA DATA DITETAPKAN
                                         ?>
                                        <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_harta_bergerak/<?php echo $hartabergerak->ID_LHKPN; ?>/<?php echo $hartabergerak->ID ? $hartabergerak->ID : 0; ?>/edit" title="Undo" onclick="onButton.go(this, 'large', true);"><i class="fa fa-repeat"></i></button>                                       
                                        <?php
                            }
                        }
                        echo $id_ht;
                        ?>

                        <!--<button type="button" class="btn btn-primary" href="index.php/eaudit/klarifikasi/update_harta_bergerak/<?php echo $hartabergerak->ID_LHKPN; ?>/<?php echo $hartabergerak->ID; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/soft_delete/<?php echo $hartabergerak->ID; ?>/harta_bergerak" title="Hapus Data" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>-->
                    </td>
                                     
                </tr>
                <?php if ($hartabergerak->KET_PEMERIKSAAN != ''): ?>
                    <tr <?php echo $bgcolor; ?>>
                        <td></td>
                        <td></td>
                        <td <?php echo $is_preview ? 'colspan="5"' : 'colspan="6"'; ?>><strong>Keterangan Pemeriksaan: </strong><?php echo $hartabergerak->KET_PEMERIKSAAN; ?></td>
                        <!-- <td></td> -->
                    </tr>
                <?php endif ?>
            <?php }} ?>
        </tbody>
        <tfoot class="table-footer">
            <tr>
                <td <?php echo $is_preview ? 'colspan="6"' : 'colspan="7"'; ?>><b>Sub Total/Total</b></td>
                <td class="text-right"><b>Rp. <?php echo number_format($totalHartaBergerak, 0, '', '.'); ?></b></td>
                <!-- <td></td> -->
            </tr>
        </tfoot>
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#klarifikasiDataHartaBergerak").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Input Klarifikasi Data Harta Bergerak', html, null, 'large');
            });            
            return false;
        });
    });

    // PENETAPAN ACTION
    $("#TableHartaBergerak tbody").on('click', '.penetapan-action', function (event) {
        var id = $(this).attr('id_harta');
        confirm("Apakah anda yakin akan melakukan penetapan harta ini ? ", function () {
            $.ajax({
                url: base_url + 'eaudit/klarifikasi/do_check_penetapan/' + id +'/t_hb',
                async: false,
                dataType: 'JSON',
                success: function (data) {
                    var rs = eval(data);
                    if (rs.result.LUAS_TANAH == 0 && rs.result.LUAS_BANGUNAN == 0){
                        notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan luas tanah dan luas bangunan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                    }else if (rs.result.NILAI_PELAPORAN == 0){
                        notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan nilai pelaporan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                    }else{
                        do_delete('eaudit/klarifikasi/do_penetapan/' + id + '/t_hb', 'Data Harta Tidak Bergerak Sudah Tetap ');
                        //   $('#TableTanah').DataTable().ajax.reload(); 
                        var url = location.href.split('#')[1];
                        url = url.split('?')[0] + '?upperli=li3&bottomli=lii1';
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
    $("#TableHartaBergerak tbody").on('click', '.delete-action', function (event) {
        var id = $(this).attr('id_harta');
        confirm("Apakah anda yakin menghapus data ? ", function () {
            do_delete('eaudit/klarifikasi/soft_delete/' + id + '/harta_bergerak', 'Data Harta Bergerak Berhasil dihapus ');
            $("#modal-success").removeClass("modal-lg"); 
            $("#modal-warning").removeClass("modal-lg");
            return false;
                
            });
        });

    function tetapkan_hb_all(){
        var jml_cek = $("#jml_check").val();
        confirm("Apakah anda yakin akan melakukan penetapan semua harta yang dipilih ? ", function () {
            $('.chk-hb:checkbox:checked').each(function(index){
                var id = $(this).val();
                if($(this).is(':checked')){
                    ajax_hb_penetapan(id, jml_cek, index);
                }
            })
        });
    }

    function ajax_hb_penetapan(id, jml_cek, index){
        $('#loader_area').show();
        $.ajax({
            url: base_url + 'eaudit/klarifikasi/do_check_penetapan/' + id +'/t_hb',
            async: true,
            dataType: 'JSON',
            success: function (data) {
                var rs = eval(data);
                if (rs.result.LUAS_TANAH == 0 && rs.result.LUAS_BANGUNAN == 0){
                    notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan luas tanah dan luas bangunan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                }else if (rs.result.NILAI_PELAPORAN == 0){
                    notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan nilai pelaporan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                }else{
                    do_delete('eaudit/klarifikasi/do_penetapan/' + id + '/t_hb', 'Data Harta Tidak Bergerak Sudah Tetap ');
                    
                    var url = location.href.split('#')[1];
                    url = url.split('?')[0] + '?upperli=li3&bottomli=lii1';
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

    function chk_all_hb(ele){
        if($(ele).is(':checked')){
            $('.chk-hb').prop('checked', true);
        }else{
            $('.chk-hb').prop('checked', false);
        }
        cekTombolHb("chk-hb");
    }

    function chkHb(ele){
        var val = $(ele).val();
        cekTombolHb("chk-hb");
    }

    function cekTombolHb(chkboxName){
        var checkboxes = document.getElementsByName(chkboxName);
            var checkboxesChecked = [];
            for (var i=0; i<checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                      checkboxesChecked.push(checkboxes[i]);
                    }
            }
            
            var jml_chk = checkboxesChecked.length;
            if (jml_chk === 0 ){
                $("#tombolTetapHb").empty();
            } else{
                $("#tombolTetapHb").html("<button style=\"margin-top: 10px;\" type=\"button\" class=\"btn btn-warning\" onclick=\"tetapkan_hb_all();\"title=\"Tetap\"><input type=\"hidden\" name=\"jml_check\" id=\"jml_check\"><i class=\"fa fa-plus-square\"></i> Tetapkan Semua</button>");
                $("#jml_check").val(jml_chk);
            }
    }
</script>
