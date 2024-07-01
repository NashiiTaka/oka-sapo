// 楽天API

import fs from 'fs';
import Papa from 'papaparse';
import { sleep } from './util.mjs';

// 楽天商品検索API (version:2022-06-01)
// https://webservice.rakuten.co.jp/documentation/ichiba-item-search
const baseUrl = `https://app.rakuten.co.jp/services/api/IchibaItem/Search/20220601?`;

const params = {
  applicationId: '1053351987856881203',
  affiliateId: '3c3d4130.5cc1bcfa.3c3d4131.20636aee',
  formatVersion: '2',
  imageFlag: '1',
  sort: '-updateTimestamp',
  minPrice: '',
  maxPrice: '',
  genreId: '',
  page: '',
};

// 取得対象のジャンルID。子ジャンルは0で設定する。
const tgtGenreIds = [
  216600, // 口紅・リップスティック
  503106, // リップライナー
  216620, // リップグロス
];

const renameFields = {
  affiliateRate: 'affiliate_rate',
  affiliateUrl: 'affiliate_url',
  asurakuArea: 'asuraku_area',
  asurakuClosingTime: 'asuraku_closing_time',
  asurakuFlag: 'asuraku_flag',
  availability: 'availability',
  catchcopy: 'catchcopy',
  creditCardFlag: 'credit_card_flag',
  endTime: 'end_time',
  genreId: 'genre_id',
  giftFlag: 'gift_flag',
  imageFlag: 'image_flag',
  itemCaption: 'item_caption',
  itemCode: 'product_id',
  itemName: 'item_name',
  itemPrice: 'item_price',
  itemPriceBaseField: 'item_price_base_field',
  itemPriceMax1: 'item_price_max_1',
  itemPriceMax2: 'item_price_max_2',
  itemPriceMax3: 'item_price_max_3',
  itemPriceMin1: 'item_price_min_1',
  itemPriceMin2: 'item_price_min_2',
  itemPriceMin3: 'item_price_min_3',
  itemUrl: 'item_url',
  pointRate: 'point_rate',
  pointRateEndTime: 'point_rate_end_time',
  pointRateStartTime: 'point_rate_start_time',
  postageFlag: 'postage_flag',
  reviewAverage: 'review_average',
  reviewCount: 'review_count',
  shipOverseasArea: 'ship_overseas_area',
  shipOverseasFlag: 'ship_overseas_flag',
  shopAffiliateUrl: 'shop_affiliate_url',
  shopCode: 'shop_code',
  shopName: 'shop_name',
  shopOfTheYearFlag: 'shop_of_the_year_flag',
  shopUrl: 'shop_url',
  startTime: 'start_time',
  tagIds: 'tag_ids',
  taxFlag: 'tax_flag',
};

let csvLines = [];

function getData(url) {
  return new Promise(async (resolve) => {
    // 負荷軽減のため、1000ms ウェイト
    await sleep(1000);
    console.log(url);
    // データ取得の実施
    fetch(url) // 例として使うAPIエンドポイント
      .then(response => response.json())
      .then(async (data) => {
        if(data.Items){
          resolve(data);
        }else{
          console.log('No items found and retry: ' + data);
          // 30秒ウェイトして再度取得
          await sleep(30000);
          fetch(url)
            .then(response => response.json())
            .then(data => {
              if(data.Items){
                resolve(data);
              }else{
                throw new Error('No items found: ' + data);
              }
            });
        }
      })
      .catch(error => {
        throw new Error('Network Error:', error);
      });
  });
}

function getParamedUrl(genreId, page, minPrice, maxPrice) {
  // 検索パラメータの生成
  params.genreId = genreId;
  params.page = page;
  params.minPrice = minPrice;
  params.maxPrice = maxPrice;
  let paramedUrl = baseUrl;
  let isFirstParam = true;
  for (const key in params) {
    paramedUrl += `${isFirstParam ? '' : '&'}${key}=${params[key]}`;
    isFirstParam = false;
  }
  return paramedUrl;
}

