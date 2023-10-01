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
<div class="box-body" id="wrapperJabatan">
<div class="box-header with-border title-alat">
    <h5 class="">"Informasi Jabatan Saat Ini"</h5>
</div>
<table class="table table-bordered table-hover table-striped" >
	<thead class="table-header">
		<tr>
            <th width="30">NO</th>
            <th>JABATAN - DESKRIPSI JABATAN / ESELON</th>
            <th>LEMBAGA</th>
            <th>UNIT KERJA</th>
            <th>TMT/SD</th>
            <th>FILE SK</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        foreach ($JABATANS as $jabatan) {
        ?>
        <tr>
            <td><?php echo ++$i; ?>.</td>
            <td><?php echo $jabatan->NAMA_JABATAN; ?> - <?php echo $jabatan->DESKRIPSI_JABATAN; ?> /
            <?php 
            switch ($jabatan->ESELON) {
                case '1':
                    echo 'I';
                    break;
                case '2':
                    echo 'II';
                    break;
                case '3':
                    echo 'III';
                    break;
                case '4':
                    echo 'IV';
                    break;
                case '5':
                    echo 'Non Eselon';
                    break;
            }
            ?>
            </td>
            <td><?php echo $jabatan->INST_NAMA; ?></td>
            <td><?php echo $jabatan->UK_NAMA; ?></td>
            <td align="center"><?php echo date('d/m/Y',strtotime($jabatan->TMT)); ?> <?php echo $jabatan->SD=='0000/00/00' || $jabatan->SD=='' || $jabatan->SD==NULL || date('Y',strtotime($jabatan->SD))=='1970' || date('Y',strtotime($jabatan->SD))=='-0001' ? '<br />s/d<br /> sekarang':'<br />s/d<br />'.date('d/m/Y',strtotime($jabatan->SD)); ?></td>
            <td align="center">
                <?php
                if($jabatan->FILE_SK){
                ?>
                    <a href="<?php echo $jabatan->FILE_SK; ?>" target="_BLANK"><i class="fa fa-file"></i> <?php echo ng::filesize_formatted($jabatan->FILE_SK); ?></a>
                <?php
                }
                ?>
            </td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>
</div>