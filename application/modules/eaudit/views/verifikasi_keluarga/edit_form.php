<?php $onAdd = isset($onAdd) ? $onAdd : FALSE; ?>
<div id="wrapperFormEdit">
    <form role="form" id="ajaxFormEdit" action="index.php/ever/verifikasi_keluarga/<?php echo $action; ?>" >
        <div class="modal-body row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>NIK </label> <?= FormHelpPopOver('nik_dkel'); ?>
                    <input type="text" name="NIK" id="NIK" class="form-control input_capital" value="<?php echo $item->NIK; ?>" />
                </div>
                <div class="form-group">
                    <?php if (!$onAdd): ?>
                        <input type="hidden" name="id_imp_xl_lhkpn_keluarga" id="id_imp_xl_lhkpn_keluarga" value="<?php echo $item->id_imp_xl_lhkpn_keluarga_secure; ?>"/>
                        <input type="hidden" name="ID" id="ID"/>
                    <?php endif; ?>
                    <label>Nama <span class="red-label">*</span> </label> <?= FormHelpPopOver('nama_dkel'); ?>
                    <input type="text" name="NAMA" id="NAMA" class="form-control input_capital" value="<?php echo $onAdd ? "" : $item->NAMA; ?>" required/>
                </div>
                <div class="form-group hubungan">
                    <label>Hubungan <span class="red-label">*</span> </label> <?= FormHelpPopOver('hubungan_dkel'); ?>
                    <?php
                    $versi = '';
                    $xver = '';
                    if (!$onAdd) {
                        $id_imp_lhkpn = $item->id_imp_xl_lhkpn;
                        $ver = $this->mglobal->get_excel_version($id_imp_lhkpn);
                        $xver = $ver->VERSI_EXCEL;
                    }

                    if ($xver == '1.8' || $xver == '1.11' || $xver == '1.6') {
                        $versi = $xver;
                    }
//                    $val_hub_keluarga = get_arr_hubungan_keluarga_imp_xl();
                    $arr_hubungan_keluarga = get_arr_hubungan_keluarga($versi);
                    ?>
                    <?php // echo form_dropdown('HUBUNGAN', $arr_hubungan_keluarga, $arr_hubungan_keluarga[$val_hub_keluarga[$item->HUBUNGAN]], 'id="HUBUNGAN" class="form-control"  required'); ?>
                    <?php echo form_dropdown('HUBUNGAN', $arr_hubungan_keluarga, ($onAdd ? 0 : intval($item->HUBUNGAN)), 'id="HUBUNGAN" class="form-control"  required'); ?>
                </div>
                <div class="form-group">
                    <label>Tempat Lahir <span class="red-label">*</span> </label> <?= FormHelpPopOver('tpt_lahir_dkel'); ?>
                    <input type="text" name="TEMPAT_LAHIR" id="TEMPAT_LAHIR" class="form-control input_capital" required value="<?php echo !$onAdd ? $item->TEMPAT_LAHIR : ''; ?>"/>
                </div>
                <div class="form-group">
                    <label>Tanggal Lahir <span class="red-label">*</span> </label> <?= FormHelpPopOver('tgl_lahir_dkel'); ?>
                    <div class="input-group date">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                        </div>
                        <input type="text" name="TANGGAL_LAHIR" id="TANGGAL_LAHIR" placeholder="( tgl/bulan/tahun )" class="form-control date" required value="<?php echo !$onAdd ? show_date_with_format($item->TANGGAL_LAHIR, 'd/m/Y') : ''; ?>"/>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <?php /**
                  <div class="form-group jenis_kelamin">
                  <label>Jenis Kelamin <span class="red-label">*</span></label> <?= FormHelpPopOver('jenis_kelamin_dkel'); ?>
                  <select class="form-control" id="jenis_kelamin" name="JENIS_KELAMIN" required>
                  <option></option>
                  <option value="LAKI-LAKI">LAKI-LAKI</option>
                  <option value="PEREMPUAN">PEREMPUAN</option>
                  </select>
                  </div>
                 * 
                 */
                ?>
                <div class="form-group">
                    <label>Pekerjaan </label> <?= FormHelpPopOver('pekerjaan_dkel'); ?>
                    <input type="text" name="PEKERJAAN" id="PEKERJAAN" class="form-control input_capital" value="<?php echo !$onAdd ? $item->PEKERJAAN : ''; ?>" />
                </div>
                <div class="form-group">
                    <label>Nomor Telepon/Handphone </label> <?= FormHelpPopOver('no_telp_dkel'); ?>
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                        <input type="text" id="NOMOR_TELPON" name="NOMOR_TELPON" class="form-control" onkeypress="return isNumber(event)"  placeholder="Isikan Nomor Handphone" value="<?php echo !$onAdd ? $item->NOMOR_TELPON : ''; ?>"  />
                    </div>
                </div>
                <div class="form-group">    
                    <label>Alamat<span class="red-label">*</span> </label> <?= FormHelpPopOver('alamat_rmh_dkel'); ?> 
                    <textarea class="form-control" rows="3" name="ALAMAT_RUMAH" id="ALAMAT_RUMAH" required ><?php echo !$onAdd ? $item->ALAMAT_RUMAH:''; ?></textarea>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" id="btn-submit" class="btn btn-primary btn-sm">
                <i class="fa fa-save"></i> Simpan
            </button>
            <button type="button" id="btn-cancel" class="btn btn-danger btn-sm" data-dismiss="modal">
                <i class="fa fa-remove"></i> Batal
            </button>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        $('#TANGGAL_LAHIR').datepicker({
            format: "dd/mm/yyyy",
        });

        var url = location.href.split('#')[1];
        url = url.split('?')[0] + '?upperli=li2&bottomli=0';
        ng.formProcess($("#ajaxFormEdit"), 'update', url);

    });
</script>