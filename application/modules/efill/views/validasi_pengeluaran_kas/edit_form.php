<?php $primary_key_secure = $primary_key . "_secure"; ?>
<form role="form" id="ajaxFormEdit" action="index.php/efill/validasi_pengeluaran_kas/update_perubahan" >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">FORM PENGELUARAN KAS</h4>
    </div>
    <div class="modal-body row">
        <ul class="nav nav-tabs" role="tablist">
            <?php
            $idtext3 = [];
            $tot3 = 0;
            $sub3 = '0';
            $label = array('A', 'B', 'C');
            $golongan = $golongan_pengeluaran_kas_pn;
            $jenis = $jenis_pengeluaran_kas_pn;
            for ($i = 0; $i < count($golongan); $i++) :
                ?>
                <li class="<?= ($i == 0) ? 'active' : ''; ?>">
                    <a class="<?php echo $label[$i] . '_PENGELUARAN'; ?>" href="#tabs3<?= $label[$i] ?>" data-toggle="tab"><?= $label[$i] ?>. <?= $golongan[$i] ?></a>
                </li>
            <?php endfor; ?>
        </ul>
        <?php
        $lain = FALSE;
        $jpA = 0;
        $jpB = 0;
        $jpC = 0;
        ?>
        <div style="width: 100%;">
            <input type='hidden' name='id_imp_xl_lhkpn_pengeluaran_kas' id='id_imp_xl_lhkpn_pengeluaran_kas'  value='<?php echo $item->id_imp_xl_lhkpn_pengeluaran_kas_secure; ?>'>
            <div class="tab-content">
                <?php
                for ($i = 0; $i < count($jenis); $i++) :
                    ?>
                    <div class="tab-pane <?php echo ($i == 0) ? 'active' : ''; ?>" id="tabs3<?php echo $label[$i] ?>">
                        <table class="table table-bordered table-hover">
                            <thead class="table-header">
                                <tr>
                                    <th width="40px">NO</th>
                                    <th>JENIS PENGELUARAN</th>
                                    <?php
                                    $style = 'style="width: 150px;"';
                                    ?>
                                    <th <?php echo $style; ?>>ISIAN NILAI PENGELUARAN KAS (Rp.)</th>
                                </tr>
                            </thead>
                            <tbody class="tabs<?php echo $label[$i] ?>">
                                <?php
                                for ($j = 0; $j < count($jenis[$i]); $j++) :
                                    ?>
                                    <tr>
                                        <td><center><?php echo ($j + 1); ?>.</center></td>
                                <td><?= $jenis[$i][$j] ?></td>
                                <td align="right">
                                    <?php $code = $label[$i] . $j; ?>
                                    <input type="text" name="<?php echo $code; ?>" id="<?php echo $code; ?>"  class="form-control money" value="<?php echo !is_null($item->$code) && $item->$code != '' ? $item->$code : '0'; ?>" />
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

<script type="text/javascript">
    $(document).ready(function () {

        var url = location.href.split('#')[1];
        url = url.split('?')[0] + '?upperli=li5&bottomli=0';
        ng.formProcess($("#ajaxFormEdit"), 'update', url);
    });
</script>