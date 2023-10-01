<?php
if ($total_rows) {
    ?>
    <div class="box-body">
        <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Tempat / Tanggal Lahir</th>
                    <th>Jabatan</th>
                    <th>No Agenda</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0 + $offset;
                $start = $i + 1;
                $end = 0;
                foreach ($items as $item) {
                    if ($item->ID_LHKPN == '') {
                        $agenda = '-';
                    }
                    else{
                        $agenda = date('Y', strtotime($item->tgl_lapor)) . '/' . ($item->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $item->NIK . '/' . $item->ID_LHKPN;
                    }
                    ?>
                    <tr>
                        <td><?php echo ++$i; ?>.</td>
                        <td><?php echo $item->NIK; ?></td>
                        <td><?php echo $item->NAMA; ?></td>
                        <td><?php echo $item->TEMPAT_LAHIR; ?> / <?php echo @$item->TGL_LAHIR && $item->TGL_LAHIR != '0000-00-00' ? tgl_format($item->TGL_LAHIR) : ''; ?></td>  
                        <td><?php echo $item->NAMA_JABATAN . ' - ' . $item->SUK_NAMA . ' - ' . $item->UK_NAMA . ' - ' . $item->INST_NAMA; ?></td>
                        <td><?php echo $agenda; ?></td>
                        <td width="120" nowrap="">
                            <?php if ($item->ID_LHKPN == ''): ?>
                            <?php else: ?>
                                <button type="button" class="btn btn-sm btn-default btnSelectPN" data-pn="<?php echo $item->ID_PN; ?>::<?php echo $item->NIK; ?>::<?php echo $agenda; ?>::<?php echo $item->EMAIL ?>" data-pn-nama="<?php echo $item->NAMA_TANPA_GELAR; ?>">pilih</button>
                            <?php endif ?>
                        </td>
                    </tr>
                    <?php
                    $end = $i;
                }
                ?>
            </tbody>
        </table>
    </div><!-- /.box-body -->
    <div class="box-footer clearfix">
        <?php
        if ($total_rows) {
            ?>
            <div class="col-sm-6">
                <div class="dataTables_info" id="datatable-1_info">Showing <?php echo $start; ?> to <?php echo $end; ?>
                    of <?php echo $total_rows; ?> entries
                </div>
            </div>
            <?php
        }
        ?>
        <div class="col-sm-6 text-right">
            <div class="dataTables_paginate paging_bootstrap paginationPN">
                <?php echo $pagination; ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php
} else {
    echo 'Data Not Found...';
}
?>
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
</style>

<script language="text/javascript">
    $(document).ready(function() {
        $('.btnSelectPN').click(function () {
            var DATAPN = $(this).attr('data-pn');
            var PN = DATAPN.split('::');
            $('#ajaxFormCari').find('#CARI_KODE').attr('value', PN[2]);
            CloseModalBox2();
        });
    });
</script>