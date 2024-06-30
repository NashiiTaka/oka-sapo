<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $brand_id 取得元のブランドID
 * @property string $brand_name ブランド名
 * @property int $maker_id メーカーID
 * @property string|null $official_site 公式サイトURL
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TBrand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TBrand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TBrand query()
 * @method static \Illuminate\Database\Eloquent\Builder|TBrand whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TBrand whereBrandName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TBrand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TBrand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TBrand whereMakerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TBrand whereOfficialSite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TBrand whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TBrand extends Model
{
    use HasFactory;
}
