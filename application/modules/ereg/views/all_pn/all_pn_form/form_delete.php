<div id="wrapperFormDelete">
    Benarkah Akan Mengofflinekan PN/WL dibawah ini ?
    <!-- <form method="post" id="ajaxFormDelete" action="index.php/ereg/all_pn/savepn"> -->
    <form method="post" id="ajaxFormDelete" action="index.php/ereg/all_pn/do_nonact_pn/<?php echo $item->ID_PN . '/' . $idjpn; ?>">
        <table class="table-detail">
            <tr><td>NIK</td><td>:</td><td><?php echo $item->NIK; ?></td></tr>
            <tr><td>Nama</td><td>:</td><td><?php echo @$item->GELAR_DEPAN . ' ' . $item->NAMA . ' ' . @$item->GELAR_BELAKANG; ?></td></tr>
            <tr><td>Jenis Kelamin</td><td>:</td><td>
                    <?php if ($item->JNS_KEL == 1) echo "LAKI_LAKI "; ?>
                    <?php if ($item->JNS_KEL == 2) echo "PEREMPUAN"; ?>
                </td></tr>
            <tr><td>Tempat , Tanggal Lahir</td><td>:</td><td>
                    <?php echo $item->TEMPAT_LAHIR; ?> , <?php echo @date('d/m/Y', strtotime(@$item->TGL_LAHIR)); ?>
                </td></tr>
        <!-- <tr><td>Agama</td><td>:</td><td>
            <?php if ($item->ID_AGAMA == 1) echo "ISLAM"; ?>
            <?php if ($item->ID_AGAMA == 2) echo "KRISTEN"; ?>
            <?php if ($item->ID_AGAMA == 3) echo "KALOTIK"; ?>
            <?php if ($item->ID_AGAMA == 4) echo "HINDU"; ?>
            <?php if ($item->ID_AGAMA == 5) echo "BUDHA"; ?>
        <td></tr> -->
<!-- 			<tr><td>Status Nikah</td><td>:</td><td>
            <?php if ($item->ID_STATUS_NIKAH == 1) echo "KAWIN"; ?>
            <?php if ($item->ID_STATUS_NIKAH == 2) echo "TIDAK KAWIN"; ?>
            <?php if ($item->ID_STATUS_NIKAH == 3) echo "JANDA"; ?>
            <?php if ($item->ID_STATUS_NIKAH == 4) echo "DUDA"; ?>
        </td></tr> -->
<!-- 		<tr><td>Pendidikan Terakhir</td><td>:</td><td>
            <?php if ($item->ID_PENDIDIKAN == 1) echo "SD"; ?>
            <?php if ($item->ID_PENDIDIKAN == 2) echo "SLTP"; ?>
            <?php if ($item->ID_PENDIDIKAN == 3) echo "STLA"; ?>
            <?php if ($item->ID_PENDIDIKAN == 4) echo "D3"; ?>
            <?php if ($item->ID_PENDIDIKAN == 5) echo "S1/D4"; ?>
            <?php if ($item->ID_PENDIDIKAN == 6) echo "S2"; ?>
            <?php if ($item->ID_PENDIDIKAN == 7) echo "S4"; ?>
        </td></tr> -->
        <!-- <tr><td>NPWP</td><td>:</td><td><?php echo $item->NPWP ?></td></tr> -->
        <!-- <tr><td>Alamat Tinggal</td><td>:</td><td><?php echo $item->ALAMAT_TINGGAL ?></td></tr> -->
            <tr><td>Email</td><td>:</td><td><?php echo $item->EMAIL ?></td></tr>
            <tr><td>NO HP</td><td>:</td><td><?php echo $item->NO_HP; ?></td></tr>
            <!-- <tr><td>FOTO</td><td>:</td><td> -->
            <?php
            //if($item->FOTO != "")
            // echo "<img src='./uploads/data_pribadi/".$item->NIK."/".$item->FOTO."' width='64'>";
            ?>
        </table>
        <div class="pull-right">
            <input type="hidden" name="ID_PN" value="<?php echo $item->ID_PN; ?>">
            <input type="hidden" name="act" value="dodelete">
            <button type="submit" class="btn btn-sm btn-primary">Set Offline WL</button>
            <button type="reset" class="btn btn-danger btn-sm " onclick="CloseModalBox2();"><i class="fa fa-remove"></i>Batal</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        ng.formProcess($("#ajaxFormDelete"), 'delete', location.href.split('#')[1]);
    });
</script>