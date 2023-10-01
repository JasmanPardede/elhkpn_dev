<?php
$on_another_module = $this->data['on_another_module'];
$on_another_module = isset($on_another_module) ? $on_another_module : FALSE;
$showUnitKerja = $this->data['showUnitKerja'];
$showUnitKerja = isset($showUnitKerja) ? $showUnitKerja : FALSE;
$min_tahun = isset($min_tahun) && $min_tahun ? $min_tahun : date('Y')-1;
?>

<?php if (!$on_another_module): ?>
    <div class="col-md-12">
        <div class="box-body">
            <div class="col-md-6">
                <div class="row"></div>
            </div>
        <?php endif; ?>
        <?php if ($isAdminAplikasi || $isAdminKPK || $isAdminInstansi || $isSuperadmin): ?>

            <?php if ($isAdminAplikasi || $isAdminKPK || $isSuperadmin): ?>
                <div class="col-md-6">
                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Instansi:</label>
                            <div id="inpCariInstansiPlaceHolder" class="col-sm-6">
                                <input type='text' class="input-sm form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI' value='' placeholder="-- Pilih Instasi --">
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!$on_another_module): ?>
                    <div class="col-md-6">
                        <div class="row"></div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
			<?php if($showUnitKerja): ?>
            <div class="col-md-6">
                <div class="row">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Unit Kerja:</label>
                        <div id="inpCariUnitKerjaPlaceHolder" class="col-sm-6">
                            <input type='text' class="input-sm form-control" name='CARI[UNIT_KERJA]' style="border:none;padding:6px 0px;" id='CARI_UNIT_KERJA' value='' placeholder="-- Pilih Unit Kerja --">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"></div>
                    </div>
                </div>
                <div class="row">
                <div class="form-group">
                    <label class="col-sm-4 control-label">WL Tahun:</label>
                    <div id="inpCariTahunPlaceHolder" class="col-sm-6">
                        <select id='CARI_TAHUN_WL' name="CARI[TAHUN_WL]" class="input-sm form-control">
                            <option value="">All</option>
                            <?php while ($min_tahun <= date('Y')): ?>
                                <option value="<?php echo $min_tahun; ?>"><?php echo $min_tahun; ?></option>
                                <?php $min_tahun ++; ?>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
            </div>
            </div>
			 <?php endif; ?>
        <?php else: ?>
            <input type='hidden' class="input-sm select2 form-control" name='CARI[UNIT_KERJA]' style="border:none;padding:6px 0px;" id='CARI_UNIT_KERJA_HDD' value='<?php echo $UK_ID; ?>' >
        <?php endif; ?>
        <?php if ($isAdminInstansi || $isAdminUnitKerja): ?>
            <input type='hidden' class="input-sm select2 form-control" name='CARI[INSTANSI]' style="border:none;padding:6px 0px;" id='CARI_INSTANSI_HDD' value='<?php echo $INST_SATKERKD; ?>' >
        <?php endif; ?>
    </div>
</div>