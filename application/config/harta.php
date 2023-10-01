<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$config['harta']["jenis_pelepasan"] = array(
    "1" => "Penjualan Harta",
    "2" => "Pelepasan Harta",
    "3" => "Penerimaan Hibah",
    "4" => "Pemberiaan Hibah",
);

$config['harta']["pelepasan_function_list"] = array(
    "harta_bergerak" => "Alat Transportasi",
    "harta_bergerak_lainnya" => "Harta Bergerak Lainnya",
    "harta_lainnya" => "Harta Lainnya",
    "harta_tidak_bergerak" => "Tanah/ Bangunan",
    "kas_dan_setara_kas" => "kas/ Setara Kas",
    "surat_berharga" => "Surat Berharga",
);

$config['harta']["pelepasan_table_postfix"] = array(
    "harta_bergerak" => "bergerak",
    "harta_bergerak_lainnya" => "bergerak_lain",
    "harta_lainnya" => "lainnya",
    "harta_tidak_bergerak" => "tidak_bergerak",
    "kas_dan_setara_kas" => "kas",
    "surat_berharga" => "surat_berharga",
);

$config['harta']['lhkpn_offline_melalui'] = array(
    "langsung" => 1,
    "pos" => 2,
    "email" => 3,
);

$config['harta']["hubungan_keluarga"] = array(
    "Istri",
    "Suami",
    "Anak Tanggungan",
    "Anak Bukan Tanggunan",
    "Lainnya",
);

$config['harta']["atas_nama"] = array(
    "1" => "PN yang bersangkutan",
    "2" => "Pasangan/Anak",
    "3" => "Lainnya",
);

$config['harta']['jenis_penerimaan_kas_pn'] = array(
    array(
        'Gaji dan Tunjangan',
        'Penghasilan dari Profesi/Keahlian',
        'Honorarium',
        'Tantiem, bonus, jasa produksi, THR',
        'Penerimaan dari pekerjaan lainnya'
    ),
    array(
        'Hasil Investasi dalam Surat Berharga',
        'Hasil Usaha/Sewa',
        'Bunga Tabungan/Deposito dan Lainnya',
        'Penjualan atau Pelepasan Harta',
    ),
    array(
        'Penerimaan Hutang',
        'Penerimaan Warisan',
        'Penerimaan Hibah/Hadiah',
        'Lainnya'
    )
);
$config['harta']['jenis_pengeluaran_kas_pn'] = array(
    array(
        'Biaya Rumah Tangga (termasuk transportasi, pendidikan, kesehatan, rekreasi, pembayaran kartu kredit)',
        'Biaya Sosial (antara lain keagamaan, zakat, infaq, sumbangan lain)',
        'Pembayaran Pajak (antara lain PBB, kendaraan, pajak daerah, pajak lain)',
        'Pengeluaran Rutin Lainnya'
    ),
    array(
        'Pembelian/Perolehan Harta Baru',
        'Pemeliharaan/Modifikasi/Rehabilitasi Harta',
        'Pengeluaran Non Rutin Lainnya'
    ),
    array(
        'Biaya Pengurusan Waris/hibah/hadiah',
        'Pelunasan/Angsuran Hutang',
        'Pengeluaran Lainnya'
    )
);
;

$config['harta']['golongan_penerimaan_kas_pn'] = array(
    'PENERIMAAN DARI PEKERJAAN',
    'PENERIMAAN USAHA / KEKAYAAN',
    'PENERIMAAN LAINNYA',
);

$config['harta']['golongan_pengeluaran_kas_pn'] = array(
    'PENGELUARAN RUTIN',
    'PENGELUARAN HARTA',
    'PENGELUARAN LAINNYA',
);
