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
 * @package Views/lhkpn
 */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Sent
        <small>Daftar surat keluar</small>
    </h1>
    <?php echo $breadcrumb; ?>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <!--<div class="box-header with-border">
                    <h3 class="box-title">Bordered Table</h3> -->

                    <!--<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"> 
                        <button class="btn btn-sm btn-primary" id="btn-add" href="index.php/mailbox/sent/addmail"><i class="fa fa-plus"></i> Tulis Pesan</button>-->
                        <!-- <button class="btn btn-sm btn-default" id="btnImportExcel" href="index.php/mailbox/inbox/importexcel"><i class="fa fa-file-excel-o"></i> Import Excel</button> -->
                    <!--</div>-->
                <!--</div> --> <!-- /.box-header -->
                <div class="box-body">
                    <table id="dt_complete" class="table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Penerima Pesan</th>
                                <th>Subjek</th>
                                <!-- <th>Isi Pesan</th> -->
                                <th>File</th>
                                <th>Tanggal Kirim</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php $i = 1; ?>
                            <?php if (empty($items)) : ?>
                                <tr>
                                    <td colspan="6">
                            <center>Data not found !</center>
                            </td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($items as $item) : ?>
                            
                            <tr>
                                <td><?= $i++ ?></td>
                                <td>
                                    <?php echo $item->NAMA ?>
                                </td>
                                <td>
                                    <?php echo $item->SUBJEK ?>
                                </td>
                                <!-- <td>
                                <?php
                                $pesan = $item->PESAN;
                                echo (strlen($pesan) > 200) ? substr($pesan, 0, 200) . " ..." : $pesan;
                                ?>
                                </td> -->
                                <td>
                                    <?php if (!empty($item->FILE)) : ?>
                                        <a href="<?php echo 'uploads/mail_out/' . $item->ID_PENGIRIM . '/' . $item->FILE; ?>" target="_BLANK">
                                            <i class="fa fa-file"></i> <?php echo ng::filesize_formatted('uploads/mail_out/' . $item->ID_PENGIRIM . '/' . $item->FILE); ?>
                                        </a>
                                    <?php else : ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><?= indonesian_date(strtotime($item->TANGGAL_KIRIM)); ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info btn-detail" href="index.php/mailbox/sent/detail/<?php echo substr(md5($item->ID), 5, 8); ?>" title="Preview"><i class="fa fa-search-plus"></i></button>
                                    <!-- <button type="button" class="btn btn-sm btn-default btn-reply" href="index.php/mailbox/sent/balas/<?php //echo substr(md5($item->ID),5,8);   ?>" title="Reply"><i class="fa fa-reply"></i></button> -->
                                    <button type="button" class="btn btn-sm btn-danger btn-delete" href="index.php/mailbox/sent/delete/<?php echo substr(md5($item->ID), 5, 8); ?>" title="Delete"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->

            </div>
        </div><!-- /.box -->
    </div><!-- /.col -->
</div><!-- /.row -->
</section><!-- /.content -->

<script type="text/javascript">
    $(document).ready(function() {
        $('.btn-detail').click(function(e) {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Lihat Pesan', html, '', 'large');
            });
            return false;
        });
        $('#btn-add').click(function(e) {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Kirim Pesan', html, '', 'standart');
            });
            return false;
        });
        $('.btn-delete').click(function(e) {
            url = $(this).attr('href');
            $.post(url, function(html) {
                OpenModalBox('Hapus Pesan', html, '', 'large');
            });
            return false;
        });
    });

    $(function() {
        $('#dt_complete').dataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": true,
            "bInfo": true,
            "bAutoWidth": true,
            "scrollY": '50vh',
            "scrollCollapse": true,
             "columnDefs": [{
    "defaultContent": "-",
    "targets": "_all"
  }]
        });
    });
</script>