<?php
$form_data = isset($form_data) ? $form_data : FALSE;
?>

<style type="text/css">
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
    .form-body-penerimaan{
        overflow: auto; 
        max-height: 400px;
    }
</style>




<div id="wrapperFormAdd" class="form-horizontal" style="">
    <div id="wrapperFormPenerimaan">
        <ol class="breadcrumb">
            <li>Form Penerimaan</li>
        </ol>

        <form method="post" id="ajaxFormAddPenerimaan" action="<?php echo site_url('efill/lhkpnoffline/simpan_penerimaan_add/'); ?>" enctype="multipart/form-data">
            <div class="box-body form-body-penerimaan">
                <div class="col-sm-12">
                    <!--<div role="tabpanel" class="tab-pane active" id="a">-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label" style="padding-left: 0px; padding-right: 0px;">Diterima Melalui <font color='red'>*</font>:</label>
                        <div class="col-sm-10">
                            <div class="radio">
                                <?php $melalui = show_me($form_data, "MELALUI", ""); ?>
                                <label><input id="MELALUI_LANGSUNG" type="radio" class="MELALUI" required name="MELALUI" value="langsung" <?php echo $melalui == "1" ? "checked" : ""; ?>  > Langsung</label>&nbsp;&nbsp;
                                <label><input id="MELALUI_POS" type="radio" class="MELALUI" required name="MELALUI" value="pos" <?php echo $melalui == "2" ? "checked" : ""; ?> > Pos</label>&nbsp;&nbsp;
                                <label><input  id="MELALUI_EMAIL" type="radio" class="MELALUI" required name="MELALUI" value="email" <?php echo $melalui == "3" ? "checked" : ""; ?> > Email</label>&nbsp;&nbsp;
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" style="padding-left: 0px; padding-right: 0px;">Tanggal Penerimaan <font color='red'>*</font> :</label>
                        <div class="col-sm-10">
                            <input type='text' style="width: 170px" class="form-control date-picker" placeholder='DD/MM/YYYY' name='TANGGAL_PENERIMAAN' id='TANGGAL_PENERIMAAN' value='<?php echo show_date_with_format(show_me($form_data, "TANGGAL_PENERIMAAN", ""), "d/m/Y"); ?>' required>
                        </div>
                        <input type="hidden" class="JENIS_DOKUMEN" required name="JENIS_DOKUMEN" value="excel" />
                        <input type="hidden" id="INST_NAMA" class="INST_NAMA" name="INST_NAMA" value="-" />
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" style="padding-left: 0px; padding-right: 0px;">PN <font color='red'>*</font> :</label>
                        <div class="col-sm-10" style="padding-left: 0px;">
                            <!-- list pn jika belum terdaftar maka ditambahkan -->
                            <div class="col-sm-7" style="padding-right: 0px;">
                                <input type='hidden' class="form-control" name='ID_PN' id='ID_PN' value='<?php echo show_me($form_data, "ID_PN", ""); ?>' required readonly>
                                <input type='hidden' class="form-control" name='NIK' id='NIK' value='<?php echo show_me($form_data, "NIK", ""); ?>' required readonly>
                                <input type='text' class="form-control" name='NAMA' id='NAMA' value='<?php echo show_me($form_data, "NAMA", ""); ?>' required readonly>
                            </div>
                            <div class="col-sm-2" style="padding-left: 0px; padding-right: 0px;">
                                <button type="button" class="btn btn-sm btn-info" id="btnCariPN"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>



                    <div id="div_on_screening">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" style="padding-left: 0px; padding-right: 0px;">Email akan dikirim ke :</label>
                            <div class="col-sm-3">
                                <input id="EMAIL" class="form-control" name="EMAIL" type="text" value="<?php echo show_me($form_data, "EMAIL_SCREENING_TO", ""); ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" style="padding-left: 0px; padding-right: 0px;">Screening Valid ? <font color='red'>*</font> :</label>
                            <div class="col-sm-10">
                                <input id="SCREENING_VALID_YA" type="radio" name="SCREENING_VALID" value="1"> Ya<br>
                                <input id="SCREENING_VALID_TIDAK" type="radio" name="SCREENING_VALID" value="0"> Tidak
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-inline">
                                <label class="col-sm-2 control-label" style="padding-left: 0px; padding-right: 0px;" for="exampleInputFile">Pilih File Excel dan Dokumen Pendukung <font color='red'>*</font>:</label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <input type='hidden' class="form-control" name='random_id' id='random_id' value='<?php echo $random_id; ?>' required readonly>
                                        <input type="file" id="file_xlsm" name="file_xlsm" class="inputFile" required data-allowed-file-extensions='["xlsm"]' multiple>
                                        &nbsp;<label for="file_xlsm" class="btn btn-xl btn-primary">Pilih File</label>
                                    </div>
                                    <div class="row">&nbsp;</div>
                                    <div class="row">
                                        <table id="tableListFileUpload" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="70%">Nama File</th>
                                                    <th width="10%">LHKPN Excel</th>
                                                    <th width="20%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div id="divUraianScreening">
                            <div  class="form-group">
                                <label class="col-sm-2 control-label" style="padding-left: 0px; padding-right: 0px;">Uraian Screening <font color='red'>*</font> :</label>
                                <div class="col-sm-10">
                                    <textarea id="URAIAN_SCREENING" name="URAIAN_SCREENING" style="width: 100%" cols="30" rows="5"><?php echo show_me($form_data, "URAIAN_SCREENING", ""); ?></textarea><br />
                                    <a href="" onclick="return false;" id="apreviewMessage">preview Message</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" style="padding-left: 0px; padding-right: 0px;">Jenis Laporan <span class="red-label">*</span> :</label>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <h4>Periodik</h4>
                                        <label><input type="radio" class="JENIS_LAPORAN" required name="JENIS_LAPORAN" value="4"> Sedang Menjabat</label><br>
                                        <span>Tahun Pelaporan</span>
                                        <select name="TAHUN_PELAPORAN" class="TAHUN_PELAPORAN" id="TAHUN_PELAPORAN" style="width: 160px;">
                                            <option value="">Pilih Tahun</option>
                                            <?php
                                            $tahun = show_me($form_data, "TAHUN_PELAPORAN", "");
                                            for ($i = date('Y') - 1; $i > (date('Y') - 10); $i--) {
                                                echo "<option value='$i' " . ($tahun == $i ? "selected" : "") . " >$i</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4"  style="border-left: 1px solid #cfcfcf;">
                                        <h4>Khusus</h4>
                                        <label><input type="radio" class="JENIS_LAPORAN" required name="JENIS_LAPORAN" value="1"> Calon PN</label>&nbsp;
                                        <label><input type="radio" class="JENIS_LAPORAN" required name="JENIS_LAPORAN" value="2"> Awal Menjabat</label>&nbsp;
                                        <label><input type="radio" class="JENIS_LAPORAN" required name="JENIS_LAPORAN" value="3"> Akhir Menjabat</label>&nbsp;
                                        <span>Tanggal Pelaporan</span> <input type="text" name="TANGGAL_PELAPORAN" id="TANGGAL_PELAPORAN" class="TANGGAL_PELAPORAN date-picker" placeholder='DD/MM/YYYY' value="<?php echo show_date_with_format(show_me($form_data, "TANGGAL_PELAPORAN", ""), "d/m/Y"); ?>">
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    /**
                     * pindah keatas, lo liat ya
                     */
                    ?>
                    <div id="div_after_screening">
                        <?php /*
                          <!--<div class="form-group">-->
                          <div class="form-group">
                          <div class="form-inline">
                          <label class="col-sm-2 control-label" style="padding-left: 0px; padding-right: 0px;" for="exampleInputFile">Pilih File Excel dan Dokumen Pendukung <font color='red'>*</font>:</label>
                          <div class="col-sm-10">
                          <div class="row">
                          <input type='hidden' class="form-control" name='random_id' id='random_id' value='<?php echo $random_id; ?>' required readonly>
                          <input type="file" id="file_xlsm" name="file_xlsm" class="inputFile" required data-allowed-file-extensions='["xlsm"]' multiple>
                          &nbsp;<label for="file_xlsm" class="btn btn-xl btn-primary">Pilih File</label>
                          </div>
                          <div class="row">&nbsp;</div>
                          <div class="row">
                          <table id="tableListFileUpload" class="table table-bordered">
                          <thead>
                          <tr>
                          <th width="70%">Nama File</th>
                          <th width="10%">LHKPN Excel</th>
                          <th width="20%">Aksi</th>
                          </tr>
                          </thead>
                          <tbody>

                          </tbody>
                          </table>
                          </div>
                          </div>
                          </div>
                          </div>
                          </div>
                          <!--</div>-->
                         * 
                         */
                        ?>
                    </div>
                </div>

                <div class="clearfix"></div>
                <br>
                <div class="pull-right">
                    <input type="hidden" name="act" value="<?php echo $act; ?>">
                    <button type="submit" class="btn btn-sm btn-primary" id="btnSimpanPenerimaan" onclick="penerimaan.submitted = true;"><i class="fa fa-save"></i> Simpan</button>
                    <input type="reset" class="btn btn-sm btn-danger" value="Batal" onclick="CloseModalBox2();">

                </div>
                <div class="clearfix"></div>
                <!--</div>-->
        </form>
    </div>
</div>
<form method="POST" action="index.php/efill/lhkpnoffline/screening_email_preview/" id="previewMailScreeningForm" style="display: none;"  target="TheWindow">
    <input type="hidden" name="nama" id="hddnnama" value="" />
    <input type="hidden" name="inst_nama" id="hddninst_nama" value="" />
    <input type="hidden" name="body" id="hddnbody" value="" />
</form>
<div id="wrapperCariPN" style="display: none;">
    <ol class="breadcrumb">
        <li>Form Penerimaan</li>
        <li>Cari PN</li>
    </ol>
    <!-- <button type="button" class="btn btn-sm btn-default" id="btnTambahPN"><i class="fa fa-plus"></i> Tambah Data PN</button> -->
    <div class="pull-left">
        <button class="btn btn-sm btn-primary" id="btn-add-pn-individual" href="index.php/ereg/all_pn/daftar_pn_individu/0/daftarindividu" >Tambah PN</button>
    </div>
    <!--<div class="pull-right">-->
    <div class="col-md-12">    
        <form method="post" class='form-horizontal' id="ajaxFormCariPN" action="index.php/efill/lhkpnoffline/hasilcaripn/">
            <!--            <div class="input-group col-sm-push-5">
                            <div class="col-sm-3">
                                <input type="text" class="form-control input-sm pull-right" style="width: 200px;" placeholder="Search PN / WL NIK" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="CARI_TEXT_PN"/>
                            </div>
                            <div class="input-group-btn col-sm-6">
                                <button type="submit" class="btn btn-sm btn-default" id="btn-cari">Cari</button>
                                <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#CARI_TEXT_PN').val(''); $('#CARI_TEXT_PN').focus(); $('#ajaxFormCariPN').trigger('submit');">Clear</button>
                            </div>
                        </div>-->
            <div class="box-body">
                <div class="col-md-6">
                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Nama/NIK <font color='red'>*</font> :</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control input-sm pull-right" placeholder="Search PN / WL NIK" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="CARI_TEXT_PN"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Tanggal Lahir :</label>
                            <div class="col-sm-3">
                                <input class="form-control input-sm pull-right date-picker field-required" type='text'name='CARI[TGL_LAHIR]' id='TGL_LAHIR' placeholder='DD/MM/YYYY'>
                            </div>
                            <div class="form-group">
                                <div class="col-col-sm-3 col-sm-offset-4-2">
                                    <button type="submit" class="btn btn-sm btn-default" id="btn-cari">Cari</button>
                                    <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#CARI_TEXT_PN').val(''); $('#CARI_TEXT_PN').focus(); $('#ajaxFormCariPN').trigger('submit');">Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <br/>
    <div class="clearfix" style="margin-bottom: 10px;"></div>
    <div id="wrapperHasilCariPN">
        <!-- draw here -->
    </div>
    <div class="pull-right">
        <button type="button" class="btn btn-sm btn-default" id="btnKembaliKePenerimaan">Kembali Ke form</button>
    </div>
</div>

<?php
/**
 * 
 * Form Add PN
 */
?>

<div id="wrapperFormAddPN" style="display: none;">
    <ol class="breadcrumb">
        <li>Form Penerimaan</li>
        <li>Cari PN</li>
        <li>Tambah PN</li>
    </ol>
    <form method="post" id="ajaxFormAddPN" action="<?php echo 'index.php/efill/lhkpnoffline/save/pn/'; ?>">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4 control-label">NIK <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="NIK" id="NIK" placeholder="NIK" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Nama Lengkap <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <div class="col-sm-2">
                            <input type="text" class='form-control' name='GELAR_DEPAN' placeholder="Gelar depan" value="" >
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class='form-control' name='NAMA_LENGKAP' id='NAMA_LENGKAP' placeholder="Nama Lengkap" value="" required>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class='form-control' name='GELAR_BELAKANG' placeholder="Gelar Belakang" value="" >
                        </div>                            
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Tempat Lahir / Tanggal Lahir <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="TEMPAT_LAHIR" placeholder="Tempat Lahir" required>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control date-picker" name="TGL_LAHIR" placeholder='DD/MM/YYYY' required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Email <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <div class="col-sm-12">
                        <input type="email" class="form-control" name="EMAIL" placeholder="email" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Nomor HP <font color='red'>*</font>:</label>
                <div class="col-sm-8">
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="NO_HP" placeholder="Nomor HP" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <input type="hidden" name="act" value="doinsertpn">
            <button type="submit" class="btn btn-sm btn-primary" id="btnSimpanAddPN">Simpan</button>
        </div>
    </form>
    <br>
    <br>
    <div class="clearfix"></div>
    <div class="pull-right">
        <button type="button" class="btn btn-sm btn-default" id="btnKembaliKeCariPN">Kembali Ke Cari PN</button>
    </div>
</div>
<?php
$js_page = isset($js_page) ? $js_page : '';
if (is_array($js_page)) {
    foreach ($js_page as $page_js) {
        echo $page_js;
    }
} else {
    echo $js_page;
}
?>