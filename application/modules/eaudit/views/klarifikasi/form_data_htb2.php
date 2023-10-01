
<form role="form" id="FormHarta">
    <div>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">FORM DATA HARTA TIDAK BERGERAK (TANAH DAN/ATAU BANGUNAN)</h4>
    </div>
    <div class="modal-body row">
        <div class="col-sm-4">
            <input type="hidden" name="is_load" value="<?php echo $onAdd ? $harta->Previous_ID : ''; ?>">
            <input type="hidden" name="ID_LHKPN" id="ID_LHKPN" value="<?php echo $ID_LHKPN; ?>" />
            <input type="hidden" name="ID" id="ID" value='<?php echo $ID; ?>' />
            <div class="form-group">
                <label>Negara Asal<span class="red-label">*</span> </label>
                <select name="NEGARA" id="NEGARA" class="form-control" required>
                    <option></option>
                    <option value="1" <?= ($harta->NEGARA == '1') ? 'selected' : ''; ?>>INDONESIA</option>
                    <option value="2" <?= ($harta->NEGARA == '2') ? 'selected' : ''; ?>>LUAR NEGERI</option>
                </select> 
            </div>
            <div class="form-group luar form-negara">
                <label>Negara <span class="red-label">*</span> </label>
                <input type="hidden" name="ID_NEGARA" id="ID_NEGARA" class="form-control"/>
            </div>
            <div class="form-group lokal form-prov">
                <label>Provinsi <span class="red-label">*</span> </label>
                <input type="hidden" name="PROV" id="PROV" class="form-control"/>
                <input type="hidden" name="PROV_NAME" id="PROV_NAME" class="form-control" value="<?php echo $onAdd ? $harta->PROV : ''; ?>" />
            </div>
            <div class="form-group lokal form-kota">
                <label>Kabupaten/Kota <span class="red-label">*</span> </label>
                <input type="text" name="KAB_KOT" id="KAB_KOT" class="form-control input_capital" value="<?php echo $onAdd ? $harta->KAB_KOT : ''; ?>" />
                <input type="hidden" name="KAB_KOT_NAME" id="KAB_KOT_NAME" class="form-control input_capital" value="<?php echo $onAdd ? $harta->KAB_KOT : ''; ?>" />
            </div>
            <div class="form-group lokal">
                <label>Kecamatan <span class="red-label">*</span> </label>
                <input type="text" id="KEC" name="KEC" class="form-control input_capital" required value='<?php echo $onAdd ? $harta->KEC : ''; ?>' />
            </div>
            <div class="form-group lokal">
                <label>Desa/Kelurahan <span class="red-label">*</span> </label>
                <input type="text" name="KEL" id="KEL" class="form-control input_capital " required value='<?php echo $onAdd ? $harta->KEL : ''; ?>' />
            </div>
            <div class="form-group alamat">
                <label class="lbl_alamat">Jalan <span class="red-label">*</span> </label>
                <textarea class="form-control input_capital" name="JALAN" id="JALAN" rows="2" required ><?php echo $onAdd ? $harta->JALAN : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label>Luas Tanah / Bangunan <span class="red-label">*</span> </label>
                <div style="overflow:hidden; clear:both;">
                    <input type="text" style="width:41%; display:inline; margin-right:5px;" name="LUAS_TANAH" id="LUAS_TANAH" onkeypress="return allowOnlyAmountAndComma(this.id);" class="form-control" <?php if ($onAdd) { echo strlen($harta->LUAS_TANAH) > 0 || strlen($harta->LUAS_BANGUNAN) > 0 ? "" : "required"; } ?> value='<?php echo $onAdd ? str_replace(".", ",", $harta->LUAS_TANAH) : '0'; ?>' required/> 
                    <label style="display:inline;">m<sup>2</sup></label>
                    <input type="text" style="width:41%; display:inline; margin-right:5px;" name="LUAS_BANGUNAN" id="LUAS_BANGUNAN" onkeypress="return allowOnlyAmountAndComma(this.id);" class="form-control" <?php if ($onAdd) { echo strlen($harta->LUAS_BANGUNAN) > 0 || strlen($harta->LUAS_TANAH) > 0 ? "" : "required"; } ?> value='<?php echo $onAdd ? str_replace(".", ",", $harta->LUAS_BANGUNAN) : '0'; ?>' required/>
                    <label style="display:inline;">m<sup>2</sup></label>
                </div>
                <small class="help-block-red"><i>Gunakan tanda koma (,) untuk angka desimal, maksimal 2 angka di belakang koma</i></small>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Jenis Bukti <span class="red-label">*</span> </label>
                <select name="JENIS_BUKTI" id="JENIS_BUKTI" class="form-control" required>
                </select>
            </div>
            <div class="form-group">
                <label>Nomor Bukti <span class="red-label">*</span> </label>
                <input type="text" name="NOMOR_BUKTI" id="NOMOR_BUKTI" placeholder="" class="form-control input_capital" required value='<?php echo $onAdd ? $harta->NOMOR_BUKTI : ''; ?>' />  
            </div>
            <!--<div class="form-group">
                <label>Atas Nama <span class="red-label">*</span> </label>
                <select name="ATAS_NAMA" id="ATAS_NAMA" class="form-control" required>
                    <option></option>
                    <option value="1" <?php if ($onAdd) { echo $harta->ATAS_NAMA == '1' ? "selected" : ""; } ?>>PN YANG BERSANGKUTAN</option>
                    <option value="2" <?php if ($onAdd) { echo $harta->ATAS_NAMA == '2' ? "selected" : ""; } ?>>PASANGAN / ANAK</option>
                    <option value="3" <?php if ($onAdd) { echo $harta->ATAS_NAMA == '3' ? "selected" : ""; } ?>>LAINNYA</option>
                </select>
            </div>
            <div class="form-group form-ket-lainnya" id="ket_lainnya_an_div">
                <label>Keterangan Lainnya </label><span class="red-label">*</span>
                <textarea class="form-control input_capital" name="ATAS_NAMA_LAINNYA" id="ATAS_NAMA_LAINNYA" rows="2" required><?php echo $onAdd ? $harta->KET_LAINNYA : ''; ?></textarea>
            </div>-->
			<div class="form-group form-atas-nama">
                <label>Atas Nama <span class="red-label">*</span> </label> <?= FormHelpPopOver('atas_nama_htb'); ?>
                <table class="table">  
                    <tbody>
                    	<tr>
                            <td><input type="checkbox" id="ATAS_NAMA_CHECK_PN" name="ATAS_NAMA[]" value="1"  /> PN YANG BERSANGKUTAN</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" id="ATAS_NAMA_CHECK_PASANGAN" name="ATAS_NAMA[]" value="2" /> PASANGAN / ANAK</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" id="ATAS_NAMA_CHECK_LAINNYA" name="ATAS_NAMA[]" value="3"  /> LAINNYA</td>
                        </tr>
                    </tbody> 
                </table>
                <small class="help-block notif-atas-nama" style="color:#a94442; display:none;">Pilih Atas Nama Harta</small>
            </div>            
            <div class="form-group form-pasangan-anak" id="ket_pasangan_anak_div">
                <label>Nama Pasangan / Anak </label><span class="red-label">*</span> <?= FormHelpPopOver('keterangan_pasangan_anak'); ?>
                <select class="selectpicker show-menu-arrow form-control" multiple name="PASANGAN_ANAK[]" id="KET_PASANGAN_ANAK" required>
                </select>
                <!-- <small class="help-block notif-pasangan-anak" style="color:#a94442; display:none;">Data ini wajib di isi</small> -->
            </div>
            <div class="form-group form-ket-lainnya" id="ket_lainnya_an_div">
                <label>Nama Orang Lain / Lainnya </label><span class="red-label">*</span> <?= FormHelpPopOver('keterangan_hb'); ?>
                <textarea class="form-control input_capital" name="KET_LAINNYA_AN" id="KET_LAINNYA_AN" rows="2" required></textarea>
                <small class="help-block notif-ket-lainnya" style="color:#a94442; display:none;"></small>
            </div>
            <!--<div class="form-group form-asal">
                <label>Asal Usul Harta<span class="red-label">*</span> </label>
                <table class="table" id="table-asal-usul" required>   
                </table>
            </div>-->
			<div class="form-group form-asal">
                <label>Asal Usul Harta<span class="red-label">*</span> </label> <?= FormHelpPopOver('asal_usul_harta_htb'); ?>
                <table class="table" id="table-asal-usul" required>   
                </table>
                <small class="help-block notif-asal" style="color:#a94442; display:none;">Pilih Asal Usul Harta</small>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Pemanfaatan<span class="red-label">*</span> </label> 
                <table class="table" id="table-pemanfaatan" required>
                </table>
            </div>
            <div class="form-group">
                <label>Nilai Perolehan (Rp) <span class="red-label">*</span> </label>
                <input type="text" name="NILAI_PEROLEHAN" id="NILAI_PEROLEHAN" placeholder="" class="form-control money" required value='<?php echo $onAdd ? $harta->NILAI_PEROLEHAN : ''; ?>' <?php //echo $is_load ? 'readonly' : '' ?> />
            </div>
            <div class="form-group">
                <label>Nilai Pelaporan (Rp) <span class="red-label">*</span> </label>
                <input type="text" name="NILAI_PELAPORAN_OLD" id="NILAI_PELAPORAN_OLD" placeholder="" class="form-control money" required value='<?php echo $onAdd ? $harta->NILAI_PELAPORAN_OLD : '0'; ?>' <?php echo $is_load ? 'readonly' : 'readonly' ?> />
            </div>
            <div class="form-group">
                <label>Nilai Klarifikasi (Rp) <span class="red-label">*</span> </label>
                <input type="text" name="NILAI_PELAPORAN" id="NILAI_PELAPORAN" placeholder="" class="form-control money" required value='<?php echo $onAdd ? $harta->NILAI_PELAPORAN : ''; ?>' />
            </div>
			<div class="form-group form-tahun-perolehan">
                <div class='input-group date' id='datetimepicker10'>
                    <label>Tahun Perolehan <span class="red-label">*</span></label> <?= FormHelpPopOver('thn_perolehan_hb'); ?>
                    <input type="text" name="TAHUN_PEROLEHAN_AWAL" id="TAHUN_PEROLEHAN_AWAL" onkeydown="return false" class="form-control year" value='<?php echo $onAdd ? $harta->TAHUN_PEROLEHAN_AWAL : ''; ?>' autocomplete="off" required />  
                    <!-- <small class="help-block notif-tahun-perolehan" style="color:#a94442; display:none;">Data ini wajib di isi</small>    -->
                </div>
            </div> 
            <div class="form-group form-keterangan-periksa">
                <label>Keterangan Pemeriksaan<span class="red-label">*</span> </label>
                <textarea class="form-control" name="KET_PEMERIKSAAN" id="KET_PEMERIKSAAN" rows="2" placeholder="Keterangan" required><?php echo $onAdd ? $harta->KET_PEMERIKSAAN : ''; ?></textarea>
            </div>
        </div> 
    </div><!--end of modal-->
    <div class="modal-footer">
        <button type="submit"  id="button-saved"  class="btn btn-primary btn-sm" ><i class="fa fa-save"></i> Simpan</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"> <i class="fa fa-remove"></i> Batal</button>
    </div>
