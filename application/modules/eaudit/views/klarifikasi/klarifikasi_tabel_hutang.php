<div class="box-header with-border portlet-header title-pribadi">
    <h5 class="">"Hutang apabila ada"</h5>
</div>
<br>&nbsp;&nbsp;&nbsp;<button class="btn btn-info aksi-hide" id="klarifikasiHutang" href="index.php/eaudit/klarifikasi/update_hutang/<?php echo $new_id_lhkpn;?>/0/new"><span class="fa fa-plus"></span> Tambah</button><br><br>
<div class="box-body" id="wrapperHutang">
    <table class="table borderless" id="tableHutang">
        <thead class="table-header">
            <tr>
                <th>NO</th>
                <th>URAIAN</th>
                <th>NAMA KREDITUR</th>
                <th>BENTUK AGUNAN/ NO KARTU KREDIT</th>
                <th width="15%">NILAI AWAL HUTANG</th>
                <th width="15%">NILAI SALDO HUTANG</th>
                <th class="aksi-hide">AKSI</th>
            </tr>                 
        </thead>
        <tbody>
            <?php if ($lhkpn_hutang): ?>
            <?php
            $survey = 'ok';
            $tot = 0;
            $i = 0;
            $stat = ['1'=>'Tetap','2'=>'Ubah','3'=>'Baru'];
            foreach ($lhkpn_hutang as $hutang) {




                ////////////////atas nama & pasangan anak//////////////////
                $get_atas_nama = $hutang->ATAS_NAMA;
                $atas_nama = '';  
                $get_atas_nama = check_atas_nama($get_atas_nama);
                if(strstr($get_atas_nama, "5")){    
                    $atas_nama = '<b>'.substr($get_atas_nama,2).'</b>';
                }               
                if(strstr($get_atas_nama, "1")){
                    $atas_nama = '<b>PN YANG BERSANGKUTAN</b>';
                }        
                if(strstr($get_atas_nama, "2")){
                    
                    $pasangan_array = explode(',', $hutang->PASANGAN_ANAK);
                    $get_list_pasangan = '';
                    $loop_first_pasangan = 0;
                    foreach($pasangan_array as $ps){
                        $sql_pasangan_anak = "SELECT NAMA FROM t_lhkpn_keluarga WHERE ID_KELUARGA = '$ps'";
                        $data_pasangan_anak = $this->db->query($sql_pasangan_anak)->result_array();
                        if($loop_first_pasangan==0){
                            $get_list_pasangan = $data_pasangan_anak[0]['NAMA'];
                        }else{
                            $get_list_pasangan = $get_list_pasangan.',<br> '.$data_pasangan_anak[0]['NAMA'];
                        }
                        $loop_first_pasangan++;
                    }
                    $show_pasangan = $get_list_pasangan;
                    if($atas_nama==''){
                        $atas_nama = $atas_nama.'<b>PASANGAN/ANAK</b> ('.$show_pasangan.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>PASANGAN/ANAK</b> ('.$show_pasangan.')';
                    }
                }
                if(strstr($get_atas_nama, "3")){
                    if($atas_nama==''){
                        $atas_nama = $atas_nama.'<b>LAINNYA</b> ('.$hutang->KET_LAINNYA.')';
                    }else{
                        $atas_nama = $atas_nama.', <b>LAINNYA</b> ('.$hutang->KET_LAINNYA.')' ;
                    }
                }
                ////////////////atas nama & pasangan anak//////////////////


                $tot += $hutang->SALDO_HUTANG;
                if ($i%2 == 0) {
                    $bgcolor = 'bgcolor="#f9f9f9"';
                }
                else{
                    $bgcolor = '';
                }
                ?>
                <tr <?php echo $bgcolor; ?>>
                    <td><?php echo ++$i ?>.</td>
                    <td><?php
                        $jenis = "
					<table class='table-child table-condensed'>
						<tr>
						    <td><b>Jenis</b></td>
                                                    <td>:</td>
                                                    <td>" . $hutang->NAMA . "</td>
						 </tr>
						 <tr>
						    <td><b>Atas Nama</b></td>
                                                    <td>:</td>
                                                    <td>" . $atas_nama . "</td>
						 </tr>
					</table>
				";

                        echo $jenis;
                        
                        ?>
                    </td>
                    <td><?php echo $hutang->NAMA_KREDITUR; ?></td>
                    <td><?php echo $hutang->AGUNAN; ?></td>
                    <td align="right">Rp. <?php echo number_format($hutang->AWAL_HUTANG, 0, '', '.'); ?></td>
                    <td>
                        <div class="row">
                            <div class="col-md-12">
                                <b>  Nilai Saldo Hutang</b>
                            </div>
                            <div class="col-md-12" align="right">
                                Rp. <?php echo number_format($hutang->SALDO_HUTANG_OLD, 0, '', '.'); ?><br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <b>  Nilai Klarifikasi Hutang</b>
                            </div>
                            <div class="col-md-12" align="right">
                                Rp. <?php echo number_format($hutang->SALDO_HUTANG, 0, '', '.'); ?>
                            </div>
                        </div>
                    </td>
                    <td align="center" class="aksi-hide">
                        <button type="button" id="verifEditDataHTBEdit" class="btn btn-primary" href="index.php/eaudit/klarifikasi/update_hutang/<?php echo $new_id_lhkpn;?>/<?php echo $hutang->ID_HUTANG; ?>/edit" title="Edit Data" onclick="onButton.go(this, 'large', true);"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-danger delete-action" id_hutang="<?php echo $hutang->ID_HUTANG; ?>" href="#" title="Hapus Data"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                <?php if ($hutang->KET_PEMERIKSAAN != ''): ?>
                    <tr <?php echo $bgcolor; ?>>
                        <td></td>
                        <td></td>
                        <td <?php echo $is_preview ? 'colspan="4"' : 'colspan="5"'; ?>><strong>Keterangan Pemeriksaan: </strong><?php echo $hutang->KET_PEMERIKSAAN; ?></td>
                        <td></td>
                    </tr>
                <?php endif ?>
            <?php } ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Data Tidak Ditemukan</td>
                </tr>
            <?php endif ?>
        </tbody>
        <tfoot class="table-footer">
            <?php if ($lhkpn_hutang): ?>
            <tr>
                <td <?php echo $is_preview ? 'colspan="5"' : 'colspan="6"'; ?> style="text-align: right;"><b>Total</b></td>
                <td style="text-align: right;"><b>Rp. <?php echo number_format($tot, 0, '', '.'); ?></b></td>
                <td></td>
            </tr>
            <?php endif ?>
        </tfoot>
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#klarifikasiHutang").click(function() {
            url = $(this).attr('href');
            $('#loader_area').show();
            $.post(url, function (html) {
                OpenModalBox('Input Klarifikasi Data Hutang', html, null, 'large');
            });            
            return false;
        });
    });

    // DELETE ACTION
    $("#tableHutang tbody").on('click', '.delete-action', function (event) {
        var id = $(this).attr('id_hutang');
        confirm("Apakah anda yakin menghapus data ? ", function () {
            do_delete('eaudit/klarifikasi/soft_delete/' + id + '/hutang', 'Data Hutang Berhasil dihapus ');
            $("#modal-success").removeClass("modal-lg"); 
            $("#modal-warning").removeClass("modal-lg"); 
            return false;
                
            });
        });

</script>
