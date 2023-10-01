<?php
/*

*/
/**
* @author Ahmad Saughi
* @version 04122018
*/

?>
<style media="screen">
#btnCetakSurat {
    background-color: #e08e0b;
    border-color: #e08e0b;
}
#btnInputSuratBalasan {
    background-color: #008d4c;
    color: white;
}
.row {
    margin-top: 10px;
}
table#table-nodin tbody {
    font-size: 13px;
}
table#table-nodin th {
    padding-right: 20px;
    padding-left: 20px;
}
table#table-nodin thead tr {
    background-color: #337ab7;
    height: 60px;
    color: ghostwhite;
}
table#table_file th {
    padding-right: 0px;
    padding-left: 0px;
}
table#table_file td {
    vertical-align: middle;
}

</style>
<section class="content-header">
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="row">
                <form class='form-horizontal' id="ajaxFormCari">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label labelNodin">Nomor Nota Dinas</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="CARI[NOMOR_ND]" placeholder="Nomor Nota Dinas" value="<?php echo @$CARI['NOMOR_SURAT']; ?>" id="CARI_NOMOR_ND">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label labelNodin">Tanggal Nota Dinas</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control date" name="CARI[TANGGAL_ND]" placeholder="Tanggal Nota Dinas" value="<?php echo @$CARI['TANGGAL_SURAT']; ?>" id="CARI_TANGGAL_ND">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label labelNodin">Jenis Nota Dinas</label>
                                <div class="col-sm-7">
                                    <select class="form-control" name='CARI[JENIS_ND]' id='CARI_JENIS_ND'>
                                        <option value="" disabled selected>--- Pilih Jenis ---</option>
                                        <option value="1">Data Keuangan</option>
                                        <option value="2">LHP (Rekomendasi)</option>
                                        <option value="3">LHP</option>
                                        <option value="4">Data Lainnya</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label labelNodin">Tujuan Nota Dinas</label>
                                <div class="col-sm-7">
                                    <!-- <input type="text" class="form-control" name="CARI[TUJUAN_ND]" placeholder="Tujuan Nota Dinas" value="<?php // echo @$CARI['NAMA_INSTANSI']; ?>" id="CARI_TUJUAN_ND"> -->
<!--                                    <select class="form-control" name="CARI[TUJUAN_ND]" id="CARI_TUJUAN_ND">
                                        <option value="" disabled selected>--- Pilih Jenis ---</option>
                                        <option value="Gratifikasi">Gratifikasi</option>
                                        <option value="Penyelidikan">Penyelidikan</option>
                                        <option value="Penyidikan">Penyidikan</option>
                                        <option value="Litbang">Litbang</option>
                                        <option value="Dumas">Dumas</option>
                                        <option value="Korsupgah">Korsupgah</option>
                                        <option value="Korsupdak">Korsupdak</option>
                                        <option value="Pengawasan Internal">Pengawasan Internal</option>
                                        <option value="Labuksi/ATR">Labuksi/ATR</option>
                                        <option value="Internal Lainnya">Internal Lainnya</option>
                                        <option value="Eksternal">Eksternal</option>
                                    </select>-->
                                    <input type='text' class="input-sm form-control" name='CARI[TUJUAN_ND]' style="border:none;padding:6px 0px;" id='CARI_TUJUAN_ND' value="<?php echo @$CARI['TUJUAN_ND']; ?>" >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label labelNodin">Nama PN</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="CARI[NAMA]" placeholder="Nama" value="<?php echo @$CARI['NAMA']; ?>" id="CARI_NAMA">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6" style="float:right;">
                                    <button type="submit" class="btn btn-sm btn-default" id="btn-filter"><i class="fa fa-search"></i></button>
                                    <button type="button" id="btn-clear" class="btn btn-sm btn-default"> Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row" style="padding-left: 15px;">
                <button class="btn btn-sm btn-primary btn-add" id="btn-add" href="<?php echo site_url('eaudit/nodin/updateNodin/new'); ?>">
                    <i class="fa fa-envelope"></i> Tambah Nota Dinas
                </button>
            </div>
        </div>
        <div class="box-body">
        <!-- table_nodin is here -->
            <table id="table-nodin" class="display table-striped" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tujuan</th>
                        <th>Jenis</th>
                        <th>PIC</th>
                        <th>Nama PN</th>
                        <th>Nomor ND</th>
                        <th>Tanggal ND</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>

