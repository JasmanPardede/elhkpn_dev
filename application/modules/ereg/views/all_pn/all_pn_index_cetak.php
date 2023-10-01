<table class="table table-striped table-bordered table-hover table-heading no-border-bottom cek" border="1">
    <thead>
        <tr>
            <th width="30">No.</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Jabatan</th>
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
            <td><?php echo $item->NAMA; ?></td>
            <td>
                <?php
                if($item->NAMA_JABATAN){
                    $j = explode(',',$item->NAMA_JABATAN);
                    echo '<ul>';
                    foreach ($j as $ja) {
                        $jb = explode(':58:', $ja);
                        $idjb = $jb[0];
                        $statakhirjb = $jb[1];
                        $statakhirjbtext = $jb[2];
                        $statmutasijb = $jb[3];
                        // $linkMutasi = $jb[4] == $this->session->userdata('INST_SATKERKD') && $statakhirjb == ''?' <a href="index.php/ereg/pn/mts/'.$idjb.'" class="btn-mutasi">[mutasi]</a> ':' - '.$statakhirjbtext;
                        
                        $linkMutasi = $statakhirjb == '0' || $statakhirjb == '' || $statakhirjb == null?'  ':' - <span class="badge">'.$statakhirjbtext.'</span>';
                        if($statmutasijb != null && $statmutasijb != ''){
                            $linkMutasi = ' - <span class="badge">sedang proses mutasi</span>';
                        }
                        echo '<li>'.' '.$jb[5].$linkMutasi.'</li>';
                    }
                    echo '</ul>';
                }
                ?>

            </td>
        </tr>
        <?php
                $end = $i;
            }
        ?>
    </tbody>
</table>