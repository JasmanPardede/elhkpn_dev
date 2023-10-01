<?php echo FormHelpAccordionEfiling('jabatan'); ?>
<div class="box-body">
    <span class="block-body">
        <a href="javascript:void(0);" id="add-jabatan" class="btn btn-info btn-sm">
            <i class="fa fa-plus"></i> Rangkap Jabatan
        </a>
    </span>
    <span class="block-body">
        <div >
            <label id="label_status_tempat">Bila Anda memiliki Jabatan rangkap, silakan tambahkan (+Jabatan Rangkap) kemudian pilihlah Jabatan Utama Anda.<span class="red-label">*</span> </label>
        </div>
    </span>
    <span class="block-body">
        <table id="TJabatan" class="table table-striped table-bordered table-hover table-heading no-border-bottom table-filing">
            <thead>
                <tr>
                    <th width="2%">NO</th>
                    <th width="20%">JABATAN</th>
                    <th width="20%">SUB UNIT KERJA</th>
                    <th width="23%">UNIT KERJA</th>
                    <th width="30%">LEMBAGA</th>
                    <th width="5%">AKSI</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </span>
    <span class="block-body" style="overflow:hidden;">
        <div class="pull-right">
            <a href="javascript:void(0)" onclick="pindah(1);" class="btn btn-warning btn-sm" style="margin-left:5px;">
                <i class="fa fa-backward"></i>  Sebelumnya
            </a>
            <a href="javascript:void(0)" onclick="pindah(3);" class="btn btn-warning btn-sm" style="margin-left:5px;">
                Selanjutnya <i class="fa fa-forward"></i>
            </a>
        </div>
    </span>
</div>
<div class="box-footer"></div>

