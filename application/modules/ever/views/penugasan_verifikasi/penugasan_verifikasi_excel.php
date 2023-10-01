 <?php
 
 header("Content-type: application/vnd-ms-excel; charset=utf-8");
 header("Content-Disposition: attachment; filename=$title.xls");
 header("Pragma: no-cache");
 header("Expires: 0");
 header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
 header("Cache-Control: private",false);
 
 ?>
 
 <table border="1" width="100%">
 
      <thead>
           <tr>
                <th>NO.</th>
                <th>NAMA</th>
                <th>NO. AGENDA</th>
                <th>JABATAN</th>
                <th>UNIT KERJA</th>
                <th>LEMBAGA</th>
                <th>TANGGAL KIRIM FINAL</th>
                <th>DIVALIDASI / DIENTRI</th>
                <th>STATUS PENUGASAN</th>
           </tr>
      </thead>
 
      <tbody>
            <tr>
                <td>NO.</td>
                <td>NAMA</td>
                <td>NO. AGENDA</td>
                <td>JABATAN</td>
                <td>UNIT KERJA</td>
                <td>LEMBAGA</td>
                <td>TANGGAL KIRIM FINAL</td>
                <td>DIVALIDASI / DIENTRI</td>
                <td>STATUS PENUGASAN</td>
           </tr>
        <?php 
            // $no = 1;
            // foreach($dataState as $item){
            //     if($item->STAT == '' && ($item->STATUS == 1 || $item->STATUS == 2)) {
            //         $status_penugasan = 'Belum Ditugaskan';
            //     }else{
            //         if($item->STAT == 2 || $item->STATUS == 3) {
            //             $status_penugasan = 'Sudah Ditugaskan.<br />verifikator: ' . $item->USERNAME . ' (' . date('d/m/Y', strtotime($item->TANGGAL_PENUGASAN)) . ')';
            //         }else{
            //             $status_penugasan = $item->STAT;
            //         }
            //     }


            //     $agenda = date('Y', strtotime($item->tgl_lapor)) . '/' . ($item->JENIS_LAPORAN == '4' ? 'R' : 'K') . '/' . $item->NIK . '/' . $item->ID_LHKPN;
            //     echo '<tr>';
            //     echo '<td>'.$no++.'</td>
            //     <td>'.$item->NAMA_LENGKAP.'</td>
            //     <td>'.$agenda.'</td>
            //     <td>'.$item->NAMA_JABATAN.'</td>
            //     <td>'.$item->UK_NAMA.'</td>
            //     <td>'.$item->INST_NAMA.'</td>
            //     <td>'.date('d/m/Y', strtotime($item->tgl_kirim_final)).'</td>
            //     <td>'.($item->ENTRY_VIA == '0' ? $item->NAMA_LENGKAP : $item->USERNAME_ENTRI).'</td>
            //     <td>'.$status_penugasan.'</td>';
            //     echo '</tr>';
            // }
        ?>
           
      </tbody>
 
 </table>