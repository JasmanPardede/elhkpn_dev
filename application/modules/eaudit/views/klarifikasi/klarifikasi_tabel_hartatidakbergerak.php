<div class="box-header with-border portlet-header title-pribadi">
    <h5 class="">"Harta Tidak Bergerak berupa Tanah dan/atau bangunan"</h5>
</div>
<br>&nbsp;&nbsp;&nbsp;<button class="btn btn-info aksi-hide" id="klarifikasiDataHTB" href="index.php/eaudit/klarifikasi/update_htb/<?php echo $new_id_lhkpn;?>/<?php echo $hartatidakbergerak->ID ? $hartatidakbergerak->ID : 0; ?>/new"><span class="fa fa-plus"></span> Tambah</button><br><br>
<div class="box-body" id="wrapperHartaTidakBergerak">
<div id="tombolTetapHtb"></div><br><br>
    <table id="TableTanah" class="table borderless" >
        <thead class="table-header">
            <tr >
                <th width="15px"><input type="checkbox" onClick="chk_all_htb(this);" /></th>
                <th width="15px">NO</th>
                <th width="80px">STATUS</th>
                <th>LOKASI/ALAMAT LENGKAP</th>
                <th>LUAS</th>
                <th>KEPEMILIKAN</th>
                <th>NILAI PEROLEHAN</th>
                <th>NILAI PELAPORAN/ KLARIFIKASI</th>
                <th class="aksi-hide">AKSI</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $survey = 'ok';
            $asal_usul = array('', 'PN YANG BERSANGKUTAN', 'PASANGAN / ANAK', 'LAINNYA');
            $i = 0;
            $totalHartaTidakBergerak = 0;
            $stat = ['1'=>'Tetap','2'=>'Ubah','3'=>'Baru'];
            foreach ($lhkpn_htb as $hartatidakbergerak) {

                ////////////////atas nama & pasangan anak//////////////////
                $get_atas_nama = $hartatidakbergerak->ATAS_NAMA;
                $atas_nama = '';  
                $get_atas_nama = check_atas_nama($get_atas_nama);
                if(strstr($get_atas_nama, "5")){    
                    $atas_nama = '<b>'.substr($get_atas_nama,2).'</b>';
                }               
                if(strstr($get_atas_nama, "1")){
                    $atas_nama = '<b>PN YANG BERSANGKUTAN</b>';
                }        
                if(strstr($get_atas_nama, "2")){
                    
                    $pasangan_array = explode(',', $hartatidakbergerak->PASANGAN_ANAK);
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
                        $atas_nama = $atas_nama.'<b>LAINNYA</b> ('.$hartatidakbergerak->KET_LAINNYA.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>LAINNYA</b> ('.$hartatidakbergerak->KET_LAINNYA.')' ;
                    }
                }
                ////////////////atas nama & pasangan anak//////////////////




                $totalHartaTidakBergerak += $hartatidakbergerak->NILAI_PELAPORAN;
                if ($i%2 == 0) {
                    $bgcolor = 'bgcolor="#f9f9f9"';
                }
                else{
                    $bgcolor = '';
                }
                if ($hartatidakbergerak->IS_PELEPASAN == '1') {
                    $style_pelepasan = 'style="background-color: rgb(128, 128, 128); color: rgb(255, 255, 255);"';
                }else{
                    $style_pelepasan = null;
                }
                ?>
                <tr <?php echo $bgcolor; ?> <?= $style_pelepasan ?> >
                    <td><?php if($hartatidakbergerak->STATUS==3 && $hartatidakbergerak->ID_HARTA!=''){ ?>
                        <input class="chk-htb" type="checkbox" onclick="chkHtb(this);" value="<?= $hartatidakbergerak->ID ?>" name="chk-htb">
                    <?php }else{ ?> <input type="checkbox" disabled="true" value="" name="chk-htb"> <?php } ?></td>
                    <td><?php echo ++$i; ?>.</td>
                    <td><?php
                            $data_label = array();
                            $data_label[1] = '<label class="label label-primary">Tetap</label>';
                            $data_label[2] = '<label class="label label-success">Ubah</label>';
                            $data_label[3] = '<label class="label label-success">Baru</label>';
                            if ($hartatidakbergerak->IS_PELEPASAN == '1') {
                                echo '<label class="label label-danger pelepasan">Lepas</label>';
                            } else {
                                if($hartatidakbergerak->STATUS==3 && $hartatidakbergerak->ID_HARTA!=''){
                                    echo '<label class="label label-warning">Lama</label>';
                                }else{
                                    echo $data_label[$hartatidakbergerak->STATUS];
                                }
                                
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($hartatidakbergerak->NEGARA == '2' && $hartatidakbergerak->ID_NEGARA != '1') {
                            echo $hartatidakbergerak->JALAN . ', ' . $hartatidakbergerak->NAMA_NEGARA;
                        } else {
                            echo '<b>Jalan/ No : </b>' . $hartatidakbergerak->JALAN . '<br/> <b>Kelurahan : </b> ' .
                            $hartatidakbergerak->KEL . '<br/> <b>Kecamatan : </b> ' .
                            $hartatidakbergerak->KEC . '<br/> <b>Kabupaten/Kota : </b> ' .
                            $hartatidakbergerak->KAB_KOT . '<br/> <b>Provinsi : </b> ' . $hartatidakbergerak->PROV;
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo '<b>Tanah </b>: ' . number_format($hartatidakbergerak->LUAS_TANAH, 2, '.', ''); ?> m<sup>2</sup> 
                        <?php echo '<br/><b>Bangunan </b>: ' . number_format($hartatidakbergerak->LUAS_BANGUNAN, 2, '.', ''); ?> m<sup>2</sup>
                    </td>
                    <td>
                        <b>Jenis Bukti : </b><?php echo $hartatidakbergerak->HTB_JENIS_BUKTI ?> <br> <b>Nomor :  </b><?php echo $hartatidakbergerak->NOMOR_BUKTI ?> <br>
                        <b>A.n : </b> <?= $atas_nama ?><br>
                        <b>Asal Usul Harta : </b>
                        <?php
                        $exp = explode(',', $hartatidakbergerak->ASAL_USUL);
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
                        <?php
                        $expm = explode(',', $hartatidakbergerak->PEMANFAATAN);
                        $text1 = '';
                        foreach ($expm as $key) {
                            $text1 .= $pemanfaatan1[$key] . '&nbsp;,&nbsp;&nbsp;';
                        }
                        $rinci1 = substr($text1, 0, -19);
                        echo $rinci1;
                        ?>
                        <br>
                    </td>

                    <td>
                        <!--  <div>
                             
                         </div> -->
                        <div align="right">
                            Rp. <?php echo number_format($hartatidakbergerak->NILAI_PEROLEHAN, 0, '', '.'); ?>
                        </div>
                    </td>
                    <td>
                        <div align="right">
                            NP: Rp. <?php echo number_format($hartatidakbergerak->NILAI_PELAPORAN_OLD, 0, '', '.'); ?>/<br>
                            NK: Rp. <?php echo number_format($hartatidakbergerak->NILAI_PELAPORAN, 0, '', '.'); ?>
                        </div>
                    </td>
                    <td align="center" class="aksi-hide">
                    <?php
                        $id_ht = '<input type="hidden" class="ID_HARTA_TABLE" value="' . $hartatidakbergerak->ID_HARTA . '" />';
                        $id_ht .= '<input type="hidden" class="STATUS_TABLE" value="' . $hartatidakbergerak->STATUS . '" />'; 
                        $nilai = 0;
                        $key = $key ? $key : 0;
                        $nilai = $hartatidakbergerak->$key;
                        $edit = null;
                        $delete = null;
                        if ($t_lhkpn->IS_COPY == '0') { // KONDISI LAPORAN PERTAMA
                            ?>
                            <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_htb/<?php echo $hartatidakbergerak->ID_LHKPN; ?>/<?php echo $hartatidakbergerak->ID ? $hartatidakbergerak->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-danger delete-action"  id_harta="<?= $hartatidakbergerak->ID  ?>" href="#" title="Hapus Data"  ><i class="fa fa-trash"></i></button>
                            <?php
                        } else { // KONDISI KEDUA , KETIGA dst
                            if ($hartatidakbergerak->IS_PELEPASAN == '0') { // JIKA BUKAN PELEPASAN
                                if ($hartatidakbergerak->STATUS == '1') { // JIKA TETAP
                                //  dump('JIKA TETAP');
                                    ?>
                                        <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_htb/<?php echo $hartatidakbergerak->ID_LHKPN; ?>/<?php echo $hartatidakbergerak->ID ? $hartatidakbergerak->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/pelepasan/<?php echo $hartatidakbergerak->ID_LHKPN; ?>/<?php echo $hartatidakbergerak->ID ? $hartatidakbergerak->ID : 0; ?>/t_htb" title="Pelepasan"  onclick="onButton.go(this, 'large', true);"><i class="fa fa-upload"></i></button>
                                        <!--<button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/soft_delete/<?php echo $hartatidakbergerak->ID; ?>/harta_tidak_bergerak" title="Hapus Data" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>-->
                                        <?php
                                    // $edit = "<a id='" . $hartatidakbergerak->ID . "'  href='javascript:void(0)' class='btn btn-success btn-sm edit-action' title='Edit'><i class='fa fa-pencil'></i></a>";
                                    // $delete = "<a id='" . $hartatidakbergerak->ID . "' data-nilai=[" . $nilai . "]  href='javascript:void(0)' class='btn btn-danger btn-sm pelepasan-action'title='Pelepasan'><i class='fa fa-upload'></i></a>";
                                } else { // JIKA BUKAN TETAP & LEPAS
                                    if ($hartatidakbergerak->ID_HARTA) { // JIKA DI AMBIL DARI HASIL COPY
                                        //  dump('JIKA DI AMBIL DARI HASIL COPY');
                                         ?>
                                        <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_htb/<?php echo $hartatidakbergerak->ID_LHKPN; ?>/<?php echo $hartatidakbergerak->ID ? $hartatidakbergerak->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/pelepasan/<?php echo $hartatidakbergerak->ID_LHKPN; ?>/<?php echo $hartatidakbergerak->ID ? $hartatidakbergerak->ID : 0; ?>/t_htb" title="Pelepasan"  onclick="onButton.go(this, 'large', true);"><i class="fa fa-upload"></i></button>
                                        <?php
                                        // $edit = "<a id='" . $hartatidakbergerak->ID . "'  href='javascript:void(0)' class='btn btn-success btn-sm edit-action' title='Edit'><i class='fa fa-pencil'></i></a>";
                                        // $delete = "<a id='" . $hartatidakbergerak->ID . "'  data-nilai=[" . $nilai . "] href='javascript:void(0)' class='btn btn-danger btn-sm pelepasan-action' title='Pelepasan'><i class='fa fa-upload'></i></a>";
                                        if ($hartatidakbergerak->STATUS == '3') {
                                            ?>
                                            <button type="button" class="btn btn-primary penetapan-action"  id_lhkpn='<?= $hartatidakbergerak->ID_LHKPN; ?>' id_harta="<?= $hartatidakbergerak->ID  ?>" href="#" title="Tetap"  ><i class="fa fa-link"></i></button>
                                            <?php
                                        }
                                    } else { // JIKA BIKIN BARU KETIKA LAPORAN KE 2,3, dst
                                //  dump('JIKA BIKIN BARU KETIKA LAPORAN KE 2,3, dst');
                                        ?>
                                        <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_htb/<?php echo $hartatidakbergerak->ID_LHKPN; ?>/<?php echo $hartatidakbergerak->ID ? $hartatidakbergerak->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-danger delete-action"  id_harta="<?= $hartatidakbergerak->ID  ?>" href="#" title="Hapus Data"  ><i class="fa fa-trash"></i></button>
                                        <!--<button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/soft_delete/<?php echo $hartatidakbergerak->ID; ?>/harta_tidak_bergerak" title="Hapus Data"  onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>
                                        <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/form_delete/<?php echo $hartatidakbergerak->ID; ?>/harta_tidak_bergerak" title="Hapus Data"  onclick="btnDeleteOnClick(this);"><i class="fa fa-trash"></i></button>-->

                                        <?php
                                    }
                                }
                            } else { // JIKA DATA DITETAPKAN
                                         ?>
                                        <button type="button" class="btn btn-success" href="index.php/eaudit/klarifikasi/update_htb/<?php echo $hartatidakbergerak->ID_LHKPN; ?>/<?php echo $hartatidakbergerak->ID ? $hartatidakbergerak->ID : 0; ?>/edit" title="Undo" onclick="onButton.go(this, 'large', true);"><i class="fa fa-repeat"></i></button>                                       
                                        <?php
                            }
                        }
                        echo $id_ht;
                        ?>

                        <!--<button type="button" class="btn btn-primary" href="index.php/eaudit/klarifikasi/update_htb/<?php echo $hartatidakbergerak->ID_LHKPN; ?>/<?php echo $hartatidakbergerak->ID ? $hartatidakbergerak->ID : 0; ?>/edit" title="Pelepasan Harta" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-primary" href="index.php/eaudit/klarifikasi/update_htb/<?php echo $hartatidakbergerak->ID_LHKPN; ?>/<?php echo $hartatidakbergerak->ID ? $hartatidakbergerak->ID : 0; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-users"></i></button>
                        <button type="button" class="btn btn-danger" href="index.php/eaudit/klarifikasi/soft_delete/<?php echo $hartatidakbergerak->ID; ?>/harta_tidak_bergerak" title="Hapus Data" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>-->
                    </td>

                </tr>
                <?php if ($hartatidakbergerak->KET_PEMERIKSAAN != ''): ?>
                    <tr <?php echo $bgcolor; ?>>
                        <td></td>
                        <td></td>
                        <td <?php echo $is_preview ? 'colspan="5"' : 'colspan="6"'; ?>><strong>Keterangan Pemeriksaan: </strong><?php echo $hartatidakbergerak->KET_PEMERIKSAAN; ?></td>
                        <td></td>
                    </tr>
                <?php endif ?>
            <?php } ?>
        </tbody>
        <tfoot class='table-footer'>
            <tr>
                <td <?php echo $is_preview ? 'colspan="6"' : 'colspan="7"'; ?>><b>Sub Total/Total</b></td>
                <td></td>
                <td>
                    <div align="right">
                        <b>Rp. <?php echo number_format($totalHartaTidakBergerak, 0, '', '.'); ?></b>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#klarifikasiDataHTB").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Input Klarifikasi Data HTB', html, null, 'large');
            });            
            return false;
        });
    });

    // PENETAPAN ACTION
    $("#TableTanah tbody").on('click', '.penetapan-action', function (event) {
        var id = $(this).attr('id_harta');
        confirm("Apakah anda yakin akan melakukan penetapan harta ini ? ", function () {
            $.ajax({
                url: base_url + 'eaudit/klarifikasi/do_check_penetapan/' + id +'/t_htb',
                async: false,
                dataType: 'JSON',
                success: function (data) {
                    var rs = eval(data);
                    if (rs.result.LUAS_TANAH == 0 && rs.result.LUAS_BANGUNAN == 0){
                        notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan luas tanah dan luas bangunan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                    }else if (rs.result.NILAI_PELAPORAN == 0){
                        notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan nilai pelaporan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                    }else{
                        do_delete('eaudit/klarifikasi/do_penetapan/' + id + '/t_htb', 'Data Harta Tidak Bergerak Sudah Tetap ');
                        //   $('#TableTanah').DataTable().ajax.reload(); 
                        var url = location.href.split('#')[1];
                        url = url.split('?')[0] + '?upperli=li3&bottomli=lii0';
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
        $("#TableTanah tbody").on('click', '.delete-action', function (event) {
            var id = $(this).attr('id_harta');
            confirm("Apakah anda yakin menghapus data ? ", function () {
                do_delete('eaudit/klarifikasi/soft_delete/' + id + '/harta_tidak_bergerak', 'Data Harta Tidak Bergerak Berhasil dihapus ');
                // do_delete('eaudit/klarifikasi/do_penetapan/' + id + '/t_lhkpn_harta_tidak_bergerak', 'Data Harta Tidak Bergerak Sudah Tetap ');

                $("#modal-success").removeClass("modal-lg"); 
                $("#modal-warning").removeClass("modal-lg"); 
                //   $('#TableTanah').DataTable().ajax.reload();
                return false;
                    
                });
            });

        // var btnDeleteOnClick = function (self) {
        //     url = $(self).attr('href');
        //     $.post(url, function (html) {
        //         OpenModalBox('KONFIRMASI ULANG', html, '');
        //     });
        //     return false;
        // };

    $('#btn-close').click(function(){ 
        var url = location.href.split('#')[1];

        var tab_1 = $('li#lii0').hasClass('active');
        var tab_2 = $('li#lii1').hasClass('active');
        var tab_3 = $('li#lii2').hasClass('active');
        var tab_4 = $('li#lii3').hasClass('active');
        var tab_5 = $('li#lii4').hasClass('active');
        var tab_6 = $('li#lii5').hasClass('active');
        var tab_7 = $('li#lii6').hasClass('active');

        var link = '';
        if(tab_1){
            link = '?upperli=li3&bottomli=lii0';
        }else if(tab_2) {
            link = '?upperli=li3&bottomli=lii1';
        }else if(tab_3){
            link = '?upperli=li3&bottomli=lii2';
        }else if(tab_4){
            link = '?upperli=li3&bottomli=lii3';
        }else if(tab_5){
            link = '?upperli=li3&bottomli=lii4';
        }else if(tab_6){
            link = '?upperli=li3&bottomli=lii5';
        }else if(tab_7){
            link = '?upperli=li3&bottomli=lii6';
        }
        
        url = url.split('?')[0] + link;
        window.location.hash = url;
        ng.LoadAjaxContent(url);
        $('.modal-open').css("overflow", "scroll");
        $('.modal-backdrop').remove();
    });

    // PENETAPAN ALL
    function tetapkan_htb_all(){
        var jml_cek = $("#jml_check").val();
        confirm("Apakah anda yakin akan melakukan penetapan semua harta yang dipilih ? ", function () {
            $('.chk-htb:checkbox:checked').each(function(index){
                var id = $(this).val();
                if($(this).is(':checked')){
                    ajax_htb_penetapan(id, jml_cek, index);
                }
            })
        });
    }    

    function ajax_htb_penetapan(id, jml_cek, index){
        $('#loader_area').show();
        $.ajax({
            url: base_url + 'eaudit/klarifikasi/do_check_penetapan/' + id +'/t_htb',
            async: true,
            dataType: 'JSON',
            success: function (data) {
                var rs = eval(data);
                if (rs.result.LUAS_TANAH == 0 && rs.result.LUAS_BANGUNAN == 0){
                    notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan luas tanah dan luas bangunan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                }else if (rs.result.NILAI_PELAPORAN == 0){
                    notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan nilai pelaporan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                }else{
                    do_delete('eaudit/klarifikasi/do_penetapan/' + id + '/t_htb', 'Data Harta Tidak Bergerak Sudah Tetap ');

                    var url = location.href.split('#')[1];
                    url = url.split('?')[0] + '?upperli=li3&bottomli=lii0';
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
            },
        });
    }
       
    function chk_all_htb(ele){
        if($(ele).is(':checked')){
            $('.chk-htb').prop('checked', true);
        }else{
            $('.chk-htb').prop('checked', false);
        }
        cekTombolHtb("chk-htb");
    }       

    function chkHtb(ele){
        var val = $(ele).val();
        cekTombolHtb("chk-htb");
    }

    function cekTombolHtb(chkboxName){
        var checkboxes = document.getElementsByName(chkboxName);
            var checkboxesChecked = [];
            for (var i=0; i<checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                      checkboxesChecked.push(checkboxes[i]);
                    }
            }
            
            var jml_chk = checkboxesChecked.length;
            if (jml_chk === 0 ){
                $("#tombolTetapHtb").empty();
            } else{
                $("#tombolTetapHtb").html("<button style=\"margin-top: 10px;\" type=\"button\" class=\"btn btn-warning\" onclick=\"tetapkan_htb_all();\"title=\"Tetap\"><input type=\"hidden\" name=\"jml_check\" id=\"jml_check\"><i class=\"fa fa-plus-square\"></i> Tetapkan Semua</button>");
                $("#jml_check").val(jml_chk);
            }
    }

</script>
