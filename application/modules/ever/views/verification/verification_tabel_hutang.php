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
    .title-hutang
    {
        background-color: rgba(191, 30, 46, 0.0);
        font-style: italic;
        color:black;
        /*font-size: 1px;*/
    }
</style>
<div class="box-header with-border portlet-header title-hutang">
    <h5 class="">"Hutang apabila ada"</h5>
</div>
<?php if ($viaa == '1'): ?>
    <br>&nbsp;&nbsp;&nbsp;<button class="btn btn-info" id="verifEditDataHutang" href="index.php/ever/verification_edit/update_hutang/<?php echo $item->ID_LHKPN;?>/new"><span class="fa fa-plus"></span> Tambah Data</button><br><br>
<?php endif ?>
<div class="box-body" id="wrapperHutang">
    <table class="table table-bordered table-hover">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th width="70px">STATUS</th>
                <th width="40%">URAIAN</th>
                <th width="20%">NAMA KREDITUR</th>
                <th width="20%">BENTUK AGUNAN/ NO KARTU KREDIT</th>
                <th width="20%">SALDO HUTANG SAAT PELAPORAN</th>
                <?php if ($viaa == '1'): ?>
                    <th>AKSI</th>
                <?php endif ?>
            </tr>                 
        </thead>
        <tbody>
            <?php
            $survey = 'ok';
            $tot = 0;
            $i = 0;
            $stat = ['1'=>'Tetap','2'=>'Ubah','3'=>'Baru'];
            foreach ($HUTANGS as $hutang) {
                $tot += $hutang->SALDO_HUTANG;






                $get_atas_nama = $hutang->ATAS_NAMA;
                $atas_nama = '';     
                $get_atas_nama = check_atas_nama($get_atas_nama);
                if(strstr($get_atas_nama, "5")){    
                    $atas_nama = '<b>'.substr($get_atas_nama,2).'</b>';
                }          
                if(strstr($get_atas_nama, "1")){
                    $atas_nama = '<b>PN YANG BERSANGKUTAN</b>';
                }        
                if(strstr($get_atas_nama, "2")){
                    
                    $pasangan_array = explode(',', $hutang->PASANGAN_ANAK);
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
                        $atas_nama = $atas_nama.'<b>LAINNYA</b> ('.$hutang->KET_LAINNYA.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>LAINNYA</b> ('.$hutang->KET_LAINNYA.')' ;
                    }
                }






                ?>
                
                <!-- /*ab penambahan kondisi untuk membuat baris menjadi hitam -->               
                <?php 
                    if ($hutang->IS_PELEPASAN == '1'){ ?>
                        <tr style="background-color:#808080;color:#fff">
                <?php       }else{  ?>
                    <tr >
                <?php       } ?>

                    <td><?php echo ++$i; ?>.</td>
                    <td><?php
                        if ($hutang->IS_PELEPASAN == '1'){
                            echo '<label class=\'label label-danger\'>Lepas</label>';
                        }else{
                            if ($hutang->STATUS == '1'){
                             echo '<label class=\'label label-primary\'>'.$stat[$hutang->STATUS].'</label>';}
                            else{
                            echo '<label class=\'label label-success\'>'.$stat[$hutang->STATUS].'</label>';}
                        }
                        ?>
                    </td>
                <!-- /*ab penambahan kondisi untuk membuat baris menjadi hitam -->   
                
                    <td><?php
                        $jenis = "
					<table class='table-child table-condensed'>
						<tr>
						    <td><b>Jenis</b></td>
                                                    <td>:</td>
                                                    <td>" . $hutang->NAMA . "</td>
						 </tr>
						 <tr>
						    <td><b>Atas Nama</b></td>
                                                    <td>:</td>
                                                    <td>" . $atas_nama . "</td>
						 </tr>
					</table>
				";

                        echo $jenis;
                        
                        ?>
                    </td>
                    <td><?php echo $hutang->NAMA_KREDITUR; ?></td>
                    <td><?php echo $hutang->AGUNAN; ?></td>
                    <td>
                        <div class="row">
                            <div class="col-md-12" align="right">
                                <p id="PELAPORAN_HT_<?php echo $i; ?>" class="NILAI_PELAPORAN_HT">
                                    Rp. <?php echo number_format($hutang->SALDO_HUTANG, 0, '', '.'); ?>
                                </p>
                            </div>
                        </div>
                        <div>
                            <p name="TERBILANG_PELAPORAN_HT_<?php echo $i; ?>" id="TERBILANG_PELAPORAN_HT_<?php echo $i; ?>" style="border-radius:5px;padding:5px;background-color:#F39C12;"></p>
                        </div>
                    </td>
                    <?php if ($viaa == '1'): ?>
                    <td align="center">
                        <button type="button" id="verifEditDataHTBEdit" class="btn btn-primary" href="index.php/ever/verification_edit/update_hutang/<?php echo $hutang->ID_HUTANG; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-danger" href="index.php/ever/verification_edit/soft_delete/<?php echo $hutang->ID_HUTANG; ?>/hutang" title="Hapus Data" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>
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
                        <p id="TOTAL_HT" class="NILAI_TOTAL_HT">
                            <b>Rp. <?php echo number_format($tot, 0, '', '.'); ?></b>
                        </p>
                    </div>
                    <div>
                        <p name="TERBILANG_TOTAL_HT" id="TERBILANG_TOTAL_HT" style="border-radius:5px;padding:5px;background-color:#F39C12;"></p>
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
    $(document).ready(function () {
        $("#verifEditDataHutang").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Verifikasi Data Hutang', html, null, 'large');
            });            
            return false;
        });
    });

    showFieldEver('.NILAI_PELAPORAN_HT','#TERBILANG_');
    showFieldEver('.NILAI_TOTAL_HT','#TERBILANG_');

</script>
