<?php
/*
  ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___
  (___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
  ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___
  (___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
 */

/**
 * View
 *
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Views/pn
 */
// $INSTANSI = array();
// foreach ($instansis as $instansi) {
//     $INSTANSI[$instansi->INST_SATKERKD]['NAMA'] = $instansi->INST_NAMA;
// }
//function dropdownMutasi($status_akhir, $idjb){
//    $out = '
//    <div class="dropdown pull-right">
//        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Mutasikan <span class="caret"></span></button>
//        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
//    foreach ( $status_akhir as $status ) {
//        $out .= '<li><a href="index.php/ereg/all_pn/mts/'.$idjb.'/'.$status->ID_STATUS_AKHIR_JABAT.'" class="btn-mutasi">'.$status->STATUS.'</a></li>';
//    }
//    $out .= '    </ul>
//    </div>';
//    return $out;
//}

function dropdownHasilPemilihan($status_akhir, $idjb) {
    $out = '
    <div class="dropdown pull-right">
        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Mutasikan <span class="caret"></span></button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
    $out .= '<li><a href="index.php/ereg/all_pn/mts/' . $idjb . '/58/1" class="btn-mutasi-a">Penetapan PN/WL</a></li>';
    foreach ($status_akhir as $status) {
        if ($status->IS_AKHIR == 0 && $status->IS_PINDAH == 0 && $status->IS_AKTIF == 0 && $status->IS_MENINGGAL == 0) {
            $out .= '<li><a href="index.php/ereg/all_pn/mts/' . $idjb . '/' . $status->ID_STATUS_AKHIR_JABAT . '/1" class="btn-mutasi-a">' . $status->STATUS . '</a></li>';
        }
    }
    // $out .= '<li><a href="#">Non WL</a></li>';

    $out .= '</ul></div>';
    return $out;
}
?>
<div id="table-jbt">
    <h3>Detail PN</h3>
    <input type="hidden" name="id_pn" value="<?php echo $id_pn; ?>" />
    <table class="table display table-striped table-bordered table-hover table-heading no-border-bottom cek table-cek">
        <thead>
            <tr>
                <th width="30">No.</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
<?php
$i = 0;
$start = $i + 1;
$end = 0;
foreach ($items as $item) {
    ?>
                <tr>
                    <td><small><?php echo ++$i; ?>.</td>
                    <td><small><?php echo $item->NIK; ?></td>
                    <td><small><?php echo $item->NAMA; ?></td>
                    <td><small><?php echo $item->N_JAB . ' - ' . $item->N_SUK . ' - ' . $item->N_UK . ' - ' . $item->INST_NAMA; ?></td>
                    <td><small><?php 
                    if($item->ID_STATUS_AKHIR_JABAT == 0)
                        echo 'Active';
                    elseif($item->ID_STATUS_AKHIR_JABAT == 5)
                        echo 'Non Active';
                    elseif($item->ID_STATUS_AKHIR_JABAT > 9)
                        echo 'Dalam Proses Verifikasi';
                    ?></td>
    <!--                <td>
    <?php
    $instUser = $this->session->userdata('INST_SATKERKD');
    $stat = FALSE;
    $meninggal = FALSE;
    $isInstansi = FALSE;
    if ($item->NAMA_JABATAN) {
        $j = explode(':|||:', $item->NAMA_JABATAN);
        echo '<ul class="listjabatan">';
        foreach ($j as $ja) {
            $jb = explode(':||:', $ja);
            $ID = @$jb[0];
            $ID_STATUS_AKHIR_JABAT = @$jb[1];
            $STATUS = @$jb[2];
            $ID_PN_JABATAN = @$jb[3] != 'NULL' ? @$jb[3] : null;
            $LEMBAGA = @$jb[4];
            $JABATAN = ucwords(strtolower(@$jb[5]));
            $TMT = @$jb[6];
            $SD = @$jb[7];
            $IS_CALON = @$jb[8];
            $INST_TUJUAN = @$jb[9];
            $ID_INST_TUJUAN = @$jb[10];

            $out = '';
            $out .= $JABATAN;
            // $out .= ' TMT : '.date('d-m-Y',strtotime($TMT));

            if ($IS_CALON == 1 && (($ID_STATUS_AKHIR_JABAT == '0' || $ID_STATUS_AKHIR_JABAT == '' || $ID_STATUS_AKHIR_JABAT == null) && $ID_PN_JABATAN == null)) {// calon pn
                $pnposisi = 'calon';
            } else if (( $ID_STATUS_AKHIR_JABAT == '0' || $ID_STATUS_AKHIR_JABAT == '' || $ID_STATUS_AKHIR_JABAT == null) && $ID_PN_JABATAN == null) {
                // jabatan masih aktif & tidak dimutasikan
                $pnposisi = 'aktif';
            } else {
                if ($ID_PN_JABATAN != null) {// sedang mutasi
                    $pnposisi = 'mutasi';
                } else {// jabatan sudah berakhir
                    $pnposisi = 'berakhir';
                }
            }
            // Jika sudah Meninggal
            if ($ID_STATUS_AKHIR_JABAT == '3') {
                $meninggal = TRUE;
            }

            switch ($pnposisi) {
                case 'calon' :
                    $stat = TRUE;
                    ($instUser == $LEMBAGA ? $isInstansi = TRUE : '');
                    $out = ' <span class="label label-primary">Calon</span> ';
                    $out .= $JABATAN;
                    $out .= dropdownHasilPemilihan($status_akhir, $ID);
                    break;
                case 'aktif' :
                    $stat = TRUE;
                    ($instUser == $LEMBAGA ? $isInstansi = TRUE : '');
                    if (($IS_KPK == 1) || ($instUser != $LEMBAGA)) {
                        $out .= ' - <button class="btn btn-primary btn-sm" onClick="f_mailbox(' . $LEMBAGA . ');">Kirim Pesan</button>';
                    }
                    break;
                case 'mutasi' :
                    if ($masuk == false) {
                        $out .= ' - <span class="label label-warning">sedang proses mutasi ke ' . $INST_TUJUAN . '</span>  - <button class="btn btn-primary btn-sm" onClick="f_mailbox(' . $ID_INST_TUJUAN . ');">Kirim Pesan</button>';
                    }
                    break;
                case 'berakhir' :
                    if ($IS_CALON == 1) {
                        $out = ' <span class="label label-primary">Calon</span> ';
                        $out .= $JABATAN;
                    }
                    $out .= ' S/D : ' . date('d-m-Y', strtotime($SD)) . ' - <span class="label label-danger">' . $STATUS . '</span>';
                    break;
            }
            echo '<li class="item">' . $out . '<div class="clearfix"></div></li>';
        }
        echo '</ul>';
    }
    ?>
                    </td>-->
                </tr>
    <?php
    $end = $i;
}
?>
        </tbody>
    </table>
