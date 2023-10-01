<!---HARTA TIDAK BERGERAK -->
<?php $onAdd = isset($onAdd) ? $onAdd : FALSE; ?>
<form role="form" id="ajaxFormEdit" action="index.php/efill/validasi_harta_tidak_bergerak/<?php echo $action; ?>" >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">FORM DATA HARTA TIDAK BERGERAK (TANAH DAN/ATAU BANGUNAN)</h4>
    </div>
    <div class="modal-body row">
        <div class="col-sm-4">
            <?php if (!$onAdd): ?>
                <input type="hidden" name="id_imp_xl_lhkpn_harta_tidak_bergerak" id="id_imp_xl_lhkpn_harta_tidak_bergerak" value='<?php echo $item->id_imp_xl_lhkpn_harta_tidak_bergerak_secure; ?>' />
            <?php endif; ?>
            <div class="form-group">
                <label>Negara Asal<span class="red-label">*</span> </label> <?= FormHelpPopOver('negara_asal_htb'); ?>
                <select name="NEGARA" id="NEGARA" class="form-control" required>
                    <option></option>
                    <option value="1">INDONESIA</option>
                    <option value="2">LUAR NEGERI</option>
                </select> 
            </div>
            <div class="form-group luar form-negara">
                <label>Negara <span class="red-label">*</span> </label> <?= FormHelpPopOver('negara'); ?>
                <input type="hidden" name="ID_NEGARA" id="ID_NEGARA" class="form-control"/>
                <small class="help-block notif-negara" style="color:#a94442; display:none;">Data ini wajib di isi</small>
            </div>
            <div class="form-group lokal form-prov">
                <label>Provinsi <span class="red-label">*</span> </label> <?= FormHelpPopOver('provinsi_htb'); ?>
                <input type="text" name="ID_PROV" id="PROV" class="form-control"/>
                <input type="hidden" name="PROV" id="NAMA_PROV" class="form-control" value='<?php echo!$onAdd ? $item->PROV : ''; ?>' />
                <small class="help-block notif-prov" style="color:#a94442; display:none;">Data ini wajib di isi</small>
            </div>
            <div class="form-group lokal form-kota">
                <label>Kabupaten/Kota <span class="red-label">*</span> </label> <?= FormHelpPopOver('kab_htb'); ?>
                <input type="text" name="KAB_KOT" id="KAB_KOT" class="form-control input_capital" value='<?php echo!$onAdd ? $item->KAB_KOT : ''; ?>' />
                <small class="help-block notif-kota" style="color:#a94442; display:none;">Data ini wajib di isi</small>
            </div>
            <div class="form-group lokal">
                <label>Kecamatan <span class="red-label">*</span> </label> <?= FormHelpPopOver('kec_htb'); ?>
                <input type="text" id="KEC" name="KEC" class="form-control input_capital" value='<?php echo!$onAdd ? $item->KEC : ''; ?>' />
            </div>
            <div class="form-group lokal">
                <label>Desa/Kelurahan <span class="red-label">*</span> </label> <?= FormHelpPopOver('kel_htb'); ?>
                <input type="text" name="KEL" id="KEL" class="form-control input_capital " value='<?php echo!$onAdd ? $item->KEL : ''; ?>' />
            </div>
            <div class="form-group alamat">
                <label class="lbl_alamat">Jalan <span class="red-label">*</span> </label> <?= FormHelpPopOver('jalan_htb'); ?>
                <textarea class="form-control input_capital" name="JALAN" id="JALAN" rows="2" required ><?php echo!$onAdd ? $item->JALAN : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label>Luas Tanah / Bangunan </label> <?= FormHelpPopOver('luas_tanah_htb'); ?>
                <div style="overflow:hidden; clear:both;">
                    <input type="text" style="width:41%; display:inline; margin-right:5px;" name="LUAS_TANAH" id="LUAS_TANAH" onkeypress="return fun_AllowOnlyAmountAndDot(this.id);" class="form-control" <?php echo strlen($item->LUAS_TANAH) > 0 || strlen($item->LUAS_BANGUNAN) > 0 ? "" : "required"; ?> value='<?php echo!$onAdd ? $item->LUAS_TANAH : '0'; ?>' /> 
                    <label style="display:inline;">m<sup>2</sup></label>
                    <input type="text" style="width:41%; display:inline; margin-right:5px;" name="LUAS_BANGUNAN" id="LUAS_BANGUNAN" onkeypress="return fun_AllowOnlyAmountAndDot(this.id);" class="form-control" <?php echo strlen($item->LUAS_BANGUNAN) > 0 || strlen($item->LUAS_TANAH) > 0 ? "" : "required"; ?> value='<?php echo!$onAdd ? $item->LUAS_BANGUNAN : '0'; ?>' />
                    <label style="display:inline;">m<sup>2</sup></label>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Jenis Bukti <span class="red-label">*</span> </label> <?= FormHelpPopOver('jenis_bukti_htb'); ?>
                <select name="JENIS_BUKTI" id="JENIS_BUKTI" class="form-control" required>
                </select>
            </div>
            <div class="form-group">
                <label>Nomor Bukti <span class="red-label">*</span> </label> <?= FormHelpPopOver('no_bukti_htb'); ?>
                <input type="text" name="NOMOR_BUKTI" id="NOMOR_BUKTI" placeholder="" class="form-control input_capital" required value='<?php echo!$onAdd ? $item->NOMOR_BUKTI : ''; ?>' />
            </div>
            <div class="form-group">
                <label>Atas Nama <span class="red-label">*</span> </label> <?= FormHelpPopOver('atas_nama_htb'); ?>
                <select name="ATAS_NAMA" id="ATAS_NAMA" class="form-control" required>
                    <option></option>
                    <option value="1" <?php echo $item->JENIS_BUKTI == '1' ? "selected" : ""; ?>>PN YANG BERSANGKUTAN</option>
                    <option value="2" <?php echo $item->JENIS_BUKTI == '2' ? "selected" : ""; ?>>PASANGAN / ANAK</option>
                    <option value="3" <?php echo $item->JENIS_BUKTI == '3' ? "selected" : ""; ?>>LAINNYA</option>
                </select>
            </div>
            <div class="form-group form-ket-lainnya" id="ket_lainnya_an_div">
                <label>Atas Nama Lainnya </label><span class="red-label">*</span> <?= FormHelpPopOver('keterangan_hb'); ?>
                <input type="text" name="ATAS_NAMA_LAINNYA" id="ATAS_NAMA_LAINNYA" placeholder="" class="form-control input_capital" value='<?php echo!$onAdd ? $item->ATAS_NAMA_LAINNYA : ''; ?>' />
                <small class="help-block notif-ket-lainnya" style="color:#a94442; display:none;"></small>
            </div>
            <div class="form-group form-asal">
                <label>Asal Usul Harta<span class="red-label">*</span> </label> <?= FormHelpPopOver('asal_usul_harta_htb'); ?>
                <table class="table" id="table-asal-usul">
                </table>
                <small class="help-block notif-asal" style="color:#a94442; display:none;">Pilih Asal Usul Harta</small>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Pemanfaatan<span class="red-label">*</span> </label> <?= FormHelpPopOver('pemanfaatan_htb'); ?>
                <table class="table" id="table-pemanfaatan">
                </table>
            </div>
            <div class="form-group">
                <label>Nilai Perolehan (Rp) <span class="red-label">*</span> </label> <?= FormHelpPopOver('nilai_perolehan_htb'); ?>
                <input type="text" name="NILAI_PEROLEHAN" id="NILAI_PEROLEHAN" placeholder="" class="form-control money"    required value='<?php echo!$onAdd ? $item->NILAI_PEROLEHAN : ''; ?>' />
            </div>
            <div class="form-group">
                <label>Nilai Estimasi Saat Pelaporan (Rp) <span class="red-label">*</span> </label> <?= FormHelpPopOver('nilai_estimasi_pelaporan_htb'); ?>
                <input type="text" name="NILAI_PELAPORAN" id="NILAI_PELAPORAN" placeholder="" class="form-control money" required value='<?php echo!$onAdd ? $item->NILAI_PELAPORAN : ''; ?>' />
            </div>
        </div> 
    </div><!--end of modal-->
    <div class="modal-footer">
        <button type="submit"  id="button-saved"  class="btn btn-primary btn-sm" ><i class="fa fa-save"></i> Simpan</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"> <i class="fa fa-remove"></i> Batal</button>
    </div>
