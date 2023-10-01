
<nav id="menu-nav" class="navbar navbar-default">
    <div class="container-fluid">
        <div class="row" id="main-menu">
            <div id="wrapper-menu" class="col-lg-12">
                <ul>
                    <li><a href="<?php echo base_url(); ?>portal/filing"  class="active">e-Filing</a></li>
                    <!-- <?php if ($STS_JAB != 5) { ?>    <li><a href="<?php echo base_url(); ?>portal/mailbox">Mailbox</a></li> <?php } ?> -->
                </ul>
            </div>
        </div>
    </div>
</nav>
<section id="wrapper">
    <div class="container-fluid">
        <div id="wrapper-main">
            <div class="row">
                <?php
                $STS_JAB =  isset($STS_JAB) && !is_null($STS_JAB) ? intval($STS_JAB) : FALSE;
                $is_wl =  isset($is_wl) && !is_null($is_wl) ? intval($is_wl) : NULL;
                $is_active_pn =  isset($is_active_pn) && !is_null($is_active_pn) ? intval($is_active_pn) : NULL;
                $tahun_lapor = substr($HISTORY[0]->tgl_lapor, 0, 4);
                $current_year = (int)date("Y"); 

                if ($STS_JAB != 5 && $is_active_pn == 1 && $draft_num <= 1 && ($is_wl == 1 || is_null($is_wl) || $is_wl == 99)) { ?>
                    <div class="col-lg-12" style="margin-bottom:5px;">
                        <?php if ($draft_num < 1 && $HISTORY[0]->STATUS != 2 && $HISTORY[0]->STATUS != 1 && $HISTORY[0]->STATUS != 7) { ?>
                            <a href="#" class="btn btn-default btn-dashboard" id="add">
                                <i class="fa fa-2x fa-plus-circle"></i>
                                ISI LHKPN BARU
                            </a>
                        <?php } else if($draft_num > 0) { ?>
                            <button class="btn btn-default btn-dashboard" id="btn09">
                                <i class="fa fa-2x fa-plus-circle"></i>
                                ISI LHKPN BARU
                            </button>
                        <?php } else if ($HISTORY[0]->STATUS == 7 && $HISTORY[0]->DIKEMBALIKAN != 0 && ($tahun_lapor+2) == $current_year) { // tombol add enable jika status dikembalikan dan sudah masuk tahun pengisian elhkpn tahun berikutnya ?> 
                            <button class="btn btn-default btn-dashboard" id="add">
                                <i class="fa fa-2x fa-plus-circle"></i>
                                ISI LHKPN BARU
                            </button>
                        <?php } else if ($HISTORY[0]->STATUS == 7 && $HISTORY[0]->DIKEMBALIKAN != 0 && ($wl_thn_minus_1 == 1 || $wl_thn_minus_1 == 0)) { // tombol add enable jika status dikembalikan (WL tahun n-1 aktif / non aktif) dan sudah masuk tahun pengisian elhkpn tahun berikutnya ?> 
                            <button class="btn btn-default btn-dashboard" id="add">
                                <i class="fa fa-2x fa-plus-circle"></i>
                                ISI LHKPN BARU
                            </button>
                        <?php } else {?> 
                            <button class="btn btn-default btn-dashboard" disabled="disabled" id="btn">
                                <i class="fa fa-2x fa-plus-circle"></i>
                                ISI LHKPN BARU
                            </button>
                        <?php } ?>
                    </div>
                    <?php
                } else if ($wl_tahun_now == 1 || $wl_thn_minus_1 == 1 ){ ?>
                    <a href="#" class="btn btn-default btn-dashboard" id="add">
                        <i class="fa fa-2x fa-plus-circle"></i>
                        ISI LHKPN BARU
                    </a>
                <?php } else {
                    echo "<h3 style='color: red'>Anda dalam kondisi non aktif sebagai PN/WL</h3>";
                }
                ?>
            </div>
            <div class="row">
                <div class="clearfix"></div>
                <div class="col-lg-4">
                    <div class="box box-default">
                        <div class="box-header" style="text-align:center">
                            <h3 class="box-title">REKAPITULASI HARTA (Rp.)</h3>
                        </div>
                        <div class="box-body">
                            <div class="chart">
                                <canvas id="barChart1" style="height:230px"></canvas>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer" style="text-align:center; border:none">
                            <ul class="legend">
                                <?php foreach ($HISTORY_LIMIT as $h): ?>
                                    <li><?php echo substr($h->tgl_lapor, 0, 4); ?></li>
                                    <?php EndForeach; ?>
                            </ul>
                        </div>
                    </div><!-- /.box -->
                </div>
                <div class="col-lg-4">
                    <div class="box box-default">
                        <div class="box-header" style="text-align:center">
                            <h3 class="box-title">REKAPITULASI PENERIMAAN (Rp.)</h3>
                        </div>
                        <div class="box-body">
                            <div class="chart">
                                <canvas id="barChart2" style="height:230px"></canvas>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer" style="text-align:center; border:none">
                            <ul class="legend">
                                <?php foreach ($HISTORY_LIMIT as $h): ?>
                                    <li><?php echo substr($h->tgl_lapor, 0, 4); ?></li>
                                    <?php EndForeach; ?>
                            </ul>
                        </div>
                    </div><!-- /.box -->
                </div>
                <div class="col-lg-4">
                    <div class="box box-default">
                        <div class="box-header" style="text-align:center">
                            <h3 class="box-title">REKAPITULASI PENGELUARAN (Rp.)</h3>
                        </div>
                        <div class="box-body">
                            <div class="chart">
                                <canvas id="barChart3" style="height:230px"></canvas>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer" style="text-align:center; border:none">
                            <ul class="legend">
                                <?php foreach ($HISTORY_LIMIT as $h): ?>
                                    <li><?php echo substr($h->tgl_lapor, 0, 4); ?></li>
                                    <?php EndForeach; ?>
                            </ul>
                        </div>
                    </div><!-- /.box -->
                </div>
                <div class="clearfix"></div>
                <?php if ($STS_JAB != 5) { ?>
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Riwayat LHKPN
                            </div>
                            <div class="panel-body no-padding">
                                <?php $error_message = $this->session->flashdata('error_message'); ?>
                                <?php if ($error_message): ?>
                                    <div id="ModalSuccess" class="modal fade" role="dialog">
                                        <div class='modal-dialog' style="margin:15% auto">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Peringatan</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="alert alert-danger">
                                                        <i class="fa fa-warning" aria-hidden"true"=""></i>
                                                        <span id="notif-text"><?php echo $this->session->flashdata('message'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">
                                                        <i class="fa fa-remove"></i> Tutup
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                            $('#ModalSuccess').modal('show');
                                        });</script>
                                    <?php EndIf; ?>
                                <?php $success_message = $this->session->flashdata('success_message'); ?>
                                <?php if ($success_message): ?>
                                    <div id="ModalSuccess" class="modal fade" role="dialog">
                                        <div class='modal-dialog' style="margin:15% auto">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Pemberitahuan</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="alert alert-success">
                                                        <i class="fa fa-check" aria-hidden"true"=""></i>
                                                        <span id="notif-text"><?php echo $this->session->flashdata('message'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">
                                                        <i class="fa fa-remove"></i> Tutup
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                            $('#ModalSuccess').modal('show');
                                        });</script>
                                    <?php EndIf; ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        *Data harta hasil migrasi yang memiliki jenis mata uang US$ dikonversi menjadi mata uang Rp. dengan nilai kurs 1 US$ = Rp. 10.000,-<br></br>
                                        <div class="box box-danger">


                                            <div class="box-body with-border">
                                                <table id="Tabel" style="width:100%;" class="table table-striped table-bordered table-hover no-border-bottom table-filing">
                                                    <thead>
                                                        <tr>
                                                            <th width="2%">No.</th>
                                                            <th width="25%">Nama PN / WL</th>
                                                            <th width="7%">Tanggal Lapor</th>
                                                            <th width="8%">Jenis Pelaporan</th>
                                                            <th width="25%">Jabatan</th>
                                                            <th width="12%">Status Laporan</th>
                                                            <th width="8%">Laporan Via</th>
                                                            <th width="13%" >Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<?php
