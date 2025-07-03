<?php
declare(strict_types=1);
namespace packages\Download\UseCase\Create;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CreateCsDataDownloadService implements CreateCsDataDownloadServiceInterface
{
    private $nowCarbonStr;

    public function __construct(
    )
    {
        $nowCarbon          = new Carbon('Asia/Tokyo');
        $this->nowCarbonStr = $nowCarbon->format('Y-m-d');
    }

    public function CreateCsDataDownload($downloadDatas, $atsCsIds)
    {   
        // Spredsheetオブジェクト生成
        $objworkSheet = new Spreadsheet();
        $sheet        = $objworkSheet->getActiveSheet();
        //sheet名の設定
        $sheetTitle   = 'apply_'.$this->nowCarbonStr;
        $sheet->setTitle($sheetTitle);
        //値の設定
        $activeSheet  = $objworkSheet->getSheetByName($sheetTitle);
        $activeSheet->fromArray($downloadDatas, null, 'A1', true);
    
        //file名の設定
        // $fileName = $sheetTitle.'.xlsx';
        // \Log::info("Excel FileName\n".$fileName);

        //ダウンロード
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        // XLSX形式オブジェクト生成
        $objWriter = new Xlsx($objworkSheet);
        // ファイル書込み・出力
        $objWriter->save('php://output');
        \Log::info('Excelダウンロード完了');
    }
}