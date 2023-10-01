<style type="text/css">
    .title-alat
    {
        background-color: rgba(191, 30, 46, 0.0);
        font-style: italic;
        color:black;
        /*font-size: 1px;*/
    }

    .inputFile {
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        position: absolute;
        z-index: -1;
    }

    .inputFile + label {
        cursor: pointer;
        margin-left: 10px;
        /*font-size: 1.25em;*/
    }

    .inputFile:focus + label,
    .inputFile + label:hover {
        cursor: pointer;
        /*background-color: red;*/
    }

    .td-lhkpn-excel, .td-aksi{
        text-align: center;
    }

    .td-lhkpn-excel, .td-aksi, .td-nama-file{
        font-size: 12px;
        margin: 5px;
    }
</style>
<div class="box-header with-border portlet-header title-alat">
    <h5 class="">"Upload Surat Kuasa Mengumumkan dan Surat Kuasa"</h5>
    <?php
    
    $id_lhkpn_skm_log = [];
    $file_skm_log = [];

    $id_lhkpn_sk_log = [];
    $file_sk_log = [];

    foreach($log_file_bukti_sk as $log_file){
        // if($log_file->ID_LHKPN!=$item->ID_LHKPN){
        //     ///////////////SK/////////////////////
        //     $check_log_sk = trim($log_file->FILE_BUKTI_SK);
        //     if($check_log_sk=='' || $check_log_sk==null || $check_log_sk=='[""]' || $check_log_sk=="['']" || $check_log_sk=="null"){
        //     }else{
        //         $file_surat_kuasa_log[] = $log_file->FILE_BUKTI_SK;
        //         $id_lhkpn_log[] = encrypt_username($log_file->ID_LHKPN, 'e');
        //     }


        //     ///////////////SKM/////////////////////
        //     $check_log_skm = trim($log_file->FILE_BUKTI_SKM);
        //     if($check_log_skm=='' || $check_log_skm==null || $check_log_skm=='[""]' || $check_log_skm=="['']" || $check_log_skm=="null"){
        //     }else{
        //         $file_surat_kuasa_mengumumkan_log[] = $log_file->FILE_BUKTI_SKM;
        //         // $id_lhkpn_log[] = encrypt_username($log_file->ID_LHKPN, 'e');
        //     }
        // }else{
        //     ///////////////SK/////////////////////
        //     $check_org_sk = trim($log_file->FILE_BUKTI_SK);
        //     if($check_org_sk==null || $check_org_sk== '[""]' || $check_org_sk== "['']" || $check_org_sk=="" || $check_org_sk=="null" ){
        //         $file_surat_kuasa_original = [];
        //     }else{
        //         $file_surat_kuasa_original = $log_file->FILE_BUKTI_SK;

        //     }
        //     $id_lhkpn_original = $log_file->ID_LHKPN;


        //     ///////////////SKM/////////////////////
        //     $check_org_skm = trim($log_file->FILE_BUKTI_SKM);
        //     if($check_org_skm==null || $check_org_skm== '[""]' || $check_org_skm== "['']" || $check_org_skm=="" || $check_org_skm=="null" ){
        //         $file_surat_kuasa_mengumumkan_original = [];
        //     }else{
        //         $file_surat_kuasa_mengumumkan_original = $log_file->FILE_BUKTI_SKM;

        //     }
        // }

        $check_log_sk2 = trim($log_file->FILE_BUKTI_SK);
        if($check_log_sk2!='' && $check_log_sk2!=null && $check_log_sk2!='[""]' && $check_log_sk2!="['']" && $check_log_sk2!="null"){
            $file_sk_log[] = $log_file->FILE_BUKTI_SK;
            $id_lhkpn_sk_log[] = encrypt_username($log_file->ID_LHKPN, 'e');
        }

        $check_log_skm2 = trim($log_file->FILE_BUKTI_SKM);
        if($check_log_skm2!='' && $check_log_skm2!=null && $check_log_skm2!='[""]' && $check_log_skm2!="['']" && $check_log_skm2!="null"){
            $file_skm_log[] = $log_file->FILE_BUKTI_SKM;
            $id_lhkpn_skm_log[] = encrypt_username($log_file->ID_LHKPN, 'e');
        }


    }
    $dataSKM = array_combine($id_lhkpn_skm_log, $file_skm_log);
    $dataSK = array_combine($id_lhkpn_sk_log, $file_sk_log);
    ?>
