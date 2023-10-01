<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
    <thead>
        <tr>
            <th colspan="1" rowspan="2">No.</th>
            <th colspan="1" rowspan="2">Nip</th>
            <th rowspan="2">Nama</th>
            <th colspan="2" rowspan="1">Sebelum</th>
            <th colspan="2" rowspan="1">Tujuan</th>
            <th colspan="1" rowspan="2">Status Approval</th>
            <th colspan="1" rowspan="2">Status Mutasi</th>
            <th colspan="1" rowspan="2" width="50px">Aksi</th>
        </tr>
        <tr>
            <th>Jabatan</th>
            <th>Instansi</th>
            <th>Jabatan</th>
            <th>Instansi</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $i = 0;
            $start = $i + 1;
            $CI =& get_instance();
            $CI->load->model('mmutasi');

            foreach ($items as $item) {
        ?>
        <tr>
            <td><?php echo  ++$i; ?></td>
            <td><?php echo  $item->NIK; ?></td>
            <td><?php echo  $item->NAMA; ?></td>
            <td><?php echo  $CI->mmutasi->get_nm_jabatan($item->JABATAN_LAMA); ?></td>
            <td><?php echo  $CI->mmutasi->get_nm_instansi($item->ID_INST_ASAL); ?></td>
            <td><?php echo  $CI->mmutasi->get_nm_jabatan($item->JABATAN_BARU); ?></td>
            <td><?php echo  $CI->mmutasi->get_nm_instansi($item->ID_INST_TUJUAN); ?></td>
            <td><?php echo  ($item->STATUS_APPROVAL==0) ? "MENUNGGU" : "APPROVED"; ?></td>
            <td><?=$item->STATUS_JABAT?></td>
            <td width="120" nowrap="">
                <button type="button" class="btn btn-sm btn-default btn-detail"
                href="index.php/ereg/all_pn/approvmutasi_table/<?php echo  $item->ID_MUTASI; ?>" title="Approved"><i
                class="fa fa-check" style="color:green;"></i></button>

<!--                <button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/ereg/all_pn/tolakmutasi/--><?php //echo $item->ID_MUTASI;?><!--" title="Tolak">-->
<!--                    <img src="--><?//= base_url('img/stop.png');?><!--" style="width:15px;height15px;">-->
<!--                </button>-->
            </td>
        </tr>
        <?php
                $end = $i;
            }
        ?>
    </tbody>
</table>
<script language="javascript">
    $(document).ready(function () {
        $(".btn-detail").click(function () {
            var url = $(this).attr('href');
            $.get(url, function(data){
                $('.modal-dialog').animate({
                    width: '-=500'
                });
                $('#table-jbt').hide('slow', function(){
                    $('#app').html(data);
                    $('#app').show('slow');
                });
            });
        });
    });
</script>