<?php
if ($total_rows) {
    ?>
    <div class="box-body" id="divHasilCariPN">
        <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
            <thead>
                <tr>
                    <th width="30">No.</th>
                    <th>NIK</th>
                    <th>Nama/EMAIL</th>
                    <th>Tempat<br />/Tanggal Lahir</th>
    <!-- 				<th>Tempat / Tanggal Lahir</th>
                    <th>Email/HP</th> -->
                    <th>Jabatan</th>
                    <th>WL TAHUN</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0 + $offset;
                $start = $i + 1;
                $end = 0;
                foreach ($items as $item) {
                    ?>
                    <tr>
                        <td><?php echo ++$i; ?>.</td>
                        <td><?php echo $item->NIK; ?></td>
                        <td><?php echo $item->NAMA; ?><br /><br /><b>Email :</b> <?php echo beautify_text($item->EMAIL); ?></td>
                        <td><?php echo $item->TEMPAT_LAHIR.", ".$item->TGL_LAHIR; ?><br /><br /><b>Email :</b> <?php echo beautify_text($item->EMAIL); ?></td>
                        <td><?php echo $item->NAMA_JABATAN . ' - ' . $item->SUK_NAMA . ' - ' . $item->UK_NAMA . ' - ' . $item->INST_NAMA; ?></td>
                        <td><?php echo $item->TAHUN_WL; ?></td>
        <!--                 <td><?php echo $item->TEMPAT_LAHIR; ?> / <?php echo @$item->TGL_LAHIR && $item->TGL_LAHIR != '0000-00-00' ? date('d-m-Y', strtotime($item->TGL_LAHIR)) : ''; ?></td>
                                        <td>
                        <?php
                        if (@$item->EMAIL) {
                            ?>
                                                <a href="mailto:<?php echo $item->EMAIL; ?>"><i class="fa fa-envelope"></i> <?php echo $item->EMAIL; ?></a><br>
                            <?php
                        }
                        ?>
                        <?php
                        if (@$item->NO_HP) {
                            ?>
                                                <a href="tel:<?php echo $item->NO_HP; ?>"><i class="fa fa-phone"></i> <?php echo $item->NO_HP; ?></a>
                            <?php
                        }
                        ?>
                        </td> -->
        <!--                <td>
                        <?php
                        if ($item->NAMA_JABATAN) {
                            $j = explode(':|||:', $item->NAMA_JABATAN);
                            // echo '<pre>';
                            // print_r($j);
                            echo '<ul class="listjabatan">';
                            foreach ($j as $ja) {
                                $jb = explode(':||:', $ja);
                                $ID = @$jb[0];
                                $ID_STATUS_AKHIR_JABAT = @$jb[1];
                                $STATUS = @$jb[2];
                                $ID_PN_JABATAN = @$jb[3] != 'NULL' ? @$jb[3] : null;
                                $LEMBAGA = @$jb[4];
                                $JABATAN = ucwords(strtoupper(@$jb[5]));
                                $TMT = @$jb[6];
                                $SD = @$jb[7];
                                $IS_CALON = @$jb[8];
                                $INST_TUJUAN = @$jb[9];

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

                                switch ($pnposisi) {
                                    case 'calon' :
                                        $out = ' <span class="label label-warning">Calon</span> ';
                                        $out .= $JABATAN;
                                        break;
                                    case 'aktif' :
                                        break;
                                    case 'mutasi' :
                                        $out .= ' - <span class="label label-warning">sedang proses mutasi ke ' . $INST_TUJUAN . '</span>';
                                        break;
                                    case 'berakhir' :
                                        switch (strtolower($STATUS)) {
                                            case 'mutasi':
                                                $labelstyle = 'label-primary';
                                                break;
                                            case 'promosi':
                                                $labelstyle = 'label-success';
                                                break;
                                            case 'non wl':
                                                $labelstyle = 'label-danger';
                                                break;
                                            default:
                                                $labelstyle = 'label-danger';
                                                break;
                                        }

                                        if ($IS_CALON == 1) {
                                            $out = ' <span class="label label-warning">Calon</span> ';
                                            $out .= '<span class="label ' . $labelstyle . '">' . $STATUS . '</span> ';
                                            $out .= $JABATAN;
                                            $out .= ' S/D : ' . date('d-m-Y', strtotime($SD));
                                        } else {
                                            $out .= ' S/D : ' . date('d-m-Y', strtotime($SD)) . ' - <span class="label ' . $labelstyle . '">' . $STATUS . '</span> ';
                                        }
                                        break;
                                }
                                echo '<li class="item">' . $out . '<div class="clearfix"></div></li>';
                            }
                            echo '</ul>';
                        }
                        ?>
                                    </td>-->
                        <td width="120" nowrap="">
                            <button type="button" class="btn btn-sm btn-default btnSelectPN" data-pn="<?php echo beautify_text($item->ID_PN); ?>::<?php echo beautify_text($item->NIK); ?>::<?php echo beautify_text($item->NAMA); ?>::<?php echo strtolower(beautify_text($item->EMAIL)); ?>" data-pn-instansi="<?php echo $item->INST_NAMA; ?>" data-pn-nama="<?php echo $item->NAMA_TANPA_GELAR; ?>">pilih</button>
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
    #divHasilCariPN{
        height: 280px;
        overflow: auto;
    }
</style>