// 一つのジャンル & 指定の結果ページの商品データを取得する。
function doOneRange(genreId, minPrice, maxPrice, endPrice) {
  return new Promise(async (resolve) => {
    let currentMinPrice = minPrice;
    let currentMaxPrice = maxPrice;

    let data = await getData(getParamedUrl(genreId, 1, currentMinPrice, currentMaxPrice));

    // ページ数を取得し、100件以上の場合には、価格帯を細かくして再度取得
    while (parseInt(data.pageCount) >= 100) {
      currentMaxPrice = currentMinPrice + Math.floor((currentMaxPrice - currentMinPrice) / 2);
      data = await getData(getParamedUrl(genreId, 1, currentMinPrice, currentMaxPrice));
      if(currentMaxPrice === currentMinPrice && parseInt(data.pageCount) >= 100){
        throw new Error('Too many items in the price range: ' + currentMinPrice);
      }
    }

    // ページ数を取得
    const currentPageCount = parseInt(data?.pageCount) || 0;

    // 全ページのデータを追記
    appendData(data);
    for(let i = 2; i <= currentPageCount; i++){
      await doOne(genreId, i, currentMinPrice, currentMaxPrice);
    }

    // 1つの価格帯のデータを書き込み
    witeToFile(csvLines, genreId, currentMinPrice, currentMaxPrice);
    // データをリセットしておく
    csvLines = [];

    // 次の価格帯を設定する。
    if(currentMaxPrice < endPrice){
      // 次の価格帯は、今回の最大値 + 1円とする。
      let nextMinPrice = currentMaxPrice + 1;
      // 今回の結果が30ページ以下の場合は、価格帯を2倍にする
      let nextMaxPrice = Math.min(nextMinPrice + (currentMaxPrice - currentMinPrice) * (currentPageCount <= 30 ? 2 : 1), endPrice);
      await doOneRange(genreId, nextMinPrice, nextMaxPrice, endPrice);
    }

    resolve();
  });
}

function doOne(genreId, page, minPrice, maxPrice) {
  return new Promise(async (resolve) => {
    const data = await getData(getParamedUrl(genreId, page, minPrice, maxPrice));
    appendData(data);
    resolve();
  });
}

const pusuhedIds = [];
function appendData(data) {
  if (!data?.Items?.length) { return; }

  // データを書き込み
  data.Items.forEach(item => {
    // 3つの画像URLをそれぞれのフィールドに分割
    item.mediumImageUrls ||= [];
    for (let i = 0; i < 3; i++) {
      item[`medium_image_url_${i + 1}`] = item.mediumImageUrls[i];
    }
    delete item.mediumImageUrls;
    item.smallImageUrls ||= [];
    for (let i = 0; i < 3; i++) {
      item[`small_image_url_${i + 1}`] = item.smallImageUrls[i];
    }
    delete item.smallImageUrls;

    // フィールド名の変更
    for (const key in renameFields) {
      if (key === renameFields[key]) { continue; }
      item[renameFields[key]] = item[key];
      delete item[key];
    }

    item.tag_ids = item.tagIds ? item.tagIds.join('|') : '';

    // product_idが重複する場合は、登録しない
    if (!pusuhedIds.includes(item.product_id)) {
      csvLines.push(item);
      pusuhedIds.push(item.product_id);
    }
    else {
      console.log('product_id duplicated: ' + item.product_id);
    }
  });
}


for (const id of tgtGenreIds) {
  await doOneRange(id, 1, 500, 200000);
  // , 1);
  // await sleep(1000);
  // for (let i = 2; i <= pageCount; i++) {
  //   await doOne(id, i);

  //   // テスト用、5ページまで
  //   // if (i >= 5) { break; }
  //   await sleep(1000);
  // }
}

function witeToFile(data, genre_id, minPrice, maxPrice){
  fs.writeFileSync(
    `util/outcsv/t_rakuten_products_${genre_id}_${minPrice}_${maxPrice}.csv`, 
    Papa.unparse(data, { header: true })
  );
}

