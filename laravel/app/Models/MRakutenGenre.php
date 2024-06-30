<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name 楽天ジャンル名
 * @property int $depth ジャンル階層、1:トップカテゴリ、以下、最大5層まで。
 * @property int|null $parent_id 親ジャンルID。トップカテゴリの場合はNULL。
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MRakutenGenre newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MRakutenGenre newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MRakutenGenre query()
 * @method static \Illuminate\Database\Eloquent\Builder|MRakutenGenre whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MRakutenGenre whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MRakutenGenre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MRakutenGenre whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MRakutenGenre whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MRakutenGenre whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MRakutenGenre extends Model
{
    use HasFactory;
}
