<table id="TableKas"  class="table table-striped table-bordered table-hover table-heading no-border-bottom table-filing">
    <thead>
        <tr>
            <th>NO</th>
            <th>STATUS</th>
            <th>URAIAN</th>
            <th>INFO REKENING</th>
            <th>ASAL USUL HARTA</th>
            <th>NILAI SALDO</th>
            <th style="width:100px;">AKSI</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<div style="overflow:hidden;">
    <div class="pull-right">
    <a href="javascript:void(0)" onclick="pindah_tab('#surat_berharga','surat_berharga')" class="btn btn-warning btn-sm" style="margin-left:5px;">
      <i class="fa fa-backward"></i>  Sebelumnya
    </a>
    <a href="javascript:void(0)" onclick="pindah_tab('#lain','lain')" class="btn btn-warning btn-sm" style="margin-left:5px;">
       Selanjutnya <i class="fa fa-forward"></i>  
   </a>
  </div>
</div>


<!-- alert Penetapan -->
<div class="modal fade" id="alertPenetapanKas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Pemberitahuan</h5>
        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>-->
      </div>
      <div class="modal-body">
        <div class="alert alert-danger">
            <i class="fa fa-warning" aria-hidden"true"=""></i>
            <span id="notif-text">
                Anda tidak dapat merubah status harta dengan status <b>"Tetap"</b> dikarenakan masih ditemukan data yang belum dilengkapi.<br>
                Silakan melengkapi data di isian harta dengan klik tombol <a title='Edit' id="" href='javascript:void(0)' class="btn btn-success  edit-action-alert"><i class="fa fa-pencil"></i></a>
