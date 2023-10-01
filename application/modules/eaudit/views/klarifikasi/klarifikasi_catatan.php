<div class="box-header with-border portlet-header title-pribadi">
    <h5 class="">"Catatan Pemeriksaan"</h5>
</div>
<div class="box-body" id="wrapperHutang">
    <div class="form-horizontal">
        <input type="hidden" id="id_catatan" value="<?php echo $new_id_lhkpn; ?>">
        <div class="form-group">
            <div class="col-md-12">
                <textarea id="catatan_pemeriksaan" class="ckeditor"><?php echo $t_lhkpn->CATATAN_PEMERIKSAAN; ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12" align="right">
                <button id="btn-simpan-catatan" class="btn btn-sm btn-danger"><i class="fa fa-save"></i> Simpan Catatan</button>
            </div>
        </div>
    </div>
</div>