<div id="myModalPrimary" class="modal fade" role="dialog" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">SET JABATAN UTAMA</h4>
            </div>
            <form id="FormJabatanPrimary">
                <div class="modal-body">
                    <input type="hidden" name="ID" id="IDJAB"/>
                    <input type="hidden" name="ID_LHKPN" id="ID_LHKPN_PRY"/>
                    <div class="form-group group-0">
                        <label>Apakah Anda Yakin akan Memilih Jabatan <span id="jab_set"></span>  Sebagai Jabatan Utama? </label>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm" >
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                            <i class="fa fa-remove"></i> Batal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" id="FormJabatan">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">FORM DATA JABATAN</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="ID" id="ID"/>
                    <input type="hidden" name="ID_LEM" id="ID_LEM"/>
                    <input type="hidden" name="ID_LHKPN" id="ID_LHKPN"/>
                    <div class="form-group group-0">
                        <label>Lembaga <span class="red-label">*</span> </label> <?php echo FormHelpPopOver('lembaga_jb'); ?>
                        <input type="text" id="lembaga" name="LEMBAGA"  class="form-control select2" required/>
                    </div>
                    <div class="form-group group-0">
                        <label>Unit Kerja <span class="red-label">*</span> </label> <?php echo FormHelpPopOver('unit_kerja_jb'); ?>
                        <input type="text" id="uk" name="UNIT_KERJA"   class="form-control select2" required/>
                    </div>
                    <div class="form-group group-0">
                        <label>Sub Unit Kerja </label> <?php echo FormHelpPopOver('sub_unit_kerja_jb'); ?>
                        <input type="text" id="sub_uk" name="SUB_UNIT_KERJA"  class="form-control select2" />
                    </div>
                    <div class="form-group group-0">
                        <label>Jabatan <span class="red-label">*</span> </label> <?php echo FormHelpPopOver('jabatan_jb'); ?>
                        <input type="text" id="jabatan" name="ID_JABATAN"   class="form-control select2" required/>
                    </div>
                    <div class="form-group group-0">
                        <label>Alamat Kantor  </label> <?php echo FormHelpPopOver('alamat_kantor_jb'); ?>
                        <textarea class="form-control" name="ALAMAT_KANTOR" id="alamat_kantor" rows="2"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm" >
                        <i class="fa fa-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                        <i class="fa fa-remove"></i> Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

    var ID_LEMBAGA = '<?php echo $this->session->userdata("INST_SATKERKD"); ?>' || 0;
    var NAMA_LEMBAGA = '<?php echo $this->session->userdata("INST_NAMA"); ?>';

    $(document).ready(function () {
        $('#ID_LHKPN').val(ID_LHKPN);
        $('#ID_LHKPN_PRY').val(ID_LHKPN);
        $('html, body').animate({
            scrollTop: 0
        }, 2000);

        $('[data-toggle="popover"]').popover({
            placement: 'top',
            trigger: 'hover',
        });

        $('a.over').css('cursor', 'pointer');
        $('a.over').on('click', function (e) {
            $('a.over').not(this).popover('hide');
        });

        $('#add-jabatan').click(function () {
        	$('textarea,#ID').val('');
            $('.select2').select2('data', null);
            $('#lembaga').select2('enable');
            $('#myModal').modal('show');
        });

        // SUBMIT FORM

        $('#FormJabatan').bootstrapValidator().on('success.form.bv', function (e) {
            e.preventDefault();
            var jabatanName = $('#jabatan').select2('data').text;
            var ID = $('#ID').val();
            var text;
            if (ID == '') {
                text = 'Data Jabatan Berhasil Di Tambahkan';
                $('#myModal').fadeOut();
                confirm("<label>Apakah anda ingin jabatan "+jabatanName+" sebagai jabatan utama?</label>", function(){
                		do_submit('#FormJabatan', 'portal/data_jabatan/update/primary', text, '#myModal');
                		$('#TJabatan').DataTable().ajax.reload();
                    },"Konfirmasi Jabatan Utama", function(){
                    	do_submit('#FormJabatan', 'portal/data_jabatan/update', text, '#myModal');
                    	$('#TJabatan').DataTable().ajax.reload();
                    },"YA","TIDAK"
                );
            } else {
                text = 'Data Jabatan Berhasil Di Update';
                do_submit('#FormJabatan', 'portal/data_jabatan/update', text, '#myModal');
                $('#TJabatan').DataTable().ajax.reload();
            }
        });

        $('#FormJabatanPrimary').bootstrapValidator().on('success.form.bv', function (e) {
            e.preventDefault();
            var ID = $('#ID').val();
            var text;
            text = 'Jabatan Primary Berhasil Di Update';
            do_submit('#FormJabatanPrimary', 'portal/data_jabatan/SetPrimary', text, '#myModalPrimary');
            $('#TJabatan').DataTable().ajax.reload();
        });

        // $('#FormJabatan').submit(function(){
        //     var ID = $('#ID').val();
        //     var text;
        //     if(ID==''){
        //         text = 'Data Jabatan Berhasil Di Tambahkan';
        //     }else{
        //         text = 'Data Jabatan Berhasil Di Update';
        //     }
        //     do_submit('#FormJabatan','portal/data_jabatan/update',text,'#myModal');
        //     $('#TJabatan').DataTable().ajax.reload();
        //     return false;
        // });

        $('#lembaga').select2({
            allowClear: true,
            ajax: {
                url: '<?php echo base_url(); ?>portal/data_jabatan/getlembaga',
                dataType: 'json',
                quietMillis: 100,
                data: function (term) {
                    return {
                        q: term, // search term
                    };
                },
                results: function (data) {
                    var myResults = [];
                    $.each(data, function (index, item) {
                        myResults.push({
                            'id': item.id,
                            'text': item.text
                        });
                    });
                    return {
                        results: myResults
                    };
                },
                minimumInputLength: 3
            }
        }).on("change", function (e) {
            var ID_LEMBAGA = $('#lembaga').val();
            GetUK(ID_LEMBAGA);
        });

        GetUK(0);
        SubUK(0);
        GetJabatan(0, 0);

        if (STATUS == '1' || STATUS == '3' || STATUS == '4' || STATUS == '5' || STATUS == '6' || VIA_VIA == '1') {
            $('#add-jabatan').hide();
        }
    });

    function GetUK(ID_LEMBAGA) {
        $('#uk').select2({
            allowClear: true,
            ajax: {
                url: '<?php echo base_url(); ?>portal/data_jabatan/getuk/' + ID_LEMBAGA,
                dataType: 'json',
                quietMillis: 100,
                data: function (term) {
                    return {
                        q: term, // search term
                    };
                },
                results: function (data) {
                    var myResults = [];
                    $.each(data, function (index, item) {
                        myResults.push({
                            'id': item.id,
                            'text': item.text
                        });
                    });
                    return {
                        results: myResults
                    };
                },
                minimumInputLength: 3
            }
        }).on("change", function (e) {
            var ID_UK = $('#uk').val();
            SubUK(ID_UK);
            GetJabatan(ID_UK);
        });
    }
    ;

    function SubUK(ID_UK) {
        $('#sub_uk').select2({
            allowClear: true,
            ajax: {
                url: '<?php echo base_url(); ?>portal/data_jabatan/getsubuk/' + ID_UK,
                dataType: 'json',
                quietMillis: 100,
                data: function (term) {
                    return {
                        q: term, // search term
                    };
                },
                results: function (data) {
                    var myResults = [];
                    $.each(data, function (index, item) {
                        myResults.push({
                            'id': item.id,
                            'text': item.text
                        });
                    });
                    return {
                        results: myResults
                    };
                },
                minimumInputLength: 3
            }
        }).on("change", function (e) {
            var UK_ID = $('#uk').val();
            var SUK_ID = $('#sub_uk').val();
//            console.log($(this).val());
            GetJabatan(UK_ID, SUK_ID);
        });
    }
    ;

    function GetJabatan(UK_ID, SUK_ID) {
        SUK_ID = SUK_ID ? SUK_ID : 0;
        $('#jabatan').select2({
            allowClear: true,
            ajax: {
                url: '<?php echo base_url(); ?>portal/data_jabatan/getjabatan/' + UK_ID + '/' + SUK_ID,
                dataType: 'json',
                quietMillis: 100,
                data: function (term) {
                    return {
                        q: term, // search term
                    };
                },
                results: function (data) {
                    var myResults = [];
                    $.each(data, function (index, item) {
                        myResults.push({
                            'id': item.id,
                            'text': item.text
                        });
                    });
                    return {
                        results: myResults
                    };
                },
                minimumInputLength: 3
            }
        });
    }
    ;

</script>
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
