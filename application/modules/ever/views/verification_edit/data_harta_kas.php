<!---HARTA KAS -->
<form role="form" id="ajaxFormEdit" action="index.php/ever/verification_edit/<?php echo $action; ?>" >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">FORM DATA KAS DAN SETARA KAS</h4>
    </div>
    <div class="modal-body row">
        <div class="col-sm-4">
            <input type="hidden" name="ID" id="ID" value="<?php echo $ID; ?>" />
            <div class="form-group">
                <label>Jenis <span class="red-label">*</span></label> <?= FormHelpPopOver('jenis_ksk'); ?>
                <select class="form-control" id="KODE_JENIS" name="KODE_JENIS" required></select>  
            </div>
            <?php 
            if (strlen($harta->NAMA_BANK) >= 32){
                $decrypt_namabank = encrypt_username($harta->NAMA_BANK,'d');
            } else {
                $decrypt_namabank = $harta->NAMA_BANK;
            }
            if (strlen($harta->NOMOR_REKENING) >= 32){
                $decrypt_norek = encrypt_username($harta->NOMOR_REKENING,'d');
            } else {
                $decrypt_norek = $harta->NOMOR_REKENING;
            }
            ?>
            <div class="form-group">
                <label>Nama Bank/Lembaga Keuangan </label> <?= FormHelpPopOver('nama_bank_ksk'); ?>
                <input type="text" name="NAMA_BANK" id="NAMA_BANK" class="form-control input_capital" maxlength="32" value="<?php echo!$onAdd ? $decrypt_namabank : ''; ?>" />
            </div>
            <div class="form-group">
                <label>Nomor Rekening </label> <?= FormHelpPopOver('no_rek_ksk'); ?>
                <input type="text" name="NOMOR_REKENING" id="NOMOR_REKENING" class="form-control input_capital" maxlength="32" value="<?php echo!$onAdd ? $decrypt_norek : ''; ?>" />
            </div>
            <div class="form-group">
                <div class='input-group date' id='datetimepicker10'>
                    <label>Tahun Buka Rekening <span class="red-label">*</span></label> <?= FormHelpPopOver('thn_perolehan_hb'); ?>
                    <input type="text" name="TAHUN_BUKA_REKENING" id="TAHUN_BUKA_REKENING"  value="<?php echo!$onAdd ? $harta->TAHUN_BUKA_REKENING : ''; ?>" class="form-control year" required/> 
                </div> 
            </div>             
            
<!--             <div class="form-group"> -->
  <!--                 <label>File Bukti </label> <?= FormHelpPopOver('file_bukti'); ?> -->
  <!--                 <?php echo form_dropdown('FILE_BUKTI', $file_list, !$onAdd ? $harta->FILE_BUKTI : '', "class=\"form-control\""); ?> -->
