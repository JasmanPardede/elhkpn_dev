<form role="form" id="ajaxFormEdit" action="index.php/ever/verification_edit/<?php echo $action; ?>" >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">FORM DATA SURAT BERHARGA</h4>
    </div>
    <div class="modal-body row">
        <div class="col-sm-6">
            <input type="hidden" name="ID" id="ID" value="<?php echo $ID; ?>" />
            <?php
            if (strlen($harta->NOMOR_REKENING) >= 32){
                $decrypt_norek = encrypt_username($harta->NOMOR_REKENING,'d');
            } else {
                $decrypt_norek = $harta->NOMOR_REKENING;
            }
            ?>
            <div class="form-group">
                <label>Nomor Rekening / No Nasabah <span class="red-label">*</span></label> <?= FormHelpPopOver('email'); ?>
                <input type="text" name="NOMOR_REKENING" id="NOMOR_REKENING" class="form-control input_capital" maxlength="32" required value="<?php echo !$onAdd ? $decrypt_norek : ''; ?>"  />
            </div> 

            <div class="form-group">
                <label>Jenis <span class="red-label">*</span></label> <?= FormHelpPopOver('jenis_sb'); ?>
                <select name="KODE_JENIS" id="KODE_JENIS" class="form-control" required></select>
            </div>
            <div class="form-group">
                <label>Atas Nama <span class="red-label">*</span> </label> <?= FormHelpPopOver('atas_nama_sb'); ?>
                <select name="ATAS_NAMA" id="ATAS_NAMA" class="form-control" required>
                    <option></option>
                    <option value="1" <?php echo $onAdd ? '' : ($harta->ATAS_NAMA == 1 ? "selected" : ""); ?> >PN YANG BERSANGKUTAN</option>  
                    <option value="2" <?php echo $onAdd ? '' : ($harta->ATAS_NAMA == 2 ? "selected" : ""); ?> >PASANGAN / ANAK</option>  
                    <option value="3" <?php echo $onAdd ? '' : ($harta->ATAS_NAMA == 3 ? "selected" : ""); ?> >LAINNYA</option>
                </select>
            </div>
            <div class="form-group form-ket-lainnya" id="ket_lainnya_an_div">
                <label>Atas Nama Lainnya </label><span class="red-label">*</span> <?= FormHelpPopOver('keterangan_hb'); ?>
                <input type="text" name="ATAS_NAMA_LAINNYA" id="ATAS_NAMA_LAINNYA" placeholder="" class="form-control input_capital" value='<?php echo !$onAdd ? $harta->ATAS_NAMA_LAINNYA : ''; ?>' />
                <small class="help-block notif-ket-lainnya" style="color:#a94442; display:none;"></small>
            </div>
            <div class="form-group">
                <label>Penerbit/Perusahaan <span class="red-label">*</span></label> <?= FormHelpPopOver('penerbit_sb'); ?>
                <input type="text" name="NAMA_PENERBIT" id="NAMA_PENERBIT" class="form-control input_capital" required value="<?php echo !$onAdd ? $harta->NAMA_PENERBIT : ''; ?>" />
            </div>
            <div class="form-group">
                <label>Custodian/Sekuritas <span class="red-label">*</span></label> <?= FormHelpPopOver('custodian_sb'); ?>
                <input type="text" name="CUSTODIAN" id="CUSTODIAN" class="form-control input_capital" required value="<?php echo !$onAdd ? $harta->CUSTODIAN : ''; ?>" />
            </div>
            <!-- /*ab menambahkan inputan tanggal -->
            <div class="form-group">
                <div class='input-group date' id='datetimepicker10'>
                    <label>Tahun Perolehan <span class="red-label">*</span></label> <?= FormHelpPopOver('thn_perolehan_hb'); ?>
                    <input type="text" name="TAHUN_PEROLEHAN_AWAL" id="TAHUN_PEROLEHAN_AWAL"  class="form-control year" required value='<?php echo!$onAdd ? $harta->TAHUN_PEROLEHAN_AWAL : ''; ?>'/>  
                </div>
            </div>                
<!--             <div class="form-group"> -->
<!--                 <label>File Bukti <span class="red-label">*</span> </label> <?= FormHelpPopOver('file_bukti'); ?>-->
<!--                 <?php echo form_dropdown('FILE_BUKTI', $file_list, !$onAdd ? $harta->FILE_BUKTI : '', "class=\"form-control\""); ?>-->
<!--             </div> -->
<!--             *) Anda dapat mengirimkan Bukti Dokumen/Rekening melalui POS atau diantarkan langsung ke Direktorat Pendaftaran dan Pemeriksaan LHKPN Komisi Pemberantasan Korupsi, Jl. Kuningan Persada Kav. 4, Setiabudi, Jakarta Selatan 12950. -->
        </div>
        <div class="col-sm-6">
            <div class="form-group form-asal">
                <label>Asal Usul Harta<span class="red-label">*</span> </label> <?= FormHelpPopOver('asal_usul_harta_sb'); ?>
                <table class="table" id="table-asal-usul" required></table>
                <small class="help-block notif-asal" style="color:#a94442; display:none;">Pilih Asal Usul Harta</small>
            </div>
            <div class="form-group">
                <label>Nilai Perolehan <span class="red-label">*</span></label> <?= FormHelpPopOver('nilai_perolehan_sb'); ?>
                <input type="text" name="NILAI_PEROLEHAN" id="NILAI_PEROLEHAN" class="form-control money" required value="<?php echo !$onAdd ? $harta->NILAI_PEROLEHAN : ''; ?>" />
            </div>
            <div class="form-group">
                <label>Nilai Estimasi Saat Pelaporan <span class="red-label">*</span></label> <?= FormHelpPopOver('nilai_estimasi_pelaporan_sb'); ?>
                <input type="text" name="NILAI_PELAPORAN" id="NILAI_PELAPORAN" class="form-control money" required value="<?php echo !$onAdd ? $harta->NILAI_PELAPORAN : ''; ?>" />
            </div> 
        </div>
    </div><!--end of modal-->
    <div class="modal-footer">
        <button type="submit" id="button-saved" class="btn btn-primary btn-sm" ><i class="fa fa-save"></i> Simpan</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i>  Batal</button>
    </div>
</form>

<!---END HARTA TIDAK BERGERAK -->
<script type="text/javascript" src="<?php echo base_url(); ?>/portal-assets/js/jquery.mask.min.js?v=<?php echo rand(4, 80); ?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/portal-assets/js/jquery.maskMoney.min.js?v=<?php echo rand(4, 80); ?>"></script>
<script type="text/javascript">
    
    $(document).ready(function () {

        $('#ket_lainnya_an_div').hide();

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
        
        var list_jenis_harta = load_html('portal/data_harta/get_jenis_harta_with_data/4', '<?php echo !$onAdd ? $harta->KODE_JENIS : ''; ?>');
        $('#KODE_JENIS').html(list_jenis_harta);
        
        var list_asal_usul = load_html('portal/data_harta/get_asal_usul_with_data', '<?php echo !$onAdd ? $harta->ASAL_USUL : ''; ?>');
        $('#table-asal-usul').html('<tbody>' + list_asal_usul + '</tbody>');

        var list_jenis_harta = load_html('portal/data_harta/get_jenis_harta_with_data/4', '<?php echo !$onAdd ? $harta->KODE_JENIS : ''; ?>');
        $('#KODE_JENIS').html(list_jenis_harta);
        
        var url = location.href.split('#')[1];
        url = url.split('?')[0]+'?upperli=li4&bottomli=3';
        ng.formProcess($("#ajaxFormEdit"), 'update', url);
        $('.money').mask('000.000.000.000.000.000', {reverse: true});

        $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });
        
        $('#TAHUN_PEROLEHAN_AWAL').datetimepicker({
            useCurrent: false, /*ab membuat nilai false pada default value di text box*/  
            viewMode: 'years',
            format: "YYYY",
            maxDate: 'now'
        }).on('dp.change dp.show',function(){ 
        });
 
        $('#TAHUN_PEROLEHAN_AWAL').datetimepicker('option', {maxDate: 'now'});          
    });
</script>