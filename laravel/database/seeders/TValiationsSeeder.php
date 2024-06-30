<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

# ライブラリの読込
use Flynsarmy\CsvSeeder\CsvSeeder;
use Illuminate\Support\Facades\DB;

class TValiationsSeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->table = 't_valiations';
        $this->filename = base_path() . '/util/outcsv/t_valiations.csv';
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
