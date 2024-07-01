import fs from 'fs';
import Papa from 'papaparse';

export function outputCsv(baseName, data) {
    fs.writeFile(`${process.cwd()}/util/outcsv/${baseName}.csv`, Papa.unparse(data), (err) => {
        if (err) {
            throw new Error('Error writing file:', err);
        } else {
            console.log('File has been saved.');
        }
    });
}

// Sleep関数の定義
export function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
};