<?php if ($masuk) { ?>
        <h3>Detail PN Masuk</h3>
        <?php echo $items_masuk;
    }
    if ((empty($items_masuk)) AND ! $isInstansi) {
        ?>
        <!-- if((!$meninggal AND empty($items_masuk)) AND !$isInstansi){ ?> -->
        <button type="button" class="btn btn-sm btn-primary" onclick="form_jabatan();">
            <?php echo ($stat ? 'Tambah Jabatan Rangkap' : 'Tambah Jabatan/Rangkap Jabatan Aktif/ '); ?>
        </button>
    <?php } ?>
    <button type="button" class="btn btn-sm btn-default" id="btnBackToForm">Kembali Ke Form</button>
</div>
<div class="app" id="div-jabatan" style="display: none;"></div>
<div class="app" id="app" style="display: none;"></div>
<div class="app" id="mailbox" style="display: none;">
    <div id="wrapperFormMail" class="form-horizontal">
        <div id="wrapperFormCreate">
            <div id="loading"></div>
            <form method="POST" id="ajaxFormMail" action="index.php/mailbox/sent/savemail" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Penerima <span class="red-label">*</span> :</label>
                            <div class="col-sm-8">
                                <input type='hidden' name='ID_PENERIMA'>
                                <input type='text' class="form-control" name='PENERIMA' required readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Subjek <span class="red-label">*</span> :</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="SUBJEK" value="Pindah PN a/n <?= $nama ?> (<?= $nik ?>)" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Pesan <span class="red-label">*</span> :</label>
                            <div class="col-sm-8">
                                <textarea rows="5" class="form-control" name="PESAN" id="PESAN" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Attachment :</label>
                            <div class="col-sm-8">
                                <input type="file" name="filename" id="filename" class="attachment">
                                <span class='help-block col-sm-12'>Type File: xls, xlsx, doc, docx, pdf, jpg, jpeg, png .  Max File: 500KB</span>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="clearfix" style="margin-bottom: 20px;"></div>
                <div class="pull-right">
                    <input type="hidden" name="act" value="doinsert">
                    <button type="submit" class="btn btn-sm btn-primary">Kirim</button>
                    <button type="button" class="btn btn-sm btn-default" id="btnBackToForm1">Kembali</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style type="text/css">
    .listjabatan{
        margin: 0px;
        padding:0px;
    }
    .listjabatan li.item{
        list-style: none;
        border: 1px solid #cfcfcf;
        padding-left: 10px;
        padding-bottom: 12px;
        margin-top: -1px;
    }
    ul.dropdown-menu {
        background-color: #99E1F4;
    }
</style>

<script language="javascript">
    function f_mailbox(id) {
        $.post('index.php/ereg/all_pn/getAdmin/' + id, function(data) {
            $('.modal-dialog').animate({
                width: '-=500'
            });
            $('#table-jbt').hide('slow', function() {
                $('#mailbox').show('slow', function() {
                    $('input[name="ID_PENERIMA"]').val(data.ID_USER);
                    $('input[name="PENERIMA"]').val(data.NAMA + ' (' + data.INST_NAMA + ')');
                });
            });
        }, 'json');
    }

    function backForm() {
        $('.modal-dialog').animate({
            width: '1100px'
        });
        $(".app").hide('slow', function() {
            $('#table-jbt').show('slow');
        });
    }

    function form_jabatan() {
        $.post('index.php/ereg/all_pn/addjabatan_table/' + $('input[name="id_pn"]').val() + '/<?= $status ?>', {redirect: '<?php echo @$redirect ?>'}, function(data) {
            $('#div-jabatan').html(data);
            $('#table-jbt').hide('slow', function() {
                $('#div-jabatan').show('slow');
            });
        })
    }

    $(document).ready(function() {
        $('table.display').DataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bSort": true,
            "bInfo": false,
            "bAutoWidth": true
        });
        $('.table-cek .btn-mutasi-a').click(function() {
            var url = $(this).attr('href');
            $.post(url, function(data) {
                $('#div-jabatan').html(data);
                $('.modal-dialog').animate({
                    width: '-=500'
                });
                $('#table-jbt').hide('slow', function() {
                    $('#div-jabatan').show('slow');
                });
            })
            return false;
        })

        $('.attachment').change(function() {
            var nil = $(this).val().split('.');
            nil = nil[nil.length - 1].toLowerCase();
            var file = $(this)[0].files[0].size;
            var arr = ['xls', 'xlsx', 'doc', 'docx', 'pdf', 'jpg', 'png', 'jpeg'];
            var maxsize = 500000;
            if (arr.indexOf(nil) < 0)
            {
                $('.attachment').val('');
                alertify.error('Type file tidak sesuai !');
            }
            if (file > maxsize)
            {
                $('.attachment').val('');
                alertify.error('Ukuran File trlalu besar !');
            }
        });

        $("#ajaxFormMail").submit(function() {
            var urll = $(this).attr('action');
            var formData = new FormData($(this)[0]);
            $('#loader_area').show();
            $.ajax({
                url: urll,
                type: 'POST',
                data: formData,
                async: false,
                success: function(html) {
                    $('#loader_area').show();
                    msg = {
                        success: 'Pesan Berhasil Dikirim!',
                        error: 'Pesan Gagal Dikirim!'
                    };
                    if (html == 0) {
                        alertify.error(msg.error);
                    } else {
                        alertify.success(msg.success);
                    }
                    if (html == 1) {
                        $('#loader_area').hide();
                        $('.modal-dialog').animate({
                            width: '+=500'
                        });
                        $("#mailbox").hide('slow', function() {
                            $('#table-jbt').show('slow');
                        });
                    } else {
                        console.log('error');
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });

            return false;

        })

        $('#myModal').on('hidden.bs.modal', function(e) {
            $('.modal-dialog').attr('style', '');
        })

        $("#btnBackToForm").click(function(e) {
            $('.modal-dialog').animate({
//                width: '-=500'
            })
            $("#wrapperFormAddPN").show('fast');
            $("#wrapperFormPNExist").hide('fast');
            $("#NIK").focus();
        });

        $("#btnBackToForm1").click(function(e) {
            $('.modal-dialog').animate({
                width: '+=500'
            });
            $("#mailbox").hide('slow', function() {
                $('#table-jbt').show('slow');
            });
        });

        $('#btn-clear').click(function(event) {
            $('#ajaxFormCari').find('input:text').val('');
            $('#INST').select2('val', '99');
            $('#ajaxFormCari').trigger('submit');
        });

//        $('.btn-keljab').click(function (e) {
//            url = $(this).attr('href');
//            $('#loader_area').show();
//            $.post(url, function (html) {
//                OpenModalBox('Riwayat Jabatan', html, '', 'large');
//            });
//            return false;
//        });

        $('#table-jbt .btn-mutasi').click(function(e) {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Mutasi Penyelenggara Negara', html, '', 'standart');
            });
            return false;
        });

    });
</script>
