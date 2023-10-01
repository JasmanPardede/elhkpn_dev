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

<input id="is_htb_loaded" type="hidden" value="false"/>

<div class="box-header with-border portlet-header title-alat">
    <h5>"Harta Tidak Bergerak berupa Tanah dan/atau bangunan <strong>Penyelenggara Negara</strong> dari TELUSUR"</h5>
</div>

<div class="box-body">
    <table class="table table-bordered table-hover table-striped datatable-telusur" id="table-harta-telusur-harta-tidak-bergerak-pn">
        <thead>
            <tr>
                <th>No</th>
                <th>Jalan/ No</th>
                <th>Kelurahan</th>
                <th>Kecamatan</th>
                <th>Kabupaten/Kota</th>
                <th>Provinsi</th>
                <th>Luas Tanah</th>
                <th>Luas Bangunan</th>
                <th>Jenis Bukti</th>
                <th>Nomor</th>
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
    <h5>"Harta Tidak Bergerak berupa Tanah dan/atau bangunan <strong>Keluarga</strong> dari TELUSUR"</h5>
</div>

<div class="box-body">
    <table class="table table-bordered table-hover table-striped datatable-telusur" id="table-harta-telusur-harta-tidak-bergerak-keluarga">
        <thead>
            <tr>
                <th>No</th>
                <th>Hubungan</th>
                <th>Jalan/ No</th>
                <th>Kelurahan</th>
                <th>Kecamatan</th>
                <th>Kabupaten/Kota</th>
                <th>Provinsi</th>
                <th>Luas Tanah</th>
                <th>Luas Bangunan</th>
                <th>Jenis Bukti</th>
                <th>Nomor</th>
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
        $('.navTab.navTabHartaTidakBergerak').on('click', function() {
            if ($("#is_htb_loaded").val() == "false") {
                $('#table-harta-telusur-harta-tidak-bergerak-pn').DataTable({
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
                        $("#is_htb_loaded").val(true);
                    },
                    "columns": [
                        {
                            "data": null,
                            "sortable": false,
                            render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        { "data": "jalan" },
                        { "data": "kelurahan" },
                        { "data": "kecamatan" },
                        { "data": "kabupaten_kota" },
                        { "data": "provinsi" },
                        { "data": "luas_tanah" },
                        { "data": "luas_bangunan" },
                        { "data": "jenis_bukti" },
                        { "data": "nomor" },
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
                            jenis_harta: 'htb',
                            id_lhkpn: <?= $ID_LHKPN; ?>
                        }
                    },
                    "aoColumnDefs": [
                        { "className": 'text-center', "aTargets": [0, 2, 3, 4, 5, 6, 7, 8, 9 , 13] },
                        { "className": 'text-right', "aTargets": [14] }
                    ]
                });

                $('#table-harta-telusur-harta-tidak-bergerak-keluarga').DataTable({
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
                        $("#is_htb_loaded").val(true);
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
                        { "data": "jalan" },
                        { "data": "kelurahan" },
                        { "data": "kecamatan" },
                        { "data": "kabupaten_kota" },
                        { "data": "provinsi" },
                        { "data": "luas_tanah" },
                        { "data": "luas_bangunan" },
                        { "data": "jenis_bukti" },
                        { "data": "nomor" },
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
                            jenis_harta: 'htb',
                            id_lhkpn: <?= $ID_LHKPN; ?>
                        }
                    },
                    "aoColumnDefs": [
                        { "className": 'text-center', "aTargets": [0, 1, 3, 4, 5, 6, 7, 8, 9, 10, 14] },
                        { "className": 'text-right', "aTargets": [15] }
                    ]
                });
            }
        });
    });
</script>
