<?php

/**
 * must be embed in all_pn_form.php
 */

if ($form == 'detail_old') {
    ?>
    <div id="wrapperFormDetail">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-3">
                <?php if (file_exists("uploads/data_pribadi/" . $item->NIK . "/" . $item->FOTO) && !empty($item->FOTO)) { ?>
                    <img src='<?php echo base_url("uploads/data_pribadi/" . $item->NIK . "/" . $item->FOTO); ?>' class="img-rounded col-md-12"/>
                <?php } else { ?>
                    <img src="<?php echo base_url(); ?>images/no_available_image.png" class="img-rounded col-md-12"/>
    <?php } ?>
            </div>
            <div class="col-md-8">
                <h3>Detail</h3>
                <table class="table-detail">
                    <tr>
                        <td>NIK</td><td>:</td><td><?php echo $item->NIK; ?></td>
                        <!-- <td><b>Negara</b></td><td>:</td><td>
                        <?php if ($item->NEGARA == 2) echo "Indonesia"; ?>
                        <?php
                        if ($item->NEGARA != 2) {
                            $sql = "SELECT * FROM M_NEGARA WHERE ID = '$item->LOKASI_NEGARA'";
                            $negara = $this->db->query($sql)->row();
                            echo $negara->NAMA_NEGARA;
                        }
                        ?>
                        </td> -->
                    </tr>
                    <tr>
                        <td>Nama</td><td>:</td><td><?php echo $item->NAMA; ?></td>
                            <?php if ($item->NEGARA == 2) { ?>
                            <td><b>Provinsi</b></td>
                            <td>:</td>
                            <td>
                                <?php
                                $prov = explode(" ", $item->PROVINSI);
                                $provi = "";
                                foreach ($prov as $key) {
                                    if ($key == 'DKI' || $key == "DI") {
                                        $provi .= ucfirst($key) . " ";
                                    } else {
                                        $provi .= ucfirst(strtolower($key)) . " ";
                                    }
                                }
                                echo $provi;
                                ?>
                            </td>
                        <?php } else { ?>
                            <td><b>Alamat Tinggal</b></td>
                            <td>:</td>
                            <td><?php echo $item->ALAMAT_TINGGAL ?></td>
    <?php } ?>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td><td>:</td>
                        <td>
                        <?php if ($item->JNS_KEL == 1) echo "Laki - laki "; ?>
    <?php if ($item->JNS_KEL == 2) echo "Perempuan"; ?>
                        </td>
                            <?php if ($item->NEGARA == 2) { ?>
                            <td><b>Kabupaten / Kota</b></td>
                            <td>:</td>
                            <td>
                                <?php
                                $kabkot = explode(" ", $item->KABKOT);
                                foreach ($kabkot as $key) {
                                    echo ucfirst(strtolower($key)) . " ";
                                }
                                ?>
                            </td>
    <?php } else { ?>
                            <td><b>Email</b></td><td>:</td><td><?php echo $item->EMAIL ?></td>
                            <?php } ?>
                    </tr>
                    <tr>
                        <td>Tempat / Tanggal Lahir</td><td>:</td><td>
    <?php echo $item->TEMPAT_LAHIR; ?> / <?php echo tgl_format($item->TGL_LAHIR); ?>
                        </td>
                            <?php if ($item->NEGARA == 2) { ?>
                            <td><b>Kecamatan</b></td>
                            <td>:</td>
                            <td>
                                <?php
                                echo $item->KEC;
                                // $kec = explode(" ", $item->KEC);
                                // foreach ($kec as $key) {
                                //     echo ucfirst(strtolower($key))." ";
                                // }
                                ?>
                            </td>
    <?php } else { ?>
                            <td><b>NO HP</b></td><td>:</td><td><?php echo $item->NO_HP; ?></td>
    <?php } ?>
                    </tr>
                    <tr>
                        <!-- <td>Agama</td><td>:</td>
                        <td>
    <?php echo $agama[0]->AGAMA; ?>
                        </td> -->
                            <?php if ($item->NEGARA == 2) { ?>
                            <td><b>Kelurahan</b></td>
                            <td>:</td>
                            <td>
                                <?php
                                echo $item->KEL;
                                // $kel = explode(" ", $item->KEL);
                                // foreach ($kel as $key) {
                                //     echo ucfirst(strtolower($key))." ";
                                // }
                                ?>
                            </td>
    <?php } ?>

                    </tr>
                    <tr>
                        <td>Status Nikah</td><td>:</td><td>
    <?php echo ucfirst(strtolower($sttnikah[0]->STATUS_NIKAH)) ?>
                        </td>
                        <?php if ($item->NEGARA == 2) { ?>
                            <td><b>Alamat Tinggal</b></td>
                            <td>:</td>
                            <td><?php echo $item->ALAMAT_TINGGAL ?></td>
    <?php } ?>
                    </tr>
                    <tr>
                        <td>Pendidikan Terakhir</td><td>:</td>
                        <td>
                        <?php echo $penhir[0]->PENDIDIKAN ?>
                        </td>
    <?php if ($item->NEGARA == 2) { ?>
                            <td><b>Email</b></td><td>:</td><td><?php echo $item->EMAIL ?></td>
    <?php } ?>
                    </tr>
                    <tr>
                        <td>NPWP</td><td>:</td>
                        <td><?php echo $item->NPWP ?></td>
    <?php if ($item->NEGARA == 2) { ?>
                            <td><b>NO HP</b></td><td>:</td><td><?php echo $item->NO_HP; ?></td>
    <?php } ?>
                    </tr>
                </table>
            </div>
        </div>
        <br />
        <br />
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <table class="table table-bordered table-hover table-striped" >
                    <thead class="table-header">
                        <tr>
                            <th width="30">No.</th>
                            <th>Jabatan/Eselon</th>
                            <th>Lembaga</th>
                            <th>Unit Kerja</th>
                            <th>TMT/SD</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php $i = 1;
    foreach ($detJabatan as $key): ?>
                            <tr>
                                <td><?php echo @$i++ . '.'; ?></td>
                                <td><?php echo @$key->IS_CALON . ' ' . @$key->NAMA_JABATAN . ' / ' . @$key->ESELON; ?></td>
                                <td><?php echo @$key->INST_NAMA; ?></td>
                                <td><?php echo @$key->UK_NAMA; ?></td>
                                <td align="center">
                                    <?php if (@$key->SD == '' || date('Y', strtotime(@$key->SD)) == '1970') { ?>
            <?php echo @date('d/m/Y', strtotime(@$key->TMT)); ?>
                            <?php } else { ?>
                                <?php echo @date('d/m/Y', strtotime(@$key->TMT)); ?> - <?php echo date('d/m/Y', strtotime(@$key->SD)); ?>
        <?php } ?>
                                </td>
                            </tr>
    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-1"></div>

        </div>
        <div class="pull-right">
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>
    </div>
    <?php
}
?>