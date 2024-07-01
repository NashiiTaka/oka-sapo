<?php

namespace Database\Seeders;

use App\Models\MRakutenGenre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

# ライブラリの読込
use Flynsarmy\CsvSeeder\CsvSeeder;
use Illuminate\Support\Facades\DB;

class MRakutenGenresSeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->table = 'm_rakuten_genres';
        $this->filename = base_path() . '/util/outcsv/m_rakuten_genres.csv';
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
