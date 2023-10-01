<div id="wrapperFormNonaktifkan">
    <div class="row">

        <div class="col-md-3">
            <?php if ($item != '') { ?>
                <img src="./uploads/data_pribadi/<?php echo$item->NIK ?>/<?php echo$item->FOTO ?>" class="img-rounded" alt="avatar" style="max-width:100px;"/>
            <?php } else { ?>
                <img src="http://10.102.2.22/elhkpndev/uploads/users/avatar.png" class="img-rounded" alt="avatar" style="max-width:100px;"/>
            <?php } ?>
        </div>

        <div class="col-md-9">
            <h3>Detail</h3>
            <table class="table-detail">
                <tr><td>NIK</td><td>:</td><td><?php echo $item->NIK; ?></td></tr>
                <tr><td>Nama</td><td>:</td><td><?php echo $item->NAMA; ?></td></tr>
                <tr><td>Jenis Kelamin</td><td>:</td><td>
                        <?php if ($item->JNS_KEL == 1) echo "LAKI_LAKI "; ?>
                        <?php if ($item->JNS_KEL == 2) echo "PEREMPUAN"; ?>
                    </td></tr>
                <tr><td>Tempat / Tanggal Lahir</td><td>:</td><td>
                        <?php echo $item->TEMPAT_LAHIR; ?> / <?php echo $item->TGL_LAHIR; ?>
                    </td></tr>
                <tr><td>Agama</td><td>:</td><td>
                        <?php if ($item->ID_AGAMA == 1) echo "ISLAM"; ?>
                        <?php if ($item->ID_AGAMA == 2) echo "KRISTEN"; ?>
                        <?php if ($item->ID_AGAMA == 3) echo "KALOTIK"; ?>
                        <?php if ($item->ID_AGAMA == 4) echo "HINDU"; ?>
                        <?php if ($item->ID_AGAMA == 5) echo "BUDHA"; ?>
                    <td></tr>
                <tr><td>Status Nikah</td><td>:</td><td>
                        <?php if ($item->ID_STATUS_NIKAH == 1) echo "KAWIN"; ?>
                        <?php if ($item->ID_STATUS_NIKAH == 2) echo "TIDAK KAWIN"; ?>
                        <?php if ($item->ID_STATUS_NIKAH == 3) echo "JANDA"; ?>
                        <?php if ($item->ID_STATUS_NIKAH == 4) echo "DUDA"; ?>

                    </td></tr>
                <tr><td>Pendidikan Terakhir</td><td>:</td><td>
                        <?php if ($item->ID_PENDIDIKAN == 1) echo "SD"; ?>
                        <?php if ($item->ID_PENDIDIKAN == 2) echo "SLTP"; ?>
                        <?php if ($item->ID_PENDIDIKAN == 3) echo "STLA"; ?>
                        <?php if ($item->ID_PENDIDIKAN == 4) echo "D3"; ?>
                        <?php if ($item->ID_PENDIDIKAN == 5) echo "S1/D4"; ?>
                        <?php if ($item->ID_PENDIDIKAN == 6) echo "S2"; ?>
                        <?php if ($item->ID_PENDIDIKAN == 7) echo "S4"; ?>
                    </td></tr>
                <tr><td>NPWP</td><td>:</td><td><?php echo $item->NPWP ?></td></tr>
                <tr><td>Alamat Tinggal</td><td>:</td><td><?php echo $item->ALAMAT_TINGGAL ?></td></tr>
                <tr><td>Email</td><td>:</td><td><?php echo $item->EMAIL ?></td></tr>
                <tr><td>No HP</td><td>:</td><td><?php echo $item->NO_HP; ?></td></tr>
                <tr>
                    <td>Status PN</td>
                    <td>:</td>
                    <td>
                        <?php echo $item->STATUS_PN == '1' ? 'PN/WL' : ($item->STATUS_PN == '2' ? 'Calon PN' : ''); ?>
                    </td>
                </tr>
            </table>
        </div>
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
                <?php
                $i = 1;
                foreach ($detJabatan as $key):
                    ?>
                    <tr>
                        <td><?php echo @$i++; ?></td>
                        <td><?php echo @$key->JABATAN; ?></td>
                        <td><?php echo @$key->INST_NAMA; ?></td>
                        <td><?php echo @$key->UK_NAMA; ?></td>
                        <td align="center"><?php echo @$key->TMT; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
    <form class="form-horizontal" method="post" id="ajaxFormNonaktifkan" action="index.php/ereg/all_pn/savepn" enctype="multipart/form-data">
        <div class="pull-right">
            <input type="hidden" name="ID_PN" id="ID_PN" value="<?php echo $item->ID_PN; ?>">
            <input type="hidden" name="act" id="act" value="dononaktif">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-share"></i>Simpan</button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        ng.formProcess($("#ajaxFormNonaktifkan"), 'insert', location.href.split('#')[1]);
    });
</script>