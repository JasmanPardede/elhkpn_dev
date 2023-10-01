<table id="TableTanah" class="table table-bordered table-striped table-filing">
    <thead>
        <tr>
            <th>NO</th>
            <th>STATUS</th>
            <th>LOKASI</th>
            <th>LUAS</th>
            <th>KEPEMILIKAN</th>
            <th>NILAI PEROLEHAN</th>
            <th>NILAI ESTIMASI SAAT PELAPORAN</th>
            <th style="width:100px;">AKSI</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<div style="overflow:hidden;">
    <div class="pull-right">
        <a href="javascript:void(0)" onclick="pindah(3)" class="btn btn-warning btn-sm" style="margin-left:5px;">
            <i class="fa fa-backward"></i>  Sebelumnya
        </a>
        <a href="javascript:void(0)" onclick="pindah_tab('#harta_bergerak', 'harta_bergerak')" class="btn btn-warning btn-sm" style="margin-left:5px;">
            Selanjutnya <i class="fa fa-forward"></i>  
        </a>
    </div>
</div>
<?php

$state_main_table = "t_lhkpn_harta_tidak_bergerak";

?>
<script type="text/javascript">
    $(document).ready(function () {
        ///OPTION PELEPASAN HARTA
        var id_pelepasan_harta = ['4','5','6','7','8','14'];
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
        if (IS_COPY == '1') {
            copy = true;
        }


        dtTanah = $('#TableTanah').dataTable({
            "oLanguage": ecDtLang,
            'sPaginationType': 'full_numbers',
            'bServerSide': true,
            'bProcessing': true,
            'sAjaxSource': '<?php echo base_url(); ?>portal/data_harta/grid_harta_tidak_bergerak/' + ID_LHKPN,
            "bLengthChange": false,
            "bFilter": true,
            "bSort": false,
//            "bInfo": false,
            "bAutoWidth": false,
            'aoColumns': [{sWidth: "1%"}, {sWidth: "1%"}, {sWidth: "15%"}, {sWidth: "15%"}, {sWidth: "20%"}, {sWidth: "13%"}, {sWidth: "13%"}, null],
            'fnServerData': function (sSource, aoData, fnCallback) {
                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            },
//            "aoColumnDefs": [{"bVisible": copy, "aTargets": [1]}],
            "fnRowCallback": function (row, data, dataIndex) {
                var rowId = $(this).closest('tr');
                var value = $(row).find('label.pelepasan').text();
                if (value == 'Lepas') {
                    $(row).css({'background-color': '#808080', 'color': '#fff'});
                } else {
                    var ID_HARTA_TABLE = $(row).find('.ID_HARTA_TABLE').val();
                    var STATUS_TABLE = $(row).find('.STATUS_TABLE').val();
                    if (STATUS_TABLE == '3' && ID_HARTA_TABLE != '') {
                        $(row).find('label.label').text('Lama');
                        $(row).find('label.label').css({'background-color': '#FFA500', 'color': '#fff'});
                    }
                }
            },

        });

        // DELETE ACTION
        $("#TableTanah tbody").on('click', '.delete-action', function (event) {
            var id = $(this).attr('id');
            confirm("Apakah anda yakin akan menghapus data ? ", function () {
                do_delete('portal/data_harta/delete/' + id + '/t_lhkpn_harta_tidak_bergerak', 'Data Harta Tanah/Bangunan berhasil dihapus ');
                $('#TableTanah').DataTable().ajax.reload();
            });
        });

        // PELEPASAN ACTION
        $("#TableTanah tbody").on('click', '.pelepasan-action', function (event) {
            var id = $(this).attr('id');
            $('#FORM_PELEPASAN').bootstrapValidator('resetForm', true);
            $('#FORM_PELEPASAN #JENIS_PELEPASAN_HARTA').select2();
            $('#FORM_PELEPASAN #MAIN_TABLE').val('t_lhkpn_harta_tidak_bergerak');
            $('#FORM_PELEPASAN #TABLE').val('t_lhkpn_pelepasan_harta_tidak_bergerak');
            $('#FORM_PELEPASAN #ID_HARTA').val(id);
            $('#FORM_PELEPASAN #TABLE_GRID').val('#TableTanah');
            $('#FORM_PELEPASAN #NOTIF').val('Data harta Tanah/Bangunan berhasil dilepas.');
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

        });

        // PENETAPAN ACTION
        $("#TableTanah tbody").on('click', '.penetapan-action', function (event) {
            var id = $(this).attr('id');
            confirm("Apakah anda yakin akan melakukan penetapan harta ini ? ", function () {
                $.ajax({
                    url: base_url + 'portal/data_harta/edit_harta_tidak_begerak/' + id,
                    async: false,
                    dataType: 'JSON',
                    success: function (data) {
                        var rs = eval(data);
                        if (rs.result.LUAS_TANAH == 0 && rs.result.LUAS_BANGUNAN == 0){
                            notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan luas tanah dan luas bangunan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                        }else if (rs.result.NILAI_PELAPORAN == 0){
                            notif('Data Harta Tidak Bergerak gagal dimutakhirkan dengan status TETAP, dikarenakan nilai pelaporan 0 (nol), silakan direview kembali nilai saat pelaporannya dengan fitur PERUBAHAN DATA');
                        }else{
                              do_delete('portal/data_harta/penetapan/' + id + '/t_lhkpn_harta_tidak_bergerak', 'Data Harta Tidak Bergerak Sudah Tetap ');
                              $('#TableTanah').DataTable().ajax.reload();  
                        }
                    }
                });
            });
        });

        // EDIT ACTION
        $("#TableTanah tbody").on('click', '.edit-action', function (event) {
            var id = $(this).attr('id');
            $.ajax({
                url: base_url + 'portal/data_harta/edit_harta_tidak_begerak/' + id,
                async: false,
                dataType: 'JSON',
                success: function (data) {
                    CallForm('harta_tidak_bergerak');
                    var rs = eval(data);
                    $('#ID').val(rs.result.REAL_ID);
                    if (rs.result.ID_NEGARA == '2') {
                        $('#PROV').select2('data', null);
                        GetKota(0);
                        $('.luar').hide();
                        $('.lokal').fadeIn('slow');
                        // $('#NEGARA').select2('val', 1);

                        $('#NEGARA').val('1').change();
                    } else {
                        $('.lokal').hide();
                        $('.luar').fadeIn('slow');
                        // $('#NEGARA').select2('val', 2);

                        $('#NEGARA').val('2').change();
                    }
                    if (rs.result.ATAS_NAMA == '3') {
//                         $('#ket_lainnya_an_div').show();
//                         $('#KET_LAINNYA_AN').val(rs.result.KET_LAINNYA);
                    } else {
                        $('#ket_lainnya_an_div').hide();
                    }
                    //dari sini
                    if (rs.result.ATAS_NAMA.indexOf("1") >= 0) {
                        $('#ATAS_NAMA_CHECK_PN').prop('checked', true);
                    }
                    if (rs.result.ATAS_NAMA.indexOf("2") >= 0) {
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
                    if (rs.result.ATAS_NAMA.indexOf("3") >= 0) {
                        $('#ket_lainnya_an_div').show();
                        $('#KET_LAINNYA_AN').val(rs.result.KET_LAINNYA);
                        $('#ATAS_NAMA_CHECK_LAINNYA').prop('checked', true);
                    }
                    $('#status_harta').val(rs.result.STATUS);
                    $('#is_load_harta').val(rs.result.IS_LOAD);
                    $('#is_pelepasan_harta').val(rs.result.IS_PELEPASAN);
                    
                    $('#ID_NEGARA').select2("data", {id: rs.result.ID_NEGARA, text: rs.result.NAMA_NEGARA});
                    $('#KAB_KOT').val(rs.result.KAB_KOT);
                    $('#KEC').val(rs.result.KEC);
                    $('#KEL').val(rs.result.KEL);
                    $('#JALAN').val(rs.result.JALAN);
                    $('#JENIS_BUKTI').select2('val', rs.result.ID_JENIS_BUKTI);
                    $('#NOMOR_BUKTI').val(rs.result.NOMOR_BUKTI);
                    $('#ATAS_NAMA').select2('val', rs.result.ATAS_NAMA);
                    $('#NILAI_PEROLEHAN').val(rs.result.NILAI_PEROLEHAN).trigger('keyup');
                    $('#NILAI_PELAPORAN').val(rs.result.NILAI_PELAPORAN).trigger('keyup');
//                     $('#KET_PASANGAN_ANAK').val(rs.result.PASANGAN_ANAK);
                    // var str = rs.result.LUAS_TANAH; 
                    var L_TANAH = rs.result.LUAS_TANAH.replace(".", ",");
                    var L_BANGUNAN = rs.result.LUAS_BANGUNAN.replace(".", ",");
                    $('#LUAS_TANAH').val(L_TANAH);
                    $('#LUAS_BANGUNAN').val(L_BANGUNAN);
                    $('#NILAI_PEROLEHAN,#NILAI_PELAPORAN').trigger('mask.maskMoney');
                    $('#PROV').select2("data", {id: rs.result.ID_PROV, text: rs.result.NAME});
                    GetKota(rs.result.ID_PROV);
                    $('#KAB_KOT').select2("data", {id: rs.result.ID_KAB, text: rs.result.NAME_KAB});
                    $('#ID_HARTA').val(rs.result.ID_HARTA);
                    $('#TAHUN_PEROLEHAN_AWAL').val(rs.result.TAHUN_PEROLEHAN_AWAL);
                    var PM = rs.result.ARR_PEMANFAATAN;
                    var A_PM = PM.split(',');
                    for (i = 0; i < A_PM.length; i++) {
                        var pm_val = A_PM[i];
                        $("#table-pemanfaatan input[value='" + pm_val + "']").prop('checked', true);
                    }
                    var AL = rs.result.ARR_ASAL_USUL;
                    var A_AL = AL.split(',');
                    for (i = 0; i < A_AL.length; i++) {
                        var as_val = A_AL[i];
                        $("#table-asal-usul input[value='" + as_val + "'].pilih").prop('checked', true);
                    }
                    // ALERT(A_AL);
                    var al = rs.asal_usul;
                    for (i = 0; i < al.length; i++) {
                        var x = new Array();
                        x[0] = dateConvert(al[i].TANGGAL_TRANSAKSI);
                        x[1] = al[i].NILAI_PELEPASAN;
                        x[2] = al[i].URAIAN_HARTA;
                        x[3] = al[i].NAMA;
                        x[4] = al[i].ALAMAT;
                        if (al[i].ID_ASAL_USUL != 1) {
                            var y = al[i].ID_ASAL_USUL;
                            $('input[name="asal_tgl_transaksi[' + y + ']"]').val(x[0]);
                            $('input[name="asal_besar_nilai[' + y + ']"]').val(numeral(x[1]).format('0,0').replace(/,/g, '.'));
                            $('input[name="asal_keterangan[' + y + ']"]').val(x[2]);
                            $('input[name="asal_pihak2_nama[' + y + ']"]').val(x[3]);
                            $('input[name="asal_pihak2_alamat[' + y + ']"]').val(x[4]);
                            var id_checkbox = $("#table-asal-usul input[value='" + y + "'].pilih").attr('id');
                            $('#view-' + id_checkbox).html('<a href="javascript:void(0)" id="view-to-' + id_checkbox + '" class="btn btn-view btn-xs btn-info">Lihat</a>');
                            $('#result-' + id_checkbox).html('<label class="label label-primary">' + numeral(x[1]).format('0,0').replace(/,/g, '.') + '</label>');
                        }
                    }

                }
            });
        });

        $("body").on('click', '.btn-view', function (event) {
            var id_btn = $(this).attr('id');
            var id_checkbox = id_btn.replace("view-to-", "");
            var title = GetTitle(id_checkbox);
            var v1 = $('#asal-tgl_transaksi-' + id_checkbox).val();
            var v2 = $('#asal-besar_nilai-' + id_checkbox).val();
            var v3 = $('#asal-keterangan-' + id_checkbox).val();
            var v4 = $('#asal-pihak2_nama-' + id_checkbox).val();
            var v5 = $('#asal-pihak2_alamat-' + id_checkbox).val();
            $('#tgl_transaksi_asal').val(v1);
            $('#besar_nilai_asal').val(v2);
            $('#keterangan_asal').val(v3);
            $('#nama_pihak2_asal').val(v4);
            $('#alamat2_asal').val(v5);
            view(id_checkbox, title);
        });



    });


    function ShowView(id_checkbox, x) {
        var id_btn = $(x).attr('id');
        var title = GetTitle(id_checkbox);
        var data_value = $(x).data('value');
        $('#input-' + id_checkbox).val(data_value);
        $('#tgl_transaksi_asal').val(data_value[0]);
        $('#besar_nilai_asal').val(data_value[1]);
        $('#besar_nilai_asal').trigger('mask.maskMoney');
        $('#keterangan_asal').val(data_value[2]);
        $('#nama_pihak2_asal').val(data_value[3]);
        $('#alamat2_asal').val(data_value[4]);
        view(id_checkbox, title);
    }

    function GetTitle(id) {
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

    function view(id, title) {
        $('#FormHarta').hide();
        $('#formAsalUsul').fadeIn('fast', function () {
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
        $('#formAsalUsul').fadeOut('fast', function () {
            $('#FormHarta').fadeIn('fast', function () {
                $('#ModalHarta .modal-content').animate({
                    'width': '100%',
                    'margin-left': '0'
                });
                var ID = $('#ID').val();
                var id_checkbox = $('#id_checkbox').val();
                if (ID) {
                    if ($('#view-to-' + id_checkbox).is(':visible')) {
                        $('#' + id_checkbox).prop('checked', true);
                    } else {
                        $('#' + id_checkbox).prop('checked', false);
                    }
                } else {
                    $('#' + id_checkbox).prop('checked', false);
                    $('#view-' + id_checkbox).html('');
                    $('#result-' + id_checkbox).html('');
                }

            });
        });
    }

    function GetKota(id) {
        $('#KAB_KOT').select2({
            //placeholder: "Pilih Kota",
            allowClear: true,
            ajax: {
                url: '<?php echo base_url(); ?>portal/filing/getkota/' + id,
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
            CustomValidation();
        });
    }

</script>