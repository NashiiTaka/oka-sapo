<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

# ライブラリの読込
use Flynsarmy\CsvSeeder\CsvSeeder;
use Illuminate\Support\Facades\DB;

class MCategoriesSeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->table = 'm_categories';
        $this->filename = base_path() . '/node/outcsv/m_categories.csv';
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