<!--             </div> -->
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Atas Nama <span class="red-label">*</span> </label> <?= FormHelpPopOver('atas_nama_ksk'); ?>
                <select name="ATAS_NAMA_REKENING" id="ATAS_NAMA" class="form-control" required>
                    <option></option>
                    <option value="1" <?php echo $onAdd ? '' : ($harta->ATAS_NAMA_REKENING == '1' ? "selected" : ""); ?>>PN YANG BERSANGKUTAN</option>
                    <option value="2" <?php echo $onAdd ? '' : ($harta->ATAS_NAMA_REKENING == '2' ? "selected" : ""); ?>>PASANGAN / ANAK</option>
                    <option value="3" <?php echo $onAdd ? '' : ($harta->ATAS_NAMA_REKENING == '3' ? "selected" : ""); ?>>LAINNYA</option>
                </select>
            </div>
            <div class="form-group form-ket-lainnya" id="ket_lainnya_an_div">
                <label>Atas Nama Lainnya </label><span class="red-label">*</span> <?= FormHelpPopOver('keterangan_hb'); ?>
                <input type="text" name="ATAS_NAMA_LAINNYA" id="ATAS_NAMA_LAINNYA" placeholder="" class="form-control input_capital" value='<?php echo!$onAdd ? $harta->ATAS_NAMA_LAINNYA : ''; ?>' />
                <small class="help-block notif-ket-lainnya" style="color:#a94442; display:none;"></small>
            </div>
            <div class="form-group form-asal">
                <label>Asal Usul Harta<span class="red-label">*</span> </label> <?= FormHelpPopOver('asal_usul_harta_ksk'); ?>
                <table class="table" id="table-asal-usul" required></table>
                <small class="help-block notif-asal" style="color:#a94442; display:none;">Pilih Asal Usul Harta</small>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Jenis Mata Uang <span class="red-label">*</span></label> <?= FormHelpPopOver('jenis_mata_uang_ksk'); ?>
                <select class="form-control" id="MATA_UANG" name="MATA_UANG" required></select>
            </div>
            <div class="form-group">
                <label>Nilai Kurs <span class="red-label">*</span></label> <?= FormHelpPopOver('kurs_ksk'); ?>
                <input type="text" id="NILAI_KURS" name="NILAI_KURS" class="form-control money" required value="<?php echo!$onAdd ? $harta->NILAI_KURS : ''; ?>" />
            </div>
            <div class="form-group">
                <label>Nilai Saldo<span class="red-label">*</span></label> <?= FormHelpPopOver('nilai_saldo_ksk'); ?>
                <input type="text" id="NILAI_SALDO" name="NILAI_SALDO" class="form-control money" required value="<?php echo!$onAdd ? $harta->NILAI_SALDO : ''; ?>" />
            </div>
            <div class="form-group">
                <label>Ekuivalen ke kurs Rp.</label> <?= FormHelpPopOver('ekuivalen_ksk'); ?>
                <input type="text" id="NILAI_EQUIVALEN" name="NILAI_EQUIVALEN" class="form-control" readonly="true" value="<?php echo!$onAdd ? $harta->NILAI_EQUIVALEN : ''; ?>" >
            </div>
        </div>
    </div><!--end of modal-->
    <div class="modal-footer">
        <button type="submit" id="button-saved" class="btn btn-primary btn-sm" ><i class="fa fa-save"></i> Simpan</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
    </div>
</form>
<!---END HARTA TIDAK BERGERAK -->
<script type="text/javascript" src="<?php echo base_url(); ?>/portal-assets/js/jquery.mask.min.js?v=<?php echo rand(4, 80); ?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/portal-assets/js/jquery.maskMoney.min.js?v=<?php echo rand(4, 80); ?>"></script>

