<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config["koneksi_telusur"] = [
		"datatable_columns_hb" => [
				"jenis",
				"merek",
				"model",
				"warna",
				"tahun_pembuatan",
				"no_polisi",
				"no_mesin",
				"no_rangka",
				"kendaraan_ke",
				"alamat",
				"jenis_bukti",
				"atas_nama",
				"asal_usul_harta",
				"pemanfaatan",
				"tahun_perolehan",
				"nilai_perolehan",
		],
		"datatable_columns_htb" => [
				"jalan",
				"kelurahan",
				"kecamatan",
				"kabupaten_kota",
				"provinsi",
				"luas_tanah",
				"luas_bangunan",
				"jenis_bukti",
				"nomor",
				"atas_nama",
				"asal_usul_harta",
				"pemanfaatan",
				"tahun_perolehan",
				"nilai_perolehan",
		],
		"collections" => [
				// "kendaraan_example" => [
				// 		"fields" => [
				// 				"jenis" => "field_name",
				// 				"merek" => "field_name",
				// 				"model" => "field_name",
				// 				"warna" => "field_name",
				// 				"tahun_pembuatan" => "field_name",
				// 				"no_polisi" => [
				// 						'$concat' => ['$field_name', ' ', '$field_name', ' ', '$field_name']
				// 				],
				// 				"no_mesin" => "field_name",
				// 				"no_rangka" => "field_name",
				// 				"kendaraan_ke" => "field_name",
				// 				"alamat" => "field_name",
				// 				"jenis_bukti" => "field_name",
				// 				"atas_nama" => "field_name",
				// 				"asal_usul_harta" => "field_name",
				// 				"pemanfaatan" => "field_name",
				// 				"tahun_perolehan" => "field_name",
				// 				"nilai_perolehan" => "field_name",
				// 		],
				// ],
				// "htb_pbb_example" => [
				// 		"fields" => [
				// 				"jalan" => "field_name",
				// 				"kelurahan" => "field_name",
				// 				"kecamatan" => "field_name",
				// 				"kabupaten_kota" => "field_name",
				// 				"provinsi" => "field_name",
				// 				"luas_tanah" => "field_name",
				// 				"luas_bangunan" => "field_name",
				// 				"jenis_bukti" => "field_name",
				// 				"nomor" => "field_name",
				// 				"atas_nama" => "field_name",
				// 				"asal_usul_harta" => "field_name",
				// 				"pemanfaatan" => "field_name",
				// 				"tahun_perolehan" => "field_name",
				// 				"nilai_perolehan" => "field_name",
				// 		],
				// ],
				"htb_pbb_dkijakarta" => [
					"fields" => [
								"jalan" => "alamat_op",
								"kelurahan" => "kd_kelurahan",
								"kecamatan" => "kd_kecamatan",
								"kabupaten_kota" => "kd_dati2",
								"provinsi" => "provinsi",
								"luas_tanah" => "luas_bumi_sppt",
								"luas_bangunan" => "luas_bng_sppt",
								"jenis_bukti" => "nop",
								"nomor" => "no_ketetapan",
								"atas_nama" => "nama",
								// "asal_usul_harta" => "field_name",
								// "pemanfaatan" => "field_name",
								"tahun_perolehan" => "tahun",
								"nilai_perolehan" => "njop_sppt",
						],
				],
				"kendaraan_dkijakarta" => [
						"fields" => [
								// "jenis" => "field_name",
								"merek" => "merek_kendaraan",
								"model" => "model_kendaraan",
								// "warna" => "field_name",
								"tahun_pembuatan" => "tahun_pembuatan",
								"no_polisi" => [
										'$concat' => ['$nopola', ' ', '$nopolb', ' ', '$nopolc']
								],
								"no_mesin" => "no_mesin",
								"no_rangka" => "no_rangka",
								"kendaraan_ke" => "kendaraan_ke",
								"alamat" => "alamat",
								"jenis_bukti" => "no_bpkb",
								"atas_nama" => "nama",
								// "asal_usul_harta" => "field_name",
								// "pemanfaatan" => "field_name",
								// "tahun_perolehan" => "field_name",
								// "nilai_perolehan" => "field_name",
						],
				],
				"kendaraan_jabar" => [
						"fields" => [
								"jenis" => "kd_jenis_kb",
								"merek" => "nm_merek_kb",
								"model" => "nm_model_kb",
								"warna" => "warna_kb",
								"tahun_pembuatan" => "th_buatan",
								"no_polisi" => [
										'$concat' => ['$nopola', ' ', '$nopolb', ' ', '$nopolc']
								],
								"no_mesin" => "no_mesin",
								"no_rangka" => "no_rangka",
								// "kendaraan_ke" => "field_name",
								"alamat" => "alamat",
								// "jenis_bukti" => "field_name",
								"atas_nama" => "nama",
								// "asal_usul_harta" => "field_name",
								// "pemanfaatan" => "field_name",
								// "tahun_perolehan" => "field_name",
								// "nilai_perolehan" => "field_name",
						],
						// "htb_pbb_kotabandung" => [
						// 		"fields" => [
						// 				"jalan" => "field_name",
						// 				"kelurahan" => "field_name",
						// 				"kecamatan" => "field_name",
						// 				"kabupaten_kota" => "field_name",
						// 				"provinsi" => "field_name",
						// 				"luas_tanah" => "field_name",
						// 				"luas_bangunan" => "field_name",
						// 				"jenis_bukti" => "field_name",
						// 				"nomor" => "field_name",
						// 				"atas_nama" => "field_name",
						// 				"asal_usul_harta" => "field_name",
						// 				"pemanfaatan" => "field_name",
						// 				"tahun_perolehan" => "field_name",
						// 				"nilai_perolehan" => "field_name",
						// 		],
						// ],
						// "kendaraan_kalimantanbarat" => [
						// 		"fields" => [
						// 				"jenis" => "field_name",
						// 				"merek" => "field_name",
						// 				"model" => "field_name",
						// 				"warna" => "field_name",
						// 				"tahun_pembuatan" => "field_name",
						// 				"no_polisi" => [
						// 						'$concat' => ['$field_name', ' ', '$field_name', ' ', '$field_name']
						// 				],
						// 				"no_mesin" => "field_name",
						// 				"no_rangka" => "field_name",
						// 				"kendaraan_ke" => "field_name",
						// 				"alamat" => "field_name",
						// 				"jenis_bukti" => "field_name",
						// 				"atas_nama" => "field_name",
						// 				"asal_usul_harta" => "field_name",
						// 				"pemanfaatan" => "field_name",
						// 				"tahun_perolehan" => "field_name",
						// 				"nilai_perolehan" => "field_name",
						// 		],
						// ],
						// "kendaraan_kalimantanselatan" => [
						// 		"fields" => [
						// 				"jenis" => "field_name",
						// 				"merek" => "field_name",
						// 				"model" => "field_name",
						// 				"warna" => "field_name",
						// 				"tahun_pembuatan" => "field_name",
						// 				"no_polisi" => [
						// 						'$concat' => ['$field_name', ' ', '$field_name', ' ', '$field_name']
						// 				],
						// 				"no_mesin" => "field_name",
						// 				"no_rangka" => "field_name",
						// 				"kendaraan_ke" => "field_name",
						// 				"alamat" => "field_name",
						// 				"jenis_bukti" => "field_name",
						// 				"atas_nama" => "field_name",
						// 				"asal_usul_harta" => "field_name",
						// 				"pemanfaatan" => "field_name",
						// 				"tahun_perolehan" => "field_name",
						// 				"nilai_perolehan" => "field_name",
						// 		],
						// ],
						// "kendaraan_kalimantantimur" => [
						// 		"fields" => [
						// 				"jenis" => "field_name",
						// 				"merek" => "field_name",
						// 				"model" => "field_name",
						// 				"warna" => "field_name",
						// 				"tahun_pembuatan" => "field_name",
						// 				"no_polisi" => [
						// 						'$concat' => ['$field_name', ' ', '$field_name', ' ', '$field_name']
						// 				],
						// 				"no_mesin" => "field_name",
						// 				"no_rangka" => "field_name",
						// 				"kendaraan_ke" => "field_name",
						// 				"alamat" => "field_name",
						// 				"jenis_bukti" => "field_name",
						// 				"atas_nama" => "field_name",
						// 				"asal_usul_harta" => "field_name",
						// 				"pemanfaatan" => "field_name",
						// 				"tahun_perolehan" => "field_name",
						// 				"nilai_perolehan" => "field_name",
						// 		],
						// ],
						// "kendaraan_lampung" => [
						// 		"fields" => [
						// 				"jenis" => "field_name",
						// 				"merek" => "field_name",
						// 				"model" => "field_name",
						// 				"warna" => "field_name",
						// 				"tahun_pembuatan" => "field_name",
						// 				"no_polisi" => [
						// 						'$concat' => ['$field_name', ' ', '$field_name', ' ', '$field_name']
						// 				],
						// 				"no_mesin" => "field_name",
						// 				"no_rangka" => "field_name",
						// 				"kendaraan_ke" => "field_name",
						// 				"alamat" => "field_name",
						// 				"jenis_bukti" => "field_name",
						// 				"atas_nama" => "field_name",
						// 				"asal_usul_harta" => "field_name",
						// 				"pemanfaatan" => "field_name",
						// 				"tahun_perolehan" => "field_name",
						// 				"nilai_perolehan" => "field_name",
						// 		],
						// ],
						// "kendaraan_malukuutara" => [
						// 		"fields" => [
						// 				"jenis" => "field_name",
						// 				"merek" => "field_name",
						// 				"model" => "field_name",
						// 				"warna" => "field_name",
						// 				"tahun_pembuatan" => "field_name",
						// 				"no_polisi" => [
						// 						'$concat' => ['$field_name', ' ', '$field_name', ' ', '$field_name']
						// 				],
						// 				"no_mesin" => "field_name",
						// 				"no_rangka" => "field_name",
						// 				"kendaraan_ke" => "field_name",
						// 				"alamat" => "field_name",
						// 				"jenis_bukti" => "field_name",
						// 				"atas_nama" => "field_name",
						// 				"asal_usul_harta" => "field_name",
						// 				"pemanfaatan" => "field_name",
						// 				"tahun_perolehan" => "field_name",
						// 				"nilai_perolehan" => "field_name",
						// 		],
						// ],
						// "kendaraan_sumateraselatan" => [
						// 		"fields" => [
						// 				"jenis" => "field_name",
						// 				"merek" => "field_name",
						// 				"model" => "field_name",
						// 				"warna" => "field_name",
						// 				"tahun_pembuatan" => "field_name",
						// 				"no_polisi" => [
						// 						'$concat' => ['$field_name', ' ', '$field_name', ' ', '$field_name']
						// 				],
						// 				"no_mesin" => "field_name",
						// 				"no_rangka" => "field_name",
						// 				"kendaraan_ke" => "field_name",
						// 				"alamat" => "field_name",
						// 				"jenis_bukti" => "field_name",
						// 				"atas_nama" => "field_name",
						// 				"asal_usul_harta" => "field_name",
						// 				"pemanfaatan" => "field_name",
						// 				"tahun_perolehan" => "field_name",
						// 				"nilai_perolehan" => "field_name",
						// 		],
						// ],
				]
		]
];