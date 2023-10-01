<?php
$email = $name = str_replace(' ', '', $dataRow->EMAIL);
if ($email == "" || $email == NULL) {
    $xEmail = 'Offline';
} else {
    $xEmail = 'Online';
    $nohp = $dataRow->NO_HP;
    if ($nohp == "" || $nohp == NULL) {
        $xEmail = 'Offline';
    }
}

$nik_8 = substr(trim($dataRow->NIK), 0, -8);
$nik_16 = substr(trim($dataRow->NIK), -8);
$xJenisKelamin = $dataRow->JNS_KEL;
if ($xJenisKelamin == "1" || $xJenisKelamin == NULL) {
    $xJenisKelamin = "Laki-laki";
} else {
    $xJenisKelamin = "Perempuan";
}
?>
<Row ss:Index="<?php echo $rowSsIndex; ?>" ss:AutoFitHeight="0">
    <Cell ss:StyleID="s03" ss:Index="1"><Data ss:Type="String"><?php echo $no; ?></Data></Cell>
    <Cell ss:StyleID="<?php echo ($nik_8 . $nik_16) == "" ? "s06" : "s05"; ?>" ss:Index="2"><Data ss:Type="String"><?php echo $nik_8 . $nik_16." "; ?></Data></Cell>
    <Cell ss:StyleID="s03" ss:Index="3"><Data ss:Type="String"><?php echo trim($dataRow->NIP_NRP)." "; ?></Data></Cell>
    <Cell ss:StyleID="<?php echo trim($dataRow->NAMA) == "" ? "s06" : "s05"; ?>" ss:Index="4"><Data ss:Type="String"><?php echo trim($dataRow->NAMA); ?></Data></Cell>
    <Cell ss:StyleID="<?php echo trim($dataRow->UK_NAMA) == "" ? "s06" : "s05"; ?>" ss:Index="5"><Data ss:Type="String"><?php echo trim($dataRow->UK_NAMA); ?></Data></Cell>
    <Cell ss:StyleID="<?php echo trim($dataRow->SUK_NAMA) == "" ? "s06" : "s05"; ?>" ss:Index="6"><Data ss:Type="String"><?php echo trim($dataRow->SUK_NAMA); ?></Data></Cell>
    <Cell ss:StyleID="<?php echo trim($dataRow->NAMA_JABATAN != "unknown" ? $dataRow->NAMA_JABATAN : "") == "" ? "s06" : "s05"; ?>" ss:Index="7"><Data ss:Type="String"><?php echo $dataRow->NAMA_JABATAN != "unknown" ? trim($dataRow->NAMA_JABATAN) : ""; ?></Data></Cell>
    <Cell ss:StyleID="s03" ss:Index="8"><Data ss:Type="String"><?php echo trim($dataRow->EMAIL); ?></Data></Cell>
    <Cell ss:StyleID="s03" ss:Index="9"><Data ss:Type="String"><?php echo trim($dataRow->NO_HP)." "; ?></Data></Cell>
    <Cell ss:StyleID="<?php echo trim($xJenisKelamin) == "" ? "s06" : "s05"; ?>" ss:Index="10"><Data ss:Type="String"><?php echo $xJenisKelamin; ?></Data></Cell>
    <Cell ss:StyleID="<?php echo trim($dataRow->TEMPAT_LAHIR) == "" ? "s06" : "s05"; ?>" ss:Index="11"><Data ss:Type="String"><?php echo trim($dataRow->TEMPAT_LAHIR); ?></Data></Cell>
    <Cell ss:StyleID="<?php echo trim($dataRow->TGL_LAHIR) == "" ? "s06" : "s05"; ?>" ss:Index="12"><Data ss:Type="String"><?php echo date('d-m-Y', strtotime($dataRow->TGL_LAHIR)); ?></Data></Cell>
</Row>