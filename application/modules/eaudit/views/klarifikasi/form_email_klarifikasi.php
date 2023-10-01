<?php ?>
<style media="screen">
  .message-body {
		height: 427px;
		overflow-y: scroll;
	}
</style>
<div class="container-fluid">
  <form>
    <div class="form-group row">
			<label class="control-label col-xs-3">Tujuan</label>
			<div class="col-xs-9">
				<?= $lhkpn_pribadi->NAMA_LENGKAP.' ('.$lhkpn_pribadi->EMAIL_PRIBADI.')'; ?>
			</div>
    </div>
    <div class="form-group row">
			<label class="col-sm-3 control-label">Cc </label>
			<div class="col-sm-9">
					<input class="form-control input_capital" type='text' name='cc_email' id='cc_email' placeholder="Cc" />
			</div>
    </div>
    <div class="form-group row">
			<label class="col-sm-3 control-label">Pesan </label>
			<div class="col-sm-9 message-body">
				<?= $mail_message; ?>
			</div>
    </div>
  <br>
  <div class="form-group row" id="input-btn-submit">
    <div class="col-xs-5 col-xs-offset-4">
			<button class="btn btn-primary progress-submit" id="btn-submit" type="submit" name="button">Kirim</button>
			<button class="btn btn-default" id="btn-batal" type="reset" name="button">Batal</button>
    </div>
  </div>
  </form>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		//enable button submit jika validasi email berhasil
		$("#cc_email").keyup(function(){
			cc_email = $('#cc_email').val();
			to_email = '<?= $lhkpn_pribadi->EMAIL_PRIBADI; ?>';
			if (cc_email == '' || cc_email == null) {
				$('#btn-submit').prop('disabled', false);
			} else {
				if (validationEmail(cc_email) && cc_email != to_email) {
					$('#btn-submit').prop('disabled', false);
				} else {
					$('#btn-submit').prop('disabled', true);
				}
			}
		});

		$('#btn-batal').click(function(e){
			e.preventDefault();
			CloseModalBox2();
		});

		$('#btn-submit').click(function(e){
			e.preventDefault();
			var cc_email = $('#cc_email').val();
			var new_id_lhkpn = '<?= $lhkpn_pribadi->ID_LHKPN; ?>';
			var url_cetak = 'index.php/ever/ikthisar/cetak_klarifikasi/<?= $lhkpn_pribadi->ID_LHKPN ?>/'+0+'/'+1;
			var url_send = "index.php/eaudit/klarifikasi/mail_klarifikasi_send_progress/" +new_id_lhkpn+ "/" +id_audit+ "/" +cc_email;
			$.post(url_cetak,function(data){
				if(data == '1') {
					$.post(url_send,function(data){
						if(data) {
							CloseModalBox2();
          		alertify.success('Berhasil kirim ulang');
						} else {
							CloseModalBox2();
							alertify.error('Gagal kirim ulang');
							return false;
						}
					})
				} else {
					CloseModalBox2();
					alertify.error('Gagal kirim ulang');
				}
			});
		});

		function validationEmail (email) {
			var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if(!regex.test(email)) {
				return false;
			}else{
				return true;
			}
		}
	});
</script>