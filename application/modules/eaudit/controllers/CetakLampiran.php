<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'/third_party/phpword/vendor/autoload.php';

class CetakLampiran extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('eaudit/Korespondensi_model');
  }

  // cetak surat dan lampiran
  public function cetakSurat($suratId, $templateId)
  {
    // get data surat
    $dataHeader = $this->Korespondensi_model->get_selected_surat($suratId);
    // get data PN dan Keluarga
    $data = $this->Korespondensi_model->get_lampiran_by_suratID_cetak($suratId);
    $kontak1 = $this->Korespondensi_model->getDataKontak($suratId, $dataHeader[0]['SURAT_KONTAK']);
    $kontak2 = $this->Korespondensi_model->getDataKontak2($suratId, $dataHeader[0]['SURAT_KONTAK_2']);
    $namaKontak = $kontak2[0]->NAMA == '' ? $kontak1[0]->NAMA : $kontak1[0]->NAMA.' atau Sdr. '.$kontak2[0]->NAMA ;
    $iptelKontak = $kontak2[0]->HANDPHONE == '' ? $kontak1[0]->HANDPHONE : $kontak1[0]->HANDPHONE.' atau '.$kontak2[0]->HANDPHONE ;
    $emailKontak = $kontak2[0]->EMAIL == '' ? $kontak1[0]->EMAIL : $kontak1[0]->EMAIL.' atau '.$kontak2[0]->EMAIL ;
    $emailKontak = strtolower($emailKontak);

    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $section = $phpWord->addSection();
    $headerAlign = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER);

    // definisi paragraph style
    $phpWord->addParagraphStyle(
      'oneLevelDecimalStyle',
      array('spaceAfter'=>100,'lineHeight'=>1.0, 'format' => 'decimal', 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::THAI_DISTRIBUTE,
            'indentation' => array('firstLine' => 400)));
    $phpWord->addParagraphStyle(
      'textAfterTableStyle',
      array('spaceAfter'=>100, 'spaceBefore'=>100, 'lineHeight'=>1.0, 'format' => 'decimal', 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::THAI_DISTRIBUTE,
            'indentation' => array('firstLine' => 400)));
    $rightStyle = "rightStyle";
    $phpWord->addParagraphStyle(
      $rightStyle,
    array('alignment'=> \PhpOffice\PhpWord\SimpleType\Jc::END, 'spaceAfter'=>0));
    $centerStyle = "centerStyle";
    $phpWord->addParagraphStyle(
      $centerStyle,
    array('alignment'=> \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter'=>0));
    $headerSuratStyle = "headerSuratStyle";
    $phpWord->addParagraphStyle(
      $headerSuratStyle, array('spaceAfter'=>0));

    $phpWord->addNumberingStyle(
        'multilevel',
        array(
            'type' => 'multilevel',
            'levels' => array(
                array('spaceAfter' => 0, 'format' => 'lowerLetter', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
                array('spaceAfter' => 0, 'format' => 'upperLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
            )
        )
    );
    $phpWord->addNumberingStyle(
        'oneleveldecimal',
        array(
            'type' => 'multilevel',
            'levels' => array(
                array('spaceAfter' => 0, 'format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
                array('spaceAfter' => 0, 'format' => 'upperLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
            )
        )
    );
    $phpWord->addNumberingStyle(
        'oneleveldecimal2',
        array(
            'type' => 'multilevel',
            'levels' => array(
                array('spaceAfter' => 0, 'format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
                array('spaceAfter' => 0, 'format' => 'upperLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
            )
        )
    );
    $paragraphStyleName = 'pStyle';
    $phpWord->addParagraphStyle(
      $paragraphStyleName,
      array(
        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::THAI_DISTRIBUTE,
        'spaceAfter' => 100)
    );
    $paragraphStyleNameDistribute = 'pStyleDistribute';
    $phpWord->addParagraphStyle(
      $paragraphStyleNameDistribute,
      array(
        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::THAI_DISTRIBUTE,
        'spaceAfter' => 0)
    );

    // setting table style
    $fancyTableStyleName = 'Fancy Table';
    $fancyTableStyle = array('borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80, 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::START);
    $fancyTableFirstRowStyle = array('borderBottomSize' => 6, 'borderBottomColor' => '000000', 'bgColor' => 'e2e1e1');
    $fancyTableCellStyle = array('vAlign' => \PhpOffice\PhpWord\Style\Cell::VALIGN_CENTER);
    $fancyTableCellBtlrStyle = array('valign' => 'center', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR);

    // setting font size, ukuran font 10 untuk Dispenda
    if ($templateId == 6) {
      $header = array('size' => 10, 'bold' => true);
      $normalFontStyle = array('size' => 10);
      $tanggalSurat = array('size' => 10, 'bold' => false, 'align'=>'right');
    }
    else {
      $header = array('size' => 11, 'bold' => true);
      $normalFontStyle = array('size' => 11);
      $tanggalSurat = array('size' => 11, 'bold' => false, 'align'=>'right');
    }

    if ($dataHeader[0]['SURAT_TANGGAL'] != null)
        $formatTanggal = explode(' ', tgl_format($dataHeader[0]['SURAT_TANGGAL']));
    else {
        $formatTanggal = explode(' ', tgl_format(date('Y-m-d')));
    }

    if($dataHeader[0]['SURAT_NOMOR'] == null) {
      $tgl = explode('-', (date('Y-m-d')));
      $tahun = $tgl[0];
      $bulan = $tgl[1];
      $noSurat = 'R/    /LHK.02/10-12/'.$bulan.'/'.$tahun;
    }
    else {
      $noSurat = $dataHeader[0]['SURAT_NOMOR'];
    }

    $table = $section->addTable();
    $table->addRow(40);
    $table->addCell(1000, null)->addText('Nomor', $normalFontStyle, $headerSuratStyle);
    $table->addCell(20, null)->addText(': ', $normalFontStyle, $headerSuratStyle);
    $table->addCell(6000, null)->addText($noSurat, $normalFontStyle, $headerSuratStyle);
    $table->addCell(6000, null)->addText($formatTanggal[1].' '.$formatTanggal[2], $normalFontStyle, 'rightStyle');
    $table->addRow(40);
    $table->addCell(1000, null)->addText('Sifat', $normalFontStyle, $headerSuratStyle);
    $table->addCell(20, null)->addText(': ', $normalFontStyle, $headerSuratStyle);
    $table->addCell(6000, null)->addText($dataHeader[0]['SURAT_SIFAT'], $normalFontStyle, $headerSuratStyle);
    $table->addRow(40);
    $table->addCell(1000, null)->addText('Lampiran', $normalFontStyle, $headerSuratStyle);
    $table->addCell(20, null)->addText(': ', $normalFontStyle, $headerSuratStyle);
    $table->addCell(6000, null)->addText($dataHeader[0]['SURAT_LAMPIRAN'], $normalFontStyle, $headerSuratStyle);
    $table->addRow(40);
    $table->addCell(1000, null)->addText('Hal', $normalFontStyle, $headerSuratStyle);
    $table->addCell(20, null)->addText(': ', $normalFontStyle, $headerSuratStyle);
    $table->addCell(6000, null)->addText($dataHeader[0]['SURAT_HAL'], $normalFontStyle, $headerSuratStyle);

    $section->addTextBreak(1);
    $textrun = $section->addTextRun($headerSuratStyle);
    $textrun->addText('Yth. '.$dataHeader[0]['ORG_TEMBUSAN_SURAT'], $header);
    $textrun = $section->addTextRun($headerSuratStyle);
    $textrun->addText($dataHeader[0]['ORG_NAMA'], $normalFontStyle);
    if($dataHeader[0]['ORG_GEDUNG'] != "")
    {
      $textrun = $section->addTextRun($headerSuratStyle);
      $textrun->addText($dataHeader[0]['ORG_GEDUNG'], $normalFontStyle);
    }
    $textrun = $section->addTextRun($headerSuratStyle);
    $textrun->addText($dataHeader[0]['ORG_ALAMAT'], $normalFontStyle);
    $textrun = $section->addTextRun($headerSuratStyle);
    $textrun->addText($dataHeader[0]['ORG_PROVINSI'], $normalFontStyle);

    $section->addTextBreak(1);
    $section->addText('Dasar hukum:', $normalFontStyle, $headerSuratStyle);

    $multilevelNumberingStyleName = 'multilevel';
    $phpWord->addNumberingStyle(
        $multilevelNumberingStyleName,
        array(
            'type'   => 'multilevel',
            'levels' => array(
                array('format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
                array('format' => 'upperLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
            ),
        )
    );

    $predefinedMultilevelStyle = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER_NESTED);

    $section->addListItem('Undang-undang Nomor 28 Tahun 1999 tentang Penyelenggaraan Negara yang Bersih dan Bebas dari Korupsi, Kolusi dan Nepotisme;', 0, $normalFontStyle, $predefinedMultilevelStyle, $paragraphStyleNameDistribute);
    $section->addListItem('Undang-Undang Nomor 30 Tahun 2002 tentang Komisi Pemberantasan Tindak Pidana Korupsi sebagaimana diubah dengan Undang-Undang Nomor 10 Tahun 2015 tentang Penetapan Peraturan Pemerintah Pengganti Undang-Undang Nomor 1 Tahun 2015 tentang Perubahan atas Undang-undang Nomor 30 Tahun 2002 tentang Komisi Pemberantasan Tindak Pidana Korupsi menjadi Undang-undang.', 0, $normalFontStyle, $predefinedMultilevelStyle, $paragraphStyleName);

    // Asuransi
    if($templateId == 1) {
        $section->addText('Merujuk pada ketentuan tersebut di atas dan surat kuasa nasabah kepada Pimpinan Komisi Pemberantasan Korupsi (terlampir), kami mengharapkan kerja sama Saudara untuk memberikan data keuangan atas nama nasabah terlampir yang berupa:', $normalFontStyle, 'oneLevelDecimalStyle');
        $section->addListItem('Customer Information File (CIF);', 0, $normalFontStyle, $multilevelNumberingStyleName, $headerSuratStyle);
        $section->addListItem('Data produk keuangan yang masih aktif berikut mutasi dan saldonya;', 0, $normalFontStyle, $multilevelNumberingStyleName, $headerSuratStyle);
        $section->addListItem('Data produk keuangan yang telah ditutup berikut mutasi dan saldonya hingga tanggal penutupan;', 0, $normalFontStyle, $multilevelNumberingStyleName, $headerSuratStyle);
        $section->addListItem('Data kepemilikan produk keuangan lain berikut mutasi dan saldonya.', 0, $normalFontStyle, $multilevelNumberingStyleName);
        $section->addText('Kami mengharapkan data tersebut dapat disampaikan dalam waktu yang tidak terlalu lama dan ditujukan kepada Deputi Bidang Pencegahan dan Monitoring c.q Direktur Pendaftaran dan Pemeriksaan Laporan Harta Kekayaan Penyelenggara Negara. Apabila diperlukan penjelasan lebih lanjut terkait dengan permintaan data ini, silakan menghubungi Sdr.'.$namaKontak.' di nomor telepon (021) 2557 8300 ext. '.$iptelKontak.' dan/atau e-mail '.$emailKontak, $normalFontStyle, 'oneLevelDecimalStyle');
    }
    // Pertanahan
    else if($templateId == 2) {
        $section->addText('Berdasarkan ketentuan tersebut di atas, kami mengharapkan kerja sama Saudara untuk memberikan data berupa warkah dan lokasi/pemetaan atas kepemilikan tanah dan bangunan sesuai dengan Nomor Hak dan nama dari bebeapa Penyelenggara Negara sebagaimana terlampir.', $normalFontStyle, 'oneLevelDecimalStyle');
        $section->addText('Untuk pelaksanaannya, penyampaian data dapat dilakukan secara bertahap dan dalam waktu yang tidak terlalu lama. Apabila diperlukan penjelasan lebih lanjut terkait dengan permintaan data ini, silakan menghubungi Sdr.'.$namaKontak.' di nomor telepon (021) 2557 8300 ext. '.$iptelKontak.' dan/atau e-mail '.$emailKontak.'. Surat permintaan data ini bukan merupakan surat permintaan blokir atas bukti kepemilikan tanah atas nama Penyelenggara Negara tersebut dan keluarganya.', $normalFontStyle, 'oneLevelDecimalStyle');
    }
    // Bank
    elseif ($templateId == 3) {
        $section->addText('Merujuk pada ketentuan tersebut di atas dan surat kuasa nasabah kepada Pimpinan Komisi Pemberantasan Korupsi (terlampir), kami mengharapkan kerja sama Saudara untuk memberikan data keuangan atas nama nasabah terlampir yang berupa:', $normalFontStyle, 'oneLevelDecimalStyle');
        $section->addListItem('Customer Information File (CIF);', 0, $normalFontStyle, $multilevelNumberingStyleName, $headerSuratStyle);
        $section->addListItem('Data produk simpanan baik yang masih aktif maupun yang telah ditutup beserta mutasi dan saldonya;', 0, $normalFontStyle, $multilevelNumberingStyleName, $headerSuratStyle);
        $section->addListItem('Data pinjaman dan kartu kredit;', 0, $normalFontStyle, $multilevelNumberingStyleName, $headerSuratStyle);
        $section->addListItem('Data kepemilikan safe deposit box;', 0, $normalFontStyle, $multilevelNumberingStyleName, $headerSuratStyle);
        $section->addListItem('Data kepemilikan produk bancassurance dan produk asuransi/investasi lainnya.', 0, $normalFontStyle, $multilevelNumberingStyleName);
        $section->addText('Kami mengharapkan data tersebut dapat disampaikan dalam waktu yang tidak terlalu lama dan ditujukan kepada Deputi Bidang Pencegahan dan Monitoring c.q Direktur Pendaftaran dan Pemeriksaan Laporan Harta Kekayaan Penyelenggara Negara. Apabila diperlukan penjelasan lebih lanjut terkait dengan permintaan data ini, silakan menghubungi Sdr.'.$namaKontak.' di nomor telepon (021) 2557 8300 ext. '.$iptelKontak.' dan/atau e-mail '.$emailKontak, $normalFontStyle, 'oneLevelDecimalStyle');
    }
    // KSEI
    elseif ($templateId == 5) {
        $section->addText('Merujuk pada ketentuan tersebut di atas dan surat kuasa nasabah kepada Pimpinan Komisi Pemberantasan Korupsi (terlampir), kami mengharapkan kerja sama Saudara untuk memberikan data keuangan atas nama nasabah terlampir yang berupa:', $normalFontStyle, 'oneLevelDecimalStyle');
        $section->addListItem('Customer Information File (CIF);', 0, $normalFontStyle, $multilevelNumberingStyleName, $headerSuratStyle);
        $section->addListItem('Data produk keuangan yang masih aktif berikut mutasi dan saldonya;', 0, $normalFontStyle, $multilevelNumberingStyleName, $headerSuratStyle);
        $section->addListItem('Data produk keuangan yang telah ditutup berikut mutasinya hingga tanggal penutupan;', 0, $normalFontStyle, $multilevelNumberingStyleName, $headerSuratStyle);
        $section->addListItem('Data kepemilikan produk keuangan lain;', 0, $normalFontStyle, $multilevelNumberingStyleName, $headerSuratStyle);
        $section->addListItem('Data kepemilikan rekening nasabah di perusahaan sekuritas.', 0, $normalFontStyle, $multilevelNumberingStyleName);
        $section->addText('Kami mengharapkan data tersebut dapat disampaikan dalam waktu yang tidak terlalu lama dan ditujukan kepada Deputi Bidang Pencegahan dan Monitoring c.q Direktur Pendaftaran dan Pemeriksaan Laporan Harta Kekayaan Penyelenggara Negara. Apabila diperlukan penjelasan lebih lanjut terkait dengan permintaan data ini, silakan menghubungi Sdr.'.$namaKontak.' di nomor telepon (021) 2557 8300 ext. '.$iptelKontak.' dan/atau e-mail '.$emailKontak, $normalFontStyle, 'oneLevelDecimalStyle');
    }
    // Dispenda
    elseif ($templateId == 6) {
        $section->addText('Berdasarkan ketentuan tersebut di atas, kami mengharapkan kerja sama Saudara untuk memberikan data kepemilikan dan nilainya untuk obyek kendaraan bermotor yang berada di wilayah kerja Saudara atas nama Penyelenggara Negara dan keluarganya sebagaimana terlampir.', $normalFontStyle, 'oneLevelDecimalStyle');
        $section->addText('Untuk pelaksanaannya, mohon agar data tersebut disajikan dalam bentuk tabel dengan format dibawah ini atau bisa disesuaikan dengan format pada system yang sudah ada disana.', $normalFontStyle, 'oneLevelDecimalStyle');

        $phpWord->addTableStyle($fancyTableStyleName, $fancyTableStyle, $fancyTableFirstRowStyle);
        $table = $section->addTable($fancyTableStyleName);
        $row = $table->addRow();
        $row->addCell(700, array('vMerge' => 'restart', 'valign' => 'center'))->addText('No.', $header, $centerStyle);
        $row->addCell(null, array('gridSpan' => 3))->addText('Kendaraan', $header, $centerStyle);
        $row->addCell(3000, array('vMerge' => 'restart', 'valign' => 'center'))->addText('Nama Pemilik', $header, $centerStyle);
        $row->addCell(3000, array('vMerge' => 'restart', 'valign' => 'center'))->addText('Alamat', $header, $centerStyle);

        $row = $table->addRow();
        $row->addCell(null, array('vMerge' => 'continue'));
        $row->addCell(2000, array('bgColor' => 'e2e1e1'))->addText('Jenis/Merk', $header, $centerStyle);
        $row->addCell(2000, array('bgColor' => 'e2e1e1'))->addText('Tahun Pembuatan', $header, $centerStyle);
        $row->addCell(2000, array('bgColor' => 'e2e1e1'))->addText('No. Polisi', $header, $centerStyle);
        $row->addCell(null, array('vMerge' => 'continue'));
        $row->addCell(null, array('vMerge' => 'continue'));

        $row = $table->addRow();
        $row->addCell()->addText('', null, $centerStyle);
        $row->addCell()->addText('', null, $centerStyle);
        $row->addCell()->addText('', null, $centerStyle);
        $row->addCell()->addText('', null, $centerStyle);
        $row->addCell()->addText('', null, $centerStyle);
        $row->addCell()->addText('', null, $centerStyle);

        $section->addText('Kami mengharapkan data tersebut dapat disampaikan dalam waktu yang tidak terlalu lama dan ditujukan kepada Deputi Bidang Pencegahan dan Monitoring c.q Direktur Pendaftaran dan Pemeriksaan Laporan Harta Kekayaan Penyelenggara Negara. Apabila diperlukan penjelasan lebih lanjut terkait dengan permintaan data ini, silakan menghubungi Sdr.'.$namaKontak.' di nomor telepon (021) 2557 8300 ext. '.$iptelKontak.' dan/atau e-mail '.$emailKontak, $normalFontStyle, 'textAfterTableStyle');
    }
    $section->addText('Atas perhatian dan kerja sama Saudara, kami ucapkan terima kasih.', $normalFontStyle, 'oneLevelDecimalStyle');

    if ($dataHeader[0]['SURAT_PENANDATANGAN_ID'] == "1") {
        $lebarCol = 3000;
        $penandatangan = "a.n. Pimpinan<w:br />Deputi Bidang Pencegahan dan Monitoring,";
    }
    else {
        $lebarCol = 5000;
        $penandatangan = "a.n. Pimpinan<w:br />Deputi Bidang Pencegahan dan Monitoring<w:br />u.b.<w:br />Direktur Pendaftaran dan Pemeriksaan LHKPN,";
    }
    $table = $section->addTable(array('alignment'=> \PhpOffice\PhpWord\SimpleType\Jc::END));
    $table->addRow(100);
    $table->addCell($lebarCol)->addText($penandatangan, $normalFontStyle, null);
    $table->addRow(100);
    $table->addCell($lebarCol)->addTextBreak(2);
    $table->addRow(100);
    $table->addCell($lebarCol)->addText($dataHeader[0]['PENANDATANGAN_NAMA'], $normalFontStyle, null);
    $section->addText('Tembusan:', null, $headerSuratStyle);
    $section->addText('1. Yth. Pimpinan KPK', null, $headerSuratStyle);
    if($templateId == 2) {
        $namaProvinsi = $dataHeader[0]['ORG_PROVINSI'];
        $section->addText('2. Yth. Deputi Bidang PIPM', null, $headerSuratStyle);
        $section->addText('3. Yth. Kepala Kantor Wilayah BPN '.$namaProvinsi, null, null);
    }
    else {
        $section->addText('2. Yth. Deputi Bidang PIPM', null, null);
    }

    // bulleted list
    // $listItemRun = $section->addListItemRun(0, null, $headerSuratStyle);
    // $listItemRun->addText('Yth. Pimpinan KPK');
    // $listItemRun = $section->addListItemRun(0, null, $headerSuratStyle);
    // $listItemRun->addText('Yth. Deputi Bidang PIPM');

    // numbered list
    // $section->addListItem('Yth. Pimpinan KPK', 0, $fontStyleName, $predefinedMultilevelStyle, $paragraphStyleNameDistribute);
    // $section->addListItem('Yth. Deputi Bidang PIPM', 0, $fontStyleName, $predefinedMultilevelStyle, $paragraphStyleNameDistribute);

    $footerStyle = array('italic'=>true, 'size' => 8);

    $phpWord->addTableStyle('footnote', $fancyTableStyle, null);
    $table = $section->addTable('footnote');
    $table->addRow(40);
    $table->addCell(9000, null)->addText('Surat ini beserta Lampirannya bersifat rahasia dan tidak untuk diserahkan/dipinjamkan kepada Nasabah dan/atau pihak lainnya tanpa seizin KPK RI. Apabila diperlukan maka penggandaan diharapkan hanya untuk kepentingan pengarsipan.', $footerStyle, $headerSuratStyle);

    // Cetak Lampiran
    // New section
    $section = $phpWord->addSection();
    $table = $section->addTable(array('alignment'=> \PhpOffice\PhpWord\SimpleType\Jc::END));
    $table->addRow(100);
    $table->addCell(2260)->addText('Lampiran Nomor Surat', $normalFontStyle, $rightStyle);
    $table->addCell(50)->addText(':', $normalFontStyle, $rightStyle);
    $table->addCell(3000)->addText($noSurat, $normalFontStyle, $rightStyle);
    $table->addRow(100);
    $table->addCell(2260)->addText('Tanggal', $normalFontStyle, $rightStyle);
    $table->addCell(50)->addText(':', $normalFontStyle, $rightStyle);
    $table->addCell(3000)->addText($formatTanggal[1].' '.$formatTanggal[2], $normalFontStyle, $rightStyle);
    // $table->addRow(100);
    // $table->addCell(2260)->addText('Halaman', $normalFontStyle, $rightStyle);
    // $table->addCell(50)->addText(':', $normalFontStyle, $rightStyle);
    // $table->addCell(3000)->addText('1 dari 1', $normalFontStyle, $rightStyle);

    $phpWord->addTableStyle($fancyTableStyleName, $fancyTableStyle, $fancyTableFirstRowStyle);
    $section->addTextBreak(1);
    // Asuransi
    if($templateId == 1 || $templateId == 5) {
      $section->addText('DAFTAR IDENTITAS NASABAH', $header, $headerAlign);
      $table = $section->addTable($fancyTableStyleName);
      $table->addRow(900);
      $table->addCell(700, $fancyTableCellStyle)->addText('No.', $header, $centerStyle);
      $table->addCell(3500, $fancyTableCellStyle)->addText('Nama', $header, $centerStyle);
      $table->addCell(3000, $fancyTableCellStyle)->addText('Tempat/<w:br />Tanggal Lahir', $header, $centerStyle);
      $table->addCell(3000, $fancyTableCellStyle)->addText('Periode Informasi Nilai Tunai Yang Dibutuhkan', $header, $centerStyle);
      $table->addCell(3000, $fancyTableCellStyle)->addText('Periode Permintaan Data', $header, $centerStyle);
    }
    // Pertanahan
    else if ($templateId == 2) {
      $section->addText('DAFTAR IDENTITAS PENYELENGGARA NEGARA DAN KELUARGA', $header, $headerAlign);
      $table = $section->addTable($fancyTableStyleName);
      $table->addRow(900);
      $table->addCell(700, $fancyTableCellStyle)->addText('No.', $header, $centerStyle);
      $table->addCell(2500, $fancyTableCellStyle)->addText('Nama', $header, $centerStyle);
      $table->addCell(2500, $fancyTableCellStyle)->addText('Tempat/<w:br />Tanggal Lahir', $header, $centerStyle);
      $table->addCell(4000, $fancyTableCellStyle)->addText('Alamat', $header, $centerStyle);
    }
    // Bank
    elseif ($templateId == 3) {
      $section->addText('DAFTAR IDENTITAS PENYELENGGARA NEGARA DAN KELUARGA', $header, $headerAlign);
      $table = $section->addTable($fancyTableStyleName);
      $table->addRow(900);
      $table->addCell(700, $fancyTableCellStyle)->addText('No.', $header, $centerStyle);
      $table->addCell(3500, $fancyTableCellStyle)->addText('Nama', $header, $centerStyle);
      $table->addCell(3000, $fancyTableCellStyle)->addText('Tempat/<w:br />Tanggal Lahir', $header, $centerStyle);
      $table->addCell(3000, $fancyTableCellStyle)->addText('Periode Permintaan Data', $header, $centerStyle);
    }
    else {
      $section->addText('DAFTAR IDENTITAS PENYELENGGARA NEGARA DAN KELUARGA', $header, $headerAlign);
      $table = $section->addTable($fancyTableStyleName);
      $table->addRow(900);
      $table->addCell(700, $fancyTableCellStyle)->addText('No.', $header, $centerStyle);
      $table->addCell(3500, $fancyTableCellStyle)->addText('Nama', $header, $centerStyle);
      $table->addCell(3000, $fancyTableCellStyle)->addText('Tempat/<w:br />Tanggal Lahir', $header, $centerStyle);
      $table->addCell(3000, $fancyTableCellStyle)->addText('Periode Permintaan Data', $header, $centerStyle);
    }

    $cellStyle = "cellStyle";
    $phpWord->addParagraphStyle(
      $cellStyle,
      array('alignment'=> \PhpOffice\PhpWord\SimpleType\Jc::START, 'spaceAfter'=>0));
    $cellNumStyle = "cellNumStyle";
    $phpWord->addParagraphStyle(
      $cellNumStyle,
      array('alignment'=> \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter'=>0));

    $ii = 1;
    for ($i = 0; $i < sizeof($data); $i++) {
      $table->addRow();
      $table->addCell(700, $fancyTableCellStyle)->addText($ii.".", NULL, $cellNumStyle);
      if($data[$i]->NAMA == null)
      {
        $table->addCell(3500, $fancyTableCellStyle)->addText($data[$i]->NAMA_LENGKAP, NULL, $cellStyle);
      }
      else {
        $table->addCell(3500, $fancyTableCellStyle)->addText($data[$i]->NAMA, NULL, $cellStyle);
      }
      if($data[$i]->TempatLahirKlg == null && $data[$i]->TANGGAL_LAHIR == null)
      {
        $table->addCell(3000, $fancyTableCellStyle)->addText($data[$i]->TempatLahirPN." /<w:br />".tgl_format($data[$i]->TGL_LAHIR), null, $centerStyle);
      }
      else {
        $table->addCell(3000, $fancyTableCellStyle)->addText($data[$i]->TempatLahirKlg." /<w:br />".tgl_format($data[$i]->TANGGAL_LAHIR), null, $centerStyle);
      }
      if($templateId == 1 || $templateId == 5)
      {
        $table->addCell(3000, $fancyTableCellStyle)->addText("");
        $table->addCell(3000, $fancyTableCellStyle)->addText("");
      }
      else if ($templateId == 2)
      {
        // if($data[$i]->USERNAME_ENTRI == $data[$i+1]->USERNAME_ENTRI)
        $table->addCell(4000, array('vMerge' => 'restart', 'valign' => 'center'))->addText($data[$i]->ALAMAT_RUMAH.", ".$data[$i]->KELURAHAN.", ".$data[$i]->KECAMATAN.", ".$data[$i]->KABKOT.", ".$data[$i]->PROVINSI, NULL, $cellStyle);
        // $table->addCell(4000, $fancyTableCellStyle)->addText($data[$i]->ALAMAT_RUMAH.", ".$data[$i]->KELURAHAN.", ".$data[$i]->KECAMATAN.", ".$data[$i]->KABKOT.", ".$data[$i]->PROVINSI, NULL, $cellStyle);
      }
      else {
        $table->addCell(3000, $fancyTableCellStyle)->addText("");
      }
      $ii++;
    }

    // menampilkan number of page
    // $section->addPreserveText('Page {PAGE} of { NUMPAGES }.');
    //     $footer = $section->createFooter();
    // $footer->addPreserveText(
    //     'Page {PAGE} of {NUMPAGES}',
    //     null,
    //     array('align' => 'center')
    // );

    // add to log
    $this->add_log_cetak_surat($suratId);

    // setting properties
    $properties = $phpWord->getDocInfo();
    $properties->setCreator('e-LHKPN');
    $properties->setLastModifiedBy('e-LHKPN');

    //  Saving the document
    $nama_file = $suratId.'_'.$dataHeader[0]['ORG_NAMA'].'_'.date('d-m-Y');
    header("Content-Description: File Transfer");
    header('Content-Disposition: attachment; filename="' . $nama_file . '.docx"');
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Expires: 0');
    $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    $xmlWriter->save("php://output");
  }

  public function add_log_cetak_surat($suratId)
  {
    $cetak = array('SURAT_ID' => $suratId,
        'CETAK_BY' => $this->session->userdata('USR'),
        'CETAK_TIME' => date('Y-m-d H:i:s'),
        'CETAK_IP' => $_SERVER["REMOTE_ADDR"]);
    $this->Korespondensi_model->addLogCetakSurat($cetak);
  }
}