</form>

<form role="form" id="formAsalUsul">
    <div >
        <h4 class="modal-title" id="asal_usul_title"></h4>
    </div>
    <div class="modal-body row">
        <div class="form-group">
            <input type="hidden" id="id_checkbox"/>
            <label>Tanggal Transaksi <span class="red-label">*</span></label> <?= FormHelpPopOver('tgl_transaksi_popup'); ?>
            <div class="input-group date">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </div>
                <input type="text" name="tgl_transaksi_asal"  id="tgl_transaksi_asal" required placeholder="( tgl/bulan/tahun )" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <label id="label-info">Nilai</label> <?= FormHelpPopOver('nilai_popup'); ?>
            <input type="text" name="besar_nilai_asal" id="besar_nilai_asal"  class="form-control money" required/>
        </div>
        <div class="form-group">
            <label>Keterangan </label> <?= FormHelpPopOver('keterangan_popup'); ?>
            <textarea class="form-control" name="keterangan_asal" id="keterangan_asal" rows="2" ></textarea>
        </div>
        <div class="form-group">
            <label><strong>Pihak Kedua</strong></label> 
        </div>
        <div class="form-group">
            <label>Nama <span class="red-label">*</span></label> <?= FormHelpPopOver('nama_pihak_kedua_popup'); ?>
            <input type="text" name="nama_pihak2_asal" id="nama_pihak2_asal" required  class="form-control input_capital" />
        </div>
        <div class="form-group">
            <label>Alamat <span class="red-label">*</span></label> <?= FormHelpPopOver('alamat_pihak_kedua_popup'); ?>
            <textarea class="form-control" name="alamat2_asal" id="alamat2_asal" rows="2" required></textarea>
        </div>
        
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary btn-sm" ><i class="fa fa-save"></i> Simpan</button>
        <button type="button" class="btn btn-danger btn-sm" onclick="Cancelparent()"><i class="fa fa-remove"></i> Batal</button>
    </div>
