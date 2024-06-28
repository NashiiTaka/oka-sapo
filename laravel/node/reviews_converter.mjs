import { JSDOM } from 'jsdom';
import fs from 'fs';
import { outputCsv } from './util.mjs';
import XLSX from 'xlsx';

const jsonFiles = [
    `${process.cwd()}/node/datas/reviews/アットコスメ_リップ_レビュー.json`,
    `${process.cwd()}/node/datas/reviews/アットコスメ_リップ_レビュー_再開.json`,
    `${process.cwd()}/node/datas/reviews/アットコスメ_リップ_レビュー_再開2.json`,
];

const mapping = {
    記事内容: { action: (content, buffer) => buffer['review_content'] = content },
    投稿日時: { action: (content, buffer) => buffer['published_at'] = content },
    著者: { action: (content, buffer) => buffer['reviewer_name'] = content },
    フィールド1: { action: (content, buffer) => { try { buffer['followers_level'] = content ? content.match(/\d+/)[0] : null } catch (e) { buffer['favorited_by'] = null } } },
    フィールド3: { action: (content, buffer) => buffer['method_of_acquisition'] = content },
    // _テキスト: { action: (content, buffer) => buffer['product_name'] = content },
    _リンク: { action: (content, buffer) => buffer['product_id'] = content.match(/\d+/)[0] },
    フィールド2_リンク: { action: (content, buffer) => { try { buffer['reviewer_id'] = content.match(/\d+/)[0] } catch (e) { buffer['reviewer_id'] = null } } },
    フィールド4: { action: (content, buffer) => buffer['reviews_count'] = content },
    フィールド5: { action: (content, buffer) => buffer['reviewer_age'] = content.match(/\d+/)[0] },
    フィールド6: { action: (content, buffer) => buffer['reviewer_skin_type'] = content },
    フィールド7: { action: (content, buffer) => { try { buffer['rating'] = content.match(/\d+/)[0] } catch (e) { buffer['rating'] = null; } } },
    フィールド8: {
        action: (content, buffer) => {
            // HTMLを解析
            const dom = new JSDOM(content);

            // 全ての<dt>要素を取得
            const dts = dom.window.document.querySelectorAll("dt");
            let targetDt = null;

            // テキストコンテンツが'効果'である<dt>要素を探す
            for (let dt of dts) {
                if (dt.textContent.trim() === '効果') {
                    targetDt = dt;
                    break;
                }
            }

            if (!targetDt) {
                buffer['effects'] = null;
            } else {
                let dd = targetDt.nextElementSibling;
                let effects = [];
                // textContentを取得
                while (dd && dd.tagName.toLowerCase() === 'dd') {
                    effects.push(dd.textContent);
                    dd = dd.nextElementSibling;
                }

                const joined = effects.join(',');
                buffer['effects'] = joined === '-' ? null : joined;
            }
        }
    },
    フィールド9: { action: (content, buffer) => buffer['review_id'] = content.match(/data-object\.parent\.id\=\"(\d+)\"/)[1] },
};

let jsonData = {};

try {
    for (const jsonFile of jsonFiles) {
        jsonData = { ...jsonData, ...JSON.parse(fs.readFileSync(jsonFile, 'utf8')) };
    }
} catch (err) {
    console.error(err);
}

// console.log(jsonData);

const convertedArr = [];
const registeredIds = [];

for (const key in jsonData) {
    if (!jsonData[key]['記事内容']) { continue; }

    const buffer = {};
    for (const [k, v] of Object.entries(jsonData[key])) {
        if (mapping[k]) {
            mapping[k].action(v, buffer);
        }
    }

    console.log(buffer);
    if (!registeredIds.includes(buffer['review_id'])) {
        convertedArr.push(buffer);
        registeredIds.push(buffer['review_id']);
    }
}

outputCsv('t_reviews', convertedArr);