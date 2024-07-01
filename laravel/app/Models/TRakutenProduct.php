<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property string $product_id 商品ID
 * @property string $item_name 商品名
 * @property string|null $catchcopy キャッチコピー
 * @property string|null $item_caption 商品説明
 * @property int $genre_id ジャンルID
 * @property int $item_price 商品価格(税込)(送料別)
 * @property float $affiliate_rate アフィリエイト率
 * @property float|null $point_rate ポイント率
 * @property string|null $point_rate_end_time ポイント率適用終了日時
 * @property string|null $point_rate_start_time ポイント率適用開始日時
 * @property string|null $shop_code ショップコード
 * @property string|null $shop_name ショップ名
 * @property float|null $review_average レビュー平均
 * @property int|null $review_count レビュー件数
 * @property string|null $item_url 商品URL
 * @property string|null $item_price_base_field 商品価格基準フィールド
 * @property int|null $item_price_max_1 商品価格最大(税込)(送料別) 1
 * @property int|null $item_price_max_2 商品価格最大(税込)(送料別) 2
 * @property int|null $item_price_max_3 商品価格最大(税込)(送料別) 3
 * @property int|null $item_price_min_1 商品価格最小(税込)(送料別) 1
 * @property int|null $item_price_min_2 商品価格最小(税込)(送料別) 2
 * @property int|null $item_price_min_3 商品価格最小(税込)(送料別) 3
 * @property int|null $gift_flag ギフトフラグ
 * @property int|null $image_flag 画像フラグ
 * @property int|null $postage_flag 送料フラグ 0：送料込 1：送料別
 * @property string|null $ship_overseas_area 海外発送フラグエリア
 * @property int|null $ship_overseas_flag 海外発送フラグ
 * @property int|null $shop_of_the_year_flag 年間ショップフラグ
 * @property string|null $start_time 開始時間
 * @property string|null $end_time 終了時間
 * @property string|null $tag_ids タグID 「|」区切りで複数あり
 * @property int|null $credit_card_flag クレジットカードフラグ
 * @property int|null $tax_flag 消費税フラグ
 * @property string|null $asuraku_area あす楽エリア
 * @property string|null $asuraku_closing_time あす楽締め切り時間
 * @property int|null $asuraku_flag あす楽フラグ
 * @property int $availability 利用可能フラグ
 * @property string $affiliate_url アフィリエイトURL
 * @property string|null $medium_image_url_1 商品画像URL(中サイズ) 1
 * @property string|null $medium_image_url_2 商品画像URL(中サイズ) 2
 * @property string|null $medium_image_url_3 商品画像URL(中サイズ) 3
 * @property string|null $small_image_url_1 商品画像URL(小サイズ) 1
 * @property string|null $small_image_url_2 商品画像URL(小サイズ) 2
 * @property string|null $small_image_url_3 商品画像URL(小サイズ) 3
 * @property string|null $shop_url ショップURL
 * @property string|null $shop_affiliate_url ショップアフィリエイトURL
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereAffiliateRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereAffiliateUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereAsurakuArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereAsurakuClosingTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereAsurakuFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereAvailability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereCatchcopy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereCreditCardFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereGiftFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereImageFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereItemCaption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereItemName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereItemPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereItemPriceBaseField($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereItemPriceMax1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereItemPriceMax2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereItemPriceMax3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereItemPriceMin1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereItemPriceMin2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereItemPriceMin3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereItemUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereMediumImageUrl1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereMediumImageUrl2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereMediumImageUrl3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct wherePointRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct wherePointRateEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct wherePointRateStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct wherePostageFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereReviewAverage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereReviewCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereShipOverseasArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereShipOverseasFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereShopAffiliateUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereShopCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereShopName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereShopOfTheYearFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereShopUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereSmallImageUrl1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereSmallImageUrl2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereSmallImageUrl3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereTagIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereTaxFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TRakutenProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TRakutenProduct extends Model
{
    use HasFactory;
}