</div>
<div class="box-body" id="wrapperDokumenPendukung">
    <!-- <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-pribadi-luar">
        <div class="row">
            <div class="col-sm-12">
                <div class="box-header with-border portlet-header judul-header">
                    <h3 class="box-title">Surat Kuasa Mengumumkan</h3>
                </div>
            </div>
        </div>
        <div class="row">
            &nbsp;
        </div>
        <div class="row" style="<?php echo ($display == 'infoLHKPN') ? 'padding-left: 18px' : ''; ?>" >
            <input type="file" id="file1" name="file1" class="inputFile aksi" data-allowed-file-extensions='["pdf", "jpg", "png", "jpeg", "doc", "docx", "tif", "tiff"]'  accept='.pdf,.jpg,.png,.jpeg,.doc,.docx,.tif,.tiff'  data-show-preview="true" required multiple />
            <label for="file1" class="btn btn-sm btn-primary aksi">Pilih File</label>
            <label class="btn btn-sm btn-warning" id="btnLogSuratKuasaMengumumkan" style="display:none">Preview SK Terdahulu</label>
            <label class="btn btn-sm btn-success" id="btnLogSuratKuasaMengumumkanDefault" style="display:none">Kembali ke Tampilan Awal</label>
        </div>
        <div class="row">
            &nbsp;
            <input type="hidden" name="ID_LHKPN" value="<?php echo $item->ID_LHKPN; ?>" required>
            <input type="hidden" name="ID" value="<?php echo $ID; ?>" required>
        </div>
        <table class="table table-bordered table-hover table-striped" id="tableSKM">
            <thead class="table-header">
                <tr>
                    <th>Upload Dokumen (pdf/doc(x)/jpg/png/jpeg)</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div> -->

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-pribadi-luar">
        <div class="row">
            <div class="col-sm-12">
                <div class="box-header with-border portlet-header judul-header">
                    <h3 class="box-title">Surat Kuasa</h3>
                </div>
            </div>
        </div>
        <div class="row">
            &nbsp;
        </div>
        <div class="row" style="<?php echo ($display == 'infoLHKPN') ? 'padding-left: 18px' : ''; ?>" >
            <input type="file" id="file2" name="file2" class="inputFile aksi"  data-allowed-file-extensions='["pdf", "jpg", "png", "jpeg", "doc", "docx", "tif", "tiff"]'  accept='.pdf,.jpg,.png,.jpeg,.doc,.docx,.tif,.tiff'  data-show-preview="true" required multiple />
            <!-- <label for="file2" class="btn btn-sm btn-primary aksi">Pilih File</label> -->
            <label class="btn btn-sm btn-warning" id="btnLogSuratKuasa" style="display:none">Preview SK Terdahulu</label>
            <label class="btn btn-sm btn-success" id="btnLogSuratKuasaDefault" style="display:none">Kembali ke Tampilan Awal</label>
        </div>
        <div class="row">
            &nbsp;
        </div>
        <table class="table table-bordered table-hover table-striped" id="tableSK">
            <thead class="table-header">
                <tr>
                    <th>Surat Kuasa (pdf/doc(x)/jpg/png/jpeg)</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <?php
        $i = 0;
        $no = 1;
        ?>
         <table class="table table-striped table-bordered table-hover table-heading no-border-bottom" style="margin-bottom: 40px;">
            <thead>
                <tr>
                    <th width="1%">No.</th>
                    <th width="20%">Nama</th>
                    <th width="14%">Keterangan</th>
                    <th width="18%">Hubungan</th>
                    <th width="12%">Status SK</th>
                </tr>
            </thead>
            <tbody>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td class="text-left"><?= $DATA_PRIBADI->NAMA_LENGKAP; ?></td>
                        <td class="text-center">PN</td>
                        <td class="text-center">-</td>
                        <td class="text-center"><input type="hidden" name="statusSK[<?php echo $i;?>]" value="<?php echo $DATA_PRIBADI->FLAG_SK; ?>"><span id="stsSK<?php echo $i;?>"></span></td>
                    </tr>

                    <?php
                    $i = 1;
                    foreach ($data_keluarga as $keluarga) {
                        if ($keluarga->umur_lapor < 17) { continue; }
                        if ($lhkpn_ver == '1.6' || $lhkpn_ver == '1.8' || $lhkpn_ver == '1.11'|| $lhkpn_ver == '2.1') {
                            if($keluarga->PN == 1){
                                if( $keluarga->HUBUNGAN == 2 || $keluarga->HUBUNGAN == 3){$pn = "PN";}
                                else{$pn = "-";}}
                            else{$pn = "-";}
                            $arr_hubungan = ['2' => 'SUAMI', '3' => 'ISTRI', '4' => 'ANAK TANGGUNGAN', '5' => 'ANAK BUKAN TANGGUNGAN'];
                            if ($keluarga->HUBUNGAN == 2 || $keluarga->HUBUNGAN == 3 || $keluarga->HUBUNGAN == 4) {
                    ?>
                                 <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td class="text-left"><?= $keluarga->NAMA; ?></td>
                                    <td class="text-center"><?= $pn; ?></td>
                                    <td class="text-center"><?= $arr_hubungan[$keluarga->HUBUNGAN]; ?></td>
                                    <td class="text-center"><input type="hidden" name="statusSK[<?php echo $i;?>]" value="<?php echo $keluarga->FLAG_SK; ?>"><span id="stsSK<?php echo $i;?>"></span></td>
                                </tr>
                    <?php
                            $i++;
                            } else { continue; }
                        } else {
                            if($keluarga->PN == 1){
                                if( $keluarga->HUBUNGAN == 1 || $keluarga->HUBUNGAN == 2){$pn = "PN";}
                                else{$pn = "-";}}
                            else{$pn = "-";}
                            $arr_hubungan = ['1' => 'ISTRI', '2' => 'SUAMI', '3' => 'ANAK TANGGUNGAN', '4' => 'ANAK BUKAN TANGGUNGAN', '5' => 'LAINNYA'];
                            if ($keluarga->HUBUNGAN == 1 || $keluarga->HUBUNGAN == 2 || $keluarga->HUBUNGAN == 3) {
                    ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td class="text-left"><?= $keluarga->NAMA; ?></td>
                                    <td class="text-center"><?= $pn; ?></td>
                                    <td class="text-center"><?= $arr_hubungan[$keluarga->HUBUNGAN]; ?></td>
                                    <td class="text-center"><input type="hidden" name="statusSK[<?php echo $i;?>]" value="<?php echo $keluarga->FLAG_SK; ?>"><span id="stsSK<?php echo $i;?>"></span></td>
                                </tr>
                    <?php
                            $i++;
                            } else { continue; }
                        }
                    }
                    ?>
            </tbody>

        </table>
        
        <div class="row">
            &nbsp;
        </div>
    </div>

