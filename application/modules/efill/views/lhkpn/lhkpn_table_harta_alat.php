<h3 class="box-title">IV.2.1 Harta Bergerak (Alat Transportasi dan mesin)</h3>
<style type="text/css">
    .title-alat
    {
        background-color: rgba(191, 30, 46, 0.0);
        font-style: italic;
        color:black;
        /*font-size: 1px;*/
    }
</style>
<div class="box-header with-border portlet-header title-alat">
    <h5 class="">"Harta Bergerak berupa alat transportasi dan mesin"</h5>
</div>
<div class="box-body" id="wrapperHartaBergerak">
    <?php if ($mode == 'edit') { ?>
        <button type="button" class="btn btn-sm btn-add btn-primary HartaBergerakverif" href="index.php/efill/lhkpn/addhartabergerak/<?php echo $id_lhkpn; ?>"><i class="fa fa-plus"></i> Tambah</button>
        <button style="display: none;" type="button" class="btn btn-sm btn-remove-harta btn-danger HartaBergerakverif" href="index.php/efill/lhkpn/removeHarta/5"><font color='white'><i class="fa fa-minus"></i> Hapus data terpilih</font></button>
        <div class="box-tools">
            <form method="post" id="ajaxFormCari2_2" action="">
                <div class="input-group  pull-right" style="width: 500px;">
                    <select name="CARI_BY" id="CARI_BY">
                        <option value="">-- Pencarian Berdasarkan --</option>
                        <option value="MEREK" <?php echo @$CARI_BY == 'MEREK' ? 'selected' : ''; ?>>Merek</option>
                        <option value="NOPOL" <?php echo @$CARI_BY == 'NOPOL' ? 'selected' : ''; ?>>No. Pol / Registrasi</option>
                        <option value="ATASNAMA" <?php echo @$CARI_BY == 'ATASNAMA' ? 'selected' : ''; ?>>Atas Nama</option>
                    </select>
                    <input type="text" name="cari" class="form-control input-sm pull-right" style="width: 200px;" placeholder="Search" value="<?php echo @$cari; ?>" id="cari"/>
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                        <button type="button" class="btn btn-sm btn-default" id="ajaxClearCari2_2" onclick="$('#CARI').val(''); $('#CARI_BY').val(''); $('#ajaxFormCari2_2').trigger('submit');">Clear</button>
                    </div>
                </div>
            </form>
        </div>
    <?php } ?>
        <br />
        <button type="button" class="btn btn-primary" href="index.php/efill/validasi_harta_bergerak/add/<?php echo $id_imp_xl_lhkpn; ?>" title="Tambah Data" onclick="onButton.go(this, null, true);"><i class="fa fa-plus"> Tambah</i></button>
        <br />
        <br />
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <!--<th width="10px" class="HartaBergerakverif"><input type="checkbox" class="chk-all" /></th>-->
                <?php /* <th width="10px">Status</th> */ ?>
                <th width="180px">JENIS</th>
                <th width="180px">IDENTITAS HARTA</th>
                <th width="250px">KEPEMILIKAN</th>
                <!--<th width="80px">TAHUN PEROLEHAN</th>-->
                <th width="170px">NILAI PEROLEHAN</th>
                <th width="170px">NILAI PELAPORAN</th>
                <th width="170px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            echo (count($HARTA_BERGERAKS) == 0 ? '<tr><td colspan="10" class="items-null">Data tidak ditemukan!</td></tr>' : '');
            ?>
            <?php
            $i = 0;
            $totalHartaBergerak = 0;
            foreach ($HARTA_BERGERAKS as $hartabergerak) :
                $totalHartaBergerak += $hartabergerak->NILAI_PELAPORAN;
                $labelHA = '';
                $nameHA = '';
                ?>
                <tr>
                    <td><?php echo ++$i; ?>.</td>
                    <td>
                        <?php
                        echo $list_harta[$hartabergerak->KODE_JENIS]
                        ?>
                        <b>Merek</b> <?php echo $hartabergerak->MEREK; ?><br>
                        <b>Model</b> <?php echo $hartabergerak->MODEL; ?><br>
                        <b>Tahun Pembuatan</b> <?php echo $hartabergerak->TAHUN_PEMBUATAN; ?><br>
                    </td>
                    <td>
                        <b>No. Pol. / Registrasi</b> <?php echo $hartabergerak->NOPOL_REGISTRASI; ?><br>
                    </td>
                    <td>
                        <?php
                        if ($hartabergerak->JENIS_BUKTI == '8') {
                            echo 'Bukti Lain';
                        } else {
                            echo $list_bukti_alat_transportasi[$hartabergerak->JENIS_BUKTI];
                        }
                        ?>, <b>a.n</b> <?php echo $hartabergerak->ATAS_NAMA != 3 ? map_data_atas_nama($hartabergerak->ATAS_NAMA) : $hartabergerak->ATAS_NAMA_LAINNYA; ?><br>
                        <b>Asal Usul Harta : </b><br>
                        <?php
                        $exp = explode(',', $hartabergerak->ASAL_USUL);
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
                        <br>
                        <b>Pemanfaatan : </b>
                        <br />
                        <?php
                        echo map_data_pemanfaatan($hartabergerak->PEMANFAATAN, 2);
