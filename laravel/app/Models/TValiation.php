<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\OsusumeParams;

/**
 * 
 *
 * @property int $id
 * @property int $valiation_id バリエーションID
 * @property int $product_id 商品ID
 * @property string $valiation_name バリエーション名
 * @property string|null $extension 拡張子、「.」を含まない
 * @property int $r RGBのR値
 * @property int $g RGBのG値
 * @property int $b RGBのB値
 * @property string $hex_color_code 16進数カラーコード「#FFEECC」の形式
 * @property int $is_active 現在有効なバリエーション化どうか
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TValiation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TValiation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TValiation query()
 * @method static \Illuminate\Database\Eloquent\Builder|TValiation whereB($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TValiation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TValiation whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TValiation whereG($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TValiation whereHexColorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TValiation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TValiation whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TValiation whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TValiation whereR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TValiation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TValiation whereValiationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TValiation whereValiationName($value)
 * @property string|null $pre_hex_color_code カラーチェックで変更された場合、変更前のカラーが入る。
 * @property string|null $checked_at カラーチェックを実施した日時
 * @property-read \App\Models\TProduct|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|TValiation whereCheckedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TValiation wherePreHexColorCode($value)
 * @mixin \Eloquent
 */
class TValiation extends Model
{
    use HasFactory;

    /**
     * 
     * 商品情報を取得
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(TProduct::class, 'product_id', 'product_id');
    }

    /**
     * 画像パスを取得する
     *
     * @return string 画像パス
     */
    public function getImagePath(): string
    {
        return '/img/valiations/' . $this->product_id . '/' . $this->valiation_id . '.' . $this->extension;
    }

    const COLOR_DIFF = 20;
    /**
     * 推薦商品の配列を返却する。
     *
     * @param OsusumeParams $params 検索パラメータ
     * @return array<TProduct> 推薦商品の配列を返却する。
     */
    static function 
    getRecommendations(OsusumeParams $params)
    {
        $colorOrderCondition = ['sql' => [], 'params' => []];

        foreach($params->colors as $index => $color) {
            $colorOrderCondition['sql'][] = 'sqrt(pow(r - ?, 2) + pow(g - ?, 2) + pow(g - ?, 2))';
            $colorOrderCondition['params'][] = $color->getRed();
            $colorOrderCondition['params'][] = $color->getGreen();
            $colorOrderCondition['params'][] = $color->getBlue();
        }

        return
            self::where('is_active', 1)
            ->orderByRaw(
                count($params->colors) > 1 ?
                    'LEAST(' . implode(', ', $colorOrderCondition['sql']) . ') asc' :
                    $colorOrderCondition['sql'][0] . ' asc'
                , $colorOrderCondition['params']
            )
            ->with('product')
            ->with('product.maker')
            ->with('product.brand')
            ->limit(300)
            ->get();
    }
}
