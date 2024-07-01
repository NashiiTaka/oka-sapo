import fs from 'fs';
import Papa from 'papaparse';
import { sleep } from './util.mjs';

// 楽天商品検索API (version:2022-06-01)
// https://webservice.rakuten.co.jp/documentation/ichiba-item-search
const baseUrl = `https://app.rakuten.co.jp/services/api/IchibaGenre/Search/20140222?`;
// APIパラメータ
const params = {
  applicationId: '1053351987856881203',
  affiliateId: '3c3d4130.5cc1bcfa.3c3d4131.20636aee',
  formatVersion: '2',
  genreId: '',
};

// データ取得の対象フィールド名
const tgtFieldName = 'children';

// データ格納用
const dataLines = [];

/**
 * ジャンル取得ロジック。再起的に子ジャンルも取得する。
 * @param {*} genreId 取得対象のジャンルID
 * @returns 
 */
async function appendOneJanre(genreId) {
  return new Promise(async (resolve) => {
    // 検索パラメータの生成
    let paramedUrl = baseUrl;
    let isFirst = true;
    params.genreId = genreId;
    for (const key in params) {
      paramedUrl += `${isFirst ? '' : '&'}${key}=${params[key]}`;
      isFirst = false;
    }
    console.log(paramedUrl);
  
    // データ取得の実施
    const response = await fetch(paramedUrl); // 例として使うAPIエンドポイント
    const data = await response.json();
    console.log('data received: ' + genreId);

    // データ行の生成と、子ジャンルの取得
    if (data && data[tgtFieldName] && data[tgtFieldName].length > 0) {
      console.log('children exists: ' + genreId);
      for(const d of data[tgtFieldName]){
        dataLines.push({genre_id: d.genreId, genre_level: d.genreLevel, genre_name: d.genreName, parent_genre_id: genreId});
      }

      // 子ジャンルの取得
      for(const d of data[tgtFieldName]){
        // 負荷軽減の為、2秒ウェイト
        await sleep(500);
        await appendOneJanre(d.genreId, true);
      };
    }else{
      console.log('children doesnt exists: ' + genreId);
    }

    // 非同期処理の完了
    resolve();
  })
}

const topGenres = [
  // 100000, // 百貨店・総合通販・ギフト
  // 100005, // 花・ガーデン・DIY
  // 100227, // 食品
  // 100316, // 水・ソフトドリンク
  // 100371, // レディースファッション
  // 100433, // インナー・下着・ナイトウェア
  // 100533, // キッズ・ベビー・マタニティ
  // 100804, // インテリア・寝具・収納
  // 100938, // ダイエット・健康
  // 101070, // スポーツ・アウトドア
  // 101114, // 車・バイク
  // 101213, // ペット・ペットグッズ
  // 101381, // カタログギフト・チケット
  // 101438, // サービス・リフォーム
  // 111427, // 住宅・不動産
  // 200162, // 本・雑誌・コミック
  // 215783, // 日用品雑貨・文房具・手芸
  // 216129, // ジュエリー・アクセサリー
  // 216131, // バッグ・小物・ブランド雑貨
  // 503190, // 車用品・バイク用品
  // 510901, // 日本酒・焼酎
  // 510915, // ビール・洋酒
  // 551167, // スイーツ・お菓子
  // 551169, // 医薬品・コンタクト・介護
  // 551177, // メンズファッション
  // 558885, // 靴

  // // 実施済み
  100939, // 美容・コスメ・香水
  // 101205, // テレビゲーム
  // 566382, // おもちゃ
  // 558929, // 腕時計
  // 112493, // 楽器・音響機器
  // 101164, // ホビー
  // 101240, // CD・DVD
  // '562637', // ,1,家電
  // '211742', // ,1,TV・オーディオ・カメラ
  // '100026', // ,1,パソコン・周辺機器
  // '564500', // ,1,スマートフォン・タブレット
  // '565004', // ,1,光回線・モバイル通信
  // '558944', // キッチン用品・食器・調理器具
];

for(const g of topGenres){
  await appendOneJanre(g);
}

const csv = Papa.unparse(dataLines);

// ファイル出力
fs.writeFile('util/outcsv/m_rakuten_genres_temp.csv', csv, (err) => {
  if (err) {
      console.error('Error writing CSV file', err);
  } else {
      console.log('CSV file was written successfully');
  }
});

// 重複データが発生してしまった場合、下記のように重複を除外する処理を追加する
// const inputFilePath = 'util/outcsv/m_rakuten_genres.csv';
// const outputFilePath = 'util/outcsv/m_rakuten_genres_converted.csv';

// // ファイルを非同期で読み込む
// fs.readFile(inputFilePath, 'utf8', (err, fileContent) => {
//   if (err) {
//     console.error('ファイルの読み込みに失敗しました:', err);
//     return;
//   }

//   // CSVデータをパース
//   Papa.parse(fileContent, {
//     header: true,
//     complete: (results) => {
//       const data = results.data;
//       const uniqueData = [];
//       const seenKeys = new Set();

//       // 重複を除外
//       data.forEach((row) => {
//         const key = row[Object.keys(row)[0]]; // 1つ目の項目をキーとして使用
//         if (!seenKeys.has(key)) {
//           seenKeys.add(key);
//           uniqueData.push(row);
//         }
//       });

//       // 新しいCSVデータを生成
//       const csv = Papa.unparse(uniqueData);

//       // 出力ファイルに書き込み
//       fs.writeFile(outputFilePath, csv, (err) => {
//         if (err) {
//           console.error('ファイルの書き込みに失敗しました:', err);
//           return;
//         }
//         console.log('重複を除外したデータを出力ファイルに書き込みました。');
//       });
//     }
//   });
// });

