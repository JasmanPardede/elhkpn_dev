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
 * @package Views/efill/lhkpn_view
*/
?>
<div class="box-body" id="wrapperLampiran1">
        <!-- <button type="button" class="btn btn-sm btn-default btn-add btn-primary" href="index.php/efill/lhkpn/add_lampiran1/<?php echo $id_lhkpn;?>"><i class="fa fa-plus"></i> Tambah</button> -->
        <div class="box-tools" style="display:none;">
           <form method="post" id="ajaxFormCari13" action="">
               <div class="input-group  pull-right" style="width: 150px;">
                   <input type="text" name="cari" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search by pihak ke 2" value="<?=@$cari?>" id="cari"/>
                   <div class="input-group-btn">
                       <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                       <button type="button" class="btn btn-sm btn-default" id="ajaxClearCari13">Clear</button>
                   </div>
               </div>
           </form>
        </div>
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-header">
                <tr>
                    <th width="10px">NO</th>
                    <th>KODE JENIS</th>
                    <th>TANGGAL TRANSAKSI</th>
                    <th>URAIAN HARTA KEKAYAAN / ATAS NAMA</th>
                    <th>NILAI PENJUALAN/ PELEPASAN / PENERIMAAN</th>
                    <th>INFORMASI PIHAK KEDUA</th>
                    <!-- <th rowspan="2" width='130px'>Aksi</th> -->
                </tr>
            </thead>
            <tbody>
                <?php $no = 0; ?>
                <?php foreach ($lampiran_pelepasan as $key) {
                    $no++;
                    ?>
                    <tr>
                        <td><?=$no?></td>
                        <td><?=$key['KODE_JENIS'];?></td>
                        <td><?=$key['TGL_TRANSAKSI'];?></td>
                        <td><?=$key['URAIAN_HARTA'];?></td>
                        <td>
                            <div class="row">
                                <div class="col-md-2">Rp</div>
                                <div class="col-md-10"><?php echo number_format($key['NILAI'],0,'','.');?></div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div><label>Nama:</label></div>
                                <div><?=$key['PIHAK_DUA'];?></div>
                            </div>
                            <div>
                                <div><label>Alamat:</label></div>
                                <div><?=$key['ALAMAT'];?></div>
                            </div>
                        </td>
                        <!-- <td style="text-align:center">
                            <button type="button" class="btn btn-sm btn-default btn-detail" href="index.php/efill/lhkpn/detail_lampiran1/10" title="Detail"><i class="fa fa-search-plus"></i></button>
                            <button type="button" class="btn btn-sm btn-default btn-edit" href="index.php/efill/lhkpn/edit_lampiran1/10" title="Edit"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-sm btn-default btn-delete" href="index.php/efill/lhkpn/delete_lampiran1/10" title="Delete"><i class="fa fa-trash"  style="color:red;"></i></button>
                        </td> -->
                    </tr>
                    <?php
                } ?>
            </tbody>
        </table>
</div><!-- /.box-body -->
<div class="box-footer">
</div><!-- /.box-footer -->