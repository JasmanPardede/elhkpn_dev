<nav id="menu-nav" class="navbar navbar-default">
    <div class="container-fluid">
        <div class="row" id="main-menu">
            <div id="wrapper-menu" class="col-lg-12">
                <ul>
                    <li><a href="<?php echo base_url(); ?>portal/filing">E-Filling</a></li>
                    <li><a href="<?php echo base_url(); ?>portal/mailbox" class="active">Mailbox</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<section id="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div id="wrapper-main">
                <div class="col-lg-2">
                    <ul id="sidebar" class="nav nav-pills affix">
                        <li  class="active"><a href="<?php echo base_url(); ?>portal/mailbox">Inbox</a></li>
                        <li><a href="<?php echo base_url(); ?>portal/mailbox/outbox">Outbox</a></li>
                    </ul>
                </div>
                <div class="col-lg-10" >
                    <div class="row" >
                        <div class="form-mailbox" >
                            <div class="col-lg-10" style="width:100%; margin-bottom:5px; overflow:hidden;">
                                <!--<a href="javascript:void(0)" id="add" class="btn btn-info btn-sm">
                                    <i class="fa fa-plus"></i> Buat Pesan Baru
                                </a>-->
                                <form id="frmHapusPesan" method="POST" style="margin-right: 5px; paddoing:0; float:left;" action="<?php echo base_url(); ?>portal/mailbox/delete_multi">
                                    <input type="hidden" name="ID_DELETE" id="DELETE"/>
                                    <button onclick="" type="submit" id="delete_all" class="btn btn-warning btn-sm" style="display:none;">
                                        <i class="fa fa-trash"></i> Hapus Pesan Yang Dipilih
                                    </button>
                                </form>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-10" style="width:100%; margin-bottom:10px;">
                                <?php $error_message = $this->session->flashdata('error_message'); ?>
                                <?php if ($error_message): ?>
                                    <div class="alert alert-error">
                                        <i class="fa fa-warning"></i> 
                                        <?php echo $this->session->flashdata('message'); ?>
                                    </div>
                                <?php endif ?>
                                <?php $success_message = $this->session->flashdata('success_message'); ?>
                                <?php if ($success_message): ?>
                                    <div class="alert alert-success">
                                        <i class="fa fa-check"></i> 
                                        <?php echo $this->session->flashdata('message'); ?>
                                    </div>
                                <?php endif; ?>       
                            </div>
                            <div class="col-lg-10" style="width:100%;">
                                <div class="box box-warning">
                                    <div class="box-body">
                                        <p style="font-size: 15px;">
                                            Untuk melakukan cetak dokumen LHKPN berupa Surat Kuasa, Lembar Pengumuman, Tanda Terima, Ringkasan Harta dan Lampiran Kekurangan klik pada tombol-tombol di kolom Aksi yang terdapat pada tabel Riwayat LHKPN.
                                            <br> 
                                            <table width="30%">
                                                <tr>
                                                    <td width="7%" style="padding: 2px 0 2px;"><a class="btn btn-xs" style="background-color: green;color: white;border-radius: 4px" disabled><i class="fa fa-file"></i></a></td>
                                                    <td>: Cetak Surat Kuasa PN</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 2px 0 2px;"><a class="btn btn-xs" style="background-color: #cccc00;color: black;border-radius: 4px" disabled><i class="fa fa-bullhorn"></i></a></td>
                                                    <td>: Cetak Lembar Pengumuman</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 2px 0 2px;"><a class="btn btn-xs" style="background-color: #c0c0c0;color: black;border-radius: 4px" disabled><i class="glyphicon glyphicon-download-alt"></i></a></td>
                                                    <td>: Cetak Tanda Terima</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 2px 0 2px;"><a class="btn btn-xs" style="background-color: #0d47a1;color: white;border-radius: 4px" disabled><i class="fa fa-print"></i></a></td>
                                                    <td>: Cetak Ringkasan Harta</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 2px 0 2px;"><a class="btn btn-xs" style="background-color: #CC0000;color: white;border-radius: 4px" disabled><i class="glyphicon glyphicon-exclamation-sign"></i></a></td>
                                                    <td>: Cetak Lampiran Kekurangan</td>
                                                </tr>
                                            </table>
                                            Untuk lebih detailnya, silahkan download Panduan Cetak Dokumen LHKPN berikut <a title='File Panduan' href='javascript:void(0)' class='btn btn-sm btn-success' onclick="btnPanduanOnClick(this);"><i class='fa fa-print'></i></a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-10" style="width:100%;">
                                <div class="box box-primary">
                                    <div class="box-body">
                                        <table id="Tabel" style="width:100%;" class="table table-bordered  table-heading no-border-bottom">
                                            <thead>
                                                <tr>
                                                    <th style="text-align:left;"><input name="select_all" value="1" type="checkbox"></th>
                                                    <th style="text-align:left;">No.</th>
                                                    <th style="text-align:left;">Pengirim Pesan</th>
                                                    <th style="text-align:left;">Subjek</th>
                                                    <!-- <th style="text-align:left;">File</th> -->
                                                    <th style="text-align:left;">Tanggal Kirim</th>
                                                    <th style="text-align:left;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">

    function updateDataTableSelectAllCtrl(table) {
        var $table = table.api().table().node();
        var $chkbox_all = $('tbody input[type="checkbox"]', $table);
        var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);
        var chkbox_select_all = $('thead input[name="select_all"]', $table).get(0);

        // If none of the checkboxes are checked
        if ($chkbox_checked.length === 0) {
            chkbox_select_all.checked = false;
            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = false;
            }

            // If all of the checkboxes are checked
        } else if ($chkbox_checked.length === $chkbox_all.length) {
            chkbox_select_all.checked = true;
            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = false;
            }

            // If some of the checkboxes are checked
        } else {
            chkbox_select_all.checked = true;
            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = true;
            }
        }
    }

    $(document).ready(function () {
        var pKey = "-";
        $("#delete_all").click(function (e) {
            e.preventDefault();

            confirm('Anda yakin akan menghapus pesan ?', function () {
                $("#frmHapusPesan").submit();
            });

            return false;
        });


        var rows_selected = [];
        oTable = $('#Tabel').dataTable({
            "oLanguage": {
                "sSearch": "Pencarian ",
                "sProcessing": "Sedang Memuat Data...",
                "sLoadingRecords": "Sedang Memuat Data...",
                "sZeroRecords": "Data Kosong",
                "sEmptyTable": "Data Kosong",
                "sInfoEmpty": "Data Kosong",
                "oPaginate": {
                    "sNext": "Selanjutnya",
                    "sPrevious": "Sebelumnya"
                }
            },
            'bJQueryUI': false,
            'sPaginationType': 'full_numbers',
            'bServerSide': true,
            'bAutoWidth': false,
            'bProcessing': true,
            'bLengthChange': true,
            'bSort': false,
            'sAjaxSource': '<?php echo base_url(); ?>portal/mailbox/json_inbox',
            'aoColumns': [null, null, null, null, null, null],
            'fnServerData': function (sSource, aoData, fnCallback)
            {
                $.ajax
                        ({
                            'dataType': 'json',
                            'type': 'POST',
                            'url': sSource,
                            'data': aoData,
                            'success': fnCallback
                        });
            },
            "fnRowCallback": function (row, data, dataIndex) {
                var rowId = $(this).closest('tr');
                var value = $(row).find('input[type="checkbox"]').val();

                // If row ID is in the list of selected row IDs
                $(row).addClass('sent');
                if ($.inArray(value, rows_selected) !== -1) {
                    $(row).find('input[type="checkbox"]').prop('checked', true);
                    $(row).addClass('selected');
                }


            },

        });


        // Handle click on checkbox
        $('#Tabel tbody').on('click', 'input[type="checkbox"]', function (e) {
            var $row = $(this).closest('tr');

            // Get row data
            var data = oTable.api().row($row).data();

            // Get row ID
            var rowId = $(this).val();

            // Determine whether row ID is in the list of selected row IDs 
            var index = $.inArray(rowId, rows_selected);

            // If checkbox is checked and row ID is not in list of selected row IDs
            if (this.checked && index === -1) {
                rows_selected.push(rowId);
                $('#DELETE').val(rows_selected);
                // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
            } else if (!this.checked && index !== -1) {
                rows_selected.splice(index, 1);
                $('#DELETE').val(rows_selected);
            }

            if (this.checked) {
                $row.removeClass('sent');
                $row.addClass('selected');
                $('#delete_all').show();
            } else {
                $row.removeClass('selected');
                $row.addClass('sent');
                if (rows_selected.length == 0) {
                    $('#delete_all').hide();
                }
            }

            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(oTable);

            // Prevent click event from propagating to parent
            e.stopPropagation();

        });

        // Handle click on table cells with checkboxes
        $('#Tabel').on('click', 'tbody td, thead th:first-child', function (e) {
            $(this).parent().find('input[type="checkbox"]').trigger('click');
        });

        // Handle click on "Select all" control
        $('thead input[name="select_all"]', oTable.api().table().container()).on('click', function (e) {
            if (this.checked) {
                $('#Tabel tbody input[type="checkbox"]:not(:checked)').trigger('click');
                $('#delete_all').show();
            } else {
                $('#Tabel tbody input[type="checkbox"]:checked').trigger('click');
                $('#delete_all').hide();
            }

            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // Handle table draw event
        oTable.api().on('draw', function () {
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(oTable);
        });

        $('#ID_USER').select2({
            placeholder: "Pilih User",
            allowClear: true,
            multiple: true,
            ajax: {
                url: '<?php echo base_url(); ?>portal/mailbox/user',
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

        $("#lampiran").fileinput({
            overwriteInitial: true,
        });

        // var o = CKEDITOR.instances['txtPesan'];
        // if (o)
        //     o.destroy();
        // CKEDITOR.replace('txtPesan');

        $('#add').click(function () {
            $("#ID_USER").select2('data', null);
            $('#myModal').modal('show');
        });

        $("#Tabel tbody").on('click', '.edit-action', function (event) {
            var id = $(this).attr('id');
            var pKey = id;
            $.post("<?php echo base_url(); ?>portal/mailbox/show_outbox/", {id: id}).done(function (data) {
                if(data=='alert_security'){
                    console.log('anda tidak memiliki akses terhadap data!!!');
                    return;
                }

                var result = eval(data);
                

                $('#lbl_penerima').text(result[0].NAMA);
                $('#lbl_tgl_kirim').text(result[0].TANGGAL_KIRIM);
                $('#lbl_subject').text(result[0].SUBJEK);
                // if (result[0].FILE) {
                //     $('#info-lampiran').html('<a href="<?php echo base_url(); ?>uploads/mail_out/' + result[0].ID_PENGIRIM + '/' + result[0].FILE + '" target="_blank"><i class="fa fa-file"></i></a>');
                // } else {
                //     $('#info-lampiran').html('<b>Tidak ada lampiran</b>');
                // }
                $('#txtPesan21').html(result[0].ID);
                $('#txtPesan21z').html(result[0].ID);

                var arrlsk = result[0].SUBJEK.split(' ');

                if (arrlsk.length > 2) {
                    var txt = (arrlsk[0] + arrlsk[1] + arrlsk[2]).toLowerCase(), show_print_btn = true;
                    // show_print_btn = txt == 'lampiransuratkuasa' ? false : true;
                    show_print_btn = txt == 'lembarpenyerahandan' ? true : false;
                }


                $("#cetak_final").attr("xpesa", result[0].ID);
                if (show_print_btn) {
                    $("#cetak_final").show();
                } else {
                    $("#cetak_final").hide();
                }

                document.getElementById('txtPesan212').value = result[0].ID;
                $('#txtPesan2').html(result[0].PESAN);
                $('#myModal2').modal('show');
                
                /**
                 * ini kalo mau melakukan kontrol table yang ada di dalam modal preview
                 */
//                $("#myModal2").find("table").attr("align", "center");
            });

        });

        $("#Tabel tbody").on('click', '.delete-action', function (event) {
            var id = $(this).attr('id');
            confirm("Apakah anda yakin akan menghapus pesan ini ? ", function () {
                window.location.href = "<?php echo base_url(); ?>portal/mailbox/delete/" + id;
            });
        });

        $("#Tabel tbody").on('click', '.cetak-action', function (event) {
            var id = $(this).attr('id');
            var idlhkpn = $(this).attr('idlhkpn');
            var idkel = $(this).attr('idkel');
            var type = $(this).attr('cetak');
            if (type == 'ikhtisar') {
                window.open('<?php echo base_url(); ?>portal/ikthisar/cetak/' + idlhkpn,'_blank');
            } else if (type == 'pengumuman') {
                window.open('<?php echo base_url(); ?>portal/filing/BeforeAnnoun/' + idlhkpn,'_blank');
            } else if (type == 'verifikasi0') {
                var url = '<?php echo base_url(); ?>ever/verification/previewmsg';
                $("<form action='" + url + "' method='post' target='_blank'></form>")
                    .append($("<input type='hidden' name='id_lhkpn' />").attr('value', idlhkpn))
                    .append($("<input type='hidden' name='verif' />").attr('value', 'kekurangan'))
                    .appendTo('body')
                    .submit()
                    .remove();
                return false;
            } else if (type == 'verifikasi1') {
                window.open('<?php echo base_url(); ?>ever/verification/preview_tandaterima/' + idlhkpn + '/0','_blank')
            } else if (type == 'skm') {
                window.open('<?php echo base_url(); ?>portal/review_harta/surat_kuasa_pdf/' + idlhkpn + '/' + '1','_blank')
            } else if (type == 'sk') {
                if (idkel == 'MQ~~') {
                    window.open('<?php echo base_url(); ?>portal/review_harta/surat_kuasa_pdf2/' + atob(idlhkpn) + '/1/1/1','_blank')
                } else {
                    window.open('<?php echo base_url(); ?>portal/review_harta/cetak_surat_kuasa_individual/' + idkel + '/' + idlhkpn + '/1','_blank')
                }
            } else {
                return false;
            }
        });


        $('#Tabel_length').html('<h4>DAFTAR PESAN MASUK</h4>');

    });

    btnPanduanOnClick = function (self) {
        window.open('<?php echo base_url(); ?>file/template/Panduan Cetak Dokumen LHKPN.pdf','_blank')
    };
</script>
<!--<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" method="POST" action="<?php echo base_url(); ?>portal/mailbox/send" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">KIRIM PESAN</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Penerima</label><br>  
                        <input type="hidden" id="ID_USER" name="ID_PENERIMA[]" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" name="subject" id="subject" placeholder="Subject"  class="form-control "/>
                    </div> 
                    <div class="form-group">
                        <label>Lampiran (Format File : jpg,png,jpeg,pdf,docx)</label>
                        <input type="file" class="file-loading" name="filename" id="lampiran" data-allowed-file-extensions='["jpg", "png","jpeg","pdf","docx"]' class="form-control" data-show-preview="false">
                    </div>
                    <div class="form-group">
                        <label>Isi Pesan</label>
                        <textarea  id="txtPesan" name="txtPesan"></textarea>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn-submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-envelope-o"></i> Kirim Pesan
                    </button>
                    <button type="button" id="btn-cancel" class="btn btn-danger btn-sm" data-dismiss="modal">
                        <i class="fa fa-close"></i> Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>-->

<div id="myModal2" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" action="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">DETAIL PESAN
                        <input type="hidden" name="txtID" id="txtPesan212" />
                    </h4>
                    <a target="_blank" onclick="actionCetakBukti(this);" id="cetak_final" class="btn btn-success btn-sm pull-left"  ><h5 class="modal-title" >Print Email </h5></a>

                </div>

                <div class="modal-body">

                    <div class="row ">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <br>
                                <div class="row">
                                    <div class="col-lg-12" id="txtPesan2">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
            </form>
        </div>
    </div>
</div>
