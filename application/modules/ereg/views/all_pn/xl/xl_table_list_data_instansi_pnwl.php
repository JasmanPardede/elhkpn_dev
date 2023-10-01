<Worksheet ss:Name="instansi">
    <Table ss:ExpandedColumnCount="8" ss:ExpandedRowCount="<?php echo $tblRowCount; ?>" x:FullColumns="1"
           x:FullRows="1" ss:DefaultRowHeight="15">
        <Column ss:Index="1" ss:AutoFitWidth="0" ss:Width="96.75"/>
        <Column ss:AutoFitWidth="0" ss:Width="144.75"/>
        <Column ss:AutoFitWidth="0" ss:Width="143.25"/>
        <Column ss:Width="249.75"/>
        
        <Row ss:AutoFitHeight="0">
            <Cell ss:StyleID="s01"  ss:MergeAcross="3" ><Data ss:Type="String">TAMBAH DATA PENYELENGGARA NEGARA / WAJIB LAPOR (Instansi)</Data></Cell>
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell ss:StyleID="s01"  ss:MergeAcross="3" ><Data ss:Type="String">KOMISI PEMBERANTASAN KORUPSI (KPK)</Data></Cell>
        </Row>
        <Row ss:AutoFitHeight="0">
            <Cell ss:StyleID="s02" ss:Index="1"><Data ss:Type="String">NO</Data></Cell>
            <Cell ss:StyleID="s02" ss:Index="2"><Data ss:Type="String">ID Instansi</Data></Cell>
            <Cell ss:StyleID="s02" ss:Index="3"><Data ss:Type="String">Kode Instansi</Data></Cell>
            <Cell ss:StyleID="s02" ss:Index="4"><Data ss:Type="String">Nama Instansi</Data></Cell>
        </Row>
        <?php echo isset($tr_records) ? $tr_records : ""; ?>
    </Table>
    <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
        <Unsynced/>
        <Selected/>
        <ProtectObjects>False</ProtectObjects>
        <ProtectScenarios>False</ProtectScenarios>
    </WorksheetOptions>
</Worksheet>