</div><!-- /.box-body -->
<div class="box-footer aksi">
    <div class="col-md-2 col-md-offset-10">
        <a id="btn-simpan-upload" href="#" class="btn btn-sm btn-primary">Simpan</a>
        <!--<a id="btn-simpan-upload" href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalSK">Simpan</a>-->
    </div>
</div><!-- /.box-footer -->

<!-- Modal -->
<div class="modal fade" id="modalSK" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalSKLabel">KONFIRMASI PENERIMAAN SK</h4>
            </div>
            <div class="modal-body">
                <div class="checkbox">
                    <label id="namaNama"><input type="checkbox" id="flagSKUploadPN" name="flagSKUpload" value="1" <?php echo ($DATA_PRIBADI->FLAG_SK == '1') ? 'checked' : ''; ?> /><?php echo $DATA_PRIBADI->NAMA_LENGKAP ." (PN)"; ?></label>
                </div>
                <?php
                foreach ($KELUARGAS as $keluarga) {
                    if ($keluarga->umur_lapor < 17) { continue; }
                    if ($lhkpn_ver == '1.6' || $lhkpn_ver == '1.8' || $lhkpn_ver == '1.11'|| $lhkpn_ver == '2.1') {
                        $arr_hubungan = ['2' => 'SUAMI', '3' => 'ISTRI', '4' => 'ANAK TANGGUNGAN', '5' => 'ANAK BUKAN TANGGUNGAN'];
                        if ($keluarga->HUBUNGAN == 2 || $keluarga->HUBUNGAN == 3 || $keluarga->HUBUNGAN == 4) {
                ?>
                            <div class="checkbox">
                                <label id="namaNama"><input type="checkbox" id="flagSKUpload" name="flagSKUpload" value="<?php echo $keluarga->ID_KELUARGA; ?>" <?php echo ($keluarga->FLAG_SK == '1') ? 'checked' : ''; ?> /><?php echo $keluarga->NAMA ." (". $arr_hubungan[$keluarga->HUBUNGAN] .")"; ?></label>
                            </div>
                <?php
                        }
                    } else {
                        $arr_hubungan = ['1' => 'ISTRI', '2' => 'SUAMI', '3' => 'ANAK TANGGUNGAN', '4' => 'ANAK BUKAN TANGGUNGAN', '5' => 'LAINNYA'];
                        if ($keluarga->HUBUNGAN == 1 || $keluarga->HUBUNGAN == 2 || $keluarga->HUBUNGAN == 3) {
                ?>
                            <div class="checkbox">
                                <label id="namaNama"><input type="checkbox" id="flagSKUpload" name="flagSKUpload" value="<?php echo $keluarga->ID_KELUARGA; ?>" <?php echo ($keluarga->FLAG_SK == '1') ? 'checked' : ''; ?> /><?php echo $keluarga->NAMA ." (". $arr_hubungan[$keluarga->HUBUNGAN] .")"; ?></label>
                            </div>
                <?php
                        }
                    }
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button id="btn-simpan-upload2" type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>

<?php
$checkData = 0;
?>
<script type="text/javascript">






/////////////////////////////PREVIEW BUTTON SK////////////////////////////////////
    // var check_file_original = <?php //echo json_encode($file_surat_kuasa_original) ?>;
    // if(check_file_original){
    //     var file_surat_kuasa_original  = <?php //echo $file_surat_kuasa_original ?>;
    // }
    // var file_surat_kuasa_log = <?php //echo json_encode($file_surat_kuasa_log) ?>;
    // var id_lhkpn_original = <?php //echo $id_lhkpn_original ?>;
    // var id_lhkpn_log = <?php //echo json_encode($id_lhkpn_log) ?>;
    // var loadData = 0;
    // if(!file_surat_kuasa_log){
    //     $('#btnLogSuratKuasa').hide();
    // }
    // $('#btnLogSuratKuasa').click(function() {
    //     $('#tableSK tbody').empty();
    //     if(file_surat_kuasa_log[loadData]==null){
    //     }else{
    //         var kuasaArray = JSON.parse(file_surat_kuasa_log[loadData]);
    //         kuasaArray.forEach(function(entry, index) {
    //                     var href_sk = "<?php //echo base_url('uploads'); ?>/data_sk/<?php //echo encrypt_username($LHKPN->NIK, 'e'); ?>/"+id_lhkpn_log[loadData]+"/"+entry;
    //                     $('#tableSK tbody').append('<tr fnm="'+entry+'">'
    //                     +  '<td class="td-nama-file">'+entry+'</td>'
    //                     + '<td class="td-lhkpn-excel">'
    //                          + '<a class="btn btn-sm" target="_blank" href="'+href_sk+'"><span class="glyphicon glyphicon-new-window text-danger"></span></a>'
    //                     +'</td></tr>');
    //         });
    //     }
    //     $('#btnLogSuratKuasaDefault').show();
    //     $('#btn-simpan-upload').hide();
    //     loadData++;
    //     if(file_surat_kuasa_log.length==loadData){
    //         $('#btnLogSuratKuasa').hide();
    //         loadData = 0;
    //     }
    // });
    // $('#btnLogSuratKuasaDefault').click(function() {
    //     $('#tableSK tbody').empty();
    //     if(check_file_original=="null" || check_file_original=="[]" || check_file_original==null || check_file_original==[] || check_file_original.length==0){

    //     }else{
    //         file_surat_kuasa_original.forEach(function(entry, index) {
    //                     var href_sk = "<?php //echo base_url('uploads'); ?>/data_sk/<?php //echo encrypt_username($LHKPN->NIK, 'e'); ?>/<?php //echo encrypt_username($ID_LHKPN, 'e'); ?>/"+entry;
    //                     $('#tableSK tbody').append('<tr fnm="'+entry+'">'
    //                     +  '<td class="td-nama-file">'+entry+'</td>'
    //                     + '<td class="td-lhkpn-excel">'
    //                         + '<a class="btn btn-sm remFile" id="remFile2"   onClick="hapusFile(this)"     href="#"><span class="glyphicon glyphicon-remove text-danger"></span></a>'
    //                         + '<a class="btn btn-sm" target="_blank" href="'+href_sk+'"><span class="glyphicon glyphicon-new-window text-danger"></span></a>'
    //                     + '</td></tr>');
    //         });
    //     }
    //     $('#btnLogSuratKuasaDefault').hide();
    //     $('#btnLogSuratKuasa').show();
    //     $('#btn-simpan-upload').show();
    //     <?php
    //         if ($display == 'infoLHKPN') {
    //     ?>
    //     $('.remFile').hide();
    //     <?php
    //         }
    //     ?>
    // });
/////////////////////////////TUTUP PREVIEW BUTTON SK////////////////////////////////////



/////////////////////////////PREVIEW BUTTON SKM////////////////////////////////////
    // var check_file_mengumumkan_original = <?php //echo json_encode($file_surat_kuasa_mengumumkan_original) ?>;
    // if(check_file_mengumumkan_original){
    //     var file_surat_kuasa_mengumumkan_original  = <?php //echo $file_surat_kuasa_mengumumkan_original ?>;
    // }
    // var file_surat_kuasa_mengumumkan_log = <?php //echo json_encode($file_surat_kuasa_mengumumkan_log) ?>;
    // // var id_lhkpn_original = <?php //echo $id_lhkpn_original ?>;
    // // var id_lhkpn_log = <?php //echo json_encode($id_lhkpn_log) ?>;
    // var loadData = 0;
    // if(!file_surat_kuasa_mengumumkan_log){
    //     $('#btnLogSuratKuasaMengumumkan').hide();
    // }
    // $('#btnLogSuratKuasaMengumumkan').click(function() {
    //     $('#tableSKM tbody').empty();
    //     if(file_surat_kuasa_mengumumkan_log[loadData]==null){
    //     }else{
    //         var kuasaArrayMengumumkan = JSON.parse(file_surat_kuasa_mengumumkan_log[loadData]);
    //         kuasaArrayMengumumkan.forEach(function(entry, index) {
    //                     var href_skm = "<?php //echo base_url('uploads'); ?>/data_skm/<?php //echo encrypt_username($LHKPN->NIK, 'e'); ?>/"+id_lhkpn_log[loadData]+"/"+entry;
    //                     $('#tableSKM tbody').append('<tr fnm="'+entry+'">'
    //                     +  '<td class="td-nama-file">'+entry+'</td>'
    //                     + '<td class="td-lhkpn-excel">'
    //                          + '<a class="btn btn-sm" target="_blank" href="'+href_skm+'"><span class="glyphicon glyphicon-new-window text-danger"></span></a>'
    //                     +'</td></tr>');
    //         });
    //     }
    //     $('#btnLogSuratKuasaMengumumkanDefault').show();
    //     $('#btn-simpan-upload').hide();
    //     loadData++;
    //     if(file_surat_kuasa_mengumumkan_log.length==loadData){
    //         $('#btnLogSuratKuasaMengumumkan').hide();
    //         loadData = 0;
    //     }
    // });
    // $('#btnLogSuratKuasaMengumumkanDefault').click(function() {
    //     $('#tableSKM tbody').empty();
    //     if(check_file_mengumumkan_original=="null" || check_file_mengumumkan_original=="[]" || check_file_mengumumkan_original==null || check_file_mengumumkan_original==[] || check_file_mengumumkan_original.length==0){

    //     }else{
    //         file_surat_kuasa_mengumumkan_original.forEach(function(entry, index) {
    //                     var href_skm = "<?php //echo base_url('uploads'); ?>/data_skm/<?php //echo encrypt_username($LHKPN->NIK, 'e'); ?>/<?php //echo encrypt_username($ID_LHKPN, 'e'); ?>/"+entry;
    //                     $('#tableSKM tbody').append('<tr fnm="'+entry+'">'
    //                     +  '<td class="td-nama-file">'+entry+'</td>'
    //                     + '<td class="td-lhkpn-excel">'
    //                         + '<a class="btn btn-sm remFile" id="remFile2"   onClick="hapusFile(this)"     href="#"><span class="glyphicon glyphicon-remove text-danger"></span></a>'
    //                         + '<a class="btn btn-sm" target="_blank" href="'+href_sk+'"><span class="glyphicon glyphicon-new-window text-danger"></span></a>'
    //                     + '</td></tr>');
    //         });
    //     }
    //     $('#btnLogSuratKuasaMengumumkanDefault').hide();
    //     $('#btnLogSuratKuasaMengumumkan').show();
    //     $('#btn-simpan-upload').show();
    //     <?php
    //         if ($display == 'infoLHKPN') {
    //     ?>
    //     $('.remFile').hide();
    //     <?php
    //         }
    //     ?>
    // });
/////////////////////////////TUTUP PREVIEW BUTTON SKM////////////////////////////////////










    function hapusFile(anchorObjRem){
        if (isDefined(anchorObjRem)) {
                var upl = 'sk', tableName = $(anchorObjRem).parent().parent().parent().parent().attr("id");
                var trr = $(anchorObjRem).parent().parent(), fnm = $(trr).attr("fnm");


                if (tableName == 'tableSKM') {
                    upl = 'skm';
                }

                $.ajax({
                    url: "<?php echo base_url('ever/verification/removeFileLampiran'); ?>?ikin=<?php echo encrypt_username($LHKPN->NIK, 'e'); ?>&inpkhl=<?php echo encrypt_username($ID_LHKPN, 'e'); ?>&upl=" + upl,
                    data: {
                        inpkhl: '<?php echo $item->ID_LHKPN; ?>',
                        fnm: fnm,
                    },
                    type: "POST",
                    success: function (data) {
                        $(trr).remove();
                    }
                });

            }
    }

    var skmidx = 0;

    function UploadFileSk(file, actionUrl, progressBar, allowedFileType, callbackIfDone) {

        if (!isDefined(allowedFileType) || (isDefined(allowedFileType) && allowedFileType != false)) {
            allowedFileType = [
                "application/msword",
                "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                "application/pdf",
                "text/plain",
                "image/jpeg",
                "image/png",
                "image/bmp",
                "image/gif",
                "image/tiff",
            ];
        }

        var fileTypeOk = jQuery.inArray(file.type, allowedFileType) !== -1;

        // following line is not necessary: prevents running on SitePoint servers
        if (location.host.indexOf("sitepointstatic") >= 0) {
            return;
        }

        var xhr = new XMLHttpRequest();
//        if (xhr.upload && fileTypeOk && file.size <= $id("MAX_FILE_SIZE").value) {
        if (xhr.upload && fileTypeOk) {

            // create progress bar
//            var o = $id("progress");
//            var progress = o.appendChild(document.createElement("p"));
//            progress.appendChild(document.createTextNode("upload " + file.name));


            // progress bar
            if (isDefined(progressBar)) {
                xhr.upload.addEventListener("progress", function (e) {
                    var pc = parseInt(100 - (e.loaded / e.total * 100));
//                    progress.style.backgroundPosition = pc + "% 0";
                    $(progressBar).attr("aria-valuenow", pc);
                }, false);
            }

            // file received/failed

            xhr.onreadystatechange = function (e) {
                if (xhr.readyState == 4) {

                    var isSuccess = (xhr.status == 200 ? "success" : "failure");
                    if (isDefined(progressBar)) {
                        var progressBarParent = $(progressBar).parent();
                        $(progressBarParent).html('');
                        $(progressBarParent).text(isSuccess);
                    }

                    if (isDefined(callbackIfDone)) {
                        callbackIfDone(xhr.status, xhr, progressBarParent);
                    }
                }
            };


            // start upload
//            xhr.open("POST", $id("upload").action, true);
            xhr.open("POST", actionUrl, true);

            var formData = new FormData();
            formData.append("file_import_excel_temp", file);
            formData.append("file_id", $("#random_id").val());

            xhr.send(formData);

        }

    }

    var uploadInput = {
        init: function () {
            // this.initFrmUpload();
            $("a.remFile").click(function () {
                uploadInput.removeFile(this);
            });
        },
        removeFile: function (anchorObjRem) {
            if (isDefined(anchorObjRem)) {
                var upl = 'sk', tableName = $(anchorObjRem).parent().parent().parent().parent().attr("id");
                var trr = $(anchorObjRem).parent().parent(), fnm = $(trr).attr("fnm");


                if (tableName == 'tableSKM') {
                    upl = 'skm';
                }

                $.ajax({
                    url: "<?php echo base_url('ever/verification/removeFileLampiran'); ?>?ikin=<?php echo encrypt_username($LHKPN->NIK, 'e'); ?>&inpkhl=<?php echo encrypt_username($ID_LHKPN, 'e'); ?>&upl=" + upl,
                    data: {
                        inpkhl: '<?php echo $item->ID_LHKPN; ?>',
                        fnm: fnm,
                    },
                    type: "POST",
                    success: function (data) {
                        $(trr).remove();
                    }
                });

            }
        },
        addFileUpload: function (rowUpload, tableName) {

            if (!isDefined(tableName)) {
                return;
            }

            if (isDefined(rowUpload)) {
                var td1 = $("<td class=\"td-nama-file\"></td>").append(document.createTextNode(rowUpload[0]));
                var td2 = $("<td class=\"td-lhkpn-excel\"></td>")
                        .append(rowUpload[1])
                        .append(rowUpload[3]);

                var tr = $("<tr fnm=\"" + rowUpload[0] + "\"></tr>");
                $(tr).append(td1);
                $(tr).append(td2);

                $("#" + tableName + " tbody").prepend(tr);
            }
        },
        resetAllRemoveButton: function () {
            $(".td-aksi a.remove-button").remove();

            $(".td-aksi").each(function (index) {
                uploadInput.addRemoveButton(this);
            });
        },
        addRemoveButton: function (progressBarcell) {
            var removeButton = $("<a href=\"#\" class=\"btn btn-sm\" ><span class=\"glyphicon glyphicon-remove text-danger\"></span></a>");
            var previewButton = $("<a href=\"#\" class=\"btn btn-sm\" ><span class=\"glyphicon glyphicon-new-window text-danger\"></span></a>");
            var self = this;
            $(progressBarcell).append(removeButton).append(previewButton);

            $(removeButton).click(function () {
                //$(progressBarcell).parent().remove();
                self.removeFile(this);
            });
            $(previewButton).click(function () {
                var trr = $(progressBarcell).parent(), fnm = $(trr).attr("fnm");
                var tableId = $(trr).parent().parent().attr("id"), url = "";

                if (tableId == "tableSKM") {
                    url = "<?php echo base_url('uploads'); ?>/data_skm/<?php echo encrypt_username($LHKPN->NIK, 'e'); ?>/<?php echo encrypt_username($ID_LHKPN, 'e'); ?>/" + fnm;
                }
                if (tableId == "tableSK") {
                    url = "<?php echo base_url('uploads'); ?>/data_sk/<?php echo encrypt_username($LHKPN->NIK, 'e'); ?>/<?php echo encrypt_username($ID_LHKPN, 'e'); ?>/" + fnm;
                }
                window.open(url, '_blank');
            });
        },
        afterUpload: function (status, xhr, progressBarcell) {
            if (status == 200) {
                $(progressBarcell).html('');

                uploadInput.addRemoveButton(progressBarcell);
            }
        },
        sendUpload: function (self, fileName, file, tableName) {
            var divProgressbarProgress = $("<div class=\"progress-bar\" role=\"progressbar\" aria-valuenow=\"0\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: 30%\"><span class=\"sr-only\">40% Complete (success)</span></div>");

            var divProgressbar = $("<div class=\"progress\"></div>");

            $(divProgressbar).append(divProgressbarProgress);

            var hiddenFileList = $("<input name=\"uploadedFiles" + tableName + "[]\" type=\"hidden\" value=\"" + fileName + "\">");
            self.addFileUpload([
                fileName,
                $(divProgressbar),
                $("<a href=\"#\" class=\"removeFile text-danger\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></a>"),
                hiddenFileList,
            ], tableName);
            var upl = 'sk';
            if (tableName == 'tableSKM') {
                upl = 'skm';
            }

            return UploadFileSk(file, "<?php echo base_url("efill/lhkpnoffline/temp_upload"); ?>?ikin=<?php echo encrypt_username($LHKPN->NIK, 'e'); ?>&inpkhl=<?php echo encrypt_username($ID_LHKPN, 'e'); ?>&upl=" + upl, divProgressbar, undefined, self.afterUpload, true);

        },
        getFilename: function (value) {
            return value.split('\\').pop();
        },
        initFrmUpload: function () {
            var inputFileSKM = document.getElementById("file1"), self = this;
            var inputFileSK = document.getElementById("file2"), self = this;

            inputFileSKM.addEventListener('change', function (e) {

                if ($('#tableSKM tr').length < 2) {
                    if (this.files.length > 0) {
                        var i = 0;
                        while (i < this.files.length) {
                            self.sendUpload(self, this.files[i].name, this.files[i], "tableSKM");
                            i++;
                        }
                    }
                }
            });
            inputFileSK.addEventListener('change', function (e) {

                if (this.files.length > 0) {
                    var i = 0;
                    while (i < this.files.length) {
                        self.sendUpload(self, this.files[i].name, this.files[i], "tableSK");
                        i++;
                    }
                }

            });
        }
    };

    var simpanUpload = {
        collectedDataSKM: [],
        collectedDataSK: [],
        collectedIDPN: [],
        collectedIDKel: [],
        collectedIDKel2: [],
        init: function () {
            var self = this;
            var cekdata = $("#flagSKUpload").val();
            $("#btn-simpan-upload").click(function () {
                $("#modalSK").modal('show');
            });
        },
        init2: function () {
            var self = this;
            $("#btn-simpan-upload2").click(function () {
                $("#modalSK").modal('hide');
                self.sendUploadData();
                self.updateSK();
            });
        },
        collectData: function () {
            var self = this;
            $("table#tableSK td.td-nama-file").each(function (index) {
                self.collectedDataSK.push($(this).text());
            });
            $("table#tableSKM td.td-nama-file").each(function (index) {
                self.collectedDataSKM.push($(this).text());
            });
            $("#flagSKUpload:checked").each(function (index) {
                self.collectedIDKel.push($(this).val());
            });
            $("#flagSKUpload:not(:checked)").each(function (index) {
                self.collectedIDKel2.push($(this).val());
            });
            $("#flagSKUploadPN:checked").each(function (index) {
                self.collectedIDPN.push($(this).val());
            });
        },
        sendUploadData: function () {
            var self = this;
            self.collectedDataSKM = [];
            self.collectedDataSK = [];
            self.collectedIDPN = [];
            self.collectedIDKel = [];
            self.collectedIDKel2 = [];
            self.collectData();
            $.ajax({
                url: '<?php echo base_url('ever/verification/uploadLampiran'); ?>',
                data: {
                    inpkhl: '<?php echo $item->ID_LHKPN; ?>',
                    skm: self.collectedDataSKM,
                    sk: self.collectedDataSK,
                    id_pn: self.collectedIDPN,
                    id_kel: self.collectedIDKel,
                    id_kel2: self.collectedIDKel2,
                },
                type: "POST",
                success: function (data) {
                    // var link = 'index.php/ever/verification/display/lhkpn/<?php //echo $item->ID_LHKPN; ?>/verifikasi/';
                    // ng.LoadAjaxContent(link);
                    // $('#li8').find('a').trigger('click');
                    // $('.navTabSuratKuasaMengumumkan').trigger('click');
                },
            });
        },
        updateSK() {
            var cekSK = $("input[name='flagSKUpload']").map(function(){
                var status = "";
                if (this.checked) {
                    status = "Sudah";
                } else {
                    status = "Belum"
                }
                return status;
                }).get();
            $.each(cekSK, function( index, value ) {
                $("#stsSK" + index + "").text(cekSK[index]);
            });
        }
    };

    /////////////////////////////////// SHOW SKM & SK ///////////////////////////////////
    var showSK = {
        loopSKM: function( data ) {
            $.each(data, function( index, value ) {
                var fileSKM = JSON.parse( value );
                $.each(fileSKM, function ( index2, value2 ) {
                    var hrefSKM = "<?php echo base_url("uploads"); ?>/data_skm/<?php echo encrypt_username($LHKPN->NIK, 'e'); ?>/" + index + "/" + value2;
                    var hidden = showSK.checkId( index );
                    if(showSK.checkExist( hrefSKM )) {
                        $( "#tableSKM tbody" ).append(
                            "<tr fnm='" + value2 + "' inpkhl='" + index + "'>" +
                                "<td>" + value2 + "</td>" +
                                "<td class='text-center'>" +
                                    "<a class='btn btn-sm remFile aksi' href='#' style='" + hidden + "'><span class='glyphicon glyphicon-remove text-danger'></span></a>" +
                                    "<a class='btn btn-sm' target='_blank' href='" + hrefSKM + "'><span class='glyphicon glyphicon-new-window text-danger'></span></a>" +
                                "</td>" +
                            "</tr>"
                        );
                    }
                });
            });
        },
        loopSK: function( data ) {
            $.each(data, function( index, value ) {
                var fileSK = JSON.parse( value );
                $.each(fileSK, function ( index2, value2 ) {
                    var hrefSK = "<?php echo base_url("uploads"); ?>/data_sk/<?php echo encrypt_username($LHKPN->NIK, 'e'); ?>/" + index + "/" + value2;
                    var hidden = showSK.checkId( index );
                    if(showSK.checkExist( hrefSK )) {
                        $( "#tableSK tbody" ).append(
                            "<tr fnm='" + value2 + "' inpkhl='" + index + "'>" +
                                "<td>" + value2 + "</td>" +
                                "<td class='text-center'>" +
                                    "<a class='btn btn-sm remFile aksi' href='#' style='" + hidden + "'><span class='glyphicon glyphicon-remove text-danger'></span></a>" +
                                    "<a class='btn btn-sm' target='_blank' href='" + hrefSK + "'><span class='glyphicon glyphicon-new-window text-danger'></span></a>" +
                                "</td>" +
                            "</tr>"
                        );
                    }
                });
            });
        },
        loopSKElo: function( data ) {

            var fileSKElo = JSON.parse( data );
            console.log('file sk elo : '+fileSKElo);
            $.each(fileSKElo, function( index, value ) {
                console.log('index : '+index);
                console.log('value : '+value.NAME);
                $( "#tableSK tbody" ).append(
                    "<tr fnm='" + value.ID + "' inpkhl='" + index + "'>" +
                        "<td>" + value.NAME + "</td>" +
                        "<td class='text-center'>" +
                            "<a class='btn btn-sm' target='_blank' href='"+value.URL+"'><span class='glyphicon glyphicon-new-window text-danger'></span></a>" +
                        "</td>" +
                    "</tr>"
                );
            });
        },
        checkExist: function( url ) {
            var res = false;
            $.ajax({
                url: url,
                type: "HEAD",
                async: false,
                success: function() {
                    res = true;
                },
            });
            return res;
        },
        checkId: function( data ) {
            var current_id = '<?php echo encrypt_username($ID_LHKPN, 'e'); ?>';
            var res = 'display: none';
            if(current_id == data) {
                res = 'display: inline';
            }
            return res;
        }
    };
    /////////////////////////////////// SHOW SKM & SK ///////////////////////////////////

    $(document).ready(function () {
        var dataSK = <?php echo json_encode($dataSK); ?>;
        var dataSKM = <?php echo json_encode($dataSKM); ?>;
        var dataSKElo = <?php echo json_encode($data_sk_elo); ?>;
        // if (dataSK) {
        //     $( "#table-SK tbody" ).empty();
        //     showSK.loopSK( dataSK );
        // }
        // if (dataSKM) {
        //     $( "#table-SKM tbody" ).empty();
        //     showSK.loopSKM( dataSKM );
        // }
        if(dataSKElo){
            $( "#table-SK tbody" ).empty();
            showSK.loopSKElo( dataSKElo );
        }
        uploadInput.init();
        simpanUpload.init();
        simpanUpload.init2();
        simpanUpload.updateSK();
    });
</script>