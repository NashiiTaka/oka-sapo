<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $category_id 取得元のカテゴリID
 * @property string $category_name カテゴリ名
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MCategories newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MCategories newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MCategories query()
 * @method static \Illuminate\Database\Eloquent\Builder|MCategories whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MCategories whereCategoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MCategories whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MCategories whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MCategories whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MCategorie extends Model
{
    use HasFactory;
}
