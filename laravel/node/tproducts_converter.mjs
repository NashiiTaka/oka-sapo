import jsonData from './datas/tproducts/20240626_1158_lips_00001-00367.json' assert { type: 'json' };
import { JSDOM } from 'jsdom';
import fs from 'fs';
import Papa from 'papaparse';
import XLSX from 'xlsx';

const mapping = {
    brand: 'brand',
    brand_URL: 'brand_url',
    btncmnbuy_URL: 'buy_url',
    count: 'review_count',
    count_URL: 'review_url',
    iconcmnbestcosme: 'is_best_cosme',
    iconcmnrankingin: 'is_rankin_cosme',
    sell: 'release_date',
    value: 'rating',
    カテゴリー1: 'category1_name',
    カテゴリー_URL: 'category1_url',
    カテゴリー3: 'category2_name',
    カテゴリー_URL2: 'category2_url',
    タイトル: 'product_name',
    タイトルURL: 'product_url',
    フィールド: 'detail_info_html',
    フィールド1: 'valiation_info_html',
    フィールド2: 'more_detail_info_html',
    価格: 'price',
    点数: 'points',
};

const convertedArr = [];
// const detailInfoMenues = [
//     'メーカー',
//     'ブランド名',
//     'アイテムカテゴリ',
//     'ベストコスメ',
//     'ランキングIN',
//     '商品説明',
//     '色',
//     '使い方',
//     '公式サイト',
//     'Pickupカテゴリ',
//     '関心の高い<br>成分・特徴<a href="https://istyle.collasq.co…id=1033" target="_blank" title="成分・特徴について">?</a>',
//     '使用上の注意'
// ];

const detailInfoMenuesWithAction = {
    'メーカー': {
        renamed: 'maker', action: (innerHTML) => {
            const dom = new JSDOM(innerHTML);
            return [{
                renamed: 'maker_name',
                value: innerHTML ? dom.window.document.querySelector('a').textContent : null,
            }, {
                renamed: 'maker_id',
                value: innerHTML ? dom.window.document.querySelector('a').href.match(/https\:\/\/www\.cosme\.net\/maker\/maker\_id\/(\d+)/)[1] : null
            }];
        }
    },
    '公式サイト': {
        renamed: 'official_site', action: (innerHtml) => {
            const dom = new JSDOM(innerHtml);
            return dom.window.document.querySelector('a').href;
        }
    },
    '商品説明': { renamed: 'description', action: 'textContent' },
    '使い方': { renamed: 'how_to_use', action: 'textContent' },
    '関心の高い': {
        renamed: 'features', action: (innerHTML) => {
            const dom = new JSDOM(innerHTML);
            const values = [];
            dom.window.document.querySelectorAll('li').forEach((li) => values.push(li.textContent));
            return values.join('\n');
        }
    },
    '使用上の注意': { renamed: 'caution', action: 'textContent' },
};

const moreDetailInfoMenuesWithAction = {
    'JANコード': { renamed: 'jan_code', action: 'textContent' },
    '成分': { renamed: 'ingredients', action: 'textContent' },
};

