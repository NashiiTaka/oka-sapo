import csv
import requests
import time
import os
from urllib.parse import urlparse

# CSVファイルのパス
csv_file_path = 'util/outcsv/main_imgs.csv'

def get_file_extension(url):
    """URLからファイル拡張子を取得する関数"""
    path = urlparse(url).path
    ext = os.path.splitext(path)[1]
    if not ext:
        return '.jpg'
    return ext

def download_image(img_url, save_path):
    response = requests.get(img_url)
    if response.status_code == 200:
        with open(save_path, 'wb') as f:
            f.write(response.content)
        print(f"Downloaded: {save_path}")
    else:
        print(f"Failed to download: {img_url}")

def main():
    with open(csv_file_path, newline='', encoding='utf-8') as csvfile:
        reader = csv.DictReader(csvfile)
        count = 0
        for row in reader:
            img_url = row['img_url']
            product_id = row['product_id']
            ext = get_file_extension(img_url)
            
            # 画像保存先のディレクトリ
            save_directory = 'public/img/main/'
            save_path = os.path.join(save_directory, f"{product_id}{ext}")
            
            if os.path.exists(save_path):
                print(f"ファイル '{save_path}' が既に存在します。処理をスキップします。")
                continue
            
            os.makedirs(save_directory, exist_ok=True)
            download_image(img_url, save_path)
            print(img_url)
            
            count += 1
            if count % 2 == 0:
                time.sleep(1)  # 1秒間に2ファイルの制限

if __name__ == "__main__":
    main()