<!-- Modal -->
<div id="modalFile" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width:600px;">
    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">List File</h4>
            </div>
            <div class="modal-body">
                <table id="table_file" class="table table-hover">
                    <!-- <thead>
                        <tr>
                            <th>No</th>
                            <th>File Name</th>
                            <th>Action</th>
                        </tr>
                    </thead> -->
                    <tbody>  
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.date').datepicker({
        orientation: "left",
        format: 'dd/mm/yyyy',
        autoclose: true
    });
    $(document).ready( function() {
        // console.log( "ready!" + "<?php $this->session->userdata() ?>" + "HAI");

        var table_nodin = $('#table-nodin').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            // "order": [], //Initial no order.
            "retrieve": true,

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('index.php/eaudit/nodin/ajax_list')?>",
                "type": "POST",
                "data": function ( data ) {
                    data.NOMOR_ND = $('#CARI_NOMOR_ND').val();
                    data.TANGGAL_ND = $('#CARI_TANGGAL_ND').val();
                    data.JENIS_ND = $('#CARI_JENIS_ND option:selected').val();
                    data.TUJUAN_ND = $('#CARI_TUJUAN_ND').val();
                    data.NAMA = $('#CARI_NAMA').val();
                }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": '_all', //first column / numbering column
                    "orderable": false, //set not orderable
                },
                {
                    "targets": [ 0,1,2,3,4,5,6 ],
                    "className": "text-center",
                },
            ],
            "columns": [
                { "width": "3%" },
                null,
                { "width": "13%" },
                null,
                { "width": "25%" },
                null,
                null,
                { "width": "7%" },
            ]
        });

        $('#table-nodin tbody').on( 'click', '#btnDownloadNodin', function (e) {
            e.preventDefault();
            $('#modalFile').modal('show');
            var id = $(this).data('idlist');
            var url = '<?php echo site_url('eaudit/nodin/getFile')?>/'+ id;
            $.get(url, function(data) {
                $('#table_file tbody tr').remove();
                var data1 = $.parseJSON(data);
                // console.log(data1);
                $.each(data1, function(i, item) {
                    $.each(item, function(i, val) {
                        $tr = $('<tr valign="middle">').append(
                            $('<td>').text(val.nomor),
                            $('<td>').text(val.nama_file),
                            $('<td>').html('<a type="button" class="btn btn-primary btn-sm pull-right" target="_blank" href="<?php echo base_url();?>'+val.link_file+'">Download</a>')
                        );
                        $('#table_file tbody').append($tr);
                    });
                });
            });
            return false;
        });

        $('#table-nodin tbody').on( 'click', '#btnUpdateNodin', function (e) {
            e.preventDefault();
            var id = $(this).data('idlist');
            var url = '<?php echo site_url('eaudit/nodin/updateNodin/edit')?>/'+ id;
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Edit Nota Dinas', html, '', 'large');
            });
            return false;
        });

        $('#table-nodin tbody').on( 'click', '#btnDeleteNodin', function (e) {
            e.preventDefault();
            var id = $(this).data('idlist');
            var nomor_nd = $(this).data('nomorlist');
            var url = '<?php echo site_url('eaudit/nodin/do_updateNodin/nonActive')?>/'+ id;
            var msg = {
                success: 'Data Berhasil Dihapus!',
                error: 'Data Gagal Dihapus!',
            };
            
            alertify.confirm('Delete Nota Dinas', 'Apakah anda yakin menghapus <b>Nomor Nota Dinas: '+ nomor_nd +'</b>?', 
                function() { 
                    $.ajax({
                        url: url,
                        dataType: 'json',
                        success: function (data) {
                            $('#loader_area').hide();
                            CloseModalBox2();
                            table_nodin.ajax.reload( null, false );
                            if (data == '1') {
                                alertify.success(msg.success);
                            } else {
                                alertify.error(msg.error);
                            }
                        }
                    });
                }, 
                function() {
                    alertify.error('Cancel')
                }
            );
        });

        //----------- process filter buttons
        $('#btn-filter').click(function(e) {
            e.preventDefault();
            table_nodin.ajax.reload();
        });

        $('#btn-clear').click(function() {
            $('#ajaxFormCari')[0].reset();
            $("#CARI_TUJUAN_ND").select2("val", "");
            $("#CARI_NOMOR_ND").select2("val", "");
            table_nodin.ajax.reload();
        });

        $('.btn-add').click(function(e) {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function(html) {
                OpenModalBox('Tambah Nota Dinas', html, '', 'large');
            });
            return false;
        });

        $('#dataDownload').click(function() {
            var id = $(this).data('info');
            var url = '<?php echo site_url('eaudit/nodin/getFile')?>/'+ id;
            // $('#loader_area').show();
            $.get(url, function(data) {
                // $('#loader_area').hide();
                // OpenModalBox('Edit Nota Dinas', html, '', 'middle');
            });

        });
        
        $('#CARI_TUJUAN_ND').select2({
            minimumInputLength: 0,
            placeholder: "-- Pilih Tujuan ND --",
            ajax: {
                url: "<?= base_url('index.php/share/reff/getUnitKerjaNodin') ?>/",
                dataType: 'json',
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        q: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?= base_url('index.php/share/reff/getUnitKerjaNodin') ?>/" + id, {
                        dataType: "json"
                    }).done(function(data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }
        });

        $('#CARI_NOMOR_ND').select2({
            minimumInputLength: 0,
            placeholder: "-- Pilih Nomor Nodin --",
            ajax: {
                url: "<?= base_url('index.php/share/reff/getNodin') ?>/",
                dataType: 'json',
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        d: term
                    };
                },
                results: function(data, page) {
                    return {results: data.item};
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax("<?= base_url('index.php/share/reff/getNodin') ?>/" + id, {
                        dataType: "json"
                    }).done(function(data) {
                        callback(data[0]);
                    });
                }
            },
            formatResult: function(state) {
                return state.name;
            },
            formatSelection: function(state) {
                return state.name;
            }
        });


        $('#table-nodin tbody').on( 'click', '#btnStakeholder', function (e) {
            e.preventDefault();
            var id = $(this).data('idlist');
            var nomor_nd = $(this).data('nomorlist');
            var stakeholder = $(this).data('isstakeholder');
            var url = '<?php echo site_url('eaudit/nodin/update_stakeholder/')?>/'+ id + '/' + stakeholder;
            var msg = {
                success: 'Data Berhasil Diperbarui!',
                error: 'Data Gagal Diperbarui!',
            };
            var info_stakeholder = 'Apakah anda yakin ' + ($(this).data('isstakeholder') == 1 ? 'menghapus' : 'menambahkan') + ' <b>Nomor Nota Dinas: '+ nomor_nd +'</b> sebagai stakeholder?'
            
            alertify.confirm('Stakeholder', info_stakeholder, 
                function() { 
                    $.ajax({
                        url: url,
                        dataType: 'json',
                        success: function (data) {
                            $('#loader_area').hide();
                            CloseModalBox2();
                            table_nodin.ajax.reload( null, false );
                            if (data == '1') {
                                alertify.success(msg.success);
                            } else {
                                alertify.error(msg.error);
                            }
                        }
                    });
                }, 
                function() {
                    alertify.error('Cancel')
                }
            );
        });

    });

</script>
