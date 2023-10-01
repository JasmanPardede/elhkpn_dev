<div id="wrapperFormEdit">
    <form class="form-horizontal" method="post" id="ajaxFormEdit" action="index.php/efill/validasi_penerimaan/update_perubahan" enctype="multipart/form-data">
        <input type='hidden' name='ID_IMP_XL_LHKPN' id='id_imp_xl_lhkpn' value='<?php echo $item->ID_IMP_XL_LHKPN_secure; ?>'>
        <div class="tab-content" style="padding: 2px; border:0px solid #cfcfcf;margin-top: -1px;">
            <div role="tabpanel" class="tab-pane active" id="a">
                <div class="contentTab">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-5">
                                    <label class="control-label">Tanggal Terima <span class="red-label">*</span>:</label>
                                </div>
                                <div class="col-sm-7">
                                    <input required class="col-sm-3 form-control date-picker" type='text' name='TANGGAL_PENERIMAAN' id='TANGGAL_PENERIMAAN' placeholder='DD/MM/YYYY' value="<?php echo date('d/m/Y', strtotime($item->TANGGAL_PENERIMAAN)); ?>" />
                                </div>
                            </div>
                            <input required class="col-sm-3 form-control date-picker" type='hidden' name='TANGGAL_PELAPORAN' id='TANGGAL_PELAPORAN' placeholder='DD/MM/YYYY' value="<?php echo date('d/m/Y', strtotime($item->TANGGAL_PELAPORAN)); ?>" />
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

        $('#TANGGAL_PENERIMAAN').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true
        });

        var url = location.href.split('#')[1];
        url = url.split('?')[0] + '?upperli=li1&bottomli=0';

        ng.formProcess($("#ajaxFormEdit"), 'update', url);

    });
</script>