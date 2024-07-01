<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

# ライブラリの読込
use Flynsarmy\CsvSeeder\CsvSeeder;
use Illuminate\Support\Facades\DB;

class TRakutenProductsSeeder extends CsvSeeder
{
    private $filenames;

    public function __construct()
    {
        $this->table = 't_rakuten_products';
        $this->filenames = $this->getFilesStartingWith(base_path() . '/util/outcsv/', 't_rakuten_products');
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

        foreach ($this->filenames as $filename) {
            $this->filename = base_path() . '/util/outcsv/' . $filename;
            parent::run();
        }
    }

    function getFilesStartingWith($dir, $prefix) {
        // ディレクトリが存在し、ディレクトリであることを確認
        if (!is_dir($dir)) {
            return "指定されたディレクトリが存在しません。";
        }
    
        // ディレクトリ内のファイルとディレクトリのリストを取得
        $files = scandir($dir);
    
        // 指定された文字列で始まるファイルを保持する配列
        $matchingFiles = [];
    
        // 各ファイルをチェック
        foreach ($files as $file) {
            // ドットファイルとディレクトリをスキップ
            if ($file === '.' || $file === '..') {
                continue;
            }
    
            // ファイルが指定されたプレフィックスで始まるかどうかを確認
            if (strpos($file, $prefix) === 0) {
                $matchingFiles[] = $file;
            }
        }
    
        return $matchingFiles;
    }
}
