<?php

// error_reporting(0);
include base_path('PHPExcel/Classes/PHPExcel.php');

$objPHPExcel = new PHPExcel();
//Settingan awal file excel
$objPHPExcel->getProperties()->setCreator('Recap')
      ->setLastModifiedBy('Recap')
      ->setTitle("Recap")
      ->setSubject("Recap")
      ->setDescription("Recap")
      ->setKeywords("Recap");

// Buat sebuah variabel untuk menampung pengaturan style dari header tabel
$style_col = array(
  'font' => array('bold' => true), // Set font nya jadi bold
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
  ),
  'borders' => array(
    'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
    'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
    'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
    'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
  )
);

// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
$style_row = array(
  'alignment' => array(
    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
  ),
  'borders' => array(
    'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
    'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
    'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
    'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
  )
);

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', " Laporan Notif Peserta Award Berhasil Ditransfer "); 
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'Dari Tanggal '.$datefrom.' s/d '.$dateto); 
$objPHPExcel->getActiveSheet()->mergeCells('A1:J1'); // Set Merge Cell pada kolom A1 sampai F1
$objPHPExcel->getActiveSheet()->mergeCells('A2:J2'); // Set Merge Cell pada kolom A1 sampai F1
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE);
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
// Set text center untuk kolom A2


// Buat header tabel nya pada baris ke 3
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', "Nama"); // Set kolom A3 dengan tulisan "NO"
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', "Invoice"); // Set kolom B3 dengan tulisan "NIS"
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', "Hp"); // Set kolom B3 dengan tulisan "NIS"
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', "Jumlah Tiket"); // Set kolom B3 dengan tulisan "NIS"
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', "Total Uang"); // Set kolom C3 dengan tulisan "NAMA"
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', "Nama Bank"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G3', "Nama Rek"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H3', "No Rek"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I3', "Domisili"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J3', "Email"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"


// Apply style header yang telah kita buat tadi ke masing-masing kolom header
$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
$objPHPExcel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
$objPHPExcel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
$objPHPExcel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
$objPHPExcel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col)->getNumberFormat()->setFormatCode("#,###");
$objPHPExcel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
$objPHPExcel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
$objPHPExcel->getActiveSheet()->getStyle('H3')->applyFromArray($style_col);
$objPHPExcel->getActiveSheet()->getStyle('I3')->applyFromArray($style_col);
$objPHPExcel->getActiveSheet()->getStyle('J3')->applyFromArray($style_col);

// Set height baris ke 1, 2 dan 3
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);

// Buat query untuk menampilkan semua data siswa

$no = 1; // Untuk penomoran tabel, di awal set dengan 1
$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
foreach ($listReportData as $key => $value){  



  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $value->nama);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $value->invoice);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $value->telp);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $value->total_tiket);
  #total uang yang harus dikembalikan
    $obj['cdate']   = $value->cdate;
    $totaltiket     = $value->total_tiket;
    $totalprice     = $AdminPanelModel->TotalPrice($obj);
    if($totalprice == null){
      $hargaticket = 0;
      $donasi    = 0;
    }else{
      $hargaticket = $totalprice;
      $donasi    = 5;
    }
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $totaltiket*$hargaticket+$donasi);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $value->nama_bank);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $value->nama_rekening);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $value->nomor_rekening);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $value->domisili_bank);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $value->email);



  // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
  $objPHPExcel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
  $objPHPExcel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
  $objPHPExcel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
  $objPHPExcel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
  $objPHPExcel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row)->getNumberFormat()->setFormatCode("#,###");
  $objPHPExcel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
  $objPHPExcel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
  $objPHPExcel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
  $objPHPExcel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);
  $objPHPExcel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row);
  
  $objPHPExcel->getActiveSheet()->getRowDimension($numrow)->setRowHeight(20);
  
  $no++; // Tambah 1 setiap kali looping
  $numrow++; // Tambah 1 setiap kali looping
}




// Set width kolom
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25); // Set width kolom A
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15); // Set width kolom B
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15); // Set width kolom C
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15); // Set width kolom D
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15); // Set width kolom E
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20); // Set width kolom F
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20); // Set width kolom F
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20); // Set width kolom F
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20); // Set width kolom F
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(35); // Set width kolom F

// mengeset sheet pertama yang aktif
$objPHPExcel->setActiveSheetIndex(0);
// output file dengan nama file 'contoh.xls'


$namedownload = 'Laporan_notif_dari_tgl'.$datefrom.'s/d'.$dateto;

// header("Content-type: application/vnd-ms-excel");
// header("Content-Disposition: attachment; filename=Data Pegawai.xls");
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$namedownload.'".xls');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

exit;
?>