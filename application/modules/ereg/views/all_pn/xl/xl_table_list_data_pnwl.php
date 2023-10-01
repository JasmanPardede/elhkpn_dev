<Worksheet ss:Name="pnwl">
    <Table ss:ExpandedColumnCount="16" ss:ExpandedRowCount="<?php echo $tblRowCount; ?>" x:FullColumns="1"
           x:FullRows="1" ss:DefaultRowHeight="15">
        <Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="96.75"/>
        <Column ss:AutoFitWidth="0" ss:Width="144.75"/>
        <Column ss:AutoFitWidth="0" ss:Width="143.25"/>
        <Column ss:Width="249.75"/>
        <Column ss:Width="387.75"/>
        <Column ss:Width="420.75"/>
        <Column ss:Width="123.75"/>
        <Column ss:Width="73.5"/>
        <Column ss:Width="43.5"/>
        <Column ss:Width="153"/>
        <Column ss:Width="67.5"/>
        <Row ss:AutoFitHeight="0">
            <Cell ss:StyleID="s01"  ss:MergeAcross="13" ><Data ss:Type="String">TAMBAH DATA PENYELENGGARA NEGARA / WAJIB LAPOR</Data></Cell>
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell ss:StyleID="s01"  ss:MergeAcross="13" ><Data ss:Type="String">KOMISI PEMBERANTASAN KORUPSI (KPK)</Data></Cell>
        </Row>
        <Row  ss:AutoFitHeight="0">
            <Cell><Data ss:Type="String">Instansi</Data></Cell>
            <Cell  ss:MergeAcross="3"><Data ss:Type="String"><?php echo isset($n_instansi) ? $n_instansi : ""; ?></Data></Cell>
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell ss:StyleID="s02" ss:Index="1"><Data ss:Type="String">NO</Data></Cell>
            <Cell ss:StyleID="s02" ss:Index="2"><Data ss:Type="String">NIK</Data></Cell>
            <Cell ss:StyleID="s02" ss:Index="3"><Data ss:Type="String">NIP/NRP</Data></Cell>
            <Cell ss:StyleID="s02" ss:Index="4"><Data ss:Type="String">NAMA LENGKAP</Data></Cell>
            <Cell ss:StyleID="s02" ss:Index="5"><Data ss:Type="String">UNIT KERJA</Data></Cell>
            <Cell ss:StyleID="s02" ss:Index="6"><Data ss:Type="String">SUB UNIT KERJA</Data></Cell>
            <Cell ss:StyleID="s02" ss:Index="7"><Data ss:Type="String">JABATAN</Data></Cell>
            <Cell ss:StyleID="s02" ss:Index="8"><Data ss:Type="String">EMAIL</Data></Cell>
            <Cell ss:StyleID="s02" ss:Index="9"><Data ss:Type="String">NO HP</Data></Cell>
            <Cell ss:StyleID="s02" ss:Index="10"><Data ss:Type="String">JENIS KELAMIN</Data></Cell>
            <Cell ss:StyleID="s02" ss:Index="11"><Data ss:Type="String">TEMPAT LAHIR</Data></Cell>
            <Cell ss:StyleID="s02" ss:Index="12"><Data ss:Type="String">TANGGAL LAHIR</Data></Cell>
        </Row>
        <?php echo isset($tr_records) ? $tr_records : ""; ?>
        <Row ss:AutoFitHeight="0">
            <Cell />
            <Cell />
            <Cell />
            <Cell />
            <Cell />
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell />
            <Cell />
            <Cell />
            <Cell />
            <Cell />
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell />
            <Cell />
            <Cell />
            <Cell />
            <Cell />
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell />
            <Cell />
            <Cell />
            <Cell />
            <Cell />
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell />
            <Cell />
            <Cell />
            <Cell />
            <Cell />
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell><Data ss:Type="String">Catatan</Data></Cell>
            <Cell><Data ss:Type="String">:</Data></Cell>
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell><Data ss:Type="Number">1</Data></Cell>
            <Cell><Data ss:Type="String">Format dalam bentuk excel</Data></Cell>
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell><Data ss:Type="Number">2</Data></Cell>
            <Cell><Data ss:Type="String">NIK</Data></Cell>
            <Cell><Data ss:Type="String">: Diisi dengan nomor induk kependudukan (16 digit)</Data></Cell>
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell><Data ss:Type="Number">3</Data></Cell>
            <Cell><Data ss:Type="String">NIP</Data></Cell>
            <Cell><Data ss:Type="String">: Diisi dengan nomor induk kepegawaian</Data></Cell>
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell><Data ss:Type="Number">4</Data></Cell>
            <Cell><Data ss:Type="String">Nama</Data></Cell>
            <Cell><Data ss:Type="String">: Diisi denagn nama Penyelenggara Negara atau Wajib LHKPN</Data></Cell>
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell><Data ss:Type="Number">5</Data></Cell>
            <Cell><Data ss:Type="String">Nama Unit Kerja</Data></Cell>
            <Cell><Data ss:Type="String">: Diisi nama unit kerja</Data></Cell>
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell><Data ss:Type="Number">6</Data></Cell>
            <Cell><Data ss:Type="String">Nama Sub Unit Kerja</Data></Cell>
            <Cell><Data ss:Type="String">: Diisi nama sub unit kerja</Data></Cell>
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell><Data ss:Type="Number">7</Data></Cell>
            <Cell><Data ss:Type="String">Nama Jabatan</Data></Cell>
            <Cell><Data ss:Type="String">: Diisi nama jabatan</Data></Cell>
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell><Data ss:Type="Number">8</Data></Cell>
            <Cell><Data ss:Type="String">Email aktif</Data></Cell>
            <Cell><Data ss:Type="String">: Diisi alamat email pribadi PN/WL</Data></Cell>
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell><Data ss:Type="Number">9</Data></Cell>
            <Cell><Data ss:Type="String">Nomor HP</Data></Cell>
            <Cell><Data ss:Type="String">: Diisi nomor handphone pribadi PN/WL</Data></Cell>
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell><Data ss:Type="Number">10</Data></Cell>
            <Cell><Data ss:Type="String">Jenis Kelamin</Data></Cell>
            <Cell><Data ss:Type="String">: Diisi dengan jenis kelamin PN/WL, pilihan </Data></Cell>
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell ss:Index="2" ss:StyleID="s04"><Data ss:Type="String">1.</Data></Cell>
            <Cell><Data ss:Type="String">Laki-laki</Data></Cell>
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell ss:Index="2" ss:StyleID="s04"><Data ss:Type="String">2.</Data></Cell>
            <Cell><Data ss:Type="String">Perempuan</Data></Cell>
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell><Data ss:Type="Number">11</Data></Cell>
            <Cell><Data ss:Type="String">Tempat Lahir</Data></Cell>
            <Cell><Data ss:Type="String">: Diisi nama kabupaten/kota tempat lahir PN/WL</Data></Cell>
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell><Data ss:Type="Number">12</Data></Cell>
            <Cell><Data ss:Type="String">Tanggal Lahir</Data></Cell>
            <Cell><Data ss:Type="String">: Diisi tanggal lahir PN/WL</Data></Cell>
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell><Data ss:Type="String">)*</Data></Cell>
            <Cell><Data ss:Type="String">Jangan gunakan merge cells</Data></Cell>
        </Row>
    </Table>
    <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
        <Unsynced/>
        <Selected/>
        <ProtectObjects>False</ProtectObjects>
        <ProtectScenarios>False</ProtectScenarios>
    </WorksheetOptions>
    <DataValidation xmlns="urn:schemas-microsoft-com:office:excel">
        <Range>R5C2:R<?php echo $tblRowCount; ?>C2</Range>
        <Type>TextLength</Type>
        <Qualifier>Equal</Qualifier>
        <Value>16</Value>
        <ErrorMessage>Isikan NIK dengan benar tepat 16 angka</ErrorMessage>
        <ErrorTitle>NIK Tidak Valid</ErrorTitle>
    </DataValidation>
    <DataValidation xmlns="urn:schemas-microsoft-com:office:excel">
        <Range>R5C10:R<?php echo $tblRowCount; ?>C10</Range>
        <Type>List</Type>
        <Value>R<?php echo (intval($tblRowCount) + 17) - 33; ?>C3:R<?php echo (intval($tblRowCount) + 18) - 33; ?>C3</Value>
        <ErrorMessage>Isikan Sesuai dengan Pilihan yang telah disediakan</ErrorMessage>
        <ErrorTitle>Jenis Kelamin tidak valid</ErrorTitle>
    </DataValidation>
</Worksheet>