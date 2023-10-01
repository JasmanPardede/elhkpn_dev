<div id="wrapperFormEdit">
    <form class="form-horizontal" method="post" id="ajaxFormEdit" action="index.php/efill/validasi_data_pribadi/update_perubahan" enctype="multipart/form-data">
        <input type='hidden' name='id_imp_xl_lhkpn_data_pribadi' id='id_imp_xl_lhkpn_data_pribadi'  value='<?php echo $item->id_imp_xl_lhkpn_data_pribadi_secure; ?>'>
        <?php if (!is_null($item_data_jabatan)): ?>
            <input type='hidden' name='id_imp_xl_lhkpn_jabatan' id='id_imp_xl_lhkpn_jabatan'  value='<?php echo $item_data_jabatan->id_imp_xl_lhkpn_jabatan_secure; ?>'>
        <?php endif; ?>

        <div class="tab-content" style="padding: 5px; border:0px solid #cfcfcf;margin-top: -1px;">
            <div role="tabpanel" class="tab-pane active" id="a">
                <div class="contentTab">
                    <div class="modal-body row">
                        <div class="col-sm-7">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">NO KK :</label>
                                <div class="col-sm-7">
                                    <div class="input-group row">
                                        <div class="col-xs-8">
                                            <input  class="form-control" type="text" placeholder="00000"  size='25' name="NO_KK" value="<?php echo $item->NO_KK ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">NIK <span class="red-label">*</span>:</label>
                                <div class="col-sm-5"><input required class="form-control" type='text' size='40' name='NIK' id='NIK' placeholder="NIK" value='<?php echo $item->NIK; ?>' /></div>
                                <!--<div class="col-sm-5"><?php echo beautify_text($item->NIK); ?></div>-->

                                <div class="col-sm-1" style="margin-top: 5px;" id="div-nik">
                                    <img class="show-hide" id="fail" src="<?php echo base_url('img/fail.png') ?>" width="24" />
                                    <img class="show-hide" id="success" src="<?php echo base_url('img/success.png') ?>" width="24" />
                                    <img class="show-hide" id="loading" src="<?php echo base_url('img/loading.gif') ?>" width="24" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Gelar Depan <span class="red-label">*</span>:</label>
                                <div class="col-sm-7">
                                    <div class="input-group row">
                                        <div class="col-xs-8">
                                            <input  class="form-control" type="text" placeholder="Dr."  size='25' name="GELAR_DEPAN" value="<?php echo $item->GELAR_DEPAN ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Nama <span class="red-label">*</span>:</label>
                                <div class="col-sm-7">
                                    <div class="input-group row">
                                        <div class="col-xs-8">
                                            <input required class="form-control" type='text' size='40' name='NAMA_LENGKAP' id='NAMA_LENGKAP' placeholder="Nama" value='<?php echo $item->NAMA_LENGKAP; ?>'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Gelar Belakang <span class="red-label">*</span>:</label>
                                <div class="col-sm-7">
                                    <div class="input-group row">
                                        <div class="col-xs-8">
                                            <input  class="form-control" type="text" placeholder=",SH"  size='25' name="GELAR_BELAKANG" value="<?php echo $item->GELAR_BELAKANG ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Jenis Kelamin <span class="red-label">*</span>:</label>
                                <div class="col-sm-5">
                                    <select class="form-control" name="JENIS_KELAMIN">
                                        <option value="1" <?php echo (map_jenis_kelamin_to_bin($item->JENIS_KELAMIN, (is_numeric($item->JENIS_KELAMIN) ? 'num' : 'txt')) == 1) ? "selected" : ""; ?> >Laki - Laki</option>
                                        <option value="2" <?php echo (map_jenis_kelamin_to_bin($item->JENIS_KELAMIN, (is_numeric($item->JENIS_KELAMIN) ? 'num' : 'txt')) == 0) ? "selected" : ""; ?>>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tempat /<br/>Tanggal Lahir <span class="red-label">*</span>:</label>
                                <div class="col-sm-4">
                                    <input required class="form-control" type='text' name='TEMPAT_LAHIR' id='TEMPAT_LAHIR' placeholder="Tempat Lahir" value='<?php echo $item->TEMPAT_LAHIR; ?>'>
                                </div>
                                <div class="col-sm-3">
                                    <input required class="form-control date-picker" type='text' name='TANGGAL_LAHIR' id='TANGGAL_LAHIR' placeholder='DD/MM/YYYY' value="<?php echo date('d/m/Y', strtotime($item->TANGGAL_LAHIR)); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Agama :</label>
                                <div class="col-sm-3">
                                    <input class="form-control" type='hidden' name='AGAMA' id='AGAMA' placeholder="AGAMA" value='<?php echo $item->AGAMA; ?>'>
                                    <select id="slcAgama" name="ID_AGAMA" class="form-control">
                                        <option value="">-- Pilih --</option>
                                        <?php foreach ($agama as $agamas): ?>
                                            <option <?php echo ($item->id_agama == $agamas->ID_AGAMA ? 'selected' : ''); ?> value="<?php echo @$agamas->ID_AGAMA; ?>"><?php echo @$agamas->AGAMA; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">NPWP <span class="red-label">*</span>:</label>
                                <div class="col-sm-5">
                                    <input required class="form-control" type='text' name='NPWP' id='NPWP' placeholder="NPWP" value='<?php echo $item->NPWP; ?>'>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">FOTO : </label>
                                <div class="col-sm-5">
                                    <?php echo form_dropdown('FOTO', $file_list, $item->FOTO, "class=\"form-control\""); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">No. Tel. Rumah :</label>
                                <div class="col-sm-5">
                                    <input class="form-control" type='text' name='TELPON_RUMAH' id='TELPON_RUMAH' placeholder="TELPON RUMAH" value='<?php echo $item->TELPON_RUMAH; ?>'>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Email Pribadi (Aktif) <span class="red-label">*</span>:</label>
                                <div class="col-sm-5">
                                    <input required class="form-control" type='text' name='EMAIL_PRIBADI' id='EMAIL_PRIBADI' placeholder="EMAIL PRIBADI" value='<?php echo $item->EMAIL_PRIBADI; ?>'>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">No. Handphone <span class="red-label">*</span>:</label>
                                <div class="col-sm-5">
                                    <input required class="form-control" type='text' name='HP' id='HP' placeholder="No. Handphone" value='<?php echo $item->HP; ?>'>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-5">

                            <div class="form-group">
                                <label class="col-sm-3 control-label"> Lembaga <span class="red-label">*</span>: </label>
                                <!--<label class="col-sm-3 control-label">Lembaga: </label>-->
                                <input required type='text' id='lembaga' style='width: 60%;' name='LEMBAGA'  class='select2' value='<?php echo !is_null($item_data_jabatan) ? $item_data_jabatan->LEMBAGA : ""; ?>' />
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"> Unit Kerja <span class="red-label">*</span>: </label>
                                <!--<label class="col-sm-3 control-label">Unit Kerja: </label>-->
                                <input required type='text' id='uk' style='width: 60%;' name='UNIT_KERJA'   class='select2' value='<?php echo !is_null($item_data_jabatan) ? $item_data_jabatan->UK_ID : ""; ?>' />
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Sub Unit<br />Kerja : </label>
                                <input type="text" id="sub_uk" style="width: 60%;" name="SUB_UNIT_KERJA" value="<?php echo !is_null($item_data_jabatan) ? $item_data_jabatan->SUK_ID : ""; ?>"  class="select2" />
                            </div>
                            <div class="form-group">
                                <!--<label class="col-sm-3 control-label">Jabatan<span class="red-label">*</span>: </label>-->
                                <label class="col-sm-3 control-label">Jabatan <span class="red-label">*</span>: </label>
                                <input type="text" id="jabatan" style="width: 60%;" name="ID_JABATAN" value="<?php echo !is_null($item_data_jabatan) ? $item_data_jabatan->ID_JABATAN : ""; ?>"   class="select2" />
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Alamat<br />Rumah : </label>
                                <textarea rows="3" cols="50" style="width: 70%;" class="form-control" name="ALAMAT_RUMAH" id="alamat_rumah" rows="2"><?php echo $item->ALAMAT_RUMAH; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Alamat<br />Kantor : </label>
                                <textarea rows="3" cols="50" style="width: 70%;" class="form-control" name="ALAMAT_KANTOR" id="alamat_kantor" rows="2"> <?php echo !is_null($item_data_jabatan) ? $item_data_jabatan->ALAMAT_KANTOR : ""; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="pull-right">
                    <button id="btnsimpan" type="submit" class="btn btn-sm btn-primary">Simpan Perubahan</button>
                    <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        $('#TANGGAL_LAHIR').datepicker({
            maxViewMode: "-17 years",
            format: "dd/mm/yyyy",
        });

        var url = location.href.split('#')[1];
        url = url.split('?')[0]+'?upperli=li1&bottomli=0';
        
        
        $("#btnsimpan").click(function(){

            var vallembaga = $("#lembaga").val();
            var valuk = $("#uk").val();
            var valjabatan = $("#jabatan").val();
            
            if(vallembaga =='' || valuk =='' || valjabatan == ''){
                alert("Lembaga, Unit Kerja, dan Jabatan harus diisi");
                return false;
            }

        });
        
        ng.formProcess($("#ajaxFormEdit"), 'update', url);

        $('#slcAgama').change(function () {
            $("#AGAMA").val(this.options[this.selectedIndex].text);
        });
    });
</script>