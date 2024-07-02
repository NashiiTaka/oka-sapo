from sklearn.cluster import KMeans # pip install scikit-learn
import cv2 # pip install opencv-python
import numpy as np
from collections import Counter
import matplotlib.pyplot as plt # pip install matplotlib
import glob
import os
import csv

def is_white_to_black_color(pixel, diff_more_than=10):
    """白から黒のグラデーションカラーかを判定。RGBの最大値と最小値の差がdiff以下であればそれに該当すると判定。"""
    max_value = np.max(pixel)
    min_value = np.min(pixel)
    return (max_value - min_value) <= diff_more_than

def is_black(pixel, threshold=50):
    """黒系の色を判定する関数。RGBの全ての値がthreshold以下であれば黒系と見なす。"""
    return np.all(pixel < threshold)

def filter_black_or_white_pixels(pixels, black_threshold=50, diff_more_than=10):
    """黒系・白系のピクセルを除外する関数"""
    return np.array([pixel for pixel in pixels if not is_black(pixel, black_threshold) and not is_white_to_black_color(pixel, diff_more_than)])

def get_dominant_colors(image_path, num_colors=5, black_threshold=50, diff_more_than=10):
    # 画像を読み込み
    image = cv2.imread(image_path)
    image = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
    
    # 画像のリサイズ（オプション）
    resized_image = cv2.resize(image, (100, 100), interpolation=cv2.INTER_AREA)
    
    # 画像のピクセルを2次元配列に変換
    pixels = resized_image.reshape((-1, 3))
    
    # 黒系 or 白系のピクセルを除外
    filtered_pixels = filter_black_or_white_pixels(pixels, black_threshold, diff_more_than)
    
    if filtered_pixels.size == 0:
        filtered_pixels = pixels
    
    # サンプル数を確認
    n_samples = filtered_pixels.shape[0]

    # n_samplesがnum_colorsを下回る場合は、n_samplesをn_clustersに設定
    n_clusters = min(num_colors, n_samples)
    
    # KMeansクラスタリングを適用
    kmeans = KMeans(n_clusters=n_clusters)
    kmeans.fit(filtered_pixels)
    
    # クラスタリングの中心点を取得（代表色）
    colors = kmeans.cluster_centers_
    colors = colors.astype(int)
    
    # 各クラスタの割合を計算
    counts = Counter(kmeans.labels_)
    total_count = sum(counts.values())
    proportions = [(count / total_count) for count in counts.values()]
    
    return colors, proportions

def plot_colors(colors, proportions):
    # 代表色とその割合を表示
    plt.figure(figsize=(8, 2))
    bar = np.zeros((50, 300, 3), dtype="uint8")
    start = 0
    
    for color, proportion in zip(colors, proportions):
        end = start + (proportion * 300)
        bar[:, int(start):int(end), :] = color
        start = end
    
    plt.imshow(bar)
    plt.axis('off')
    plt.show()

# すべての画像ファイルを取得
def get_all_image_files_with_details(root_dir):
    # 対象とする画像ファイルの拡張子リスト
    image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp']

    # すべての画像ファイルの詳細を格納するリスト
    image_files_details = []

    # 画像拡張子ごとにファイルを検索
    for ext in image_extensions:
        # 指定されたディレクトリ以下のすべての画像ファイルを取得
        for image_file in glob.glob(os.path.join(root_dir, '**', f'*.{ext}'), recursive=True):
            # 商品IDを取得（root_dirの直下のディレクトリ名）
            product_id = os.path.basename(os.path.dirname(image_file))
            # 拡張子を除いた画像ファイル名を取得
            file_name_without_ext = os.path.splitext(os.path.basename(image_file))[0]
            # 詳細をリストに追加
            image_files_details.append({
                "product_id": product_id, 
                "file_name_without_ext": file_name_without_ext,
                "ext": ext,
                "path": image_file
            })

    return image_files_details

def rgb_to_hex(rgb):
    return "#{:02X}{:02X}{:02X}".format(round(rgb[0]), round(rgb[1]), round(rgb[2]))

def get_valiation_dict():
    # CSVファイルのパスを指定
    csv_file_path = 'util/outcsv/valiations_source_imgs.csv'

    # 空の辞書を作成
    valiation_dict = {}

    # CSVファイルを読み込む
    with open(csv_file_path, mode='r', encoding='utf-8') as file:
        reader = csv.DictReader(file)
        for row in reader:
            # valiation_idをキー、valiation_nameをバリューとして辞書に追加
            valiation_id = row['valiation_id']
            valiation_name = row['valiation_name']
            valiation_dict[valiation_id] = valiation_name
    
    return valiation_dict

# 使用例
def main():
    all_img_files = get_all_image_files_with_details('public/img/valiations')

    count = 0
    
    valiation_dict = get_valiation_dict()

    csvColorInfo = "product_id,valiation_id,ttl_color,c1_color,c1_proportion,c2_color,c2_proportion,c3_color,c3_proportion,c4_color,c4_proportion,c5_color,c5_proportion\n"
    csvValiations = "valiation_id,product_id,valiation_name,extension,r,g,b,hex_color_code,is_active\n"
    
    for valiation in all_img_files:
        # gifファイルは除外
        if valiation["ext"] == "gif":
            continue
        
        dominant_colors, proportions = get_dominant_colors(valiation["path"], num_colors=5, black_threshold=50, diff_more_than=10)
        
        # 配色を描画
        # plot_colors(dominant_colors, proportions)
        
        buff = valiation["product_id"] + "," + valiation["file_name_without_ext"]
        buff_append = ""
        ttl = [0, 0, 0]

        # RGB値と割合を出力
        for i, (color, proportion) in enumerate(zip(dominant_colors, proportions)):
            # print(f"Color {i+1}: RGB = {color}, Proportion = {proportion:.2%}")
            ttl = [ttl[0] + color[0] * proportion, ttl[1] + color[1] * proportion, ttl[2] + color[2] * proportion]
            buff_append += "," + rgb_to_hex(color) + "," + str(proportion)
        ttlColor =rgb_to_hex(ttl)
        csvColorInfo += buff + "," + ttlColor + buff_append + "\n"
        
        if valiation_dict.get(valiation["file_name_without_ext"]) is None:
            print(f"valiation_dictに{valiation['file_name_without_ext']}が存在しません。")
            continue
        
        valiation_name = valiation_dict[valiation["file_name_without_ext"]]
        
        csvValiations += (
            valiation["file_name_without_ext"] + "," + 
            valiation["product_id"] + "," + 
            "\"" + valiation_name + "\"," + 
            valiation["ext"] + "," + 
            str(round(ttl[0])) + "," +
            str(round(ttl[1])) + "," +
            str(round(ttl[2])) + "," +
            ttlColor + "," + 
            ("0" if "生産終了" in valiation_name else "1") + "\n"
        )

        count += 1
        print(f"完了: {count}")
        
        # テスト用、途中で出力を止める
        # if count >= 100:
        #     break

    with open("util/outcsv/valiations_with_color.csv", 'w') as file:
        file.write(csvColorInfo)
    
    with open("util/outcsv/t_valiations.csv", 'w') as file:
        file.write(csvValiations)

if __name__ == "__main__":
    main()