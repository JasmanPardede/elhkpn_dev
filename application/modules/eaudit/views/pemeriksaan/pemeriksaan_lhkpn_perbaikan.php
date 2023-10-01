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
        <?php echo $VERIFICATIONS[0]->MSG_VERIFIKASI_INSTANSI; ?>
        <div class="clearfix"></div>
    </div>
</div>
<script type="text/javascript">
    function test() {
        var param = $('#PARAM').val();
        var email = $('#EMAILPN').val();
        var entry_via = $('#ENTRY_VIA').val();
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
    };
</script>