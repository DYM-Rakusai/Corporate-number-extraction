<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB; 

class AdjustData extends Command
{
   
    protected $signature = 'command:AdjustData {nowDate?}'; 
    protected $description = 'sales_listsとall_corporate_dataを突合してmatched_companiesに登録';

    public function handle()
    {
        $this->info('マッチング処理を開始します。');

        DB::table('matched_companies')->truncate();
        $this->info('既存の matched_companies データを削除しました。');

        $chunkSize = 8000; 
        $now       = now(); 

        $totalMatched = 0; 

       
        DB::table('sales_lists')
            ->join('all_corporate_data', 'sales_lists.company', '=', 'all_corporate_data.company')
            ->whereNotNull('sales_lists.company') 
            ->where('sales_lists.company', '!=', '') 
            ->select(
                'sales_lists.company',
                'sales_lists.sheet_name',
                'all_corporate_data.prefectures', 
                'all_corporate_data.cities',      
                'all_corporate_data.address',
                'all_corporate_data.corporate_number'
            )
            ->orderBy('sales_lists.company') 
            ->chunk($chunkSize, function ($matched) use ($now, &$totalMatched) {
                $insertData = []; 

                foreach ($matched as $row) {
                    $insertData[] = [
                        'company'          => $row->company,
                        'sheet_name'       => $row->sheet_name,       
                        'prefectures'      => $row->prefectures,     
                        'cities'           => $row->cities,           
                        'address'          => $row->address,         
                        'corporate_number' => $row->corporate_number,
                        'created_at'       => $now,
                        'updated_at'       => $now,
                    ];
                }

                if (!empty($insertData)) {
                    DB::table('matched_companies')->insert($insertData);
                    $totalMatched += count($insertData);
                    $this->info("{$totalMatched} 件のデータを matched_companies に挿入しました。");
                }
            });
        $this->info('マッチング処理が完了しました。');
        $this->info("最終的に {$totalMatched} 件のデータが matched_companies に登録されました。");
    }
}