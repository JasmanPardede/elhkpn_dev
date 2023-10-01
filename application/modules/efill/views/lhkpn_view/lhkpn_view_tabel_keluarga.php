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
<table class="table table-bordered table-hover table-striped">
    <thead class="table-header">
        <tr>
            <th width="10px">NO.</th>
            <th width="200px">NAMA ISTRI / SUAMI / ANAK</th>
            <th width="200px">TEMPAT & TANGGAL LAHIR / JENIS KELAMIN</th>
            <th width="200px">TEMPAT & TANGGAL NIKAH</th>
            <th width="200px">PEKERJAAN</th>
            <th width="200px">ALAMAT RUMAH / NOMOR TELEPON</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        foreach ($KELUARGAS as $keluarga) {
        ?>
        <tr>
            <td><?php echo ++$i; ?>.</td>
            <td><?php echo $keluarga->NAMA; ?><br>
                <?php
                switch ($keluarga->HUBUNGAN) {
                    case 1 : echo 'Istri';
                        break;
                    case 2 : echo 'Suami';
                        break;
                    case 3 : echo 'Anak Tanggungan';
                        break;
                    case 4 : echo 'Anak Bukan Tanggungan';
                        break;
                };
                ?>
            </td>
            <td><?php echo $keluarga->TEMPAT_LAHIR; ?><br>
                <?php echo $keluarga->TANGGAL_LAHIR; ?><br>
                <?php
                switch ($keluarga->JENIS_KELAMIN) {
                    case 'P' : echo 'Pria';
                        break;
                    case 'W' : echo 'Wanita';
                        break;
                };
                ?>
            </td>
            <td><?php echo $keluarga->TEMPAT_NIKAH; ?><br><?php echo $keluarga->TANGGAL_NIKAH; ?><br></td>
            <td><?php echo $keluarga->PEKERJAAN; ?></td>
            <td><?php echo $keluarga->ALAMAT_RUMAH; ?>
            <?php echo $keluarga->NOMOR_TELPON; ?></td>
            </tr>                    
            <?php
        }
        ?>
    </tbody>
</table>
<?php if (@$LHKPN->FILE_KK != '') { ?>
    <div class="col-sm-12"><label class="control-label">Kartu Keluarga:</label></div>
    <div class="col-sm-12">
        <div class="col-sm-1" style="margin-top:3px;width:120px;">
            <a href="<?php echo 'uploads/data_keluarga/'.$LHKPN->NIK.'/'.$LHKPN->FILE_KK; ?>" target="_BLANK"><i class="fa fa-file"></i> Kartu Keluarga (<?php echo ng::filesize_formatted('uploads/data_keluarga/'.$LHKPN->NIK.'/'.$LHKPN->FILE_KK); ?>)</a>
        </div>
    </div>
<?php } ?>