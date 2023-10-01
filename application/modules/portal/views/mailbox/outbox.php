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
                        <li><a href="<?php echo base_url();?>portal/mailbox">Inbox</a></li>
                        <li class="active"><a href="<?php echo base_url();?>portal/mailbox/outbox">Outbox</a></li>
                    </ul>
                </div>
                <div class="col-lg-10" >
                    <div class="row" >
                        <div class="form-mailbox" >
                           <div class="col-lg-10" style="width:100%; margin-bottom:5px; overflow:hidden;">
                                <form id="frmHapusPesan" method="POST" style="margin-right: 5px; paddoing:0; float:left;" action="<?php echo base_url();?>portal/mailbox/delete_multi_outbox">
                                   <input type="hidden" name="ID_DELETE" id="DELETE"/>
                                   <button onclick="" type="submit" id="delete_all" class="btn btn-warning btn-sm" style="display:none;">
                                       <i class="fa fa-trash"></i> Hapus Pesan Yang Dipilih
                                   </button>
                                </form>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-10" style="width:100%; margin-bottom:10px;">
                              <?php $error_message = $this->session->flashdata('error_message');?>
                              <?php if ($error_message):?>
                              <div class="alert alert-error">
                                 <i class="fa fa-warning"></i> 
                                 <?php echo $this->session->flashdata('message'); ?>
                              </div>
                              <?php endif?>
                              <?php $success_message = $this->session->flashdata('success_message');?>
                              <?php if ($success_message):?>
                               <div class="alert alert-success">
                                 <i class="fa fa-check"></i> 
                                <?php echo $this->session->flashdata('message'); ?>
                              </div>
                              <?php endif; ?>       
                            </div>
                            <div class="col-lg-10" style="width:100%;">
                                <div class="box box-primary">
                                    <div class="box-body">
                                         <table id="Tabel" style="width:100%;" class="table table-bordered  table-heading no-border-bottom">
                                            <thead>
                                                <tr>
                                                    <th style="text-align:left;"><input name="select_all" value="1" type="checkbox"></th>
                                                    <th style="text-align:left;">No.</th>
                                                    <th style="text-align:left;">Penerima Pesan</th>
                                                    <th style="text-align:left;">Subjek</th>
                                                    <th style="text-align:left;">File</th>
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

    function updateDataTableSelectAllCtrl(table){
       var $table             = table.api().table().node();
       var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
       var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
       var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

       // If none of the checkboxes are checked
       if($chkbox_checked.length === 0){
          chkbox_select_all.checked = false;
          if('indeterminate' in chkbox_select_all){
             chkbox_select_all.indeterminate = false;
          }

       // If all of the checkboxes are checked
       } else if ($chkbox_checked.length === $chkbox_all.length){
          chkbox_select_all.checked = true;
          if('indeterminate' in chkbox_select_all){
             chkbox_select_all.indeterminate = false;
          }

       // If some of the checkboxes are checked
       } else {
          chkbox_select_all.checked = true;
          if('indeterminate' in chkbox_select_all){
             chkbox_select_all.indeterminate = true;
          }
       }
    }


    $(document).ready(function(){

        $("#delete_all").click(function(e){
            e.preventDefault();
            
            confirm('Anda yakin akan menghapus pesan ?', function(){
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
            'bJQueryUI'      : false,
            'sPaginationType': 'full_numbers',
            'bServerSide'    : true,
            'bAutoWidth'     : false,
            'bProcessing'    : true,
            'bLengthChange'  : true,
            'bSort'          : false,
            'sAjaxSource'    : '<?php echo base_url();?>portal/mailbox/json_outbox',
            'aoColumns'      : [null,null,null,null,null,null,null],
            'fnServerData': function(sSource, aoData, fnCallback)
            {
                 $.ajax
                     ({
                        'dataType': 'json',
                        'type'    : 'POST',
                        'url'     : sSource,
                        'data'    : aoData,
                        'success' : fnCallback
                     });
            },
            "fnRowCallback": function (row, data, dataIndex) {
                var rowId = $(this).closest('tr');
                var value = $(row).find('input[type="checkbox"]').val();
               
                // If row ID is in the list of selected row IDs
                 $(row).addClass('sent');
                 if($.inArray(value, rows_selected) !== -1){
                    $(row).find('input[type="checkbox"]').prop('checked', true);
                    $(row).addClass('selected');
                 }
                

            },

        });
        

        // Handle click on checkbox
           $('#Tabel tbody').on('click', 'input[type="checkbox"]', function(e){
              var $row = $(this).closest('tr');

              // Get row data
              var data = oTable.api().row($row).data();

              // Get row ID
              var rowId = $(this).val();

              // Determine whether row ID is in the list of selected row IDs 
              var index = $.inArray(rowId, rows_selected);

              // If checkbox is checked and row ID is not in list of selected row IDs
              if(this.checked && index === -1){
                 rows_selected.push(rowId);
                 $('#DELETE').val(rows_selected);
              // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
              } else if (!this.checked && index !== -1){
                 rows_selected.splice(index, 1);
                 $('#DELETE').val(rows_selected);
              }

              if(this.checked){
                 $row.removeClass('sent');
                 $row.addClass('selected');
                 $('#delete_all').show();
              } else {
                 $row.removeClass('selected');
                 $row.addClass('sent');
                 if(rows_selected.length==0){
                    $('#delete_all').hide();
                 }
              }

              // Update state of "Select all" control
              updateDataTableSelectAllCtrl(oTable);

              // Prevent click event from propagating to parent
              e.stopPropagation();
             
           });

           // Handle click on table cells with checkboxes
           $('#Tabel').on('click', 'tbody td, thead th:first-child', function(e){
              $(this).parent().find('input[type="checkbox"]').trigger('click');
           });

           // Handle click on "Select all" control
           $('thead input[name="select_all"]', oTable.api().table().container()).on('click', function(e){
              if(this.checked){
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
           oTable.api().on('draw', function(){
              // Update state of "Select all" control
              updateDataTableSelectAllCtrl(oTable);
           });

        

        $("#Tabel tbody").on('click','.edit-action',function(event){
            var id = $(this).attr('id');
            $.post( "<?php echo base_url();?>portal/mailbox/show_outbox", { id: id }).done(function( data ) {
                var result = eval(data);
                $('#lbl_penerima').text(result[0].NAMA);
                $('#lbl_tgl_kirim').text(result[0].TANGGAL_KIRIM);
                $('#lbl_subject').text(result[0].SUBJEK);
                if(result[0].FILE){
                    $('#info-lampiran').html('<a href="<?php echo base_url();?>uploads/mail_out/'+result[0].ID_PENGIRIM+'/'+result[0].FILE+'" target="_blank"><i class="fa fa-file"></i></a>');
                }else{
                     $('#info-lampiran').html('<b>Tidak ada lampiran</b>');
                }
               
                $('#txtPesan2').html(result[0].PESAN);
                $('#myModal2').modal('show');
            });      
           
        });

        $("#Tabel tbody").on('click','.delete-action',function(event){
            var id = $(this).attr('id');
            confirm("Apakah anda yakin akan menghapus pesan ini ? ", function(){
                window.location.href = "<?php echo base_url();?>portal/mailbox/delete_oubox/"+id;
            });
        });

        $('#Tabel_length').html('<h4>DAFTAR PESAN KELUAR</h4>');


    });
</script>


<div id="myModal2" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">DETAIL PESAN</h4>
                </div>
                <div class="modal-body">
                    <div class="row ">
                        <div class="col-lg-12">
                            <div class="form-group">
<!--                                <div class="row">
                                    <div class="col-lg-3"><strong>Penerima</strong></div>
                                    <div class="col-lg-1">:</div>
                                    <div class="col-lg-8"><label id="lbl_penerima"></label></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3"><strong>Tanggal Kirim</strong></div>
                                    <div class="col-lg-1">:</div>
                                    <div class="col-lg-8"><label id="lbl_tgl_kirim"></label></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3"><strong>Subject</strong></div>
                                    <div class="col-lg-1">:</div>
                                    <div class="col-lg-8"><label id="lbl_subject"></label></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3"><strong>Lampiran</strong></div>
                                    <div class="col-lg-1">:</div>
                                    <div class="col-lg-8" id="info-lampiran"></div>
                                </div>
                                <div class="row">
                                     <div class="col-lg-3"><strong>Isi Pesan</strong></div> 
                                    <div class="col-lg-1">:</div>
                                    <div class="col-lg-8"></div>
                                </div>-->
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
