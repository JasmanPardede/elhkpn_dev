<?php if (!$show) { ?>
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> Info Data Tercatat
            <small>LHKPN penerimaan offline</small>
        </h1>
        <?php echo $breadcrumb; ?>
    </section>
<?php } ?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box" style="padding: 10px;">
                <div class="row">
                    <div class="col-md-12">
                        <p>
                            Data yang anda masukkan sudah pernah disimpan sebelumnya.<br />
                            Berikut adalah informasi data tersebut.
                        </p>
                    </div>
                </div>
                <div class="row">
                    <h3>Data yang telah ada : </h3>
                    <hr>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label>Di terima Melalui : </label> <?php echo beautify_str($lhkpn_offline_melalui[$detail_info->MELALUI]); ?>
                    </div>
                    <div class="col-md-6">
                        <label>Tanggal Terima : </label> <?php echo show_date_with_format($detail_info->TANGGAL_PENERIMAAN); ?>
                    </div>
                    <div class="col-md-6">
                        <label>Jenis Laporan : </label>&nbsp;
                        <?php echo map_jenis_laporan_xl($detail_info->JENIS_LAPORAN); ?><br />
                        <label><?php echo $detail_info->JENIS_LAPORAN != '4' ? "Tanggal" : "Tahun"; ?>&nbsp;Pelaporan : </label>
                        <?php echo $detail_info->JENIS_LAPORAN != '4' ? tgl_format($detail_info->TANGGAL_PELAPORAN) : $detail_info->TAHUN_PELAPORAN; ?>
                        <?php echo $detail_info->JENIS_LAPORAN != '4' ? "&nbsp;" : ""; ?>
                        <br />
                        <label>Versi Excel : </label><?php echo $detail_info->VERSI_EXCEL; ?>
                    </div>
                    <div class="col-md-12">
                        <label>Uraian Screening : </label><?php echo $detail_info->URAIAN_SCREENING; ?>
                    </div>
                </div>
                <div class="row">
                    <h3>Data yang yang baru diinput : </h3>
                    <hr>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label>Di terima Melalui : </label> <?php echo beautify_str($lhkpn_offline_melalui[$input_baru->MELALUI]); ?>
                    </div>
                    <div class="col-md-6">
                        <label>Tanggal Terima : </label> <?php echo show_date_with_format($input_baru->TANGGAL_PENERIMAAN); ?>
                    </div>
                    <div class="col-md-6">
                        <label>Jenis Laporan : </label>&nbsp;
                        <?php echo map_jenis_laporan_xl($input_baru->JENIS_LAPORAN); ?><br />
                        <label><?php echo $input_baru->JENIS_LAPORAN != '4' ? "Tanggal" : "Tahun"; ?>&nbsp;Pelaporan : </label>
                        <?php echo $input_baru->JENIS_LAPORAN != '4' ? tgl_format($input_baru->TANGGAL_PELAPORAN) : $input_baru->TAHUN_PELAPORAN; ?>
                        <?php echo $input_baru->JENIS_LAPORAN != '4' ? "&nbsp;" : ""; ?>
                        <br />
                        <label>Versi Excel : </label><?php echo $input_baru->VERSI_EXCEL; ?>
                    </div>
                    <div class="col-md-12">
                        <label>Uraian Screening : </label><?php echo $input_baru->URAIAN_SCREENING; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        
                    </div>
                    <div class="col-md-4">
                        <?php if($detail_info->IS_SEND == '0'): ?>
                            <button class="btn btn-primary" id="btnLanjutkanDataDoubleLhkpnOffline">Validasi isian yang sudah ada</button>&nbsp;
                        <?php endif; ?>
                        <button class="btn btn-danger" id="btnBatalDataDoubleLhkpnOffline">Batalkan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section><!-- /.content -->

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