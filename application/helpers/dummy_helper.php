<?php

function insertPNWL() {
	$CI =& get_instance();
	$CI->load->model('mglobal');

	$data =	$CI->mglobal->get_data_all('T_USER', null, ['USERNAME' => '3268579104']);
	return $data;
}

function insertDataCalon() {
	$CI =& get_instance();

	$lokasi	= [
				['prov' => '31', 'kot' => '71', 'kec' => 'jaga karsa', 'kel' => 'cipedak'],
				['prov' => '31', 'kot' => '71', 'kec' => 'jaga karsa', 'kel' => 'srengseng sawah'],
				['prov' => '31', 'kot' => '71', 'kec' => 'jaga karsa', 'kel' => 'ciganjur'],
				['prov' => '31', 'kot' => '71', 'kec' => 'jaga karsa', 'kel' => 'jagakarsa'],
				['prov' => '31', 'kot' => '71', 'kec' => 'jaga karsa', 'kel' => 'lenteng agung'],
				['prov' => '31', 'kot' => '71', 'kec' => 'jaga karsa', 'kel' => 'tanjung barat'],
				['prov' => '31', 'kot' => '71', 'kec' => 'pasar minggu', 'kel' => 'cilandak timur'],
				['prov' => '31', 'kot' => '71', 'kec' => 'pasar minggu', 'kel' => 'ragunan'],
				['prov' => '31', 'kot' => '71', 'kec' => 'pasar minggu', 'kel' => 'kebagusan'],
				['prov' => '31', 'kot' => '71', 'kec' => 'pasar minggu', 'kel' => 'pasar minggu'],
				['prov' => '31', 'kot' => '71', 'kec' => 'pasar minggu', 'kel' => 'jati padang'],
				['prov' => '31', 'kot' => '71', 'kec' => 'pasar minggu', 'kel' => 'pejaten barat'],
				['prov' => '31', 'kot' => '71', 'kec' => 'pasar minggu', 'kel' => 'pejaten timur'],
				['prov' => '31', 'kot' => '71', 'kec' => 'cilandak', 'kel' => 'lebak bulus'],
				['prov' => '31', 'kot' => '71', 'kec' => 'cilandak', 'kel' => 'pondok labu'],
				['prov' => '31', 'kot' => '71', 'kec' => 'cilandak', 'kel' => 'cilandak barat'],
				['prov' => '31', 'kot' => '71', 'kec' => 'cilandak', 'kel' => 'gandaria selatan'],
				['prov' => '31', 'kot' => '71', 'kec' => 'cilandak', 'kel' => 'cipete selatan'],
				['prov' => '31', 'kot' => '71', 'kec' => 'pesanggrahan', 'kel' => 'bintaro'],
				['prov' => '31', 'kot' => '71', 'kec' => 'pesanggrahan', 'kel' => 'pesanggrahan'],
			];
	$tempat 	= ['Ngawi', 'Jakarta', 'Malang', 'Surabaya', 'Denpasar', 'Bandung'];
	$nama 		= [
					['Anang', '1'], ['Bambang', '1'], ['Bagus', '1'], ['Danang', '1'], ['Darto', '1'], ['Candra', '1'], ['Dani', '1'], ['Danu', '1'], ['Karnam', '1'], ['Marianto', '1'], 
					['Bagong', '1'], ['Basuki', '1'], ['Cahyo', '1'], ['Joko', '1'], ['Tingkir', '1'], ['Jailani', '1'], ['Susilo', '1'], ['Bayu', '1'], ['Narto', '1'], ['Kustoyo', '1'], 
					['Susandi', '1'], ['Imam', '1'], ['Derma', '1'], ['Darius', '1'], ['Dudung', '1'], ['Suliono', '1'], ['Sugeng', '1'], ['Bejo', '1'], ['Ryan', '1'], ['Waluyo', '1'], 
					['Andik', '1'], ['Habibi', '1'], ['Irul', '1'], ['Pratama', '1'], ['Dana', '1'], ['Gilang', '1'], ['Agung', '1'], ['Bagas', '1'], ['Rio', '1'], ['Janjam', '1'], 
					['Andi', '1'], ['Sohib', '1'], ['Khoirul', '1'], ['Hermawan', '1'], ['Damar', '1'], ['Dadang', '1'], ['Mashadi', '1'], ['Baskara', '1'], ['Roi', '1'], ['Timbul', '1'], 
					['Susis', '2'], ['Anisa', '2'], ['Wulan', '2'], ['Siti', '2'], ['Putri', '2'], ['Puput', '2'], ['Moren', '2'], ['Indah', '2'], ['Cinta', '2'], ['Ika', '2'], 
					['Nisa', '2'], ['Ningrum', '2'], ['Bunga', '2'], ['Sekar', '2'], ['Melati', '2'], ['Nami', '2'], ['Lia', '2'], ['Lya', '2'], ['Tyas', '2'], ['Sutami', '2'], 
					['Susi', '2'], ['Susan', '2'], ['Sukma', '2'], ['SUsanti', '2'], ['Mei', '2'], ['Okki', '2'], ['Ayu', '2'], ['Ayunda', '2'], ['Chelsea', '2'], ['Nitami', '2'], 
					['Silsi', '2'], ['Titi', '2'], ['Rianti', '2'], ['Aurel', '2'], ['Meilanti', '2'], ['Duwi', '2'], ['Dwi', '2'], ['Diana', '2'], ['Dianti', '2'], ['Diah', '2'], 
					['Dian', '2'], ['Rahayu', '2'], ['Harum', '2'], ['Ranum', '2'], ['Rina', '2'], ['Rima', '2'], ['Risa', '2'], ['Rosa', '2'], ['Irnawati', '2'], ['Isma', '2'], 
				  ];
	$tanggal 	= ['1985-10-10', '1988-12-10', '1981-11-11', '1985-09-01', '1990-12-15', '1986-10-15', '1987-10-20', '1989-10-28', '1985-10-28'];
	$data 		= array();
	$jab 		= ['GUBERNUR' => 129, 'WALIKOTA' => 132];
	$j 			= 0;

	for ($i=0; $i < 100; $i++) {
		if ($i%5 == 0 && $i <> 0) {
			$j++;
		}
		$data[$i] = array(
				'nama' 		=> $nama[$i][0],
				'jk' 		=> $nama[$i][1],
				'tanggal'	=> $tanggal[array_rand($tanggal, 1)],
				'jabatan'	=> ($i > 50) ? $jab['GUBERNUR'] : $jab['WALIKOTA'],
				'lokasi'	=> $lokasi[$j],
				'tempat'	=> $tempat[array_rand($tempat, 1)],
				'agama'		=> rand(1, 6),
				'status'	=> rand(1, 2),
				'pend'		=> rand(1, 9),
				'npwp'		=> substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10),
				'nik'		=> substr(str_shuffle("0123456789"), 0, 10),
			);
	}

	return $data;
}

?>