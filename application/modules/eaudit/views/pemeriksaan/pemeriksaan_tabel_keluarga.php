<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/**
 * View
 *
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Views/ever/verification
*/
?>
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
    <h5 class="">"Data Keluarga Inti"</h5>
</div>
<div class="box-body" id="wrapperKeluarga">
    <?php if ($item->entry_via == '1'): ?>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#keluargaModal">
            <span class="fa fa-plus"></span> Tambah
        </button>
        <br>
        <br>
    <?php endif ?>
    <table id="TKeluarga" class="table table-striped table-bordered table-hover table-heading no-border-bottom table-filing">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NAMA</th>
                    <th>HUBUNGAN DENGAN PN</th>
                    <th>TEMPAT DAN TANGGAL LAHIR / JENIS KELAMIN</th>
                    <th>PEKERJAAN</th>
                    <th>ALAMAT</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    <!-- <table id="aaa" class="table table-bordered table-hover table-striped">
        <thead class="table-header">
            <tr>
                <th width="10px">NO</th>
                <th width="200px">NAMA</th>
                <th width="200px">HUBUNGAN DENGAN PN</th>
                <th width="200px">TEMPAT &amp; TANGGAL LAHIR / JENIS KELAMIN</th>
                <th width="200px">PEKERJAAN</th>
                <th width="200px">ALAMAT RUMAH</th>
                <?php if ($item->entry_via == '1'): ?>
                <th>AKSI</th>
                <?php endif ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($KELUARGAS as $keluarga) {
            ?>
            <tr>
                <td><?php echo ++$i; ?>.</td>
                <td>
                    <?php echo $keluarga->NAMA; ?><br>
					
                    
                </td>
                <td>
                    <?php
                    if ($lhkpn_ver == '1.6' || $lhkpn_ver == '1.8' || $lhkpn_ver == '1.11' || $lhkpn_ver == '2.1') {
                        switch ($keluarga->HUBUNGAN) {
                            case 3 : echo 'ISTRI';echo '<br>';
                                break;
                            case 2 : echo 'SUAMI';echo '<br>';
                                break;
                            case 4 : echo 'ANAK TANGGUNGAN';echo '<br>';
                                break;
                            case 5 : echo 'ANAK BUKAN TANGGUNGAN';echo '<br>';
                                break;
                            default : echo 'LAINNYA';echo '<br>';
                        };
                    }
                    else{
                        switch ($keluarga->HUBUNGAN) {
                            case 1 : echo 'ISTRI';echo '<br>';
                                break;
                            case 2 : echo 'SUAMI';echo '<br>';
                                break;
                            case 3 : echo 'ANAK TANGGUNGAN';echo '<br>';
                                break;
                            case 4 : echo 'ANAK BUKAN TANGGUNGAN';echo '<br>';
                                break;
                            default : echo 'LAINNYA';echo '<br>';
                        };
                    }

                    ?>
                    
                </td>
                <td><?php echo $keluarga->TEMPAT_LAHIR.', '. tgl_format($keluarga->TANGGAL_LAHIR).' / '.show_jenis_kelamin($keluarga->JENIS_KELAMIN); ?><br>
                    
                </td>
                <td><?php echo $keluarga->PEKERJAAN; ?></td>
                <td>
                    <?php echo $keluarga->ALAMAT_RUMAH; ?>
                </td>
                <?php if ($item->entry_via == '1'): ?>
                <td style="text-align:center">
                    <a id='<?php echo $keluarga->ID_KELUARGA; ?>'  href='javascript:void(0)' class='btn btn-success btn-sm edit-action' title='Edit'><i class='fa fa-pencil'></i></a>
                    <button type="button" class="btn btn-danger" href="index.php/ever/verifikasi_keluarga/hapus/<?php echo $keluarga->ID_KELUARGA; ?>" title="Hapus Data" onclick="onButton.delete(this);"><i class="fa fa-trash"></i></button>
                </td>
                <?php endif ?>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table> -->
    <br />
    
</div>

<div class="modal fade" id="ss" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">FORM DATA KELUARGA</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div id="keluargaModal" class="modal fade container-fluid" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" id="FormKeluarga">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">FORM DATA KELUARGA</h4>
                </div>
                <div class="modal-body row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="hidden" name="ID_LHKPN" id="ID_LHKPN"/>
                            <input type="hidden" name="ID" id="ID"/>
                            <label>Nama <span class="red-label">*</span> </label> <?= FormHelpPopOver('nama_dkel'); ?>
                            <input type="text" name="NAMA" id="NAMA" class="form-control input_capital" required/>
                        </div>
                        <div class="form-group">
                            <label>Nomor Induk Kependudukan (NIK) </label> <?php echo FormHelpPopOver('nik_dp'); ?>
                            <input name='NIK_KELUARGA' id='NIK_KELUARGA' onkeypress="return isNumber(event)" maxlength="16" type="text" class="form-control" <?php echo FormHelpPlaceholderToolTip('nik'); ?> >
                        </div>
                        <div class="form-group hubungan">
                            <label>Hubungan <span class="red-label">*</span> </label> <?= FormHelpPopOver('hubungan_dkel'); ?>
                            <select name="HUBUNGAN" id="hubungan" class="form-control"  required>
                                <option></option>
                                <option value="1">ISTRI</option>
                                <option value="2">SUAMI</option>
                                <option value="3">ANAK TANGGUNGAN</option>
                                <option value="4">ANAK BUKAN TANGGUNGAN</option>
                                <option value="5">LAINNYA</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tempat Lahir <span class="red-label">*</span> </label> <?= FormHelpPopOver('tpt_lahir_dkel'); ?>
                            <input type="text" name="TEMPAT_LAHIR" id="TEMPAT_LAHIR" class="form-control input_capital" required/>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir <span class="red-label">*</span> </label> <?= FormHelpPopOver('tgl_lahir_dkel'); ?>
                            <div class="input-group date">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                </div>
                                <input type="text" name="TANGGAL_LAHIR" id="TANGGAL_LAHIR" placeholder="( tgl/bulan/tahun )" class="form-control date" required/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group jenis_kelamin">
                            <label>Jenis Kelamin <span class="red-label">*</span></label> <?= FormHelpPopOver('jenis_kelamin_dkel'); ?>
                            <select class="form-control" id="jenis_kelamin" name="JENIS_KELAMIN" required>
                                <option></option>
                                <option value="LAKI-LAKI">LAKI-LAKI</option>
                                <option value="PEREMPUAN">PEREMPUAN</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Pekerjaan </label> <?= FormHelpPopOver('pekerjaan_dkel'); ?>
                            <input type="text" name="PEKERJAAN" id="PEKERJAAN" class="form-control input_capital" />
                        </div>
                        <div class="form-group">
                            <label>Nomor Telepon/Handphone </label> <?= FormHelpPopOver('no_telp_dkel'); ?>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                                <input type="text" id="NOMOR_TELPON" name="NOMOR_TELPON" class="form-control" onkeypress="return isNumber(event)"  placeholder="Isikan Nomor Handphone" />
                            </div>
                        </div>
                        <div class="form-group">    
                            <label>Alamat<span class="red-label">*</span> </label> <?= FormHelpPopOver('alamat_rmh_dkel'); ?> 
                            <textarea class="form-control" rows="3" name="ALAMAT_RUMAH" id="ALAMAT_RUMAH" required ></textarea>
                            <input id="alamat_pn" type="button" class="btn  btn-sm btn-primary" value="sama dengan PN" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn-submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                    <button type="button" id="btn-cancel" class="btn btn-danger btn-sm" data-dismiss="modal">
                        <i class="fa fa-remove"></i> Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var ID_LHKPN = "<?php echo $item->ID_LHKPN; ?>";
        var ID_PN = "<?php echo $item->ID_PN; ?>";
        var alamat_rumah = "<?php echo $DATA_PRIBADI->ALAMAT_RUMAH; ?>";

        $('#ID_LHKPN').val(ID_LHKPN);

        $('#alamat_pn').on('click', function(e) {
            $('#ALAMAT_RUMAH').val(alamat_rumah);
        });

        $('html, body').animate({
            scrollTop: 0
        }, 2000);

        $('#TANGGAL_LAHIR').datetimepicker({
            viewMode: 'years',
            format: "DD/MM/YYYY",
            maxDate: 'now'
        });

        $('#TANGGAL').datetimepicker({
            format: "DD/MM/YYYY",
            maxDate: 'now'
        });

        $(function () {
        $('.over').popover();
        $('.over')
                .mouseenter(function (e) {
                    $(this).popover('show');
                })
                .mouseleave(function (e) {
                    $(this).popover('hide');
                });
    });

        // $('select').select2();

        // GET TABLE DATA SOURCE
        $('#TKeluarga').dataTable({
            "oLanguage": ecDtLang,
            'sPaginationType': 'full_numbers',
            'bServerSide': true,
            'bProcessing': true,
           'sAjaxSource': '<?php echo base_url(); ?>portal/data_keluarga/tablekeluarga/' + ID_LHKPN + '/' + ID_PN,
            // 'sAjaxSource': '<?php echo base_url(); ?>portal/data_keluarga/tablekeluarga/' + ID_LHKPN,
            "bLengthChange": false,
            "bFilter": true,
            "bSort": false,
//            "bInfo": true,
            "bAutoWidth": false,
            'aoColumns': [{sWidth: "5%"}, {sWidth: "13%"}, {sWidth: "15%"}, {sWidth: "25%"}, {sWidth: "10%"}, {sWidth: "22%"}, {sWidth: "10%"}],
            'fnServerData': function(sSource, aoData, fnCallback) {
                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            }

        });


        // ADD NEW FORM
        $('#add').click(function() {
            $('#FormKeluarga')[0].reset();
            $('select').select2('data', null);
            $('#ID').val('');
            $('#keluargaModal').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        });


        $('#keluargaModal .modal-dialog').css({
            'margin-top': '5px',
            'width': '70%',
            'height': '100%'
        });

        $('#keluargaModal .form-group').css({
            'margin-bottom': '7.5px'
        });

        $('#keluargaModal .modal-footer').css({
            'padding': '10px'
        });

        // SUBMIT FORM

        $('#FormKeluarga').bootstrapValidator().on('success.form.bv', function(e) {
            
            e.preventDefault();
            var ID = $('#ID').val();
            var text;
            if (ID == '') {
                text = 'Data Keluarga Berhasil Di Tambahkan';
            } else {
                text = 'Data Keluarga Berhasil Di Update';
            }
            do_submit('#FormKeluarga', 'portal/data_keluarga/update', text, '#keluargaModal');
            $('#TKeluarga').DataTable().ajax.reload();
        });

        $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });

        // $('#FormKeluarga').submit(function(){
        //     var ID = $('#ID').val();
        //     var text;
        //     if(ID==''){
        //         text = 'Data Keluarga Berhasil Di Tambahkan';
        //     }else{
        //         text = 'Data Keluarga Berhasil Di Update';
        //     }
        //     do_submit('#FormKeluarga','portal/data_keluarga/update',text,'#myModal');
        //     $('#TKeluarga').DataTable().ajax.reload();
        //     return false;
        // });

        // DELETE ACTION
        $("#TKeluarga tbody").on('click', '.delete-action', function(event) {
            var id = $(this).attr('id');
            confirm("Apakah anda yakin akan menghapus data ? ", function(){
                do_delete('portal/data_keluarga/delete/' + id, 'Data Keluarga Berhasil Di Hapus ');
                $('#TKeluarga').DataTable().ajax.reload();
            });
        });

        // EDIT ACTION
        $("#TKeluarga tbody").on('click', '.edit-action', function(event) {
            $('#FormKeluarga')[0].reset();
            var id = $(this).attr('id');
            var data = do_edit('portal/data_keluarga/edit/' + id);
            $('#ID').val(data.ID_KELUARGA);
            $('#NAMA').val(data.NAMA);
            $('#NIK_KELUARGA').val(data.NIK);
            var val;
            
            if (data.JENIS_KELAMIN == '2'){
                var JenKel = 'LAKI-LAKI';
            }else if (data.JENIS_KELAMIN == '3'){
                var JenKel = 'PEREMPUAN';
            }else{
                var JenKel = data.JENIS_KELAMIN;
            }
            
            $('#label_status_tempat').text('Tempat ' + val);
            $('#label_status_tanggal').text('Tanggal ' + val);
            $('#TEMPAT_LAHIR').val(data.TEMPAT_LAHIR);
            $('#TANGGAL_LAHIR').val(dateConvert(data.TANGGAL_LAHIR));
            $('#jenis_kelamin').select2('val', JenKel);
            $('#PEKERJAAN').val(data.PEKERJAAN);
            $('#NOMOR_TELPON').val(data.NOMOR_TELPON);
            $('#ALAMAT_RUMAH').val(data.ALAMAT_RUMAH);
            $('#keluargaModal').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
            alamat_rumah = data.Alamatrumah;
            
            $('#btn-submit').prop('disabled', false);
        });
    });

function do_edit(url) {
    var result;
    var ajaxTime = new Date().getTime();
    $.ajax({
        url: base_url + '' + url,
        async: false,
        dataType: 'JSON',
        beforeSend: function () {
            Loading('show');
        },
        complete: function () {
            // Loading('hide');
        },
        success: function (data) {
            result = eval(data);
            var totalTime = new Date().getTime() - ajaxTime;
            stf(totalTime);
        },
        error: function (jqXHR, exception) {
            ajax_error_xhr(jqXHR, exception);
        },
    });
    return result;
}
</script>