for (const json of jsonData) {
    const converted = {};
    for (const mapKey in mapping) {
        converted[mapping[mapKey]] = json[mapKey];
        // ブランドIDを設定する。
        if (mapping[mapKey] === 'brand_url') {
            converted['brand_id'] = json[mapKey].match(/https\:\/\/www.cosme\.net\/brands\/(\d+)\//)[1];
        }
    }
    const dom = new JSDOM(converted['detail_info_html']);
    dom.window.document.querySelectorAll('dl').forEach((dl) => {
        const dtValue = dl.querySelector('dt').textContent.trim();
        for (const detailKey in detailInfoMenuesWithAction) {
            if (dtValue.startsWith(detailKey)) {
                const dd = dl.querySelector('dd');
                const treat = detailInfoMenuesWithAction[detailKey];
                const convertedValue = treat.action === 'textContent' ? dd.textContent : treat.action(dd.innerHTML);
                if (typeof (convertedValue) === 'string') {
                    converted[treat.renamed] = convertedValue;
                } else {
                    for (const value of convertedValue) {
                        converted[value.renamed] = value.value;
                    }
                }
                break;
            }
        }
    });

    const domMore = new JSDOM(converted['more_detail_info_html']);
    const modeDetailDl = domMore.window.document.querySelector('dl');
    const children = modeDetailDl?.children || [];
    for (let i = 0; i < children.length; i += 2) {
        const dt = modeDetailDl.children[i];
        const dd = modeDetailDl.children[i + 1];

        for (const moreDetailKey in moreDetailInfoMenuesWithAction) {
            if (dt.textContent.startsWith(moreDetailKey)) {
                const treat = moreDetailInfoMenuesWithAction[moreDetailKey];
                const convertedValue = treat.action === 'textContent' ? dd.textContent : treat.action(dd.innerHTML);
                if (typeof (convertedValue) === 'string') {
                    converted[treat.renamed] = convertedValue;
                } else {
                    for (const value of convertedValue) {
                        converted[value.renamed] = value.value;
                    }
                }
                break;
            }
        }
    }

    convertedArr.push(converted);
    console.log(converted.product_name);
}

function outputCsv(baseName, data) {
    fs.writeFile(`${process.cwd()}/node/outcsv/${baseName}.csv`, Papa.unparse(data), (err) => {
        if (err) {
            throw new Error('Error writing file:', err);
        } else {
            console.log('File has been saved.');
        }
    });
}

const makers = [];
const brands = [];
const categories = [];
const products = [];

/**
 * 出力用のカテゴリを追加する。常にカテゴリIDを返す。
 * @param {string} categoryName 
 * @param {string} categoryUrl 
 * @returns {string} カテゴリID
 */
function addCategoryIfNeeds(categoryName, categoryUrl) {
    // カテゴリー名がない場合は、何もしない
    if (!categoryName) { return; }

    // カテゴリIDを取得する
    const category_id = categoryUrl.match(/https\:\/\/www\.cosme\.net\/categories\/item\/(\d+)\//)[1];

    // https://www.cosme.net/categories/item/(\d+)/

    if (categoryName && !categories.find((c) => c.category_id === category_id)) {
        categories.push({ category_name: categoryName, category_id: category_id });
    }

    return category_id;
}

for (const converted of convertedArr) {
    // メーカーIDが欠損したデータがあったので、その場合は他の行の同ブランドから、メーカーIDを取得する
    if (!converted.maker_id) {
        const hasMakerRecord = convertedArr.find((c) => c.brand_id === converted.brand_id && c.maker_id);
        if (hasMakerRecord) {
            converted.maker_id = hasMakerRecord.maker_id;
            converted.maker_name = hasMakerRecord.maker_name;
        } else {
            throw new Error(`メーカーIDが欠損しているデータがあります。product_url: ${converted.product_url}`);
        }
    }

    // 重複を避け、メーカーテーブルのデータを作成
    if (converted.maker_id && !makers.find((m) => m.maker_id === converted.maker_id)) {
        makers.push({ maker_name: converted.maker_name, maker_id: converted.maker_id });
    }

    // 重複を避け、ブランドテーブルのデータを作成
    if (converted.brand_id && !brands.find((b) => b.brand_id === converted.brand_id)) {
        brands.push({ brand_name: converted.brand, brand_id: converted.brand_id, official_site: converted.official_site, maker_id: converted.maker_id });
    }

    // 重複を避け、カテゴリーテーブルを作成
    const category1_id = addCategoryIfNeeds(converted.category1_name, converted.category1_url);
    const category2_id = addCategoryIfNeeds(converted.category2_name, converted.category2_url);

    let release_date_additional = null;
    try {
        release_date_additional = converted.release_date.match(/(\d+\/\d+\/\d+)追加発売/)[1];
    } catch (e) {
    }

    let release_date = null;
    try {
        release_date = converted.release_date.match(/発売日：(\d+\/\d+\/\d+)/)[1];
    } catch (e) {
    }

    let price_with_tax = null;
    try {
        price_with_tax = Number.parseInt(converted.price.match(/([\d,]+)円/)[1].replace(/,/g, ''));
    } catch (e) {
    }

    let net_content = null;
    try {
        net_content = converted.price.match(/容量.*：([^・]+).*$/)[1];
    } catch (e) {
    }
    
    // 商品情報を作成
    products.push({
        product_id: converted.product_url.match(/https\:\/\/www\.cosme\.net\/products\/(\d+)\//)[1],
        product_name: converted.product_name,
        maker_id: converted.maker_id,
        brand_id: converted.brand_id,
        category1_id: category1_id,
        category2_id: category2_id,
        jan_code: converted.jan_code,
        release_date: release_date,
        release_date_additional: release_date_additional,
        rating: converted.rating,
        points: Number.parseInt(converted.points.replace('pt', '')),
        review_count: converted.review_count.match(/(\d+)/)[1],
        is_best_cosme: converted.is_best_cosme ? 1 : 0,
        is_rankin_cosme: converted.is_rankin_cosme ? 1 : 0,
        price_with_tax: price_with_tax,
        net_content: net_content,
        description: converted.description,
        ingredients: converted.ingredients,
        how_to_use: converted.how_to_use,
        features: converted.features,
        caution: converted.caution,
        buy_url: converted.buy_url,
    });
}
outputCsv('t_makers', makers);
outputCsv('t_brands', brands);
outputCsv('m_categories', categories);
outputCsv('t_products', products);

// // Excel用に、300文字以上の文字列は300文字に切り詰める
// for(const converted of convertedArr) {
//     for(const key in converted) {
//         if(converted[key].length > 300) {
//             converted[key] = converted[key].substring(0, 300);
//         }
//     }
// }

// ワークブックとワークシートを作成
// const workbook = XLSX.utils.book_new();
// const worksheet = XLSX.utils.json_to_sheet(convertedArr);

// // ワークシートをワークブックに追加
// XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet1");

// // Excel ファイルとして書き出し
// XLSX.writeFile(workbook, 'output2.xlsx');

// console.log(detailInfoMenues);


// // ファイルを書き出す
// fs.writeFile('data.json', jsonData.map((d) => `${d.genreId}\t${d.genreLevel}\t${d.genreName}`).join(`\n`), (err) => {
//   if (err) {
//     console.error('Error writing file:', err);
//   } else {
//     console.log('File has been saved.');
//   }
// });



