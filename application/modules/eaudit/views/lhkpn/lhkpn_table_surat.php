<style type="text/css">
    .title-surat-berharga
    {
        background-color: rgba(191, 30, 46, 0.0);
        font-style: italic;
        color:black;
        /*font-size: 1px;*/
    }
</style>
<div class="box-header with-border portlet-header title-surat-berharga">
    <h5 class="">"Harta berupa Surat Berharga, contoh penyertaan modal saham dan investasi"</h5>
</div><!-- /.box-header -->
<div class="box-body" id="wrapperSuratBerharga">
    <br />
    <button type="button" class="btn btn-primary" href="index.php/efill/validasi_harta_surat_berharga/add/<?php echo $id_imp_xl_lhkpn; ?>" title="Tambah Data" onclick="onButton.go(this);"><i class="fa fa-plus"></i> Tambah</button>
    <br />
    <br />
    <table class="table table-bordered table-hover table-striped table-custom">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th width="150px">KODE JENIS</th>
                <th width="100px">PENERBIT/PERUSAHAAN</th>
                <th width="100px">NOMOR REKENING</th>
                <th width="150px">KEPEMILIKAN</th>
                <th width="130px">NILAI PEROLEHAN</th>
                <th width="190px">NILAI ESTIMASI PELAPORAN</th>
                <th width="100px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            echo (count($HARTA_SURAT_BERHARGAS) == 0 ? '<tr><td colspan="12" class="items-null">Data tidak ditemukan!</td></tr>' : '');
            ?>
            <?php
            $i = 0;
            $totalsuratberharga = 0;
            foreach ($HARTA_SURAT_BERHARGAS as $suratberharga) :
                $totalsuratberharga += $suratberharga->PELAPORAN;
                ?>
                <tr>
                    <td><?php echo ++$i; ?>.</td>
                    <!--<td class="HartaSuratverif"><?php echo ifaTetap($suratberharga) ? '<input type="checkbox" class="chk-tetap hartasurat" />' : '' ?></td>-->

                    <td>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo $suratberharga->NAMA; ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo $suratberharga->NAMA_PENERBIT; ?> <br />
                                <?php if (!empty($suratberharga->FILE_BUKTI)): ?>
                                    <a href="<?php echo base_url('uploads/data_suratberharga/' . encrypt_username($LHKPN->NIK, 'e') . '/' . $suratberharga->FILE_BUKTI) ?>" target="_blank"><i class="fa fa-download btn btn-default btn-sm"></i></a>
                                <?php endif ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo $suratberharga->NOMOR_REKENING; ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12">
                                <strong>a.n</strong> 
                                <br />
                                <?php //echo $suratberharga->ATAS_NAMA; ?>
                                <?php echo $suratberharga->ATAS_NAMA != 3 ? map_data_atas_nama($suratberharga->ATAS_NAMA) : $suratberharga->ATAS_NAMA_LAINNYA; ?>
                            </div>
                            <div class="col-sm-12">
                                <strong>Asal Usul</strong>
                                <br />
                                <?php
                                $exp = explode(',', $suratberharga->ASAL_USUL);
                                $text = '';
                                foreach ($exp as $key) {
                                    foreach ($asalusul as $au) {
                                        if ($au->ID_ASAL_USUL == $key) {
                                            $text .= ($au->IS_OTHER === '1' ? '<font color="blue">' . $au->ASAL_USUL . '</font>' : $au->ASAL_USUL) . '&nbsp;,&nbsp;&nbsp;';
                                        }
                                    }
                                }
                                $rinci = substr($text, 0, -19);
                                echo $rinci;
                                ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-md-12" align="right">
                                Rp. <?php echo $suratberharga->SIMBOL . ' ' . _format_number($suratberharga->NILAI_PEROLEHAN, 0); ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12" align="right">
                                Rp. <?php echo _format_number($suratberharga->NILAI_PELAPORAN, 0); ?>
                            </div>
                        </div>
                    </td>
                    
                    <td nowrap="" style="text-align:center">
                        <button type="button" class="btn btn-primary" href="index.php/efill/validasi_harta_surat_berharga/edit/<?php echo $suratberharga->id_imp_xl_lhkpn_harta_surat_berharga_secure; ?>" title="Edit" onclick="onButton.go(this);"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-danger" href="index.php/efill/validasi_harta_surat_berharga/hapus/<?php echo $suratberharga->id_imp_xl_lhkpn_harta_surat_berharga_secure; ?>" title="Hapus" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>
                        <?php /* <button type="button" class="btn btn-danger" href="index.php/efill/validasi_harta_surat_berharga/hapus/<?php echo $suratberharga->id_imp_xl_lhkpn_harta_surat_berharga_secure; ?>" title="Delete" onclick="onButton.go(this);"><i class="fa fa-trash" ></i></button> */ ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot class="table-footer">
            <tr>
                <td class="HartaSuratspan" colspan="8"><b>Sub Total/Total</b></td>
                <td align="right"><b>Rp. <?php echo _format_number($totalsuratberharga, 0); ?></b></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div><!-- /.box-body -->
<div class="box-footer">
</div><!-- /.box-footer -->

<script type="text/javascript">
    $(document).ready(function () {
        $('.hasilVerif').tooltip();

        $('.year-picker').datepicker({
            orientation: "left",
            format: 'yyyy',
            viewMode: "years",
            minViewMode: "years",
            autoclose: true
        });
        $(".int").inputmask("integer", {
            groupSeparator: '.',
            'autoGroup': true,
            'removeMaskOnSubmit': false,
            'digits': 0
        });
        $(".int").inputmask("integer", {
            groupSeparator: '.',
            'autoGroup': true,
            'removeMaskOnSubmit': false,
            'digits': 0
        });
        $("#suratberharga form").submit(function (e) {
            var id = $('input[name="ID_LHKPN"]').val();

            ng.LoadAjaxContentPost('index.php/efill/lhkpn/showTable/7/' + id + '/edit', $(this), $('#suratberharga').find('.contentTab'));
            return false;
        });
        $("#ajaxClearCari1_1").click(function (e) {
            var id = $('input[name="ID_LHKPN"]').val();
            $('#hartabergerak input[name="cari"]').val('');

            ng.LoadAjaxContentPost('index.php/efill/lhkpn/showTable/7/' + id + '/edit', $(this), $('#suratberharga').find('.contentTab'));
            return false;
        });
        $("#wrapperSuratBerharga .btn-detail").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Detail Surat Berharga', html, '', 'large');
            });
            return false;
        });
        $("#wrapperSuratBerharga .btn-add").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah Surat Berharga', html, '', 'large');
            });
            return false;
        });
        $('#wrapperSuratBerharga .btn-edit').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Edit Surat Berharga', html, '', 'large');
            });
            return false;
        });
        $('#wrapperSuratBerharga .btn-delete').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Delete Surat Berharga', html, '', 'large');
            });
            return false;
        });
        $('#wrapperSuratBerharga .btn-pembanding').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Perbandingan Surat Berharga', html, '', 'standart');
            });
            return false;
        });
        $('#wrapperSuratBerharga .btn-pelaporan').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Data Pelepasan', html, '', 'standart');
            });
            return false;
        });
    });
</script>