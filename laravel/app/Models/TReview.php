<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $review_id 取得元のレビューID
 * @property int|null $reviewer_id レビュアーID
 * @property string $reviewer_name レビュアー名
 * @property int|null $followers_level フォロワー数のレベル 〜名以上という意味
 * @property int $reviews_count レビューアーの総レビュー数
 * @property int $reviewer_age レビュアーの年齢
 * @property string $reviewer_skin_type レビュアーの肌質
 * @property int $product_id 商品ID
 * @property string|null $method_of_acquisition 入手方法
 * @property float|null $rating レビュー点数
 * @property string|null $effects 効果、、「|」区切りで複数登録
 * @property string $review_content レビュー内容
 * @property string $published_at レビュー投稿日時
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TReview query()
 * @method static \Illuminate\Database\Eloquent\Builder|TReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TReview whereEffects($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TReview whereFollowersLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TReview whereMethodOfAcquisition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TReview whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TReview wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TReview whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TReview whereReviewContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TReview whereReviewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TReview whereReviewerAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TReview whereReviewerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TReview whereReviewerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TReview whereReviewerSkinType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TReview whereReviewsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TReview whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TReview extends Model
{
    use HasFactory;
}