echo isset($v_modal_buat_lhkpn_baru) ? $v_modal_buat_lhkpn_baru : '';
?>


<script type="text/javascript">
    

    var INDEX_CHART_NOW = <?php echo!isset($NOW) || $NOW == NULL ? 'null' : $NOW; ?>;
    var INDEX_CHART_LAST = <?php echo!isset($LAST) || $LAST == NULL ? 'null' : $LAST; ?>;
    var TAHUN_1 = <?php echo!isset($tahun_1) || $tahun_1 == NULL ? 'null' : $tahun_1; ?>;
    var TAHUN_2 = <?php echo!isset($tahun_2) || $tahun_2 == NULL ? 'null' : $tahun_2; ?>;


    $(document).ready(function () {

        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!

        var yyyy = today.getFullYear();
        var yearminsatu = today.getFullYear()-1;
        if(dd<10){
            dd='0'+dd;
        }
        if(mm<10){
            mm='0'+mm;
        }
        var today = dd+'/'+mm+'/'+yyyy;
        BAR_CHART1();
        BAR_CHART2();
        BAR_CHART3();
        $('.group-0,.group-1').hide();
        $('select').select2();
        $('#jenis_laporan').change(function () {
            var val = $(this).val();
            if (val == 4) {
                $('.group-1').hide();
                $('.group-0').fadeIn('slow');
                $('#tahun_pelaporan').val(yearminsatu);
            } else {
                $('.group-0').hide();
                $('.group-1').fadeIn('slow');
            }
        });
        var d = new Date();
        d.setFullYear(d.getFullYear() - 1);
        var limit = new Date(d.getFullYear(), 11, 31);
        // var limitq = new Date(d.setFullYear(d.getFullYear(), 0, 0));
        var limitq = new Date(yyyy,1-1,1); //1 januari tahun sekarang
        var limit_tahun_min_1 = new Date(yyyy-1,1-1,1); //1 januari tahun-1
        
        $('#tahun_pelaporan').datetimepicker({
            viewMode: 'years',
            allowInputToggle: true,
            locale: 'id',
            format: "YYYY",
            maxDate: limit
        });
        $('#add').click(function () { 
            var cekJenisLapor = <?php echo ($check_lhkpn_jenis > 0) ? '4' : '2' ; ?>;
            var cek_wl_now = <?php echo $cek_wl_now == NULL ? 'null' : $cek_wl_now; ?>;
            var maxWL = <?php echo $max_tahun_wl; ?>;
            var wl_tahun_now = <?php echo $wl_tahun_now; ?>;
            var wl_thn_minus_1 = <?php echo $wl_thn_minus_1; ?>;
            var tahun_1 = <?php echo $tahun_1 == NULL ? 'null' : $tahun_1; ?>;
            // var batasLapor = yyyy + "-10-01";           //berlaku setelah tanggal 30 Sept
            // var akhirTahun = yearminsatu + "-12-31";    //berlaku setelah tanggal 1 Jan

            if(maxWL >= yyyy - 1) {
                $('#myform')[0].reset();
                // $('#jenis_laporan').val(null).change();
                $('.group-0,.group-1, #img_pop_content').hide();
                $('select').val('');
                $('#is_update').val('insert');
                $('#title-label').text('BUAT LHKPN BARU');
                $('#FillingEditLaporan').modal('show');

                var minLapor = new Date(maxWL, 00, 01);
                var maxLapor = new Date(maxWL, 11, 31);
                
                if(maxWL == yyyy || maxWL == yyyy-1) {
                    var tahun_min_1 = yyyy-1;
                    var defaultLapor = '31/12/'+tahun_min_1;
                    
                    if(wl_tahun_now == 1 && wl_thn_minus_1 == 1){ 
                        if(tahun_min_1 == tahun_1){ 
                            $('#tgl_pelaporan').val(today);
                            $('#tgl_pelaporan').datetimepicker({
                                format: "DD/MM/YYYY",
                                allowInputToggle: true,
                                locale: 'id',
                                maxDate: 'now',
                                minDate: new Date(limitq)
                            });
                        }else{ 
                            //jika tahun n-1 tidak memiliki tanggal lapor
                            $('#tgl_pelaporan').val(defaultLapor);
                            $('#tgl_pelaporan').datetimepicker({
                                format: "DD/MM/YYYY",
                                allowInputToggle: true,
                                locale: 'id',
                                maxDate: 'now',
                                minDate: new Date(limit_tahun_min_1)
                            });
                        }
                    }else if(wl_thn_minus_1 == 0){ // jika tidak ada laporan sebelumnya
                        $('#tgl_pelaporan').val(today);
                        $('#tgl_pelaporan').datetimepicker({
                            format: "DD/MM/YYYY",
                            allowInputToggle: true,
                            locale: 'id',
                            maxDate: 'now',
                            minDate: new Date(limitq)
                        });
                    }else if(wl_tahun_now == 0 && wl_thn_minus_1 == 1){
                        $('#tgl_pelaporan').val(defaultLapor);
                        $('#tgl_pelaporan').datetimepicker({
                            format: "DD/MM/YYYY",
                            allowInputToggle: true,
                            locale: 'id',
                            maxDate: maxLapor,
                            minDate: new Date(limit_tahun_min_1)
                        });
                    }
                    
                } else {
                    var defaultLapor = '31/12/'+maxWL;
                    
                    $('#tgl_pelaporan').val(defaultLapor);
                    $('#tgl_pelaporan').datetimepicker({
                        format: "DD/MM/YYYY",
                        allowInputToggle: true,
                        locale: 'id',
                        maxDate: maxLapor,
                        minDate: minLapor
                    });
                }

                if(wl_thn_minus_1 == 1){
                    $('#jenis_laporan').val('4').change();
                }else if(maxWL == yyyy){
                    $('#jenis_laporan').val('2').change();
                }

                // if((new Date() < new Date(batasLapor)) && (new Date() > new Date(akhirTahun))) {
                // if (cek_wl_now != yyyy - 1){
                //     $('#jenis_laporan').val('2').change();
                // }else{
                //     $('#jenis_laporan').val(cekJenisLapor).change();
                // }
                // } else {
                //     $('#jenis_laporan').val(2).change();
                //     $('#jenis_laporan option[value="4"]').wrap('<span style="display: none;" />');
                // }
            } else {
                Swal({
                    type: 'warning',
                    html: '<p style="font-family: Century Gothic;text-align: center;">Silahkan hubungi Admin LHKPN di Instansi Anda.</p>',
                    width: '25%'
                });
            return false;
            }
        });
        $('#img_popup').click(function () {
            $('#img_pop_content')[0].reset();
            $('.group-0,.group-1').hide();
            $('select').val('');
            $('#FillingEditLaporan').modal('show');
        });
        $('form').bootstrapValidator();
        /*$('#myform').submit(function(){
         var tahun = $('#tahun_pelaporan').val();
         var tgl_pelaporan  = $('#tgl_pelaporan').val();

         return false;
         });*/


        $('[data-toggle="popover"]').popover({});
        $('a.over').css('cursor', 'pointer');
        $('a.over').on('click', function (e) {
            $('a.over').not(this).popover('hide');
        });

        $('#btn09').click(function() {
            // alert('Terimalah lagu ini, dari orang biasa..!!');
            Swal({
                type: 'warning',
                html: '<p style="font-family: Century Gothic;">Anda sudah memiliki LHKPN dengan status Draft.<br />Silahkan klik pada tombol Edit <a class="btn btn-sm" style="background-color: green;color: white;border-radius: 4px" disabled><i class="fa fa-pencil"></i></a> di kolom Aksi yang terdapat pada tabel Riwayat LHKPN di bawah.</p>',
                width: '30%'
            });
            return false;
        })


    });

</script>
<?php
$js_page = isset($js_page) ? $js_page : '';
if (is_array($js_page)) {
    foreach ($js_page as $page_js) {
        echo $page_js;
    }
} else {
    echo $js_page;
}
?>
