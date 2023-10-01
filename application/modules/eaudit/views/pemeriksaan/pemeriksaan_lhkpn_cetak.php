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
 * @package Views/ever/verification
*/
?>
<?php
    if($total_rows){
?>
<table border="1" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
    <thead>
        <tr>
            <th width="30">No.</th>
            <th>LHKPN</th>
            <th>PN</th>
            <th>Instansi</th>
            <!-- <th>Status</th> -->
        </tr>
    </thead>
    <tbody>
        <?php
            $i = 0 + $offset;
            $start = $i + 1;
            $aJenis = ['1' => 'Calon Penyelenggara Negara', '2' => 'Awal Menjabat', '3' => 'Akhir Menjabat', '4' => 'Sedang Menjabat'];
            foreach ($items as $item) {
        ?>
        <tr>
            <td><?php echo ++$i; ?>.</td>
            <td><?php echo $item->TGL_LAPOR;?> <br><?php echo $item->JENIS_LAPORAN == '4' ? 'Periodik' : 'Khusus'; ?> <?php echo $aJenis[$item->JENIS_LAPORAN];?></td>
            <td><?php echo $item->NAMA;?></td>
            <td><?php echo $item->JABATAN;?></td>
<!--             <td><?php 
                    echo ($item->IS_SUBMITED == '1' ? 'Diterima <small>'.date('d-m-Y', strtotime($item->SUBMITED_DATE)).'</small></br>' : '');
                    echo ($item->IS_VERIFIED == '1' ? 'Verifikasi <small>'.date('d-m-Y', strtotime($item->VERIFIED_DATE)).'</small></br>' : '');
                    echo ($item->IS_PUBLISHED == '1' ? 'Annauncement <small>'.date('d-m-Y', strtotime($item->PUBLISHED_DATE)).'</small></br>' : '');
                ?></td> -->
        </tr>
        <?php
                $end = $i;
            }
        ?>
    </tbody>
</table>
<?php
    }else{
        echo 'Data Not Found...';
    }
?>