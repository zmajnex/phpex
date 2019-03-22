<?php
require_once "vendor/autoload.php";
use App\Helpers\Helper;
use App\Helpers\Logger;
$error = new Logger;

//Upload file
try {
    $target_dir = "app/Excell_files/";
    $file_name = $_FILES['fileToUpload']['name'];
    $file_tmp = $_FILES['fileToUpload']['tmp_name'];
    move_uploaded_file($file_tmp, $target_dir . $file_name);
    if (!file_exists($target_dir)) {
        throw new Exception('Something went wrong file is not uploaded');
    }
} catch (\Throwable $e) {
    //TODO catch php errors
    $error->logger($e->getMessage());
}
// Get form data
$post = new Helper;
$des = $post->postRequest('description');
$start = $post->postRequest('startid');
$season = $post->postRequest('season');
// Load excell file
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($target_dir . $file_name);

$highestRow = $spreadsheet->getActiveSheet()->getHighestRow();
$highestColumn = $spreadsheet->getActiveSheet()->getHighestColumn();
// Create sheets
$urlSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'seourl');
$spreadsheet->addSheet($urlSheet);

//Crete new cells from array
$rowArray = ['cena bez pdv-a', 'ime', 'sku/model', 'id','url', 'sirina','visina','precnik','namena','opis','title'];
$spreadsheet->getActiveSheet()
    ->fromArray(
        $rowArray,   
        NULL,        
        'F1'                              
    );

//add formula to cell bez PDV-a
 for($i=2; $i<=$highestRow; $i++){
    
$spreadsheet->getActiveSheet()->setCellValue(
    'F'.$i,
    '=E'.$i.'/1.2'
);
};
// Remove SG character from cell

for ($i=2; $i<=$highestRow; $i++) {
    $name = $spreadsheet->getActiveSheet()->getCell('D'.$i);
    $name=str_replace("SG", "",$name);
    $name = ltrim($name);
    $spreadsheet->getActiveSheet()->setCellValue(
        'G'.$i,
        $name
    );
};
//set SKU/Model

for ($i=2; $i<=$highestRow; $i++) {
    $sku = $spreadsheet->getActiveSheet()->getCell('C'.$i);
    $sku=substr($sku,2);
    $spreadsheet->getActiveSheet()->setCellValue(
        'H'.$i,
        $sku
    );
};
//set URL 
for ($i=2; $i<=$highestRow; $i++) {
    $url = $spreadsheet->getActiveSheet()->getCell('G'.$i);
    $patern=array('+','*','/',' ');
    $url=str_replace($patern,'-',$url);
    $spreadsheet->getActiveSheet()->setCellValue(
        'J'.$i,
        $url
    );
}
//set id:
$start;
for ($i=2; $i<=$highestRow; $i++) {
    $start;
    $spreadsheet->getActiveSheet()->setCellValue(
        'I'.$i,
        $start
    );
    $start++;
}
//set k,l,m sirina visina precnik
for ($i=2; $i<=$highestRow; $i++) {
    $name = $spreadsheet->getActiveSheet()->getCell('G'.$i);
    $nameArray =  explode(' ',$name);
    $sirinaVisina=$nameArray[0];
    $tmpArray=explode('/',$sirinaVisina);
    $sirina=$tmpArray[0];
    if(isset($tmpArray[1])){
        $visina=$tmpArray[1];
    }else{
        $visina=0;
    }
    
    $precnik=$nameArray[1];
 
    $spreadsheet->getActiveSheet()->setCellValue(
        'K'.$i,
        $sirina
    );
    $spreadsheet->getActiveSheet()->setCellValue(
        'L'.$i,
        $visina
    );
    $spreadsheet->getActiveSheet()->setCellValue(
        'M'.$i,
        $precnik
    );
} 
// set namena N column
for ($i=2; $i<=$highestRow; $i++) {
    $namena = $spreadsheet->getActiveSheet()->getCell('B'.$i);
     if ($namena == 'putnicke') {
         $spreadsheet->getActiveSheet()->setCellValue(
        'N'.$i,
        1221
    );
     } elseif($namena=='poluteretne'){
        $spreadsheet->getActiveSheet()->setCellValue(
            'N'.$i,
            1222
        );
     } else {
        $spreadsheet->getActiveSheet()->setCellValue(
            'N'.$i,
            1223
        );
     }    
}
//set description column O
for ($i=2; $i<=$highestRow; $i++) {
    $nameForDes = $spreadsheet->getActiveSheet()->getCell('G'.$i);
    $description=$nameForDes.' '.$des;   
    $spreadsheet->getActiveSheet()->setCellValue(
        'O'.$i,
        $description
    );
}
// Set title column P
for ($i=2; $i<=$highestRow; $i++) {
    $nameForTitle = $spreadsheet->getActiveSheet()->getCell('G'.$i);
    $title=$nameForTitle.' | '.$season;   
    $spreadsheet->getActiveSheet()->setCellValue(
        'P'.$i,
        $title
    );
}
//set column width:
foreach(range('A','J') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}

//saving files
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
$writer->save($target_dir . $file_name);

