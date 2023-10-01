<?= FormHelpAccordionEfiling('lampiran_fasilitas'); ?>
<div class="box-body">
    <span class="block-body">
        <a href="javascript:void(0)" id="add" class="btn btn-info btn-sm">
            <i class="fa fa-plus"></i> Tambah
        </a>
    </span>
    <span class="block-body">
        <table id="TableFasilitas" class="table table-striped table-bordered table-hover table-heading no-border-bottom table-filing">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>URAIAN</th>
                    <th>NAMA PIHAK PEMBERI FASILITAS</th>
                    <th>KETERANGAN</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </span>
    <span class="block-body" style="overflow:hidden;">
        <div class="pull-right">
            <a href="javascript:void(0)" onclick="pindah(8)" class="btn btn-warning btn-sm" style="margin-left:5px;">
                <i class="fa fa-backward"></i>   Sebelumnya 
            </a>
            <a href="javascript:void(0)" onclick="pindah(9)" class="btn btn-warning btn-sm" style="margin-left:5px;">
                Selanjutnya <i class="fa fa-forward"></i>  
            </a>
        </div>
    </span>
</div>
<div class="box-footer"></div>

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" id="FormFasilitas" method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">FORM DATA LAMPIRAN FASILITAS</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="ID" id="ID"/>
                    <input type="hidden" name="ID_LHKPN" id="ID_LHKPN"/>
                    <div class="form-group">
                        <label>Jenis <span class="red-label">*</span> </label> <?= FormHelpPopOver('jenis_fas'); ?>
                        <select name="JENIS_FASILITAS" id="JENIS_FASILITAS" class="form-control" required>
                            <option></option>	
                            <option value="RUMAH DINAS">RUMAH DINAS</option>
                            <option value="BIAYA HIDUP">BIAYA HIDUP</option>
                            <option value="JAMINAN KESEHATAN">JAMINAN KESEHATAN</option>
                            <option value="MOBIL DINAS">MOBIL DINAS</option>
                            <option value="OPSI PEMBELIAN SAHAM">OPSI PEMBELIAN SAHAM</option>
                            <option value="FASILITAS LAINNYA">FASILITAS LAINNYA</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Keterangan </label> <?= FormHelpPopOver('keterangan_fas'); ?>
                        <textarea class="form-control" name="KETERANGAN" id="KETERANGAN" rows="3"  ></textarea>
                    </div> 
                    <div class="form-group">
                        <label>Nama Pihak Pemberi Fasilitas <span class="red-label">*</span> </label> <?= FormHelpPopOver('nama_pihak_pemberi_fas'); ?>
                        <input type="text"  id="PEMBERI_FASILITAS" name="PEMBERI_FASILITAS"  class="form-control" required/>
                    </div>
                    <div class="form-group">
                        <label>Keterangan Lain </label> <?= FormHelpPopOver('keterangan_lain_fas'); ?>
                        <textarea class="form-control" name="KETERANGAN_LAIN" id="KETERANGAN_LAIN" rows="4"  ></textarea>
                    </div>   


                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn-submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                    <button type="button" id="btn-cancel" class="btn btn-danger btn-sm" data-dismiss="modal">
                        <i class="fa fa-close"></i> Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

    	$('#ID_LHKPN').val(ID_LHKPN);

        $('html, body').animate({
            scrollTop: 0
        }, 2000);


        $('[data-toggle="popover"]').popover({
            placement: 'top',
            trigger: 'hover',
        });
        $('a.over').css('cursor', 'pointer');
        $('a.over').on('click', function(e) {
            $('a.over').not(this).popover('hide');
        });

        // GET TABLE DATA SOURCE
        $('#TableFasilitas').dataTable({
            "oLanguage": ecDtLang,
            'sPaginationType': 'full_numbers',
            'bServerSide': true,
            'bProcessing': true,
            'sAjaxSource': '<?php echo base_url(); ?>portal/data_fasilitas/TableFasilitas/' + ID_LHKPN,
            "bLengthChange": false,
            "bFilter": true,
            "bSort": false,
//            "bInfo": false,
            "bAutoWidth": false,
            'aoColumns': [null, null, null, null, null],
            'fnServerData': function(sSource, aoData, fnCallback) {
                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            },
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td:eq(1)', nRow).addClass("list-value");
                if (STATUS == '1' || STATUS == '3' || STATUS == '4' || STATUS == '5' || STATUS == '6' || VIA_VIA == '1') {
                    $(nRow).children().last().empty();
                }
                return nRow;
            }

        });
        $('#TableFasilitas tbody tr td').addClass('list-value');


        // ADD NEW FORM
        $('#add').click(function() {
            $('textarea,input').val('');
            $('#ID_LHKPN').val(ID_LHKPN);
            $('select').select2('data', null);
            $('#myModal').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        });

        // SUBMIT FORM

        $('#FormFasilitas').bootstrapValidator().on('success.form.bv', function(e) {
            e.preventDefault();
            var ID = $('#ID').val();
            var text;
            if (ID == '') {
                text = 'Data Lampiran Fasilitas berhasil ditambahkan';
            } else {
                text = 'Data Lampiran Fasilitas berhasil diperbaharui';
            }
            do_submit('#FormFasilitas', 'portal/data_fasilitas/update', text, '#myModal');
            $('#TableFasilitas').DataTable().ajax.reload();
        });

        // DELETE ACTION
        $("#TableFasilitas tbody").on('click', '.delete-action', function(event) {
            var id = $(this).attr('id');
            confirm("Apakah anda yakin akan menghapus data ? ", function() {
                do_delete('portal/data_fasilitas/delete/' + id, 'Data Fasilitas Berhasil Di Hapus ');
                $('#TableFasilitas').DataTable().ajax.reload();
            });
        });

        // EDIT ACTION
        $("#TableFasilitas tbody").on('click', '.edit-action', function(event) {
            var id = $(this).attr('id');
            var data = do_edit('portal/data_fasilitas/edit/' + id);
            if(data.result=='alert_security'){
                notif('Anda tidak memiliki akses pada data ini');
                return;
            }
            $('#ID').val(data.ID);
            $('#ID_LHKPN').val(ID_LHKPN);
            $('#JENIS_FASILITAS').select2('val', data.JENIS_FASILITAS);
            $('#NAMA_FASILITAS').val(data.NAMA_FASILITAS);
            $('#PEMBERI_FASILITAS').val(data.PEMBERI_FASILITAS);
            $('#KETERANGAN').val(data.KETERANGAN);
            $('#KETERANGAN_LAIN').val(data.KETERANGAN_LAIN);
            $('#myModal').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
            $('#btn-submit').prop('disabled', false);
        });

        if (STATUS == '0' || STATUS == '2' || STATUS == '7' && VIA_VIA == '0') {
            $('#add').show();
        } else {
            $('#add').hide();
        }

        $('#JENIS_FASILITAS').select2({
        });



    });
</script>