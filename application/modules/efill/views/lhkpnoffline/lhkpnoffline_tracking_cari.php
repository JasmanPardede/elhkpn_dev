<style type="text/css">
    .inputFile {
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        position: absolute;
        z-index: -1;
    }

    .inputFile + label {
        cursor: pointer;
        /*font-size: 1.25em;*/
    }

    .inputFile:focus + label,
    .inputFile + label:hover {
        cursor: pointer;
        /*background-color: red;*/
    }

    .td-lhkpn-excel, .td-aksi{
        text-align: center;
    }

    .td-lhkpn-excel, .td-aksi, .td-nama-file{
        font-size: 12px;
        margin: 5px;
    }
    .form-body-penerimaan{
        overflow: auto; 
        max-height: 400px;
    }
</style>

<div id="wrapperCariPN">
    <ol class="breadcrumb">
        <li>Tracking LHKPN</li>
        <li>Cari PN</li>
    </ol>

        <div class="col-md-12">
        <form method="post" class='form-horizontal' id="ajaxFormCariPN" action="index.php/efill/lhkpnoffline/hasiltrackingpn/">
            <div class="box-body">
                <div class="col-md-6">
                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Nama/NIK <font color='red'>*</font> :</label>
                            <div class="col-sm-5">
                                <input type="text" required class="form-control input-sm pull-right" placeholder="Search PN / WL NIK" name="CARI[TEXT]" value="<?php echo @$CARI['TEXT']; ?>" id="CARI_TEXT_PN"/>    
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Tanggal Lahir :</label>
                            <div class="col-sm-3">
                                <input class="form-control input-sm pull-right TGL_LAHIR field-required" type='text'name='CARI[TGL_LAHIR]' id='TGL_LAHIR' placeholder='DD/MM/YYYY'>
                            </div>
                            <div class="form-group">
                                <div class="col-col-sm-3 col-sm-offset-4-2">
                                    <button type="submit" class="btn btn-sm btn-default" id="btn-cari">Cari</button>
                                    <button type="button" class="btn btn-sm btn-default" id="btn-clear" onclick="$('#CARI_TEXT_PN').val(''); $('#CARI_TEXT_PN').focus(); $('#ajaxFormCariPN').trigger('submit');">Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <br/>
    <div class="clearfix" style="margin-bottom: 10px;"></div>
    <div id="wrapperHasilCariPN">
        <!-- draw here -->
    </div>
    <div class="pull-right">
        <button type="button" class="btn btn-sm btn-default" id="btnKembaliKePenerimaan" onclick="CloseModalBox2();">Kembali Ke form</button>
    </div>
</div>
<script language="text/javascript">
    $(document).ready(function() {
        $("#ajaxFormCariPN").submit(function (e) {
                var val_cari = $('#CARI_TEXT_PN').val().length;
                if (val_cari > 0) {
                    e.preventDefault();
                    var url = $(this).attr('action');
                    ng.LoadAjaxContentPost(url, $(this), '#wrapperHasilCariPN', eventShowHasilCariPN());
                } else {
                    $('#CARI_TEXT_PN').focus();
                    alertify.error('Silahkan Isi Nama atau NIK!');
                }
                return false;
            });
        $("#TGL_LAHIR").datepicker({
            format: "dd/mm/yyyy",
            autoclose: true,
            maxDate: '-17y'
        });    
    });
    
    function eventShowHasilCariPN (){
        $(".paginationPN").find("a").click(function () {
            var url = $(this).attr('href');
            // window.location.hash = url;
            ng.LoadAjaxContentPost(url, $('#ajaxFormCariPN'), '#wrapperHasilCariPN', eventShowHasilCariPN());
            return false;
        });
    }
</script>