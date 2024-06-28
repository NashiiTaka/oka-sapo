<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

# ライブラリの読込
use Flynsarmy\CsvSeeder\CsvSeeder;
use Illuminate\Support\Facades\DB;

# 継承元をCsvSeederに変更
class TProductsSeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->table = 't_products';
        $this->filename = base_path() . '/node/outcsv/t_products.csv';
        $this->timestamps = true;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Recommended when importing larger CSVs
        DB::disableQueryLog();
        // 全データ削除後に再登録する。
        DB::table($this->table)->truncate();

        parent::run();
    }
}
