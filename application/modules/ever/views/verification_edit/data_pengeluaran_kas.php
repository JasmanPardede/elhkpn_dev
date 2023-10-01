<form role="form" id="ajaxFormEdit" action="index.php/ever/verification_edit/<?php echo $action; ?>" >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">FORM DATA PENGELUARAN KAS</h4>
    </div>
    <div class="modal-body row">
        <ul class="nav nav-tabs" role="tablist">
            <?php
            $tot2 = 0;
            $sub2 = '0';
            $label = array('A', 'B', 'C');
            $golongan = $golongan_pengeluaran_kas_pn;
            for ($i = 0; $i < count($golongan); $i++) :
                ?>
                <li class="<?= ($i == 0) ? 'active' : ''; ?>">
                    <a class="<?php echo $label[$i] . '_PENGELUARAN'; ?>" href="#tabss<?= $label[$i] ?>" data-toggle="tab" onClick="change('<?= $label[$i] ?>')"><?= $label[$i] ?>. <?= $golongan[$i] ?></a>
                </li>
            <?php endfor; ?>
        </ul>
        <?php
        $jenis = $jenis_pengeluaran_kas_pn;
        $lain = FALSE;

        $jAPN = 0;
        $jAP = 0;
        $JB = 0;
        $jC = 0;
        ?>
        <div style="width: 100%;">
            <input type='hidden' name='ID' id='ID'  value='<?php echo $ID; ?>'>
            <div class="tab-content">
                <?php
                for ($i = 0; $i < count($jenis); $i++) :
                    ?>
                    <div class="tab-pane <?= ($i == 0) ? 'active' : ''; ?>" id="tabss<?= $label[$i] ?>">
                        <table class="table table-bordered table-hover">
                            <thead class="table-header">
                                <tr>
                                    <th width="40px">NO</th>
                                    <th>JENIS PENGELUARAN</th>
                                    <?php
                                    $style = 'style="width: 150px;"';
                                    ?>
                                    <?php echo ($i == 0) ? '<th ' . $style . '>PENYELENGGARA NEGARA (Rp.)</th>' : '<th>ISIAN NILAI PENGELUARAN KAS (Rp.)</th>'; ?>
                                </tr>
                            </thead>
                            <tbody class="tabss<?= $label[$i] ?>">
                                <?php
                                for ($j = 0; $j < count($jenis[$i]); $j++) :
                                    $PA_val = 'PA' . $j;
                                    ?>
                                    <tr>
                                        <td><center><?= ($j + 1) ?>.</center></td>
                                <td><?= $jenis[$i][$j]; ?></td>
                                <td align="right">
                                    <?php
                                    /**
                                     * lws
                                     * Pengeluaran PN
                                     */
                                    ?>
                                    <?php $code = $label[$i] . $j; ?>
                                    <input type="text" name="<?php echo $code; ?>" id="<?php echo $code; ?>" class="form-control money" value="<?php echo !is_null($item->$code) && $item->$code != '' ? $item->$code : '0'; ?>" />
                                </td>
                               
                                </tr>
                                <?php
                            endfor;
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                endfor;
                ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary btn-sm" ><i class="fa fa-save"></i> Simpan</button>
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
        </div>
    </div>
</form>
<script type="text/javascript" src="<?php echo base_url(); ?>/portal-assets/js/jquery.mask.min.js?v=<?php echo rand(4, 80); ?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/portal-assets/js/jquery.maskMoney.min.js?v=<?php echo rand(4, 80); ?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
//
//        var list_jenis_harta = load_html('portal/data_harta/get_jenis_pelepasan_with_data', '<?php echo $item->JENIS_PELEPASAN_HARTA; ?>');
//        $('#JENIS_PELEPASAN_HARTA').html(list_jenis_harta);
//


        var url = location.href.split('#')[1];
        url = url.split('?')[0] + '?upperli=li6&bottomli=0';
        ng.formProcess($("#ajaxFormEdit"), 'update', url);
        $('.money').mask('000.000.000.000.000.000', {reverse: true});
    });
</script>