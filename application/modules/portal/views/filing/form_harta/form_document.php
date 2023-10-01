<!---SURAT BERHARGA-->
<!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">-->
<div id="ModalHarta" class="modal fade container-fluid" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" id="FormDocument" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">BERKAS LAMPIRAN</h4>
                </div>
                <div class="modal-body row" style="text-align:center">
                    <div >        
                        <input type="hidden" name="checkext" id="checkext" value=""/>    
                        <input type="hidden" name="id_file" id="id_file" value=""/>     
                        <input type="hidden" name="secretname" id="secretname" value=""/>              
                        <input type="hidden" name="tipeharta" id="tipeharta" value=""/>              
                        <?= $dadir ?>
                        <img src="#" id="img_file" style="width:70%"/>
                        <img src="#" id="no_image" style="width:40%;border:0"/>
                        
                    </div>
                    <!--<div class="form-group">
                        <label>Nomor Rekening / No Nasabah <span class="red-label">*</span></label> <?= FormHelpPopOver('email'); ?>
                        <input type="text" name="NOMOR_REKENING" id="NOMOR_REKENING" class="form-control" required/>    
                    </div> -->
                   
                </div><!--end of modal-->
                <div class="modal-footer">
                    <button type="submit" id="button-saved" class="btn btn-warning btn-sm"><i class="fa fa-trash"></i> Delete</button>
                    <a target="_blank" id="btn-download-minio" href="" class="btn btn-success btn-sm" ><i class="fa fa-download"></i> Download</a>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i>  Batal</button>
                </div>
            </form>
           
        </div>
    </div>
</div>
<!---END HARTA TIDAK BERGERAK -->
<script type="text/javascript">

    $(document).ready(function() {
        $('#ModalHarta .modal-dialog').css({
            'margin-top': '5px',
            'width': '60%',
            'height': '100%'
        });

        $('#ModalHarta .form-group').css({
            'margin-bottom': '7.5px'
        });

        $('#ModalHarta .modal-footer').css({
            'padding': '10px'
        });
        $("#img_file").hide();
        $("#no_image").hide();

        if (STATUS == '0' || STATUS == '2' || STATUS == '7') {
            $('input[type=submit],button[type=submit]').show();
        } else {
            $('input[type=submit],button[type=submit]').hide();
        }

        
        setTimeout(function(){ 
            var checkext = $("#checkext").val();
            if(checkext=='png' || checkext=='jpg' || checkext=='jpeg'){       
                $("#img_file").show();
            }else if(checkext=='doc' || checkext=='docx'){
                fileWord = '<?= base_url() ?>/img/icon/word.png';
                $("#no_image").attr("src", fileWord);     
                $("#no_image").show();
            }else if(checkext=='xls' || checkext=='xlsx'){
                fileExcel = '<?= base_url() ?>/img/icon/excel.png';
                $("#no_image").attr("src", fileExcel);     
                $("#no_image").show();
            }else if(checkext=='pdf'){
                filePdf = '<?= base_url() ?>/img/icon/pdf.png';
                $("#no_image").attr("src", filePdf);     
                $("#no_image").show();
            }else{
                fileLainnya = '<?= base_url() ?>/img/icon/lainnya.svg';
                $("#no_image").attr("src", fileLainnya);     
                $("#no_image").show();
            }
         }, 100);
     

    
        $('#FormDocument').bootstrapValidator({
            fields: {
                'KET_LAINNYA_AN': {
                    validators: {
                        notEmpty: {
                            message: 'Data ini wajib di isi'
                        }
                    }
                }
            }
        }).on('error.form.bv', function(e, data) {
            // CustomValidation();
        }).on('success.form.bv', function(e) {
            e.preventDefault();
            text = 'Data Harta berhasil diperbaharui';
            confirm("Apakah berkas akan dihapus ? ", function(){
                var tipeharta = $("#tipeharta").val();
                do_submit('#FormDocument', 'portal/data_harta/delete_file_pendukung/'+tipeharta, text, '#ModalHarta');
                if(tipeharta=='surat_berharga'){
                    $('#TableSurat').DataTable().ajax.reload(null,false);
                }else if(tipeharta=='kas'){
                    $('#TableKas').DataTable().ajax.reload(null,false);
                }else if(tipeharta=='harta_lainnya'){
                    $('#TableLain').DataTable().ajax.reload(null,false);
                }
            });
        
            return false;
        }).on('added.field.fv', function(e, data) {
            // data.field   --> The field name
            // data.element --> The new field element
            // data.options --> The new field options

            if (data.field === 'KET_LAINNYA_AN') {
                if ($('#FormHarta').find(':visible[name="KET_LAINNYA_AN"').val() == '') {
//                    $('#surveyForm').find('.addButton').attr('disabled', 'disabled');
                }
            }
        }).on('removed.field.fv', function(e, data) { // Called after removing the field
            if (data.field === 'option[]') {
                if ($('#FormHarta').find(':visible[name="KET_LAINNYA_AN"').val() == '') {
//                    $('#surveyForm').find('.addButton').removeAttr('disabled');
                }
            }
        });

        $('#ModalHarta').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });


    });

 

</script>