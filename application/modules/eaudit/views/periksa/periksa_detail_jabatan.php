<link href="<?php echo base_url(); ?>css/custom.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url(); ?>plugins/barcode/jquery-barcode.js"></script>
<div class="">
    <table class="table table-striped table-bordered table-hover table-heading no-border-bottom" style="margin-bottom: 40px;">
        <thead>
            <tr>
                <th width="1%">No.</th>
                <th width="15%">NIK</th>
                <th width="15%">Nama</th>
                <th width="25%">Jabatan</th>
                <th width="10%">Status WL</th>
                <th width="10%">WL Tahun</th>
                <th width="10%">Status Rangkap</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $arr2 = array();
            if ($getJabatan == $arr2) {
            ?>
                <tr>
                    <td colspan="7">Data Not Found...</td>
                </tr>
            <?php
            } else {
                $i = 1;
                foreach($getJabatan as $jab) {
            ?>
                <tr>
                    <td class="text-center"><?= @$i++; ?></td>
                    <td class="text-left"><?= @$jab->NIK; ?></td>
                    <td class="text-left"><?= @$jab->NAMA; ?></td>
                    <td class="text-center"><?= @$jab->N_JAB.' - '.@$jab->N_SUK.' - '.@$jab->N_UK.' - '.@$jab->INST_NAMA; ?></td>
                    <td class="text-center"><?= @$jab->IS_WL == 1 ? 'Wajib Lapor' : 'Non Wajib Lapor'; ?></td>
                    <td class="text-center"><?= @$jab->tahun_wl; ?></td>
                    <td class="text-center"><?= @$jab->ID_STATUS_AKHIR_JABAT == 1 ? 'Rangkap' : 'Utama'; ?></td>
                    
                </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
    <div class="pull-right">
        <input type="reset" class="btn btn-sm btn-danger" value="Tutup" onclick="CloseModalBox2();">
    </div>
</div>
<style>
    td .btn {
        margin: 0px;
    }
</style>