<script type="text/javascript">

    var remove_mask = function (i) {
        var s = i.split('.').join('');
        return s.split(',').join('.');
    };
    var add_mask = function(i){
        var s = i.split(',').join('--');
        s = s.split('.').join(',');
        return s.split('--').join('.');
    };
    var hitung_equivalent = function (s, k) {
        if (isDefined(s) && isDefined(k)) {
            s = remove_mask(s);
            k = remove_mask(k);
            s = parseInt(s) > 0 ? parseInt(s) : 0;
            k = parseInt(k) > 0 ? parseInt(k) : 0;
            var r = add_mask((s * k).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
            
            $("#NILAI_EQUIVALEN").val(r);
        }
    };

    $(document).ready(function () {
        var get_mata_uang = <?php if($harta->MATA_UANG){ echo $harta->MATA_UANG; }else{ echo 'null'; } ?>;
        var get_nilai_kurs = <?php if($harta->NILAI_KURS){ echo $harta->NILAI_KURS; }else{ echo 'null'; } ?>;
        var get_nilai_saldo = <?php if($harta->NILAI_SALDO){ echo $harta->NILAI_SALDO; }else{ echo 'null'; } ?>;
        var get_nilai_equivalen = <?php if($harta->NILAI_EQUIVALEN){ echo $harta->NILAI_EQUIVALEN; }else{ echo 'null'; } ?>;
    	if (get_mata_uang == 1) {
            if(get_nilai_kurs == null){
                var NILAI_KURS = parseInt(get_nilai_equivalen/get_nilai_saldo);
                $('#NILAI_KURS').val(NILAI_KURS);
            }
            $('#NILAI_KURS').attr('readonly', true);
        } else {

            $('#NILAI_KURS').attr('readonly', false);
        }

        if ($("#ATAS_NAMA").val() == '3') {
            $('#ket_lainnya_an_div').show();
            $('#ATAS_NAMA_LAINNYA').prop('required', true);
        } else {
            $('#ATAS_NAMA_LAINNYA').removeAttr('required');
            $('#ket_lainnya_an_div').hide();
        }

        $("#ATAS_NAMA").change(function() {
            $("#ATAS_NAMA_LAINNYA").val('');

            if ($("#ATAS_NAMA").val() == '3') {
                $('#ket_lainnya_an_div').show();
                $('#ATAS_NAMA_LAINNYA').prop('required', true);
            } else {
                $('#ATAS_NAMA_LAINNYA').removeAttr('required');
                $('#ket_lainnya_an_div').hide();
            }
        });



        $('#MATA_UANG').change(function() {
            if ($(this).val() == '1') {
                $('#NILAI_KURS').val('1');
                $('#NILAI_KURS').attr('readonly', true);
            } else {
                $('#NILAI_KURS').val('0');
                $('#NILAI_KURS').attr('readonly', false);
            }
            $('#NILAI_SALDO').maskMoney('unmasked')[0];
            $('#NILAI_KURS').maskMoney('unmasked')[0];
            var v_saldo = $('#NILAI_SALDO').val();
            var v_kurs = $('#NILAI_KURS').val();
            var NILAI_SALDO = parseFloat(v_saldo.replace(/\./g, ''));
            var NILAI_KURS = parseFloat(v_kurs.replace(/\./g, ''));
            var NILAI_EQUIVALEN = parseInt(NILAI_SALDO * NILAI_KURS) || 0;
            $('#NILAI_EQUIVALEN').val(numeral(NILAI_EQUIVALEN).format('0,0').replace(/,/g, '.'));
            CustomValidation();
        });

        $('#KODE_JENIS').change(function() {
            if ($(this).val() == 18) {
                $('#NAMA_BANK').attr('requried', false);
                $('#NOMOR_REKENING').attr('requried', false);
                $('#NAMA_BANK').attr('readonly', true);
                $('#NOMOR_REKENING').attr('readonly', true);
				$('#NAMA_BANK').val('-');
				$('#NOMOR_REKENING').val('-');
            } else {
                $('#NAMA_BANK').attr('requried', true);
                $('#NOMOR_REKENING').attr('requried', true);
                $('#NAMA_BANK').attr('readonly', false);
                $('#NOMOR_REKENING').attr('readonly', false)
            }
        });
        

        var list_jenis_harta = load_html('portal/data_harta/get_jenis_harta_with_data/5', '<?php echo!$onAdd ? $harta->KODE_JENIS : ''; ?>');
        $('#KODE_JENIS').html(list_jenis_harta);

        var list_mata_uang = load_html('portal/data_harta/get_mata_uang_with_data/', '<?php echo!$onAdd ? $harta->MATA_UANG : ''; ?>');
        $('#MATA_UANG').html(list_mata_uang);

        var list_jenis_bukti = load_html('portal/data_harta/get_jenis_bukti_with_data/1', '<?php echo!$onAdd ? $harta->JENIS_BUKTI : ''; ?>');
        $('#JENIS_BUKTI').html(list_jenis_bukti);

        var list_asal_usul = load_html('portal/data_harta/get_asal_usul_with_data', '<?php echo!$onAdd ? $harta->ASAL_USUL : ''; ?>');
        $('#table-asal-usul').html('<tbody>' + list_asal_usul + '</tbody>');

        $("#NILAI_SALDO, #NILAI_KURS").change(function (e) {
            e.preventDefault();
            hitung_equivalent($("#NILAI_SALDO").val(), $("#NILAI_KURS").val());
        });

        var url = location.href.split('#')[1];
        url = url.split('?')[0] + '?upperli=li4&bottomli=4';
        ng.formProcess($("#ajaxFormEdit"), 'update', url);
        hitung_equivalent($("#NILAI_SALDO").val(), $("#NILAI_KURS").val());
        $('.money').mask('000.000.000.000.000.000', {reverse: true});

        $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });
        
        $('#TAHUN_BUKA_REKENING').datetimepicker({
            useCurrent: false, /*ab membuat nilai false pada default value di text box*/ 
            viewMode: 'years',
            format: "YYYY",
            maxDate: 'now'
        }).on('dp.change dp.show',function(){ 
        });
 
        $('#TAHUN_BUKA_REKENING').datetimepicker('option', {maxDate: 'now'});        
    });

</script>