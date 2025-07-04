<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AdjustData extends Command
{
    protected $signature   = 'command:AdjustData {nowDate?}';
    protected $description = 'sales_listsとall_corporate_dataを突合してmatched_companiesに登録';

    public function handle()
    {
        DB::table('matched_companies')->truncate();

        $chunkSize = 10000;
        $now       = now();

        DB::table('sales_lists')
            ->join('all_corporate_data', 'sales_lists.company', '=', 'all_corporate_data.company')
            ->whereNotNull('sales_lists.company')
            ->where('sales_lists.company', '!=', '')
            ->select(
                'sales_lists.company',
                'sales_lists.sheet_name',
                'sales_lists.prefectures',
                'sales_lists.cities',
                'sales_lists.address',
                'all_corporate_data.corporate_number'
            )
            ->orderBy('sales_lists.company')
            ->chunk($chunkSize, function ($matched) use ($now) {
                $insertData = [];

                foreach ($matched as $row) {
                    $insertData[] = [
                        'company'          => $row->company,
                        'sheet_name'       => $row->sheet_name,
                        'corporate_number' => $row->corporate_number,
                        'prefectures'      => $row->prefectures,
                        'cities'           => $row->cities,
                        'address'          => $row->address,
                        'created_at'       => $now,
                        'updated_at'       => $now,
                    ];
                }

                if (!empty($insertData)) {
                    DB::table('matched_companies')->insert($insertData);
                }
            });

        $this->info('完了しました');
    }
}
