<?php
/**
 * View
 *
 * @author Hilman Yanuar - PT. Akhdani Reka Solusi
 * @package Views/ever/verification
 */
?>

<style type="text/css">
    table.datatable-telusur th, table.datatable-telusur td {
        white-space: nowrap;
    }

    table.dataTable td.dataTables_empty {
        text-align: center;    
    }
</style>

<input id="is_hb_loaded" type="hidden" value="false"/>

<div class="box-header with-border portlet-header title-alat">
    <h5>"Harta Bergerak berupa alat transportasi dan mesin <strong>Penyelenggara Negara</strong> dari TELUSUR"</h5>
</div>

<div class="box-body">
    <table class="table table-bordered table-hover table-striped datatable-telusur" id="table-harta-telusur-harta-bergerak-pn">
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis</th>
                <th>Merek</th>
                <th>Model</th>
                <th>Warna</th>
                <th>Tahun Pembuatan</th>
                <th>No Pol / Registrasi</th>
                <th>No Mesin</th>
                <th>No Rangka</th>
                <th>Kendaraan Ke</th>
                <th>Alamat</th>
                <th>Jenis Bukti</th>
                <th>Atas Nama</th>
                <th>Asal Usul Harta</th>
                <th>Pemanfaatan</th>
                <th>Tahun Perolehan</th>
                <th>Nilai Perolehan</th>
            </tr>
        </thead>
    </table>
</div>

<div class="box-header with-border portlet-header title-alat">
    <h5>"Harta Bergerak berupa alat transportasi dan mesin <strong>Keluarga</strong> dari TELUSUR"</h5>
</div>

<div class="box-body">
    <table class="table table-bordered table-hover table-striped datatable-telusur" id="table-harta-telusur-harta-bergerak-keluarga">
        <thead>
            <tr>
                <th>No</th>
                <th>Hubungan</th>
                <th>Jenis</th>
                <th>Merek</th>
                <th>Model</th>
                <th>Warna</th>
                <th>Tahun Pembuatan</th>
                <th>No Pol / Registrasi</th>
                <th>No Mesin</th>
                <th>No Rangka</th>
                <th>Kendaraan Ke</th>
                <th>Alamat</th>
                <th>Jenis Bukti</th>
                <th>Atas Nama</th>
                <th>Asal Usul Harta</th>
                <th>Pemanfaatan</th>
                <th>Tahun Perolehan</th>
                <th>Nilai Perolehan</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.navTab.navTabHartaBergerak').on('click', function() {
            if ($("#is_hb_loaded").val() == "false") {
                $('#table-harta-telusur-harta-bergerak-pn').DataTable({
                    "scrollX": true,
                    'responsive': true,
                    'destroy': true,
                    'searching': false,
                    'autoWidth': false,
                    "oLanguage": ecDtLang,
                    'bServerSide': true,
                    'bProcessing': true,
                    "bPaginate": false,
                    "bLengthChange": false,
                    "bFilter": false,
                    "bInfo": false,
                    "order": [[ 0, 'asc' ]],
                    "drawCallback": function(settings) {
                        $("#is_hb_loaded").val(true);
                    },
                    "columns": [
                        {
                            "data": null,
                            "sortable": false,
                            render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        { "data": "jenis" },
                        { "data": "merek" },
                        { "data": "model" },
                        { "data": "warna" },
                        { "data": "tahun_pembuatan" },
                        { "data": "no_polisi" },
                        { "data": "no_mesin" },
                        { "data": "no_rangka" },
                        { "data": "kendaraan_ke" },
                        { "data": "alamat" },
                        { "data": "jenis_bukti" },
                        { "data": "atas_nama" },
                        { "data": "asal_usul_harta" },
                        { "data": "pemanfaatan" },
                        { "data": "tahun_perolehan" },
                        { "data": "nilai_perolehan" },
                    ],
                    "ajax": {
                        "url": '<?= base_url("ever/verification/telusur"); ?>',
                        "type": "POST",
                        "cache": false,
                        "data": {
                            status: 'pn',
                            jenis_harta: 'hb',
                            id_lhkpn: <?= $ID_LHKPN; ?>
                        }
                    },
                    "aoColumnDefs": [
                        { "className": 'text-center', "aTargets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 13] },
                        { "className": 'text-right', "aTargets": [16] }
                    ]
                });

                $('#table-harta-telusur-harta-bergerak-keluarga').DataTable({
                    "scrollX": true,
                    'responsive': true,
                    'destroy': true,
                    'searching': false,
                    'autoWidth': false,
                    "oLanguage": ecDtLang,
                    'bServerSide': true,
                    'bProcessing': true,
                    "bPaginate": false,
                    "bLengthChange": false,
                    "bFilter": false,
                    "bInfo": false,
                    "order": [[ 0, 'asc' ]],
                    "drawCallback": function(settings) {
                        $("#is_hb_loaded").val(true);
                    },
                    "columns": [
                        {
                            "data": null,
                            "sortable": false,
                            render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        { "data": "hubungan" },
                        { "data": "jenis" },
                        { "data": "merek" },
                        { "data": "model" },
                        { "data": "warna" },
                        { "data": "tahun_pembuatan" },
                        { "data": "no_polisi" },
                        { "data": "no_mesin" },
                        { "data": "no_rangka" },
                        { "data": "kendaraan_ke" },
                        { "data": "alamat" },
                        { "data": "jenis_bukti" },
                        { "data": "atas_nama" },
                        { "data": "asal_usul_harta" },
                        { "data": "pemanfaatan" },
                        { "data": "tahun_perolehan" },
                        { "data": "nilai_perolehan" },
                    ],
                    "ajax": {
                        "url": '<?= base_url("ever/verification/telusur"); ?>',
                        "type": "POST",
                        "cache": false,
                        "data": {
                            status: 'keluarga',
                            jenis_harta: 'hb',
                            id_lhkpn: <?= $ID_LHKPN; ?>
                        }
                    },
                    "aoColumnDefs": [
                        { "className": 'text-center', "aTargets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 14] },
                        { "className": 'text-right', "aTargets": [17] }
                    ]
                });
            }
        });
    });
</script>
