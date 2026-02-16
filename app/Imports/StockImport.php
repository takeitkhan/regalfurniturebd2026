<?php

namespace App\Imports;

use App\Models\ProductStock;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StockImport implements ToModel, WithStartRow
{
    /**
     * @param Collection $collection
     */
    public function model(array $row)
    {
        try {

            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            ProductStock::updateOrCreate(
                [
                    'depot_id' => (int)$row[1], 'product_code' => (int)$row[2]
                ],
                [
                    'product_id' => (string)$row[3],
                    'available_qty' => (string)$row[4]
                ]
            );
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

        } catch (\Throwable $th) {
            dd($th);
        }

    }

    public function startRow(): int
    {
        return 2;
    }
}