<!--<a id='" . $ID . "'  href='javascript:void(0)' class='btn btn-success btn-sm edit-action' title='Edit'><i class='fa fa-pencil'></i></a>-->
            </span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-remove"></i> Tutup</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
    	///OPTION PELEPASAN HARTA
        var id_pelepasan_harta = ['5','7','10','14'];
        var option_jenis_pelepasan_harta = document.getElementById('JENIS_PELEPASAN_HARTA');
        option_jenis_pelepasan_harta.length = 0;
        var no_pelepasan = 1;
        if(state_jenis_pelepasan_harta){
            for (var i = 0;i <= state_jenis_pelepasan_harta.length - 1;i++){
                if (id_pelepasan_harta.indexOf(state_jenis_pelepasan_harta[i].ID) > -1 ) {
                    option_jenis_pelepasan_harta[no_pelepasan] = new Option(state_jenis_pelepasan_harta[i].JENIS_PELEPASAN_HARTA,state_jenis_pelepasan_harta[i].ID,false,false);
                    no_pelepasan++;
                }
            }
        }


      var copy = false;
        if(IS_COPY=='1'){
            copy = true;
        }
    
      

       dtKasSetaraKas = $('#TableKas').dataTable({
            "oLanguage": ecDtLang,
            'sPaginationType': 'full_numbers',
            'bServerSide': true,
            'bProcessing': true,
            'sAjaxSource': '<?php echo base_url(); ?>portal/data_harta/grid_kas/'+ID_LHKPN,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": false,
//            "bInfo": false,
            "bAutoWidth": false,
            'aoColumns': [null,null,null,null,null,null,null],
            'fnServerData': function (sSource, aoData, fnCallback){
                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            },
//           "aoColumnDefs": [{ "bVisible": copy, "aTargets": [1] }],
            "fnRowCallback": function (row, data, dataIndex) {
                var rowId = $(this).closest('tr');
                var value = $(row).find('label.pelepasan').text();
                if(value=='Lepas'){
                    $(row).css({'background-color':'#808080','color':'#fff'});
                }else{
                   var ID_HARTA_TABLE = $(row).find('.ID_HARTA_TABLE').val();
                   var STATUS_TABLE = $(row).find('.STATUS_TABLE').val();
                   if(STATUS_TABLE=='3' && ID_HARTA_TABLE !=''){
                       $(row).find('label.label').text('Lama');
                       $(row).find('label.label').css({'background-color':'#FFA500','color':'#fff'});
                   }
                }
            },
        });
        
        // DELETE ACTION
        $("#TableKas tbody").on('click','.delete-action',function(event){
             var id = $(this).attr('id');
             confirm("Apakah anda yakin akan menghapus data ? ", function(){
                 do_delete('portal/data_harta/delete/'+id+'/t_lhkpn_harta_kas','Data Harta Kas/Setara Kas berhasil dihapus ');
                 $('#TableKas').DataTable().ajax.reload();
             });
        });

        // PENETAPAN ACTION
        $("#TableKas tbody").on('click','.penetapan-action',function(event){
             var id = $(this).attr('id');
             confirm("Apakah anda yakin akan melakukan penetapan harta ini ? ", function(){
                 $.ajax({
                    url: base_url + 'portal/data_harta/edit_kas/' + id,
                    async: false,
                    dataType: 'JSON',
                    success: function (data) {
                        var rs = eval(data);
                        if (rs.norek == 0){
                            notif('Data Harta Kas gagal dimutakhirkan dengan status TETAP, dikarenakan nomor rekening 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                        }else if (rs.result.NILAI_EQUIVALEN == 0){
                            notif('Data Harta Kas gagal dimutakhirkan dengan status TETAP, dikarenakan nilai saldo 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                        }else if(rs.result.TAHUN_BUKA_REKENING == null || rs.result.TAHUN_BUKA_REKENING == '' || rs.result.TAHUN_BUKA_REKENING == 0){
                            $(".edit-action-alert").attr("id",id);
                            $('#alertPenetapanKas').modal('toggle');
                        } else if (rs.result.ATAS_NAMA_REKENING == null || rs.result.ATAS_NAMA_REKENING == '') {
                            notif('Data Harta Kas gagal dimutakhirkan dengan status TETAP, dikarenakan Atas Nama bernilai kosong atau null, silakan direview kembali');
                        }else if((rs.result.ATAS_NAMA_REKENING.search(2)!="-1") && (rs.result.PASANGAN_ANAK == null || rs.result.PASANGAN_ANAK == '')){
                            $(".edit-action-alert").attr("id", id);
                            $('#alertPenetapanKas').modal('toggle');
                        } else if (rs.result.ATAS_NAMA_REKENING.search(3) != "-1" && (rs.result.ATAS_NAMA_LAINNYA == null || rs.result.ATAS_NAMA_LAINNYA == '' || rs.result.KETERANGAN == null || rs.result.KETERANGAN == '')) {
                            notif('Data Harta Kas gagal dimutakhirkan dengan status TETAP, dikarenakan Nama Orang Lain / Lainnya bernilai kosong atau null, silakan direview kembali');
                        }else{
                              do_delete('portal/data_harta/penetapan/'+id+'/t_lhkpn_harta_kas','Data Kas Sudah Tetap ');
                              $('#TableKas').DataTable().ajax.reload();  
                        }
                    }
                });
             });
        });

          // PELEPASAN ACTION
        $("#TableKas tbody").on('click','.pelepasan-action',function(event){
             var id = $(this).attr('id');
             $('#FORM_PELEPASAN').bootstrapValidator('resetForm', true);
             $('#FORM_PELEPASAN #JENIS_PELEPASAN_HARTA').select2();
             $('#FORM_PELEPASAN #MAIN_TABLE').val('t_lhkpn_harta_kas');
             $('#FORM_PELEPASAN #TABLE').val('t_lhkpn_pelepasan_harta_kas');
             $('#FORM_PELEPASAN #ID_HARTA').val(id);
             $('#FORM_PELEPASAN #TABLE_GRID').val('#TableKas');
             $('#FORM_PELEPASAN #NOTIF').val('Data Harta Kas/Setara Kas berhasil dilepas.');
             // KONDISI NILAI
             var data_nilai = $(this).data('nilai');
             var nilai = data_nilai[0];
             $('#FORM_PELEPASAN #NILAI_PELEPASAN').mask('000.000.000.000.000', {reverse: true});
             $('#FORM_PELEPASAN #NILAI_PELEPASAN').val(nilai).trigger('keyup');
             $('#FORM_PELEPASAN #NILAI_PELEPASAN').trigger('mask.maskMoney');
             // END KONDISI NILAI
             showLhkpnModal('#ModalPelpasan', {
                backdrop: 'static',
                keyboard: false,
                show: true
            }, function(){
                $('#ModalPelpasan .group-0').attr('style', ''); 
            });
//             $('#ModalPelpasan').modal({
//                backdrop: 'static',
//                keyboard: false,
//                show:true
//             });

        });

        // EDIT ACTION DOCUMENT
        $("#TableKas tbody").on('click','.edit-action-document',function(event){
            var id = $(this).attr('id');
            var secretname = $(this).attr('secretname');
            var link = $(this).attr('link');
            var ext = $(this).attr('ext');
            CallForm('document');
            $("#img_file").attr("src", link);
            $("#btn-download-minio").attr("href", link);
            $("#checkext").val(ext);
            $("#secretname").val(secretname);
            $("#id_file").val(id);
            $("#tipeharta").val('kas');
        });

        // EDIT ACTION
        $("#TableKas tbody").on('click','.edit-action',function(event){
            var id = $(this).attr('id');
            $.ajax({
                 url: base_url +'portal/data_harta/edit_kas/'+id,
                 async: false,
                 dataType: 'JSON',
                 success:function(data){
                    var rs = eval(data);

                    if(rs.result=='alert_security'){
                         notif('Anda tidak memiliki akses pada data ini');
                         return;
                    }

                    CallForm('harta_kas');
                    if (rs.result.ATAS_NAMA_REKENING == 'LAINNYA') {
                            $('#ket_lainnya_an_div').show();
                            $('#KET_LAINNYA_AN').val(rs.result.KETERANGAN);
                        } else {
                            $('#ket_lainnya_an_div').hide();
                        }
                    $('select').select2();
//                     var NILAI_KURS = parseInt(rs.result.NILAI_EQUIVALEN/rs.result.NILAI_SALDO);
                    $('#status_harta').val(rs.result.STATUS);
                    $('#is_load_harta').val(rs.result.IS_LOAD);
                    $('#is_pelepasan_harta').val(rs.result.IS_PELEPASAN);
                    $('#ID').val(rs.result.REAL_ID);
                    
                    // $('#NILAI_KURS').val(rs.result.NILAI_KURS);
                    $('#NILAI_KURS').val(parseFloat(rs.result.NILAI_KURS).toFixed()).trigger('keyup');
                    $('#NILAI_SALDO').val(parseFloat(rs.result.NILAI_SALDO).toFixed()).trigger('keyup');
                    $('#NILAI_EQUIVALEN').val(parseFloat(rs.result.NILAI_EQUIVALEN)).trigger('keyup');
                   /* $('#NILAI_SALDO,#NILAI_EQUIVALEN,#NILAI_KURS').trigger('mask.maskMoney');*/
                    // $('#NAMA_BANK').val(rs.result.NAMA_BANK);
                    $('#NAMA_BANK').val(rs.nama_bank);
                    // $('#NOMOR_REKENING').val(rs.result.NOMOR_REKENING);
                    $('#NOMOR_REKENING').val(rs.norek);
                    $('#ATAS_NAMA_REKENING').select2('val',rs.result.ATAS_NAMA_REKENING);
                    
                    $('#ID_HARTA').val(rs.result.ID_HARTA);
                    $('#TAHUN_BUKA_REKENING').val(rs.result.TAHUN_BUKA_REKENING);
//                    $('#KETERANGAN').val(rs.result.KETERANGAN);
                    if(rs.result.ATAS_NAMA_REKENING != null){
                        if (rs.result.ATAS_NAMA_REKENING.indexOf("1") >= 0) {
                            $('#ATAS_NAMA_CHECK_PN').prop('checked', true);
                        }
                        if (rs.result.ATAS_NAMA_REKENING.indexOf("2") >= 0) {
                            $('#ket_pasangan_anak_div').show();
                            $('#ATAS_NAMA_CHECK_PASANGAN').prop('checked', true);
                            var values_pasangan_anak=rs.result.PASANGAN_ANAK;
                            if(values_pasangan_anak!==null){
                                $.each(values_pasangan_anak.split(","), function(i,e){
                                    $('#KET_PASANGAN_ANAK option[value="'+e+'"]').prop('selected', true);
                                }); 
                            }
                        }
                        $('select').select2();  
                        if (rs.result.ATAS_NAMA_REKENING.indexOf("3") >= 0) {
                            $('#ket_lainnya_an_div').show();
                            $('#KET_LAINNYA_AN').val(rs.result.KETERANGAN);
                            $('#ATAS_NAMA_CHECK_LAINNYA').prop('checked', true);
                        }
                     }
                    if (rs.result.KODE_JENIS == 18) {                        
                        $('input#NAMA_BANK').attr({
                            'required': false,
                            'readonly': true,
                            'value': '-'
                        });
                        $('input#NAMA_BANK').val('-');
                        $('input#NOMOR_REKENING').attr({
                            'required': false,
                            'readonly': true,
                            'value': '-'
                        });
                        $('input#NOMOR_REKENING').val('-');
                        $('input#TAHUN_BUKA_REKENING').attr({
                            'required': false,
                            'readonly': true,
                            'value': '-'
                        });
                        $('input#TAHUN_BUKA_REKENING').val('-');
                    }else{
                        $('#NAMA_BANK').attr('requried', true);
                        $('#NOMOR_REKENING').attr('requried', true);
                        $('#NAMA_BANK').attr('readonly', false);
                        $('#NOMOR_REKENING').attr('readonly', false)
                        $('#TAHUN_BUKA_REKENING').attr('requried', true);
                        $('#TAHUN_BUKA_REKENING').attr('readonly', false);
                    }   
                    var file = rs.result.FILE_BUKTI;
                    
                    var fileExist = file !== null ? fileExists(base_url+''+file) : false;
                    if(fileExist){
                      $('#show-download').html('<a target="_blank" href="<?php echo base_url();?>'+file+'"><i class="fa fa-download"></i></a>');
                    }
                      var AL = rs.result.ARR_ASAL_USUL;
                      var A_AL= AL.split(',');
                      for(i=0;i<A_AL.length;i++){
                          var as_val = A_AL[i];
                          $("#table-asal-usul input[value='"+as_val+"'].pilih" ).prop('checked',true);     
                      }
                      var al = rs.asal_usul;
                      for(i=0;i<al.length;i++){
                          var x = new Array();
                          x[0] = dateConvert(al[i].TANGGAL_TRANSAKSI);
                          x[1]  = al[i].NILAI_PELEPASAN;
                          x[2]  = al[i].URAIAN_HARTA;
                          x[3]  = al[i].NAMA;
                          x[4]  = al[i].ALAMAT;
                          if(al[i].ID_ASAL_USUL!=1){
                              var y = al[i].ID_ASAL_USUL;
                              $('input[name="asal_tgl_transaksi['+y+']"]').val(x[0]);
                              $('input[name="asal_besar_nilai['+y+']"]').val(numeral(x[1]).format('0,0').replace(/,/g,'.'));
                              $('input[name="asal_keterangan['+y+']"]').val(x[2]);
                              $('input[name="asal_pihak2_nama['+y+']"]').val(x[3]);
                              $('input[name="asal_pihak2_alamat['+y+']"]').val(x[4]);
                              var id_checkbox = $("#table-asal-usul input[value='"+y+"'].pilih").attr('id');
                              $('#view-' + id_checkbox).html('<a href="javascript:void(0)" id="view-to-'+id_checkbox+'" class="btn btn-view btn-xs btn-info">Lihat</a>');
                              $('#result-' + id_checkbox).html('<label class="label label-primary">'+numeral(x[1]).format('0,0').replace(/,/g,'.')+'</label>');
                          }
                      }
                      $('#KODE_JENIS').select2('val',rs.result.KODE_JENIS);
                	  if (rs.result.MATA_UANG == 1) {
                          if(rs.result.NILAI_KURS == null){
                              console.log('masuk');
                              var NILAI_KURS = parseInt(rs.result.NILAI_EQUIVALEN/rs.result.NILAI_SALDO);
                              $('#NILAI_KURS').val(NILAI_KURS);
                          }
                          $('#NILAI_KURS').attr('readonly', true);
                      } else {
                    	  console.log('tidak');
                    	  $('#NILAI_KURS').attr('readonly', false);
                      }
                      $('#MATA_UANG').select2('val',rs.result.MATA_UANG);

                      
                }
            });
        });


        // EDIT ACTION
        $("#alertPenetapanKas").on('click','.edit-action-alert',function(event){
            $('#alertPenetapanKas').modal('toggle');
            var id = $(this).attr('id');
            $.ajax({
                 url: base_url +'portal/data_harta/edit_kas/'+id,
                 async: false,
                 dataType: 'JSON',
                 success:function(data){
                    var rs = eval(data);

                    if(rs.result=='alert_security'){
                         notif('Anda tidak memiliki akses pada data ini');
                         return;
                    }

                    CallForm('harta_kas');
                    if (rs.result.ATAS_NAMA_REKENING == 'LAINNYA') {
                            $('#ket_lainnya_an_div').show();
                            $('#KET_LAINNYA_AN').val(rs.result.KETERANGAN);
                        } else {
                            $('#ket_lainnya_an_div').hide();
                        }
                     
//                     var NILAI_KURS = parseInt(rs.result.NILAI_EQUIVALEN/rs.result.NILAI_SALDO);
                    $('#status_harta').val(rs.result.STATUS);
                    $('#is_load_harta').val(rs.result.IS_LOAD);
                    $('#is_pelepasan_harta').val(rs.result.IS_PELEPASAN);
                    $('#ID').val(rs.result.REAL_ID);
                    
                    // $('#NILAI_KURS').val(rs.result.NILAI_KURS);
                    $('#NILAI_KURS').val(parseFloat(rs.result.NILAI_KURS).toFixed()).trigger('keyup');
                    $('#NILAI_SALDO').val(parseFloat(rs.result.NILAI_SALDO).toFixed()).trigger('keyup');
                    $('#NILAI_EQUIVALEN').val(parseFloat(rs.result.NILAI_EQUIVALEN)).trigger('keyup');
                   /* $('#NILAI_SALDO,#NILAI_EQUIVALEN,#NILAI_KURS').trigger('mask.maskMoney');*/
                    // $('#NAMA_BANK').val(rs.result.NAMA_BANK);
                    $('#NAMA_BANK').val(rs.nama_bank);
                    // $('#NOMOR_REKENING').val(rs.result.NOMOR_REKENING);
                    $('#NOMOR_REKENING').val(rs.norek);
                    $('#ATAS_NAMA_REKENING').select2('val',rs.result.ATAS_NAMA_REKENING);
                    
                    $('#ID_HARTA').val(rs.result.ID_HARTA);
                    $('#TAHUN_BUKA_REKENING').val(rs.result.TAHUN_BUKA_REKENING);
//                    $('#KETERANGAN').val(rs.result.KETERANGAN);
                    if(rs.result.ATAS_NAMA_REKENING != null){
                        if (rs.result.ATAS_NAMA_REKENING.indexOf("1") >= 0) {
                            $('#ATAS_NAMA_CHECK_PN').prop('checked', true);
                        }
                        if (rs.result.ATAS_NAMA_REKENING.indexOf("2") >= 0) {
                            $('#ket_pasangan_anak_div').show();
                            $('#ATAS_NAMA_CHECK_PASANGAN').prop('checked', true);
                            var values_pasangan_anak=rs.result.PASANGAN_ANAK;
                            if(values_pasangan_anak!==null){
                                $.each(values_pasangan_anak.split(","), function(i,e){
                                    $('#KET_PASANGAN_ANAK option[value="'+e+'"]').prop('selected', true);
                                }); 
                            }
                        }
                        $('select').select2();  
                        if (rs.result.ATAS_NAMA_REKENING.indexOf("3") >= 0) {
                            $('#ket_lainnya_an_div').show();
                            $('#KET_LAINNYA_AN').val(rs.result.KETERANGAN);
                            $('#ATAS_NAMA_CHECK_LAINNYA').prop('checked', true);
                        }
                    }
                    if (rs.result.KODE_JENIS == 18) {                        
                        $('input#NAMA_BANK').attr({
                            'required': false,
                            'readonly': true,
                            'value': '-'
                        });
                        $('input#NAMA_BANK').val('-');
                        $('input#NOMOR_REKENING').attr({
                            'required': false,
                            'readonly': true,
                            'value': '-'
                        });
                        $('input#NOMOR_REKENING').val('-');
                        $('input#TAHUN_BUKA_REKENING').attr({
                            'required': false,
                            'readonly': true,
                            'value': '-'
                        });
                        $('input#TAHUN_BUKA_REKENING').val('-');
                    }else{
                        $('#NAMA_BANK').attr('requried', true);
                        $('#NOMOR_REKENING').attr('requried', true);
                        $('#NAMA_BANK').attr('readonly', false);
                        $('#NOMOR_REKENING').attr('readonly', false)
                        $('#TAHUN_BUKA_REKENING').attr('requried', true);
                        $('#TAHUN_BUKA_REKENING').attr('readonly', false);
                    }   
                    var file = rs.result.FILE_BUKTI;
                    
                    var fileExist = file !== null ? fileExists(base_url+''+file) : false;
                    if(fileExist){
                      $('#show-download').html('<a target="_blank" href="<?php echo base_url();?>'+file+'"><i class="fa fa-download"></i></a>');
                    }
                      var AL = rs.result.ARR_ASAL_USUL;
                      var A_AL= AL.split(',');
                      for(i=0;i<A_AL.length;i++){
                          var as_val = A_AL[i];
                          $("#table-asal-usul input[value='"+as_val+"'].pilih" ).prop('checked',true);     
                      }
                      var al = rs.asal_usul;
                      for(i=0;i<al.length;i++){
                          var x = new Array();
                          x[0] = dateConvert(al[i].TANGGAL_TRANSAKSI);
                          x[1]  = al[i].NILAI_PELEPASAN;
                          x[2]  = al[i].URAIAN_HARTA;
                          x[3]  = al[i].NAMA;
                          x[4]  = al[i].ALAMAT;
                          if(al[i].ID_ASAL_USUL!=1){
                              var y = al[i].ID_ASAL_USUL;
                              $('input[name="asal_tgl_transaksi['+y+']"]').val(x[0]);
                              $('input[name="asal_besar_nilai['+y+']"]').val(numeral(x[1]).format('0,0').replace(/,/g,'.'));
                              $('input[name="asal_keterangan['+y+']"]').val(x[2]);
                              $('input[name="asal_pihak2_nama['+y+']"]').val(x[3]);
                              $('input[name="asal_pihak2_alamat['+y+']"]').val(x[4]);
                              var id_checkbox = $("#table-asal-usul input[value='"+y+"'].pilih").attr('id');
                              $('#view-' + id_checkbox).html('<a href="javascript:void(0)" id="view-to-'+id_checkbox+'" class="btn btn-view btn-xs btn-info">Lihat</a>');
                              $('#result-' + id_checkbox).html('<label class="label label-primary">'+numeral(x[1]).format('0,0').replace(/,/g,'.')+'</label>');
                          }
                      }
                      $('#KODE_JENIS').select2('val',rs.result.KODE_JENIS);
                	  if (rs.result.MATA_UANG == 1) {
                          if(rs.result.NILAI_KURS == null){
                              console.log('masuk');
                              var NILAI_KURS = parseInt(rs.result.NILAI_EQUIVALEN/rs.result.NILAI_SALDO);
                              $('#NILAI_KURS').val(NILAI_KURS);
                          }
                          $('#NILAI_KURS').attr('readonly', true);
                      } else {
                    	  console.log('tidak');
                    	  $('#NILAI_KURS').attr('readonly', false);
                      }
                      $('#MATA_UANG').select2('val',rs.result.MATA_UANG);

                      
                }
            });
        });

        $("body").on('click','.btn-view',function(event){
             var id_btn = $(this).attr('id');
             var id_checkbox = id_btn.replace("view-to-", ""); 
             var title = GetTitle(id_checkbox);
             var v1 = $('#asal-tgl_transaksi-'+id_checkbox).val();
             var v2 = $('#asal-besar_nilai-'+id_checkbox).val();
             var v3 = $('#asal-keterangan-'+id_checkbox).val();
             var v4 = $('#asal-pihak2_nama-'+id_checkbox).val();
             var v5 = $('#asal-pihak2_alamat-'+id_checkbox).val();
             $('#tgl_transaksi_asal').val(v1);
             $('#besar_nilai_asal').val(v2);
             $('#keterangan_asal').val(v3);
             $('#nama_pihak2_asal').val(v4);
             $('#alamat2_asal').val(v5);
             view(id_checkbox,title);
        });

        

    });

     function ShowView(id_checkbox,x){
        var id_btn = $(x).attr('id');
        var title = GetTitle(id_checkbox);
        var data_value = $(x).data('value');
        $('#input-'+id_checkbox).val(data_value);
        $('#tgl_transaksi_asal').val(data_value[0]);
        $('#besar_nilai_asal').val(data_value[1]);
        $('#besar_nilai_asal').trigger('mask.maskMoney');
        $('#keterangan_asal').val(data_value[2]);
        $('#nama_pihak2_asal').val(data_value[3]);
        $('#alamat2_asal').val(data_value[4]);
        view(id_checkbox,title);
    }
    
     function GetTitle(id){
        var res = id.split("-");
        var resCount = res.length;
        var arr_title = new Array();
        for (i = 0; i < resCount; i++) {
            if (i > 0) {
                arr_title[i] = res[i];
            }
        }
        return arr_title.join(" ");
    }

    function view(id,title){
        $('#FormKas').hide();
            $('#formAsalUsul').fadeIn('fast', function() {
            $('#asal_usul_title').text('ASAL USUL ' + title.toUpperCase());
            $('#label-info').text('Besar Nilai (Rp)');
            $('#formAsalUsul #id_checkbox').val(id);
            $('#ModalHarta .modal-content').animate({
                'width': '50%',
                'margin-left': '25%'
            });
        });
    }

    function Cancelparent() {
        $('#formAsalUsul').fadeOut('fast', function() {
            $('#FormKas').fadeIn('fast', function() {
                $('#ModalHarta .modal-content').animate({
                    'width': '100%',
                    'margin-left': '0'
                });
                var ID = $('#ID').val();
                var id_checkbox = $('#id_checkbox').val();
                if(ID){
                  if($('#view-to-'+id_checkbox).is(':visible')){
                      $('#' + id_checkbox).prop('checked', true);
                  }else{
                       $('#' + id_checkbox).prop('checked', false);
                  }
                }else{
                  $('#view-'+id_checkbox).html('');
                  $('#result-'+id_checkbox).html('');
                  $('#' + id_checkbox).prop('checked', false);
                }

            });
        });
    }
</script>