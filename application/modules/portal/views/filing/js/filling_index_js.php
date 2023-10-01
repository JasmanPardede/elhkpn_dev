<div id="modalCetakSK" class="modal fade container-fluid" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" id="FormDocument" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">CETAK SK</h4>
                </div>
                <div class="modal-body row" style="text-align:center">
                    <table class="table table-striped table-bordered table-hover no-border-bottom table-filing dataTable no-footer">
                        <thead>
                            <tr>
                                <td>NAMA</td>
                                <td>STATUS / HUBUNGAN</td>
                                <td>AKSI</td>
                            </tr>
                        </thead>
                        <tbody id="data_keluarga">
                        </tbody>
                    </table>
                   
                </div><!--end of modal-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i>  Tutup</button>
                </div>
            </form>
           
        </div>
    </div>
</div>

<script type="text/javascript">


    var tblFilling = {
        tableId: 'Tabel',
        reloadFn: {tableReload: true, tableCollectionName: 'Tabel'},
        conf: {
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bServerSide": true,
            "sAjaxSource": "<?php echo base_url('portal/Filing/TableFiling'); ?>",
            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {

                var passData = getRecordDtTbl(sSource, aoData, oSettings);
                $.getJSON(sSource, passData, function (json) {
                    fnCallback(json);
                });
            },
            "bAutoWidth": false,
            "aoColumns": [
                {"mDataProp": "NO_URUT", bSearchable: true, bSortable: false},
                {"mDataProp": "NAMA", bSearchable: true, bSortable: false},
                {"mDataProp": "TGL_LAPOR", bSearchable: true, bSortable: false},
                {"mDataProp": "TIPE_PELAPORAN", bSearchable: true, bSortable: false},
                {
                    "mDataProp": function (source, type, val) {
                        var jabatan = '', arr_jabatan = [];
                        if (isObjectAttributeExists(source, 'ALL_JABATAN') && !isEmpty(source.ALL_JABATAN)) {
                            jabatan += '<ul>';
                            arr_jabatan = source.ALL_JABATAN;
                            for (var i in arr_jabatan) {
                                var key = '';
                                if (arr_jabatan[i].IS_PRIMARY == '1') {
                                    key = '(<span class="fa fa-key"></span>)';
                                }
                                jabatan += '<li>' + arr_jabatan[i].NAMA_JABATAN + ' ' + key + '</li>';
                            }
                            jabatan += '</ul>';
                        }

                        return  jabatan;
                    },
                    bSearchable: false,
                    bSortable: false
                },
                // {"mDataProp": "STATUS_LHKPN", bSearchable: false, bSortable: false},
                {"mDataProp": function (source, type, val){
                            var isi = source.STATUS_LHKPN;
                            var sk = source.SK;
                            var ketSK = '';
                            if(sk==0){
                                ketSK = '<br><strong>Surat Kuasa: Belum Diterima</strong>';
                            }
                            else{
                                ketSK = '<br><strong>Surat Kuasa: Sudah Diterima</strong>';
                            }
                            return isi+ketSK;
                }, 
                bSearchable: false, bSortable: false},
                {"mDataProp": "ENTRY_VIA", bSearchable: false, bSortable: false},
                {
                    "mDataProp": function (source, type, val) {
                        var is_wl = <?php echo $is_wl; ?>;
                        var vBtnIco = '<i class="fa fa-search-plus"></i>', vBtnCls = 'btn-info btn-sm edit-action', vBtnOnclick = 'btnEditFillingOnClick', vBtnTitle = 'Preview', vBtnAction = ' ', vBtnAction2 = ' ', vBtnAction3 = ' ', vBtnAction4 = ' ';

                        if (source.STATUS == '0' || source.STATUS == '2'  || source.STATUS == '7' && source.VIA_VIA == '0') {
                            vBtnIco = '<i class="fa fa-pencil"></i>';
                            vBtnCls = 'btn-success btn-sm edit-action';
                            vBtnTitle = 'Edit';
                            vBtnOnclick = 'btnEditFillingOnClick';
                        }

                        var btnEdit = '<button type="button" id="btnEditFilling' + source.ID_LHKPN + '" dkey="' + source.ID_LHKPN + '" class="btn ' + vBtnCls + '" title="' + vBtnTitle + '" onclick="' + vBtnOnclick + '(this);">' + vBtnIco + '</button>';
                        var btnHapus = '<button type="button" id="btnDeleteFilling' + source.ID_LHKPN + '" dkey="' + source.ID_LHKPN + '" class="btn btn-danger btn-sm delete-action" title="Delete" onclick="btnDeleteFillingOnClick(this);"><i class="fa fa-trash" style="color:white;"></i></button>';
                        var btnEditJenis = '<button type="button" id="btnEditJenisFilling' + source.ID_LHKPN + '" dkey="' + source.ID_LHKPN + '" class="btn btn-warning btn-sm edit-action" title="Edit Jenis Laporan" onclick="btnEditJenisFillingOnClick(this);"><i class="fa fa-bars" style="color:white;"></i></button>';
                        var btnCetakSK = source.JENIS_LAPORAN == '5' ? '' : '<button type="button" id="btnCetakSKFilling' + source.ID_LHKPN + '" dkey="' + source.ID_LHKPN + '" class="btn btn-success btn-sm edit-action" title="Cetak Surat Kuasa" onclick="btnCetakSKFillingOnClick(this);" data-toggle="modal" data-target="#modalCetakSK"><i class="fa fa-file" style="color:white;"></i></button>';
//                        var btnCetak = '<button type="button" id="btnDeleteFilling' + source.ID_LHKPN + '" dkey="' + source.ID_LHKPN + '" class="btn btn-danger btn-sm delete-action" title="Delete" onclick="'+View(9, +'"REVIEW HARTA"');'"><i class="fa fa-print" style="color:white;"></i></button>';
                        var btnCetak = '<button type="button"  id="btnCetakFilling' + source.ID_LHKPN + '" dkey="' + source.ID_LHKPN + '" class="btn btn-primary btn-sm" title="Ringkasan Harta" onclick="btnCetakOnClick(this);"><i class="fa fa-print" style="color:white;"></i></button>';
                        var btnCetakLembarPenyerahan = '<button type="button"  id="btnCetakLembarPenyerahan' + source.ID_LHKPN + '" dkey="' + source.ID_LHKPN + '" class="btn btn-sm" style="background-color: #FFD433 ;" title="Lembar Penyerahan LHKPN" onclick="btnCetakLembarPenyerahanOnClick(this);"><i class="fa fa-print" style="color:black;"></i></button>';
//                        var btnCetak = '<a target="_blank" id="btnCetakFilling' + source.ID_LHKPN + '" class="btn btn-success btn-sm pull-right" title="Cetak Ikhtisar" href="btnCetakOnClick(this);"><i class="fa fa-print" style="color:white;"></i></a>';
//                        var btnCetak = '<a target="_blank" id="btnCetakFilling' + source.ID_LHKPN + '" class="btn btn-success btn-sm pull-right" title="Cetak Ikhtisar" href="javascript:void(0)" onclick="'+View(9, +'"REVIEW HARTA"');'"><i class="fa fa-print" style="color:white;"></i></a>';
                        var btnDownloadTandaTerima = source.JENIS_LAPORAN == '5' ? '' : '<button type="button" id="btnDownloadTandaTerima' + source.ID_LHKPN + '" dkey="' + source.ID_LHKPN + '|' + source.VIA_VIA + '" class="btn btn-sm" style="background-color: #c0c0c0;"  title="Download Tanda Terima" onclick="btnDownloadTandaTerimaOnClick(this);"><i class="glyphicon glyphicon-download-alt"></i></button>';
                        var btnCetakPengumuman = '<button type="button" id="btnCetakPengumuman' + source.ID_LHKPN + '" dkey="' + source.ID_LHKPN + '" class="btn btn-sm" style="background-color: #cccc00;" title="Download Pengumuman" onclick="btnCetakPengumumanOnClick(this);"><i class="fa fa-bullhorn"></i></button>';
                        var btnCetakKekurangan = '<button type="button" id="btnCetakKekurangan' + source.ID_LHKPN + '" dkey="' + source.ID_LHKPN + '" class="btn btn-sm btn-danger" title="Download Lampiran Kekurangan" onclick="btnCetakKekuranganOnClick(this);"><i class="glyphicon glyphicon-exclamation-sign"></i></button>';
                        
                        var tanggal_lapor = source.tgl_lapor;
                        var tahun_lapor = tanggal_lapor.split("-", 1);
                        tahun_lapor = parseInt(tahun_lapor[0]);

                        var current_year = new Date().getFullYear();

                        var status_tidak_cetak_lembar_penyerahan = ['0', '7'];
                        var n = current_year - tahun_lapor;
                        
                        if (source.rowcount > 1){
                            if (is_wl == '0') {
                                vBtnAction = ' ' + btnCetak;
                            } else {
                                // tombol edit dihide jika status dikembalikan dan sudah masuk tahun pengisian elhkpn tahun berikutnya atau jika WL non aktif
                                if((source.STATUS == '7' && source.DIKEMBALIKAN != '0' && n >= 2) || (source.IS_WL != '1')){
                                    vBtnAction = ' ' + btnCetak;
                                }else{ 
                                    vBtnAction = btnEdit + ' ' + btnCetak;
                                }
                            }

                            if (source.STATUS == '0') {
                                vBtnAction += ' ' + btnHapus;
                            }

                            /// status lhkpn bukan ['0', '7'] dan via entry = online ///
                            if(status_tidak_cetak_lembar_penyerahan.indexOf(source.STATUS) == -1 && typeof(source.IS_WL) !=  "undefined"){
                                vBtnAction += ' ' + btnCetakLembarPenyerahan;
                            }

                            // if (source.STATUS == '0' || source.STATUS == '2' && source.VIA_VIA == '0'){
                            //     vBtnAction += ' ' + btnEditJenis;
                            // }
                            if (source.STATUS == '0' && source.VIA_VIA == '0' && is_wl != '0'){
                                vBtnAction += ' ' + btnEditJenis;
                            }

                            // else{
                            //     if (source.VIA_VIA == '0'){
                            //         vBtnAction += ' ' + btnCetakSK;
                            //     }
                            // }
                            if (source.STATUS != '0' && source.VIA_VIA == '0'){
                                vBtnAction2 = ' ' + btnCetakSK;
                            }

                            if ((source.STATUS == '3' || source.STATUS == '4' || source.STATUS == '5' || source.STATUS == '6') && source.VIA_VIA != '2'){
                                if (source.STATUS == '4' || source.STATUS == '6'){
                                    vBtnAction3 = ' ' + btnDownloadTandaTerima + btnCetakPengumuman;
                                } else {
                                    vBtnAction3 = ' ' + btnDownloadTandaTerima;
                                }
                            }
                            if ((source.STATUS == '2' || source.STATUS == '5' || source.STATUS == '6' || source.STATUS == '7') && source.VIA_VIA != '2'){
                                vBtnAction4 = ' ' + btnCetakKekurangan;
                            }
                            if((source.STATUS == '1' && source.ALASAN != null)){ // sudah diperbaiki
                                vBtnAction4 = ' ' + btnCetakKekurangan;
                            }
                        }else{
                            if (is_wl == '0') {
                                vBtnAction = ' ' + btnCetak;
                            } else {
                                // tombol edit dihide jika status dikembalikan dan sudah masuk tahun pengisian elhkpn tahun berikutnya atau jika WL non aktif
                                if((source.STATUS == '7' && source.DIKEMBALIKAN != '0' && n >= 2) || (source.IS_WL != '1')){
                                    vBtnAction = ' ' + btnCetak;
                                }else{
                                    vBtnAction = btnEdit + ' ' + btnCetak;
                                }
                            }
                            if (source.STATUS == '0' && source.VIA_VIA == '0' && is_wl != '0'){
                                vBtnAction += ' ' + btnEditJenis;
                            }

                            /// status lhkpn bukan ['0', '7'] dan via entry = online ///
                            if(status_tidak_cetak_lembar_penyerahan.indexOf(source.STATUS) == -1 && typeof(source.IS_WL) !=  "undefined"){
                                vBtnAction += ' ' + btnCetakLembarPenyerahan;
                            }
                            // else{
                            //     if (source.VIA_VIA == '0'){
                            //         vBtnAction += ' ' + btnCetakSK;
                            //     }
                            // }
                            if (source.STATUS != '0' && source.VIA_VIA == '0'){
                                vBtnAction2 = ' ' + btnCetakSK;
                            }
                            if ((source.STATUS == '3' || source.STATUS == '4' || source.STATUS == '5' || source.STATUS == '6') && source.VIA_VIA != '2'){
                                if (source.STATUS == '4' || source.STATUS == '6'){
                                    vBtnAction3 = ' ' + btnDownloadTandaTerima + btnCetakPengumuman;
                                } else {
                                    vBtnAction3 = ' ' + btnDownloadTandaTerima;
                                }
                            }
                            if ((source.STATUS == '2' || source.STATUS == '5' || source.STATUS == '6' || source.STATUS == '7') && source.VIA_VIA != '2'){
                                vBtnAction4 = ' ' + btnCetakKekurangan;
                            }
                            if((source.STATUS == '1' && source.ALASAN != null)){ // sudah diperbaiki
                                vBtnAction4 = ' ' + btnCetakKekurangan;
                            }
                        }


                        return (vBtnAction + vBtnAction2 + vBtnAction3 + vBtnAction4).toString();
                    },
                    bSortable: false,
                    bSearchable: true
                }
            ],
        }
    }, btnDeleteFillingOnClick, btnCetakOnClick, btnEditFillingOnClick = function (self) {
        var id = $(self).attr('dkey');
        window.location.href = '<?php echo base_url(); ?>portal/filing/entry/' + id;
    };

    $(document).ready(function () {
        var gtblFilling = initDtTblSimpleSearch(tblFilling);

        btnCetakOnClick = function (self) {
            var id = $(self).attr('dkey');
            window.location.href = '<?php echo base_url(); ?>portal/filing/entry/' + id+'?strh=ok_do_it';
        };

        btnCetakLembarPenyerahanOnClick = function (self) {
            var id = $(self).attr('dkey');
            window.location.href = '<?php echo base_url(); ?>portal/filing/cetak_lembar_penyerahan/' + id;
        };

        btnCetakSKFillingOnClick = function (self) {
            
            var id = $(self).attr('dkey');
            var LINK = '<?php echo base_url(); ?>portal/filing/ajax_cetak_sk/' + id;
            $.post(LINK, function (data) {
                if (data) {
                    var tabel_keluarga = '';
                    var i;
                    data = jQuery.parseJSON(data);
                    $.each(data, function(i, item) {
                        tabel_keluarga += '<tr role="row" class="odd"  align="left">';
                        tabel_keluarga += '<td>'+ item.NAMA +'</td>';
                        tabel_keluarga += '<td>'+ item.HUBUNGAN_DESC + '</td>';
                        tabel_keluarga += '<td align=center>';
                        if(item.FLAG_SK === '0' && item.CETAK_LINK !== ""){
                            tabel_keluarga += '<button type="button" class="btn btn-success btn-sm edit-action cetakSK-action" title="Cetak Surat Kuasa" href="' + item.CETAK_LINK + '"><i class="fa fa-file" style="color:white;"></i></button>';
                        }else if(item.UMUR_LAPOR < 17){
                            tabel_keluarga += '<strong>Surat Kuasa: Belum Wajib</strong>';
                        }else if(item.HUBUNGAN === '4' || item.HUBUNGAN === '5'){
                            tabel_keluarga += '<strong>Surat Kuasa: Tidak Wajib</strong>';
                        }else{
                            tabel_keluarga += '<strong>Surat Kuasa: Sudah Diterima</strong>';
                        }
                        tabel_keluarga += '</td>';
                        tabel_keluarga += '</tr>';
                    });
                    $("#data_keluarga").html(tabel_keluarga);
                    
                    // CETAK ACTION
                    $(".cetakSK-action").click(function(self) {
                        // var id = $(this).attr('id');
                        // var id_lhkpn = $(this).attr('data-id');
                        var LINK = $(this).attr('href');
                        window.open(LINK, '_blank');
                    });
                }
            });
            
        };

        btnCetakSKFillingMengumumkanOnClick = function (self) {
            var id = $(self).attr('dkey');
            var LINK = '<?php echo base_url(); ?>portal/review_harta/surat_kuasa_pdf/' + id + '/' + '1';
            window.open(LINK);
        };

        btnEditJenisFillingOnClick = function (self) {
            var id = $(self).attr('dkey');
                $.ajax({
                 url: '<?php echo base_url();?>' +'portal/filing/edit_jenis_pelaporan/'+id,
                 async: false,
                 dataType: 'JSON',
                 success:function(data){
                    var rs = eval(data);
                    var val = rs.result.JENIS_LAPORAN == '1' || rs.result.JENIS_LAPORAN == '2' || rs.result.JENIS_LAPORAN == '3' ? '2' : '4';
                    $('#jenis_laporan').select2('val',rs.result.JENIS_LAPORAN == '1' || rs.result.JENIS_LAPORAN == '2' || rs.result.JENIS_LAPORAN == '3' ? '2' : '4');

                    var today = new Date();
                    var yearminsatu = today.getFullYear()-1;

                    var check_date = rs.result.tgl_lapor;
                    if(check_date==null || check_date==''){
                        var get_full_date = new Date();
                        var get_day = get_full_date.getDate();
                        var get_month = get_full_date.getMonth() + 1;
                        var get_year = get_full_date.getFullYear();
                        var date_while = get_year+'-'+get_month+'-'+get_day;
                    }else{
                        var date_while   = rs.result.tgl_lapor;
                    }
                    var date    = date_while.split("-"),
                        yr      = date[0],
                        month   = date[1],
                        day     = date[2],
                        newDate = day + '/' + month + '/' + yr;
                        var today = new Date();

                        var dd = today.getDate();
                        var mm = today.getMonth()+1; //January is 0!

                        var yyyy = today.getFullYear();

                    var d = new Date();
                        d.setFullYear(d.getFullYear() - 1);
                        var limit = new Date(d.getFullYear(), 11, 31);

                        var tahun_max = <?php echo $max_tahun_wl; ?>;
                        var limitqq =  new Date(tahun_max,1-1,1); //1 januari tahun max wl
                        
                        var limitq = new Date(yyyy,1-1,1); //1 januari tahun sekarang
                        var limit_tahun_min_1 = new Date(yyyy-1,1-1,1); //1 januari tahun n-1

                        var tgl_kirim_final = new Date(rs.result.tgl_kirim_final);    
                    if (val == 4) {
                        if(yr < yearminsatu){
                            $('#tahun_pelaporan').val(yearminsatu);
                        }else{
                            $('#tahun_pelaporan').val(yr);
                        }
                        
                        $('.group-1').hide();
                        $('.group-0').fadeIn('slow');
                    } else {
                        $('#status').select2('val',rs.result.JENIS_LAPORAN);
                        // $('#tgl_pelaporan').val(newDate);
                        $('.group-0').hide();
                        $('.group-1').fadeIn('slow');
                    }
                    $('#is_update').val('update');
                    $('#id_lhkpn').val(rs.result.ID_LHKPN);
                    
                    var maxLapor = new Date(tahun_max, 11, 31); //31 desember tahun max 

                    if(yyyy == tahun_max){
                        maxLapor = "now";
                    }else{
                        maxLapor;
                    }

                    if(rs.is_update == 'update'){

                        var tgl_lapor = rs.result.tgl_lapor;
                        var thn_lapor = tgl_lapor.split("-", 1);
                        thn_lapor = thn_lapor[0];

                        if (thn_lapor < tahun_max){
                    
                            var this_day = new Date();
                            var dd = this_day.getDate();
                            var mm = this_day.getMonth() + 1;
                            var yyyy = this_day.getFullYear();

                            if (dd < 10) { 
                                dd = '0' + dd; 
                            } 
                            
                            if (mm < 10) { 
                                mm = '0' + mm; 
                            } 

                            var this_date = dd+'/'+mm+'/'+yyyy;

                            $('#tgl_pelaporan').val(this_date)
                        }else{
                            $('#tgl_pelaporan').val(newDate);
                        }  

                        if (rs.wl_tahun_now == 1 && rs.wl_thn_minus_1 == 1){
                            var tahun_min_satu = yyyy-1;

                            if(rs.tahun_minus_1 == tahun_min_satu){
                                $('#tgl_pelaporan').datetimepicker({
                                    format: "DD/MM/YYYY",
                                    allowInputToggle: true,
                                    locale: 'id',
                                    maxDate: maxLapor,
                                    minDate: new Date(limitqq)
                                }); 
                            }else{
                                $('#tgl_pelaporan').val(newDate);
                                $('#tgl_pelaporan').datetimepicker({
                                    format: "DD/MM/YYYY",
                                    allowInputToggle: true,
                                    locale: 'id',
                                    maxDate: maxLapor,
                                    minDate: new Date(limit_tahun_min_1)
                                });
                            }
                            
                        }else if(rs.wl_thn_minus_1 == 0){  // jika tidak ada laporan sebelumnya
                            $('#tgl_pelaporan').datetimepicker({
                                format: "DD/MM/YYYY",
                                allowInputToggle: true,
                                locale: 'id',
                                maxDate: maxLapor,
                                minDate: new Date(limitqq)
                            }); 
                        }

                        $('#title-label').text('EDIT JENIS LAPORAN');
                        // if(rs.result.tgl_kirim_final){ 
                            $('#tgl_pelaporan').datetimepicker({
                                format: "DD/MM/YYYY",
                                allowInputToggle: true,
                                locale: 'id',
                                maxDate: maxLapor,
                                minDate: new Date(limitqq)
                            });
                        // }else{ 
                        //     $('#tgl_pelaporan').datetimepicker({
                        //         format: "DD/MM/YYYY",
                        //         allowInputToggle: true,
                        //         locale: 'id',
                        //         maxDate: 'now',
                        //         minDate: new Date(limitq)
                        //     });
                        // }
                    }else{
                        $('#title-label').text('BUAT LHKPN BARU');
                    }

                }
            });
//            $('.group-0,.group-1, #img_pop_content').hide();
            $('#FillingEditLaporan').modal('show');
        };

        btnDeleteFillingOnClick = function (self) {

            var id = $(self).attr('dkey');
            confirm("Apakah anda yakin akan menghapus data ? ", function () {
                $.ajax({
                    url: '<?php echo base_url(); ?>portal/filing/delete/' + id,
                    dataType: 'html',
                    async: false,
                    success: function (data) {
                        location.reload();
                        reloadTableDoubleTime(gtblFilling);
                    }
                });
            });
            return false;

        };

        btnCetakPengumumanOnClick = function (self) {
            var id = $(self).attr('dkey');
            window.open('<?php echo base_url(); ?>portal/filing/BeforeAnnoun/' + id,'_blank')
        };

        btnDownloadTandaTerimaOnClick = function (self) {
            var id_all = $(self).attr('dkey');
            var split = id_all.split('|');
            var id = split[0];
            var via = split[1];
            window.open('<?php echo base_url(); ?>ever/verification/preview_tandaterima/' + id + '/' + via,'_blank')
        };

        btnCetakKekuranganOnClick = function (self) {
            var id = $(self).attr('dkey');
            var url = '<?php echo base_url(); ?>ever/verification/previewmsg';
            $("<form action='" + url + "' method='post' target='_blank' ></form>")
                .append($("<input type='hidden' name='id_lhkpn' />").attr('value', id))
                .append($("<input type='hidden' name='verif' />").attr('value', 'kekurangan'))
                .appendTo('body')
                .submit()
                .remove();
            return false;
        };

        $('#TblFilling_filter').hide();
    });
</script>

<style>
    #modalCetakSK .modal-body {
        margin: 20px;
    }
</style>