</form>



<script type="text/javascript">

// Opera 8.0+
var OPERA = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
// Firefox 1.0+
var FIREFOX = typeof InstallTrigger !== 'undefined';
// At least Safari 3+: "[object HTMLElementConstructor]"
var SAFARI = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
// Internet Explorer 6-11
var IE = /*@cc_on!@*/false || !!document.documentMode;
// Edge 20+
var EDGE = !IE && !!window.StyleMedia;
// Chrome 1+
var CHROME = !!window.chrome && !!window.chrome.webstore;
// Blink engine detection
var BLINK = (CHROME || OPERA) && !!window.CSS;

var TIMEOUT_BROWSER = 0;

if (OPERA) {
    TIMEOUT_BROWSER = 50 / 100;
} else if (FIREFOX) {
    TIMEOUT_BROWSER = 10 / 100;
} else if (SAFARI) {
    TIMEOUT_BROWSER = 50 / 100;
} else if (IE) {
    TIMEOUT_BROWSER = 50 / 100;
} else if (EDGE) {
    TIMEOUT_BROWSER = 50 / 100;
} else if (CHROME) {
    TIMEOUT_BROWSER = 50 / 100;
} else if (BLINK) {
    TIMEOUT_BROWSER = 50 / 100;
} else {
    TIMEOUT_BROWSER = 50 / 100;
}

    function allowOnlyAmountAndComma(txt)
    {
        if(event.keyCode > 47 && event.keyCode < 58 || event.keyCode == 44)
        {
            var txtbx=document.getElementById(txt);
            var amount = document.getElementById(txt).value;
            var present=0;
            var count=0;

            if(amount.indexOf(",",present)||amount.indexOf(",",present+1));
            {
            }

            do
            {
                present=amount.indexOf(",",present);
                if(present!=-1)
                {
                    count++;
                    present++;
                }
            }
            while(present!=-1);

            if(present==-1 && amount.length==0 && event.keyCode == 44)
            {
                event.keyCode=0;
                return false;
            }

            if(count>=1 && event.keyCode == 44)
            {
                event.keyCode=0;
                return false;
            }

            if(count==1)
            {
                var lastdigits=amount.substring(amount.indexOf(",")+1,amount.length);
                if(lastdigits.length>=2)
                {
                    event.keyCode=0;
                    return false;
                }
            }
            return true;
        }

        else
        {
            event.keyCode=0;
            return false;
        }

    }

    $(document).ready(function() {

        $('#ID_NEGARA').change(function(){
            var ID_NEGARA = $('#ID_NEGARA').val();
            if (ID_NEGARA != '') { 
                $('.form-negara').removeClass('has-error').addClass('has-success');
                $('#button-saved').prop('disabled', false);
            } 
        });

        $('#PROV').change(function(){
            $('#KAB_KOT').val('');
            var KAB_KOT = $('#KAB_KOT').val();
            if (KAB_KOT == '') { 
                $('.notif-kota').show();
                $('.form-prov').removeClass('has-error').addClass('has-success');
                $('.form-kota').removeClass('has-success').addClass('has-error');
                $('#button-saved').prop('disabled', true);
            } 
        });

        $('#KAB_KOT').change(function(){
            var KAB_KOT = $('#KAB_KOT').val();
            if (KAB_KOT != '') { 
                $('.notif-kota').show();
                $('.form-kota').removeClass('has-error').addClass('has-success');
                $('#button-saved').prop('disabled', false);
            } 
        });

        var tipe_negara = $('#NEGARA').val();
        if(tipe_negara == '2'){
            $('.lokal').hide();
            $('.form-negara').show();
        }else{
            $('.lokal').show();
            $('.form-negara').hide();
        }

        $('#ket_lainnya_an_div').hide();
        $('#ket_pasangan_anak_div').hide();

        $('#ATAS_NAMA_CHECK_PN').click(function(){
            if($(this).is(':checked')){

            } else {
				
            }
        });
        $('#ATAS_NAMA_CHECK_PASANGAN').click(function(){
            if($(this).is(':checked')){
            	$('#ket_pasangan_anak_div').show();
            	$('select').select2();
            } else {
            	$('#ket_pasangan_anak_div').hide();
            	$('#ket_pasangan_anak_div').removeClass('has-error').addClass('has-success');
            }
        });
        $('#ATAS_NAMA_CHECK_LAINNYA').click(function(){
            if($(this).is(':checked')){
            	$('#ket_lainnya_an_div').show();
            } else {
            	$('#ket_lainnya_an_div').hide();
            	$('#ket_lainnya_an_div').removeClass('has-error').addClass('has-success');
            }
        });
		
		$("#ATAS_NAMA").change(function() {
            $("#KET_LAINNYA_AN").val('');
            $("#KET_PASANGAN_ANAK").val('');
            var isKeteranganLainnyaExists = document.getElementById("KET_LAINNYA_AN");
            var isKeteranganPasanganAnakExists = document.getElementById("KET_PASANGAN_ANAK");
            if ($("#ATAS_NAMA").val() == '3') {
                $('#ket_lainnya_an_div').show();
                $('#ket_pasangan_anak_div').hide();
                $('#ket_pasangan_anak_div').removeClass('has-error').addClass('has-success');
                $('#FormHarta').bootstrapValidator('addField', isKeteranganLainnyaExists);
                $('#FormHarta').bootstrapValidator('removeField', isKeteranganPasanganAnakExists);
            } else if($("#ATAS_NAMA").val() == '2') {
                $('#ket_pasangan_anak_div').show();

                $('#ket_lainnya_an_div').hide();
                $('.notif-ket-lainnya').hide();
                $('#ket_lainnya_an_div').removeClass('has-error').addClass('has-success');
                $('#FormHarta').bootstrapValidator('removeField', isKeteranganLainnyaExists);
                $('#FormHarta').bootstrapValidator('addField', isKeteranganPasanganAnakExists);
            }else {
            	$('#ket_pasangan_anak_div').hide();
                $('#ket_pasangan_anak_div').removeClass('has-error').addClass('has-success');
                $('#ket_lainnya_an_div').hide();
                $('.notif-ket-lainnya').hide();
                $('#ket_lainnya_an_div').removeClass('has-error').addClass('has-success');
                $('#FormHarta').bootstrapValidator('removeField', isKeteranganLainnyaExists);
                $('#FormHarta').bootstrapValidator('removeField', isKeteranganPasanganAnakExists);
            }
        });
		$('#TAHUN_PEROLEHAN_AWAL').datetimepicker({
              useCurrent: false, /*ab membuat nilai false pada default value di text box*/
              viewMode: 'years',
              format: "YYYY",
              maxDate: 'now'
          }).on('dp.change dp.show',function(){ 
                $('#FormHarta').bootstrapValidator('revalidateField', 'TAHUN_PEROLEHAN_AWAL'); 
          });
		  
        $('.input_capital').keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });
		var ID = $('#ID').val();
		var ID_LHKPN = $('#ID_LHKPN').val();
		
        var list_jenis_bukti = load_html('eaudit/klarifikasi/get_jenis_bukti_with_data/1');
        $('#JENIS_BUKTI').html(list_jenis_bukti);
        var id_jenis_bukti = '<?php echo $harta->JENIS_BUKTI ?>';
        $('#JENIS_BUKTI').val(id_jenis_bukti).trigger('change');

        var list_pasangan_anak = load_html('eaudit/klarifikasi/get_pasangan_anak_by_id/1/'+ ID_LHKPN); 
        $('#KET_PASANGAN_ANAK').html(list_pasangan_anak);
        // $('#JENIS_BUKTI').val(1510899).trigger('change');

        var state_atas_nama = '<?php echo $harta->ATAS_NAMA ?>';
        if (state_atas_nama.indexOf("1") >= 0) {
            $('#ATAS_NAMA_CHECK_PN').prop('checked', true);
        }
    
        if (state_atas_nama.indexOf("2") >= 0) {
            $('#ket_pasangan_anak_div').show();
            $('#ATAS_NAMA_CHECK_PASANGAN').prop('checked', true);
            var state_pasangan_anak = '<?php echo $harta->PASANGAN_ANAK ?>';
            if(state_pasangan_anak!==null){
                $.each(state_pasangan_anak.split(","), function(i,e){
                    $('#KET_PASANGAN_ANAK option[value="'+e+'"]').prop('selected', true); 
                }); 
            } 
        }
        $('select').select2(); 
        if (state_atas_nama.indexOf("3") >= 0) {
            var state_ket_lainnya = '<?php echo $harta->KET_LAINNYA ?>';
            $('#ket_lainnya_an_div').show();
            $('#KET_LAINNYA_AN').val(state_ket_lainnya);
            $('#ATAS_NAMA_CHECK_LAINNYA').prop('checked', true);
        }
		
        var list_asal_usul = load_html('eaudit/klarifikasi/get_asal_usul_with_data_p/1', '{"asal_usul" : [<?php echo $onAdd ? $harta->ASAL_USUL : ''; ?>],  "ID" : <?php echo $harta->ID ?>}' );
        $('#table-asal-usul').html('<tbody>' + list_asal_usul + '</tbody>');
        var state_asal_usul = '<?php echo $harta->ASAL_USUL ?>';
        if (state_asal_usul.indexOf("1") >= 0) {
            $('#pilih-hasil-sendiri').prop('checked', true);
        }
        if (state_asal_usul.indexOf("2") >= 0) {
            $('#pilih-warisan').prop('checked', true);
        }
        if (state_asal_usul.indexOf("3") >= 0) {
            $('#pilih-hibah-dengan-akta').prop('checked', true);
        }
        if (state_asal_usul.indexOf("4") >= 0) {
            $('#pilih-hibah-tanpa-akta').prop('checked', true);
        }
        if (state_asal_usul.indexOf("5") >= 0) {
            $('#pilih-hadiah').prop('checked', true);
        }
        if (state_asal_usul.indexOf("6") >= 0) {
            $('#pilih-lainnya').prop('checked', true);
        }
        // $('#pilih-hasil-sendiri').prop('checked', true);
        // $('#pilih-warisan').prop('checked', true);


        var list_pemanfaatan = load_html('eaudit/klarifikasi/get_pemanfaatan_with_data/1', '<?php echo $onAdd ? $harta->PEMANFAATAN : ''; ?>');
        $('#table-pemanfaatan').html('<tbody>' + list_pemanfaatan + '</tbody>');
        var state_pemanfaatan = '<?php echo $harta->PEMANFAATAN ?>';
        if (state_pemanfaatan.indexOf("1") >= 0) {
            $('.set-pemanfaatan-1').prop('checked', true);
        }
        if (state_pemanfaatan.indexOf("2") >= 0) {
            $('.set-pemanfaatan-2').prop('checked', true);
        }
        if (state_pemanfaatan.indexOf("3") >= 0) {
            $('.set-pemanfaatan-3').prop('checked', true);
        }
        if (state_pemanfaatan.indexOf("4") >= 0) {
            $('.set-pemanfaatan-4').prop('checked', true);
        }
        if (state_pemanfaatan.indexOf("5") >= 0) {
            $('.set-pemanfaatan-5').prop('checked', true);
        }
        if (state_pemanfaatan.indexOf("6") >= 0) {
            $('.set-pemanfaatan-6').prop('checked', true);
        }
        if (state_pemanfaatan.indexOf("7") >= 0) {
            $('.set-pemanfaatan-7').prop('checked', true);
        }
        if (state_pemanfaatan.indexOf("8") >= 0) {
            $('.set-pemanfaatan-8').prop('checked', true);
        }

		$("#KET_PASANGAN_ANAK").change(function() {
			CustomValidation();
		});
		
		$('#tgl_transaksi_asal').datetimepicker({
            format: "DD/MM/YYYY",
//            maxDate: TGL_LAPOR
        }); 

		$('#formAsalUsul').hide();
        $('#myModal .pilih').click(function() {
            var id = $(this).attr('id'); 
            var ID = $('#ID').val(); 
            var title = GetTitle(id); 
            if ($(this).is(':checked') && $(this).hasClass("order-1")) { 
                $('#formAsalUsul')[0].reset();
                view(id, title);
                $('.valid-asal').hide();
            } else { 
                $('#result-' + id).html('');
                $('#view-' + id).html('');
                $('.valid-asal').show();
            }
            CustomValidation();
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
		
        $('#formAsalUsul').submit(function() {
            $('.input').maskMoney('unmasked')[0];
            var tgl_transaksi_asal = $('#tgl_transaksi_asal').val();
            var besar_nilai_asal = $('#besar_nilai_asal').val();
            var keterangan_asal = $('#keterangan_asal').val();
            var nama_pihak2_asal = $('#nama_pihak2_asal').val();
            var alamat2_asal = $('#alamat2_asal').val();
            var title = $('#asal_usul_title').text();

            $('#formAsalUsul').fadeOut('fast', function() {
                $('#FormHarta').fadeIn('fast', function() {
                    $('#myModal .modal-content').animate({
                        'width': '100%',
                        'margin-left': '0'
                    });
                    var id_checkbox = $('#id_checkbox').val();
                    $('#view-' + id_checkbox).html('<a href="javascript:void(0)" id="view-to-' + id_checkbox + '" class="btn btn-view btn-xs btn-info">Lihat</a>');
                    $('#result-' + id_checkbox).html('<label class="label label-primary">' + besar_nilai_asal + '</label>');
                    $('#asal-tgl_transaksi-' + id_checkbox).val(tgl_transaksi_asal);
                    $('#asal-besar_nilai-' + id_checkbox).val(besar_nilai_asal);
                    $('#asal-keterangan-' + id_checkbox).val(keterangan_asal);
                    $('#asal-pihak2_nama-' + id_checkbox).val(nama_pihak2_asal);
                    $('#asal-pihak2_alamat-' + id_checkbox).val(alamat2_asal);
                    $('.valid-asal').hide();
                    CustomValidation();
                });
            });
            return false;
        });


        
        $('#ModalHarta .modal-dialog').css({
            'margin-top': '5px',
            'width': '100%',
            'height': '100%'
        });

        $('#ModalHarta .form-group').css({
            'margin-bottom': '7.5px'
        });

        $('#ModalHarta .modal-footer').css({
            'padding': '10px'
        });
		
		
		$('#FormHarta').bootstrapValidator({
            fields: {
                'PEMANFAATAN[]': {
                    validators: {
                        notEmpty: {
                            message: 'Pilih Pemanfaatan Harta'
                        }
                    }
                },
                'KET_LAINNYA_AN': {
                    validators: {
                        notEmpty: {
                            message: 'Data ini wajib di isi'
                        }
                    }
                }

            }
        }).on('error.form.bv', function(e, data) {
            CustomValidation();
            onChangeAtasNama();
            AsalUsulValidation();
        }).on('success.form.bv', function(e, data) {
            CustomValidation();
            onChangeAtasNama();
            AsalUsulValidation();

            var is_atas_nama = $('input[name="ATAS_NAMA[]"]').is(':checked');

            if(is_atas_nama ==  false){  
                $('#button-saved').prop('disabled', true);
                $('.notif-atas-nama').show();
                $('.form-atas-nama').removeClass('has-success').addClass('has-error');
                return false;
            }else{ 
                $('.notif-atas-nama').hide();
                $('.form-atas-nama').removeClass('has-error').addClass('has-success');
                $('#button-saved').prop('disabled', false);
            }

            var pilih_asal_usul = $("[name='ASAL_USUL[]']:checked").is(':checked');
            if (pilih_asal_usul ==  false) {
                $('.notif-asal').show();
                $('#button-saved').prop('disabled', true);
                return false;
            } else {
                $('.notif-asal').hide();
                $('#button-saved').prop('disabled', false);
            }


            var action = "/eaudit/klarifikasi/<?php echo $action; ?>";
           
            if(e.type == 'success'){
                do_submit('#FormHarta', action, 'Data Berhasil Disimpan', '#myModal');
            };
            
            /*
            var error = $('.has-error').length;
            var asal_usul = $('.notif-asal').is(":visible");
            if (error == 0 && !asal_usul) {
                e.preventDefault();
                $('.input').maskMoney('unmasked')[0];
                var ID = $('#ID').val();
                var text;
                if (ID == '') {
                    text = 'Data Harta Tanah/Bangunan berhasil ditambahkan';
                } else {
                    text = 'Data Harta Tanah/Bangunan berhasil diperbaharui';
                }
                if ((($('#status_harta').val() == '3' && $('#is_load_harta').val() == '1') || $('#status_harta').val() == '2' || $('#status_harta').val() == '1') && $('#is_pelepasan_harta').val() !== '1'){
                    if ($('#NILAI_PELAPORAN').val() == 0 || $('#NILAI_PELAPORAN').val() == '0'){
                        notif('Isian nilai estimasi pelaporan harta Anda 0, apabila anda bermaksud menghapus/melepas harta silakan gunakan tombol lepas');
                    }else{
                        do_submit('#FormHarta', '/eaudit/klarifikasi/do_update_htb/edit', text, '#myModal');
                    }
                } else if ($('#NILAI_PELAPORAN').val() == 0 || $('#NILAI_PELAPORAN').val() == '0'){
                    notif('Maaf, Isian nilai estimasi pelaporan harta Anda 0');
                } else {
                    do_submit('#FormHarta', '/eaudit/klarifikasi/do_update_htb/edit', text, '#myModal');
                }
                // $('#TableTanah').DataTable().ajax.reload(null,false);
				//location.reload();
            }*/
            return false;
        }).on('added.field.fv', function(e, data) {
            // data.field   --> The field name
            // data.element --> The new field element
            // data.options --> The new field options

            if (data.field === 'KET_LAINNYA_AN') {
                if ($('#FormHarta').find(':visible[name="KET_LAINNYA_AN"').val() == '') {
//                    $('#surveyForm').find('.addButton').attr('disabled', 'disabled');
                }
            }
        }).on('removed.field.fv', function(e, data) { // Called after removing the field
            if (data.field === 'option[]') {
                if ($('#FormHarta').find(':visible[name="KET_LAINNYA_AN"').val() == '') {
//                    $('#surveyForm').find('.addButton').removeAttr('disabled');
                }
            }
        });
	 
        $('#ID_LHKPN').val(ID_LHKPN);
        // $('#NEGARA').val('1');
        // if ($("#NEGARA").val() == '1') {
        //     $('.luar').hide();
        // } else {
        //     $('.lokal').hide();
        // }

        $('#NEGARA').change(function() {
            var val = $(this).val();
            if (val == '1') {
              
                $('#PROV').val('');
                $('#KAB_KOT').val(''); 
                $("[name='ASAL_USUL[]']:checked").attr('checked', false);
                $('#view-pilih-warisan').hide();
                $('#result-pilih-warisan').hide();

                $('#PROV').select2('data', null);
                GetKota(0);
                $('.luar').hide();
                $('.lokal').fadeIn('slow');
            } else {
                $('#ID_NEGARA').select2("data", null);
                $("[name='ASAL_USUL[]']:checked").attr('checked', false);
                $('#view-pilih-warisan').hide();
                $('#result-pilih-warisan').hide();

                $('.lokal').hide();
                $('.luar').fadeIn('slow');
                $('#PROV').prop('required', false);
                $('#KAB_KOT').prop('required', false);
                $('#KEC').prop('required', false);
                $('#KEL').prop('required', false);
            }
        });

        //$('select').select2();

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

        $('#PROV').select2({
            allowClear: true,
            ajax: {
                url: '<?php echo base_url(); ?>portal/filing/getprovinsi',
                dataType: 'json',
                quietMillis: 100,
                data: function(term, page) {
                    return {
                        q: term, // search term
//                        pageLimit: 10,
//                        page: page
                    };
                },
                results: function(data, page) {
                    var myResults = [], more = (page * 10) < data.total;
//                    $.each(data.province, function(index, item) {
                    $.each(data, function(index, item) {
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
            }
        }).on("change", function(e) {
            var value = $('#PROV').val();
            var data = $('#PROV').select2('data');
            if(data) {
              $('#PROV_NAME').val(data.text);
            }

            if (isDefined(value) && value != '') {
                GetKota(value);
            }
        });
        <?php if ($onAdd): ?> 
			$('#ID_NEGARA').select2("data", {id: '<?php echo $js->ID; ?>', text: '<?php echo $js->NAMA_NEGARA; ?>'});
            $('#PROV').select2("data", {id: '<?php echo $js->ID_PROV; ?>', text: '<?php echo $js->NAME; ?>'});
            GetKota('<?php echo $js->ID_PROV ?>');
            $('#KAB_KOT').select2("data", {id: '<?php echo $js->ID_KAB; ?>', text: '<?php echo $js->NAME_KAB; ?>'});
        <?php endif ?>
        // var url = location.href.split('#')[1];
        // url = url.split('?')[0] + '?upperli=li3&bottomli=lii0';
        // ng.formProcess($("#FormHarta"), 'update', url);
        $('.money').mask('000.000.000.000.000.000', {reverse: true});
        $(".input").maskMoney({prefix: '', thousands: '.', decimal: ',', precision: 0});
        $('#ModalHarta').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
				
    });

    function onChangeAtasNama(){
        $('input[name="ATAS_NAMA[]"]').on('change', function(){
                
            var is_atas_nama = $('input[name="ATAS_NAMA[]"]').is(':checked');
            if(is_atas_nama ==  false){  
                $('#button-saved').prop('disabled', true);
                $('.notif-atas-nama').show();
                $('.form-atas-nama').removeClass('has-success').addClass('has-error');
                return false;
            }else{ 
                $('.notif-atas-nama').hide();
                $('.form-atas-nama').removeClass('has-error').addClass('has-success');
                $('#button-saved').prop('disabled', false);
            }
        });
    }
	
	function notif(t, at) {
		if (isDefined(at)) {
			t = t + at;
		}
		$('#ModalWarning #notif-text').text(t);
		$('#ModalWarning').modal('show');
	}

	function stf(t) {
		if (isDefined(t)) {
			setTimeout(function () {
				Loading('hide');
			}, parseInt(t * TIMEOUT_BROWSER));
		}
	}

	function success(t) {
		$('#ModalSuccess #notif-text').text(t);
        $('#ModalSuccess').modal('show');        
	}
	
	function do_submit(form, url, text, modal) {
    if (modal) {
        $(modal).modal('hide');
    }
    $("#modal-success").removeClass("modal-lg"); 
    $("#modal-warning").removeClass("modal-lg"); 

    var ajaxTime = new Date().getTime();
    var formData = new FormData($(form)[0]);
    $.ajax({
        url: base_url + 'index.php' + url,
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'html',
        beforeSend: function () {
            Loading('show');
        },
        complete: function () {
            Loading('hide');
        },
        success: function (data) {
            if (data == 1) {
                success(text); 
            } else {
                notif('Mohon Maaf, Ada kesalahan pada system !!');
            }
            var totalTime = new Date().getTime() - ajaxTime; 
            stf(totalTime);
        },
        error: function (jqXHR, exception) {
            ajax_error_xhr(jqXHR, exception);
        },
    });
	}
	
	function Loading(t) {
		var m = document.getElementById('loader_area');
		if (t == 'hide') {
			m.style.display = "none";
		} else {
			m.style.display = "block";
		}
	}
	
	function Cancelparent() {
        $('#formAsalUsul').fadeOut('fast', function () {
            $('#FormHarta').fadeIn('fast', function () {
                $('#myModal .modal-content').animate({
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
	
	function view(id, title) {
        $('#FormHarta').hide();
        $('#formAsalUsul').fadeIn('fast', function () {
            $('#asal_usul_title').text('ASAL USUL ' + title.toUpperCase());
            $('#label-info').html('Besar Nilai (Rp) <span class="red-label">*</span>');
            $('#formAsalUsul #id_checkbox').val(id);
            $('#myModal .modal-content').animate({
                'width': '50%',
                'margin-left': '25%'
            });
        });
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
	
	function CustomValidation() {

        var keterangan_periksa = $('#KET_PEMERIKSAAN').val();
        if(keterangan_periksa == ''){
            $('.form-keterangan-periksa').removeClass('has-success').addClass('has-error');
            $('#button-saved').prop('disabled', true);
        }else{
            $('.form-keterangan-periksa').removeClass('has-error').addClass('has-success');
            $('#button-saved').prop('disabled', false);
        }

        var is_check_pasangan_anak =  $("#ATAS_NAMA_CHECK_PASANGAN").is(':checked');
        if(is_check_pasangan_anak){
            var check_pasangan_anak = $('#KET_PASANGAN_ANAK').val();
            if(check_pasangan_anak == null){
                $('.notif-pasangan-anak').show();
                $('.form-pasangan-anak').removeClass('has-success').addClass('has-error');
                // $('#button-saved').prop('disabled', true);
                return;
            }else{
                $('.notif-pasangan-anak').hide();
                $('.form-pasangan-anak').removeClass('has-error').addClass('has-success');
                $('#button-saved').prop('disabled', false);
            }
        }else{
            $('.notif-pasangan-anak').hide();
            $('.form-pasangan-anak').removeClass('has-error').addClass('has-success');
            $('#button-saved').prop('disabled', false);
        }

        var TAHUN_PEROLEHAN_AWAL = $("#TAHUN_PEROLEHAN_AWAL").val();
        if(TAHUN_PEROLEHAN_AWAL == ''){
            $('.notif-tahun-perolehan').show();
            $('.form-tahun-perolehan').removeClass('has-success').addClass('has-error');
        }else{
            $('.notif-tahun-perolehan').hide();
            $('.form-tahun-perolehan').removeClass('has-error').addClass('has-success');
        }



        var NEGARA = $('#NEGARA').val();
        if (NEGARA == '1') {
            var PROV = $('#PROV').val();
            var KAB_KOT = $('#KAB_KOT').val();
            if (PROV == '') {
                $('.notif-prov').show();
                $('.form-prov').removeClass('has-success').addClass('has-error');
                $('#button-saved').prop('disabled', true);
            } else {
                $('.notif-prov').hide();
                $('.form-prov').removeClass('has-error').addClass('has-success');
                $('#button-saved').prop('disabled', false);
            }
            if (KAB_KOT == '') {
                $('.notif-kota').show();
                $('.form-kota').removeClass('has-success').addClass('has-error');
                $('#button-saved').prop('disabled', true);
            } else {
                $('.notif-kota').hide();
                $('.form-kota').removeClass('has-error').addClass('has-success');
                $('#button-saved').prop('disabled', false);
            }
        } else {
            var ID_NEGARA = $('#ID_NEGARA').val();
            if (ID_NEGARA == '') {
                $('.notif-negara').show();
                $('.form-negara').removeClass('has-success').addClass('has-error');
                $('#button-saved').prop('disabled', true);
            } else {
                $('.notif-negara').hide();
                $('.form-negara').removeClass('has-error').addClass('has-success');
                $('#button-saved').prop('disabled', false);
            }
        }


//         if ($("#ATAS_NAMA").val() != '3') {
//             console.log('dibawah');
//             $('.notif-ket-lainnya').hide();
//             $('.ket_lainnya_an_div').removeClass('has-error').addClass('has-success');
//         } else {
//             $('.notif-ket-lainnya').show();
//             $('.ket_lainnya_an_div').removeClass('has-success').addClass('has-error');
//         }

        AsalUsulValidation();
    }

    function AsalUsulValidation() {
		var pilih_asal_usul = $("[name='ASAL_USUL[]']:checked").is(':checked');
		if (pilih_asal_usul) {
			$('.notif-asal').hide();
			// $('#button-saved').prop('disabled', false);
		} else {
			$('.notif-asal').show();
			// $('#button-saved').prop('disabled', true);
             return false;
		}
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
        }).on("change", function(e) {
            var data = $('#KAB_KOT').select2('data');
            if(data) {
              $('#KAB_KOT_NAME').val(data.text);
            }
        });
    }

    function load_html(url, data) {
        var result;
        $.ajax({
            url: base_url + '' + url,
            type: 'html',
            async: false,
            method: 'POST',
            data: {
                "data": data
            },
            error: function (jqXHR, exception) {
                ajax_error_xhr(jqXHR, exception);
            },
            success: function (data) {
                result = data;
            },
        });
        return result;
    }


</script>