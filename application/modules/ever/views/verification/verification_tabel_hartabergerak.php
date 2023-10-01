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
 * @package Views/ever/verification
 */
?>
<style type="text/css">
    .title-alat
    {
        background-color: rgba(191, 30, 46, 0.0);
        font-style: italic;
        color:black;
        /*font-size: 1px;*/
    }
</style>
<div class="box-header with-border portlet-header title-alat">
    <h5 class="">"Harta Bergerak berupa alat transportasi dan mesin"</h5>
</div>
<?php if ($viaa == '1'): ?>
    <br>&nbsp;&nbsp;&nbsp;<button class="btn btn-info" id="verifEditDataHartaBergerak" href="index.php/ever/verification_edit/update_harta_bergerak/<?php echo $item->ID_LHKPN;?>/new"><span class="fa fa-plus"></span> Tambah Data</button><br><br>
<?php endif ?>
<div class="box-body" id="wrapperHartaBergerak">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th width="70px">STATUS</th>
                <th width="30%">URAIAN</th>
                <th width="30%">KEPEMILIKAN</th>
                <th width="20%">NILAI PEROLEHAN</th>
                <th width="20%">NILAI PELAPORAN</th>
                <?php if ($viaa == '1'): ?>
                    <th>AKSI</th>
                <?php endif ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $survey = 'ok';
            $i = 0;
            $totalHartaBergerak = 0;
            $stat = ['1'=>'Tetap','2'=>'Ubah','3'=>'Baru'];
            foreach ($HARTA_BERGERAKS as $hartabergerak) {
                $totalHartaBergerak += $hartabergerak->NILAI_PELAPORAN;


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



                ?>
                <!-- /*ab penambahan kondisi untuk membuat baris menjadi hitam -->               
                <?php 
                    if ($hartabergerak->IS_PELEPASAN == '1'){ ?>
                        <tr style="background-color:#808080;color:#fff">
                <?php       }else{  ?>
                    <tr >
                <?php       } ?>

                    <td><?php echo ++$i; ?>.</td>
                    <td><?php
                        if ($hartabergerak->IS_PELEPASAN == '1'){
                            echo '<label class=\'label label-danger\'>Lepas</label>';
                        }else{
                            if ($hartabergerak->STATUS == '1'){
                             echo '<label class=\'label label-primary\'>'.$stat[$hartabergerak->STATUS].'</label>';}
                            else{
                            echo '<label class=\'label label-success\'>'.$stat[$hartabergerak->STATUS].'</label>';}
                        }
                        ?>
                    </td>
                <!-- /*ab penambahan kondisi untuk membuat baris menjadi hitam -->   
                    <td><b>Jenis : </b><?php echo $list_harta[$hartabergerak->KODE_JENIS] ?><br>
                        <b>Merek : </b> <?php echo $hartabergerak->MEREK; ?><br>
                        <b>Model : </b> <?php echo $hartabergerak->MODEL; ?><br>
                        <b>Tahun Pembuatan : </b> <?php echo $hartabergerak->TAHUN_PEMBUATAN; ?><br>
                        <b>No Pol / Registrasi : </b> <?php echo $hartabergerak->NOPOL_REGISTRASI; ?><br>
                    </td>

                    <td>
                        <?php
                        // $an = array('', 'PN YANG BERSANGKUTAN', 'PASANGAN / ANAK', 'LAINNYA');
                        // if ($hartabergerak->ATAS_NAMA == '3') {
                        //     $atas_nama = $hartabergerak->KET_LAINNYA;
                        // }
                        // else{
                        //     $atas_nama = $an[$hartabergerak->ATAS_NAMA];
                        // }
                        if ($hartabergerak->JENIS_BUKTI == '8') {
                            echo 'Bukti Lain';
                        } else {
                            echo '<b>Jenis bukti : </b>'.$list_bukti_alat_transportasi[$hartabergerak->JENIS_BUKTI];
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
                        <b>Tahun Perolehan : </b>
                        <?php
                        echo $hartabergerak->TAHUN_PEROLEHAN_AWAL;
                        ?>
                        
                    </td>

                    <td>
                        <div align="right">
                            <p id="PEROLEHAN_HB_<?php echo $i; ?>" class="NILAI_PEROLEHAN_HB">
                                <?php echo $hartabergerak->SIMBOL; ?>
                                <!-- </div> -->
                                <!-- <div align="right"> -->
                                <?php echo number_format($hartabergerak->NILAI_PEROLEHAN, 0, '', '.'); ?>
                            </p>
                        </div>
                        <div>
                            <p name="TERBILANG_PEROLEHAN_HB_<?php echo $i; ?>" id="TERBILANG_PEROLEHAN_HB_<?php echo $i; ?>" style="border-radius:5px;padding:5px;background-color:#F39C12;"></p>
                        </div>
                    </td>
                    <td>
                        <div align="right">
                            <p id="PELAPORAN_HB_<?php echo $i; ?>" class="NILAI_PELAPORAN_HB">
                                Rp. <?php echo number_format($hartabergerak->NILAI_PELAPORAN, 0, '', '.'); ?>
                            </p>
                        </div>
                        <div>
                            <p name="TERBILANG_PELAPORAN_HB_<?php echo $i; ?>" id="TERBILANG_PELAPORAN_HB_<?php echo $i; ?>" style="border-radius:5px;padding:5px;background-color:#F39C12;"></p>
                        </div>
                    </td>
                    <?php if ($viaa == '1'): ?>
                    <td align="center">
                        <button type="button" id="verifEditDataHTBEdit" class="btn btn-primary" href="index.php/ever/verification_edit/update_harta_bergerak/<?php echo $hartabergerak->ID; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-danger" href="index.php/ever/verification_edit/soft_delete/<?php echo $hartabergerak->ID; ?>/harta_bergerak" title="Hapus Data" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>
                    </td>
                    <?php endif ?>

                </tr>
            <?php } ?>
        </tbody>
        <tfoot class="table-footer">
            <tr>
                <td colspan="5"><b>Sub Total/Total</b></td>
                <td>
                    <div class="text-right">
                        <p id="TOTAL_HB" class="NILAI_TOTAL_HB">
                            <b>Rp. <?php echo number_format($totalHartaBergerak, 0, '', '.'); ?></b>
                        </p>
                    </div>
                    <div>
                        <p name="TERBILANG_TOTAL_HB" id="TERBILANG_TOTAL_HB" style="border-radius:5px;padding:5px;background-color:#F39C12;"></p>
                    </div>
                </td>
                <?php if ($viaa == '1'): ?>
                    <td></td>
                <?php endif ?>
            </tr>
        </tfoot>
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#verifEditDataHartaBergerak").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Verifikasi Data Harta Bergerak', html, null, 'large');
            });
            return false;
        });
    });

    showFieldEver('.NILAI_PEROLEHAN_HB','#TERBILANG_');
    showFieldEver('.NILAI_PELAPORAN_HB','#TERBILANG_');
    showFieldEver('.NILAI_TOTAL_HB','#TERBILANG_');

</script>
