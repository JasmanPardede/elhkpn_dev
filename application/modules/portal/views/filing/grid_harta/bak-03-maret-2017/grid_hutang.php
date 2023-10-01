<table id="TableHutang"  class="table table-striped table-bordered table-hover table-heading no-border-bottom table-filing">
    <thead>
        <tr>
            <th style="width:10% important;">NO</th>
            <th style="width:10% important;">URAIAN</th>
            <th style="width:10% important;">NAMA KREDITUR</th>
            <th style="width:10% important;">BENTUK AGUNAN</th>
            <th style="width:20% important;">NILAI AWAL HUTANG</th>
            <th style="width:20% important;">NILAI SALDO HUTANG</th>
            <th style="width:80px;">AKSI</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<div style="overflow:hidden;">
    <div class="pull-right">
    <a href="javascript:void(0)" onclick="pindah_tab('#lain','lain')" class="btn btn-warning btn-sm" style="margin-left:5px;">
      <i class="fa fa-backward"></i>  Sebelumnya
    </a>
    <a href="javascript:void(0)" onclick="pindah(5)" class="btn btn-warning btn-sm" style="margin-left:5px;">
       Selanjutnya <i class="fa fa-forward"></i>  
   </a>
  </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){

        
        
       $('#TableHutang').dataTable({
            "oLanguage": ecDtLang,
            'sPaginationType': 'full_numbers',
            'bServerSide': true,
            'bProcessing': true,
            'sAjaxSource': '<?php echo base_url(); ?>portal/data_harta/grid_hutang/'+ID_LHKPN,
            "bLengthChange": false,
            "bFilter": true,
            "bSort": false,
//            "bInfo": false,
            "bAutoWidth": false,
            'aoColumns': [{sWidth:"1%"},{sWidth:"30%"},{sWidth:"15%"},{sWidth:"12%"},{sWidth:"12%"},{sWidth:"12%"},{sWidth:"12%"}],
            'fnServerData': function (sSource, aoData, fnCallback){
                 $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            }
        });

        // DELETE ACTION
        $("#TableHutang tbody").on('click','.delete-action',function(event){
             var id = $(this).attr('id');
             confirm("Apakah anda yakin akan menghapus data ? ", function(){
                 do_delete('portal/data_harta/delete/'+id+'/t_lhkpn_hutang','Data Huatang Berhasil Di Hapus ');
                 $('#TableHutang').DataTable().ajax.reload();
             });
        });

         $("#TableHutang tbody").on('click','.edit-action',function(event){
            var id = $(this).attr('id');
            $.ajax({
                 url: base_url +'portal/data_harta/edit_hutang/'+id,
                 async: false,
                 dataType: 'JSON',
                 success:function(data){
                    var rs = eval(data);
                    CallForm('hutang');
                    if (rs.ATAS_NAMA == 'LAINNYA') {
                            $('#ket_lainnya_an_div').show();
                            $('#KET_LAINNYA_AN').val(rs.KET_LAINNYA);
                        } else {
                            $('#ket_lainnya_an_div').hide();
                        }
                    $('#ID').val(rs.ID_HUTANG);
                    $('#ATAS_NAMA').select2('val',rs.ATAS_NAMA);
                    $('#NAMA_KREDITUR').val(rs.NAMA_KREDITUR);
                    $('#AGUNAN').val(rs.AGUNAN);
                    $('#KODE_JENIS').select2('val',rs.KODE_JENIS);
                    $('#SALDO_HUTANG').val(rs.SALDO_HUTANG).trigger('keyup');
                    $('#AWAL_HUTANG').val(rs.AWAL_HUTANG).trigger('keyup');
                    $('#ID_HARTA').val(rs.ID_HARTA);
                 }
            });
        });

        $("body").on('click','.btn-view',function(event){
             var id_btn = $(this).attr('id');
             var id_checkbox = id_btn.replace("view-to-", ""); 
             var title = GetTitle(id_checkbox);
             var data_value = $(this).data('value');
             $('#input-'+id_checkbox).val(data_value);
             $('#tgl_transaksi_asal').val(data_value[0]);
             $('#besar_nilai_asal').val(parseFloat(data_value[1]).toFixed(2));
             $('#besar_nilai_asal').trigger('mask.maskMoney');
             $('#keterangan_asal').val(data_value[2]);
             $('#nama_pihak2_asal').val(data_value[3]);
             $('#alamat2_asal').val(data_value[4]);
             view(id_checkbox,title);
        });

      
        
        
    });
    function ShowView(id_checkbox,x){
        var id_btn = $(x).attr('id');
        var title = GetTitle(id_checkbox);
        var data_value = $(x).data('value');
        $('#input-'+id_checkbox).val(data_value);
        $('#tgl_transaksi_asal').val(data_value[0]);
        $('#besar_nilai_asal').val(parseFloat(data_value[1]).toFixed(2));
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
        $('#FormHutang').hide();
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
            $('#FormHutang').fadeIn('fast', function() {
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
                  $('#' + id_checkbox).prop('checked', false);
                }

            });
        });
    }
</script>