</form>

<!---END HARTA TIDAK BERGERAK -->


<script type="text/javascript">

    function fun_AllowOnlyAmountAndDot(txt)
        {
            if(event.keyCode > 47 && event.keyCode < 58 || event.keyCode == 46)
            {
               var txtbx=document.getElementById(txt);
               var amount = document.getElementById(txt).value;
               var present=0;
               var count=0;

               if(amount.indexOf(".",present)||amount.indexOf(".",present+1));
               {
              // alert('0');
               }

              /*if(amount.length==2)
              {
                if(event.keyCode != 46)
                return false;
              }*/
               do
               {
               present=amount.indexOf(".",present);
               if(present!=-1)
                {
                 count++;
                 present++;
                 }
               }
               while(present!=-1);
               if(present==-1 && amount.length==0 && event.keyCode == 46)
               {
                    event.keyCode=0;
                    //alert("Wrong position of decimal point not  allowed !!");
                    return false;
               }

               if(count>=1 && event.keyCode == 46)
               {

                    event.keyCode=0;
                    //alert("Only one decimal point is allowed !!");
                    return false;
               }
               if(count==1)
               {
                var lastdigits=amount.substring(amount.indexOf(".")+1,amount.length);
                if(lastdigits.length>=2)
                            {
                              //alert("Two decimal places only allowed");
                              event.keyCode=0;
                              return false;
                              }
               }
                    return true;
            }
            else
            {
                    event.keyCode=0;
                    //alert("Only Numbers with dot allowed !!");
                    return false;
            }

        }

    var resetLuasTanahLuasBangunan = function () {
        $("#LUAS_BANGUNAN").attr("required", "required");
        $("#LUAS_TANAH").attr("required", "required");
    }

    var CheckLuasTanahLuasBangunan = function () {
        var lBangunan = checkLuasBangunan($("#LUAS_BANGUNAN"), "i");
        var lTanah = checkLuasBangunan($("#LUAS_TANAH"), "i");

        if (lBangunan || lTanah) {
            $("#LUAS_BANGUNAN").attr("required", false);
            $("#LUAS_TANAH").attr("required", false);
        }

    }

    var checkLuasBangunan = function (self, i) {
        var valLuasBangunan = $(self).val();

        if (isDefined(i)) {
            return !isEmpty(valLuasBangunan);
        }
    };

    var checkLuasTanah = function (self, i) {
        var valLuasTanah = $(self).val();

        if (isDefined(i)) {
            return !isEmpty(valLuasTanah);
        }
    };

    $(document).ready(function () {

        $('#NEGARA').val('1');

        $('.luar').hide();
        $('#NEGARA').change(function() {
            var val = $(this).val();
            if (val == '1') {
                $('#PROV').select2('data', null);
                GetKota(0);
                $('.luar').hide();
                $('.lokal').fadeIn('slow');
            } else {
                $('.lokal').hide();
                $('.luar').fadeIn('slow');
            }
        });

        $('#ID_NEGARA').select2({
            //placeholder: "Pilih Negara",
            allowClear: true,
            ajax: {
                url: '<?php echo base_url(); ?>portal/data_harta/getnegara',
                dataType: 'json',
                quietMillis: 100,
                data: function(term) {
                    return {
                        q: term, // search term
                    };
                },
                results: function(data) {
                    var myResults = [];
                    $.each(data, function(index, item) {
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

//        resetLuasTanahLuasBangunan();

//        CheckLuasTanahLuasBangunan();

        $("#LUAS_BANGUNAN").change(function () {
            var lBangunan = checkLuasBangunan(this, "i");
            var lTanah = checkLuasBangunan(this, "i");

            if (!(lBangunan || lTanah)) {
                resetLuasTanahLuasBangunan();
            }

        });
//
        $("#LUAS_TANAH").change(function () {
            var lBangunan = checkLuasBangunan(this, "i");
            var lTanah = checkLuasBangunan(this, "i");

            if (!(lBangunan || lTanah)) {
                resetLuasTanahLuasBangunan();
            }
        });

        var list_jenis_bukti = load_html('portal/data_harta/get_jenis_bukti_with_data/1', '<?php echo!$onAdd ? $item->JENIS_BUKTI : ''; ?>');
        $('#JENIS_BUKTI').html(list_jenis_bukti);

        var list_asal_usul = load_html('portal/data_harta/get_asal_usul_with_data', '<?php echo!$onAdd ? $item->ASAL_USUL : ''; ?>');
        $('#table-asal-usul').html('<tbody>' + list_asal_usul + '</tbody>');

        var list_pemanfaatan = load_html('portal/data_harta/get_pemanfaatan_with_data/1', '<?php echo!$onAdd ? $item->PEMANFAATAN : ''; ?>');
        $('#table-pemanfaatan').html('<tbody>' + list_pemanfaatan + '</tbody>');

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

        $('select').select2();
        var cfg_prov_select = {
        ajax: {
        placeholder: 'Provinsi',
                method: 'post',
                width: '100%',
                url: '<?php echo base_url(); ?>portal/filing/getprovinsi',
                dataType: 'json',
                data: function (term, page) {
                return {
                q: term, // search term
//                        pageLimit: 10,
//                        page: page
                };
                },
                cache: true,
                results: function (data, params) {
                var myResults = [], more = (params.page * 10) < data.total;
//                    $.each(data.province, function(index, item) {
                        $.each(data, function (index, item) {
                        myResults.push({
                        'id': item.id,
                                'text': item.text
                        });
                        });
                        return {
                        results: myResults,
//                        more: more
                        };
                },
                minimumInputLength: 3
        },
<?php if ($item->ID_PROV != NULL || $item->ID_PROV != ''): ?>
            initSelection: function (element, callback) {

            return callback({ id: '<?php echo $item->ID_PROV; ?>', text: '<?php echo strtoupper($item->PROV); ?>' });
            }
<?php endif; ?>
        };

        $('#PROV').select2(cfg_prov_select);

<?php if ($item->ID_PROV != NULL || $item->ID_PROV != ''): ?>
            $("#PROV").val('<?php echo $item->ID_PROV; ?>').trigger("change");
<?php endif; ?>


//        $('.money').mask('000.000.000.000.000.000', {reverse: true});

        $('.date').datepicker({
            formatDate: "DD/MM/YYYY",
//            maxDate: 'now'
        });

        var url = location.href.split('#')[1];
        url = url.split('?')[0] + '?upperli=li3&bottomli=li11';
        ng.formProcess($("#ajaxFormEdit"), 'update', url);
    });

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
        });
    }


</script>