//                        var_dump($pemanfaatan2, $hartabergerak->PEMANFAATAN);
                        ?>
                        <br>
                    </td>
                    <td>
                        <div align="right">
                            <?php echo $hartabergerak->SIMBOL; ?>&nbsp;
                            Rp. <?php echo _format_number($hartabergerak->NILAI_PEROLEHAN, 0); ?>
                        </div>
                    </td>
                    <td>
                        <div align="right">
                            Rp. <?php echo _format_number($hartabergerak->NILAI_PELAPORAN, 0); ?>
                        </div>
                    </td>
                    
                    <td nowrap="" style="text-align:center">
                        <button type="button" class="btn btn-primary" href="index.php/efill/validasi_harta_bergerak/edit/<?php echo $hartabergerak->id_imp_xl_lhkpn_harta_bergerak_secure; ?>" title="Edit" onclick="onButton.go(this, null, true);"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-danger" href="index.php/efill/validasi_harta_bergerak/hapus/<?php echo $hartabergerak->id_imp_xl_lhkpn_harta_bergerak_secure; ?>" title="Hapus" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>
                       <?php /* <button type="button" class="btn btn-danger" href="index.php/efill/validasi_harta_bergerak/hapus/<?php echo $hartabergerak->id_imp_xl_lhkpn_harta_bergerak_secure; ?>" title="Delete" onclick="onButton.go(this);"><i class="fa fa-trash" ></i></button> */ ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot class="table-footer">
            <tr>
                <td class="HartaBergerakspan" colspan="5"><b>Sub Total/Total</b></td>
                <td align="right"><b>Rp. <?php echo _format_number($totalHartaBergerak, 0); ?></b></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div><!-- /.box-body -->
<div class="box-footer">
</div><!-- /.box-footer -->

<script type="text/javascript">
    jQuery(document).ready(function () {
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
        $("#hartabergerak form").submit(function (e) {
            var id = $('input[name="ID_LHKPN"]').val();

            ng.LoadAjaxContentPost('index.php/efill/lhkpn/showTable/5/' + id + '/edit', $(this), $('#hartabergerak').find('.contentTab'));
            return false;
        });
        $("#ajaxClearCari2_2").click(function (e) {
            var id = $('input[name="ID_LHKPN"]').val();
            $('#hartabergerak input[name="cari"]').val('');

            ng.LoadAjaxContentPost('index.php/efill/lhkpn/showTable/5/' + id + '/edit', $(this), $('#hartabergerak').find('.contentTab'));
            return false;
        });
        $("#wrapperHartaBergerak .btn-detail").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Detail Harta Bergerak', html, '', 'large');
            });
            return false;
        });
        $("#wrapperHartaBergerak .btn-add").click(function () {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Tambah Harta Bergerak', html, '', 'large');
            });
            return false;
        });
        $('#wrapperHartaBergerak .btn-edit').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Edit Harta Bergerak', html, '', 'large');
            });
            return false;
        });
        $('#wrapperHartaBergerak .btn-delete').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Delete Harta Bergerak', html, '', 'standart');
            });
            return false;
        });
        $('#wrapperHartaBergerak .btn-pelaporan').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Data Pelepasan', html, '', 'standart');
            });
            return false;
        });
        $('#wrapperHartaBergerak .btn-pembanding').click(function (e) {
            url = $(this).attr('href');
            $.post(url, function (html) {
                OpenModalBox('Perbandingan Harta Bergerak', html, '', 'standart');
            });
            return false;
        });
    });
</script>