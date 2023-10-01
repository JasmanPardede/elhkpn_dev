<div id="wrapperFormDetail" class="form-horizontal">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">PN :</label>
                <label class="col-sm-8">
                    <?php echo $item->ID_PN; ?>
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-4" style="text-align:right !important;">Tanggal Penerimaan :</label>
                <label class="col-sm-8">
                    <?php echo $item->TANGGAL_PENERIMAAN; ?>
                </label>
            </div>
        </div>
    </div>
    <div class="pull-right">
        <input type="reset" class="btn btn-sm btn-default" value="Batal" onclick="CloseModalBox();">
    </div>