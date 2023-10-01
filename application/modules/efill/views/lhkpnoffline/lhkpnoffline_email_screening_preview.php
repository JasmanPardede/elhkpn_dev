<?php if($PAR): ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>LHKPN</title>

        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Bootstrap 3.3.4 -->
        <link href="<?php echo base_url('img/favicon.ico'); ?>" rel="shortcut icon" type="image/x-icon">
        <link href="<?php echo base_url(); ?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
    	<div class="row" style="padding-top: 50px">
    		<div class="col-md-offset-2 col-md-8">
                    <?php endif; ?>
    			<table class="table table-bordered">
    				<tbody>
    					<tr>
    						<td>
    							Yth. Sdr. <strong><?=$NAMA ?></strong><br>
    							<strong><?=$INST_NAMA?></strong><br>
    							Di Tempat<br><br>

    							&emsp;&emsp;&emsp;Berdasarkan hasil verifikasi awal terhadap LHKPN Format Excel yang telah Saudara sampaikan, LHKPN Saudara dinyatakan tidak dapat diproses dikarenakan tidak memenuhi kriteria yang telah ditetapkan. Untuk pemrosesan lebih lanjut, Saudara diminta untuk mengirimkan kembali LHKPN Format Excel ke Komisi Pemberantasan Korupsi dan  melengkapi data LHKPN sebelumnya berupa data sebagai berikut :<br>
    							&emsp;&emsp;&emsp;<?=str_replace(PHP_EOL,"<br>&emsp;&emsp;&emsp;",$URAIAN_SCREENING) ?> <br>
    							&emsp;&emsp;&emsp;Email pemberitahuan ini tidak dapat digunakan sebagai tanda terima LHKPN. Untuk informasi lebih lanjut, silakan menghubungi kami kembali melalui email elhkpn@kpk.go.id  atau call center 198. <br><br>

    							Atas kerjasama yang diberikan, Kami ucapkan terima kasih <br><br>

    							Direktorat Pendaftaran dan Pemeriksaan LHKPN <br>
    							-------------------------------------------------------------- <br>
    							Email ini dikirim secara otomatis oleh sistem e-LHKPN dan anda tidak perlu membalas email ini. <br>
    							&copy; 2017 Direktorat PP LHKPN KPK | www.kpk.go.id. | elhkpn.kpk.go.id | Layanan LHKPN 198

    						</td>
    					</tr>
    				</tbody>
    			</table>
                    <?php if($PAR): ?>
    			<a href="#" onclick="closeWin()" class="btn btn-danger">Tutup</a>
    		</div>
    	</div>
    </body>
</html>
<script type="text/javascript">
	function closeWin() {
	    self.close();
	}
</script>
<?php endif; ?>