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
    .title-hartaLain
    {
        background-color: rgba(191, 30, 46, 0.0);
        font-style: italic;
        color:black;
        /*font-size: 1px;*/
    }
</style>
<div class="box-header with-border portlet-header title-hartaLain">
    <h5 class="">"Harta berupa piutang, kerjasama usaha, HAKI, sewa dibayar dimuka atau hak pengelolaan"</h5>
</div>
<?php if ($viaa == '1'): ?>
    <br>&nbsp;&nbsp;&nbsp;<button class="btn btn-info" id="verifEditDataHartaLainnya" href="index.php/ever/verification_edit/update_harta_lainnya/<?php echo $item->ID_LHKPN;?>/new"><span class="fa fa-plus"></span> Tambah Data</button><br><br>
<?php endif ?>
<div class="box-body" id="wrapperHartaLain">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th width="70px">STATUS</th>
                <th width="30%">URAIAN</th>
                <th width="30%">ASAL USUL HARTA</th>
                <th width="20%">NILAI PEROLEHAN</th>
                <th width="20%">NILAI PELAPORAN</th>
                <th class="aksi">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $survey = 'ok';
            $i = 0;
            $no_column = 0;
            $totalLainya = 0;
            $stat = ['1'=>'Tetap','2'=>'Ubah','3'=>'Baru'];
            foreach ($HARTA_LAINNYAS as $hartalainnya) {
                $totalLainya += str_replace('.', '', $hartalainnya->NILAI_PELAPORAN);
                ?>

                <!-- /*ab penambahan kondisi untuk membuat baris menjadi hitam -->
                <?php
                    if ($hartalainnya->IS_PELEPASAN == '1'){ ?>
                        <tr style="background-color:#808080;color:#fff">
                <?php       }else{  ?>
                    <tr >
                <?php       } ?>

                    <td><?php echo ++$no_column; ?>.</td>
                    <td><?php
                        if ($hartalainnya->IS_PELEPASAN == '1'){
                            echo '<label class=\'label label-danger\'>Lepas</label>';
                        }else{
                            if ($hartalainnya->STATUS == '1'){
                             echo '<label class=\'label label-primary\'>'.$stat[$hartalainnya->STATUS].'</label>';}
                            else{
                            echo '<label class=\'label label-success\'>'.$stat[$hartalainnya->STATUS].'</label>';}
                        }
                        ?>
                    </td>
                <!-- /*ab penambahan kondisi untuk membuat baris menjadi hitam -->

                    <td>
                        <?php
//                         $img = null;
//                         if ($hartalainnya->FILE_BUKTI) {
//                             if (file_exists($hartalainnya->FILE_BUKTI)) {
//                                 $img = "  <a target='_blank' href='" . base_url() . '' . $hartalainnya->FILE_BUKTI . "'><i class='fa fa-download'></i></a>";
//                             }
//                         }
                        $img = null;
                        if ($hartalainnya->FILE_BUKTI) {

                            $filelist = explode(',', $hartalainnya->FILE_BUKTI);

                            $dir = null;

                            foreach ($filelist as $key => $tmp_name) {
                                //if (empty($tmp_name)) {

                                if ($key==0) {
                                    $dt = explode("/", $tmp_name);
                                    $c = count($dt);
                                    for($i=0; $i<$c-1; $i++) {
                                        $dir = $dir . $dt[$i] . "/";
                                    }
                                    $tmp_name = $dt[$i++];
                                }

                                //get data from minio
                                $ext = strtolower(end(explode('.',$tmp_name)));
                                if($ext=='png' || $ext=='jpg' || $ext=='jpeg'){
                                    $classIcon = 'fa-file-image-o';
                                }else{
                                    $classIcon = 'fa-file-text';
                                }
                                $checkFile = linkFromMinio($dir . $tmp_name,null,'t_lhkpn_harta_lainnya','ID',$hartalainnya->ID);
                                if($checkFile){
                                    $link = $checkFile;
                                    $img = $img . " <a class='files btnShow' href='index.php/ever/verification/display/image/". $hartalainnya->ID . '/hartalainnya/'. $tmp_name .  "'><i class='fa ".$classIcon." fa-2x'></i></a>";
                                }else{
                                  if (file_exists($dir . $tmp_name) && $tmp_name!="") {
                                    $tmp_name = $tmp_name;
                                    $img = $img . " <a class='files btnShow' href='index.php/ever/verification/display/image/". $hartalainnya->ID . '/hartalainnya/'. $tmp_name .  "'><i class='fa ".$classIcon." fa-2x'></i></a>";
                                  }
                                }
                            }
                        }

                        $uraian = "
					<table class='table-child table-condensed'>
						<tr>
						    <td><b>Jenis</b></td>
                            <td>:</td>
                            <td>" . $hartalainnya->NAMA . "  " . $img . "</td>
						 </tr>
						 <tr>
						    <td><b>Keterangan</b></td>
                            <td>:</td>
                            <td>" . @$hartalainnya->KETERANGAN . "</td>
						 </tr>
                         <tr>
						    <td><b>Tahun Perolehan</b></td>
                            <td>:</td>
                            <td>" . $hartalainnya->TAHUN_PEROLEHAN_AWAL . "</td>
						 </tr>
					</table>
				";

                        echo $uraian;
                        ?>
                    </td>

                    <td>


                                <?php
                                $exp = explode(',', $hartalainnya->ASAL_USUL);
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

                    </td>
                    <td>
                        <div class="row">
                            <div class="col-md-12" align="right">
                                <p id="PEROLEHAN_HL_<?php echo $no_column; ?>" class="NILAI_PEROLEHAN_HL">
                                    <?php echo $hartalainnya->SIMBOL . ' ' . number_format($hartalainnya->NILAI_PEROLEHAN, 0, '', '.'); ?>
                                </p>
                            </div>
                        </div>
                        <div>
                            <p name="TERBILANG_PEROLEHAN_HL_<?php echo $no_column; ?>" id="TERBILANG_PEROLEHAN_HL_<?php echo $no_column; ?>" style="border-radius:5px;padding:5px;background-color:#F39C12;"></p>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12" align="right">
                                <p id="PELAPORAN_HL_<?php echo $no_column; ?>" class="NILAI_PELAPORAN_HL">
                                    Rp. <?php echo number_format($hartalainnya->NILAI_PELAPORAN, 0, '', '.'); ?>
                                </p>
                            </div>
                        </div>
                        <div>
                            <p name="TERBILANG_PELAPORAN_HL_<?php echo $no_column; ?>" id="TERBILANG_PELAPORAN_HL_<?php echo $no_column; ?>" style="border-radius:5px;padding:5px;background-color:#F39C12;"></p>
                        </div>
                    </td>
                    <td class="aksi">
                        <button type="button" class="btn btn-sm btn-success btnCek" href="index.php/ever/verification/display/upload/<?php echo $hartalainnya->ID; ?>/hartalainnya" >Upload</button>
                        <?php if ($viaa == '1'): ?>
                            <button type="button" class="btn btn-primary" href="index.php/ever/verification_edit/update_harta_lainnya/<?php echo $hartalainnya->ID; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-danger" href="index.php/ever/verification_edit/soft_delete/<?php echo $hartalainnya->ID; ?>/harta_lainnya" title="Hapus Data" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>
                        <?php endif ?>
                    </td>

                </tr>
                    <?php } ?>
        </tbody>
        <tfoot class="table-footer">
            <tr>
                <td colspan="5"><b>Sub Total/Total</b></td>
                <td>
                    <div class="text-right">
                        <p id="TOTAL_HL" class="NILAI_TOTAL_HL">
                            <b>Rp. <?php echo number_format($totalLainya, 0, '', '.'); ?></b>
                        </p>
                    </div>
                    <div>
                        <p name="TERBILANG_TOTAL_HL" id="TERBILANG_TOTAL_HL" style="border-radius:5px;padding:5px;background-color:#F39C12;"></p>
                    </div>
                </td>
                <td class="aksi"></td>
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#verifEditDataHartaLainnya").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Verifikasi Data Harta Lainnya', html, null, 'large');
            });
            return false;
        });
        $("#wrapperHartaLain .btnCek").click(function() {
            var url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Cek Data Harta Lainnya', html, '', 'standart');
            });
            return false;
        });
        $("#wrapperHartaLain .btnShow").click(function() {
            var url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('File Dokumen Pendukung', html, '', 'large');
            });
            return false;
        });

    });

    showFieldEver('.NILAI_PEROLEHAN_HL','#TERBILANG_');
    showFieldEver('.NILAI_PELAPORAN_HL','#TERBILANG_');
    showFieldEver('.NILAI_TOTAL_HL','#TERBILANG_');

</script>
