<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#emailpn" aria-controls="emailpn" role="tab" data-toggle="tab" class="navTab" title="Email PN" aria-expanded="true"><i class="fa fa-envelope"></i> <span>Surat</span></a></li>
    <li role="presentation" class=""><a href="#emailinstansi" aria-controls="emailinstansi" role="tab" data-toggle="tab" class="navTab" title="Email Instansi" aria-expanded="false"><i class="fa fa-envelope"></i> <span>Draft lampiran</span></a></li>
</ul>
<div class="tab-content" style="padding: 5px; border:1px solid #cfcfcf;margin-top: -1px;">
    <div role="tabpanel" class="tab-pane active" id="emailpn">
        <input type="hidden" id="EMAILPN" value="<?php echo $datapn->EMAIL ?>">
        <input type="hidden" id="ENTRY_VIA" value="<?php echo $entry_via ?>">
        <input type="hidden" id="PARAM" value="<?php echo $VERIFICATIONS[0]->ID_LHKPN; ?>">
        <div class="clearfix"></div>
        <div class="col-md-10">
            <?php echo $VERIFICATIONS[0]->MSG_VERIFIKASI; ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <!--  -->
    <div role="tabpanel" class="tab-pane" id="emailinstansi">
         
         Daftar kekurangan kelengkapan yang harus diisi dan dilengkapi oleh Sdr. <?php echo $datapn->NAMA ?>, <?php echo $datapn->NAMA_JABATAN.' '.$datapn->INST_NAMA; ?> :
         <br>
         <table border="1" cellpadding="0" cellspacing="0"  width="650px">
             <thead>
                <tr>
                    <th>JENIS</th>
                    <th>URAIAN</th>
                </tr>
            </thead>
            <tbody>
                <?php 
        
                $jenis_data_lhkpn_2 = ['DATAPRIBADI' => 'Data Pribadi', 'JABATAN' => 'Jabatan', 'KELUARGA' => 'Keluarga', 'HARTATIDAKBERGERAK' => 'Tanah / Bangunan', 'HARTABERGERAK' => 'Mesin / Alat Transportasi', 'HARTABERGERAK2' => 'Harta Bergerak Lainnya', 'SURATBERHARGA' => 'Surat Berharga', 'KAS' => 'Kas', 'HARTALAINNYA' => 'Harta Lainnya', 'HUTANG' => 'Hutang', 'PENERIMAANKAS' => 'Penerimaan Kas', 'PENGELUARANKAS' => 'Pengeluaran Kas', 'PELEPASANHARTA' => 'Pelepasan Harta', 'PENERIMAANHIBAH' => 'Penerimaan Hibah', 'PENERIMAANFASILITAS' => 'Penerimaan Fasilitas', 'DOKUMENPENDUKUNG' => 'Dokumen Pendukung', 'SURATKUASAMENGUMUMKAN' => 'Surat Kuasa'];
                $msg_verifikasi = json_decode($VERIFICATIONS[0]->HASIL_VERIFIKASI); 
                foreach($msg_verifikasi->VAL as $key => $mv){
                    if($mv==-1){
                        $jenis_name = array_search($key, array_flip($jenis_data_lhkpn_2));
                        ?>
                        <tr>
                            <td><?php echo $jenis_name ?></td>
                            <td><?php echo $msg_verifikasi->MSG->$key ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
         </table>
         Team Verifikasi KPK
        <div class="clearfix"></div>
    </div>
</div>
<script type="text/javascript">
    function test(emailCc) {
        var param = $('#PARAM').val();
        var email = $('#EMAILPN').val();
        var entry_via = $('#ENTRY_VIA').val();
        if(emailCc) {
            confirm("Anda akan mengirimkan email ke "+email+" <br>dan di CC ke "+emailCc+".", function () {
                $('#loader_area').show();
                $.ajax({
                    url: '<?php echo base_url();?>ever/verification/test_kirim_email/'+param+'/'+entry_via+'/'+emailCc,
                    dataType: 'json',
                    success: function (data) {
                        $('#loader_area').hide();
                        CloseModalBox2();
                        alert('Email Berhasil Dikirim ke '+email+' dan di CC ke '+emailCc);
                    }
                });

            }, "Konfirmasi Kirim Email", undefined, "YA", "TIDAK");    
        } else {
            confirm("Anda akan mengirimkan email ke "+email+".", function () {
                $('#loader_area').show();
                $.ajax({
                    url: '<?php echo base_url();?>ever/verification/test_kirim_email/'+param+'/'+entry_via,
                    dataType: 'json',
                    success: function (data) {
                        $('#loader_area').hide();
                        CloseModalBox2();
                        alert('Email Berhasil Dikirim ke '+email);
                    }
                });

            }, "Konfirmasi Kirim Email", undefined, "YA", "TIDAK");
        }
    };
</script>