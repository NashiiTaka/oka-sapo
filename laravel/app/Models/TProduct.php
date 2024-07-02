<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\OsusumeParams;

/**
 * 
 *
 * @property int $id
 * @property int $product_id 取得元の商品ID
 * @property string $product_name 商品名
 * @property int $maker_id メーカーID
 * @property int $brand_id ブランドID
 * @property int $category1_id カテゴリ1ID
 * @property int|null $category2_id カテゴリ2ID
 * @property string|null $jan_code JANコード、未設定の場合有り
 * @property string|null $release_date 発売日
 * @property string|null $release_date_additional 追加発売日
 * @property float|null $rating レビュー点数
 * @property float|null $points 評価ポイント
 * @property int|null $review_count レビュー数
 * @property int $is_best_cosme ベストコスメ受賞有無
 * @property int $is_rankin_cosme ランキンコスメ受賞有無
 * @property int|null $price_with_tax 税込価格
 * @property string|null $net_content 内容量
 * @property string|null $description 商品説明
 * @property string|null $ingredients 成分
 * @property string|null $how_to_use 使用方法
 * @property string|null $features 特徴
 * @property string|null $caution 注意事項
 * @property string|null $buy_url 購入URL
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereBuyUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereCategory1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereCategory2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereCaution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereHowToUse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereIngredients($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereIsBestCosme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereIsRankinCosme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereJanCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereMakerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereNetContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct wherePriceWithTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereReleaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereReleaseDateAdditional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereReviewCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TProduct whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TValiation> $valiations
 * @property-read int|null $valiations_count
 * @mixin \Eloquent
 */
class TProduct extends Model
{
    public function maker(): BelongsTo
    {
        return $this->belongsTo(TMaker::class, 'maker_id', 'maker_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(TBrand::class, 'brand_id', 'brand_id');
    }

    /**
     * 画像パスを取得する
     *
     * @return string 画像パス
     */
    public function getImagePath(): string
    {
        return '/img/main/' . $this->img_file_name;
    }

    /**
     * バリエーション情報を取得
     *
     * @return HasMany
     */
    public function valiations(): HasMany
    {
        return $this->hasMany(TValiation::class, 'product_id', 'product_id');
    }

    use HasFactory;

    /**
     * 推薦商品の配列を返却する。
     *
     * @param OsusumeParams $params 検索パラメータ
     * @return array<TProduct> 推薦商品の配列を返却する。
     */
    static function getRecommendations(OsusumeParams $params)
    {
        $valiationsWithProduct = TValiation::getRecommendations($params);

        // バリエーション情報から商品情報を取得。
        $ret = [];
        $insertedProductIds = [];
        $idAndValiations = [];

        foreach ($valiationsWithProduct as $valiation) {
            if(count($insertedProductIds) <= 2 && !in_array($valiation->product_id, $insertedProductIds)){
                $ret[] = $valiation->product;
                $insertedProductIds[] = $valiation->product_id;
            }
            
            if(in_array($valiation->product_id, $insertedProductIds)){
                $idAndValiations[$valiation->product_id] = $idAndValiations[$valiation->product_id] ?? [];
                $idAndValiations[$valiation->product_id][] = $valiation;
            }
        }

        foreach ($ret as $product) {
            $product->valiations = $idAndValiations[$product->product_id];
        }

        return $ret;
    }
}