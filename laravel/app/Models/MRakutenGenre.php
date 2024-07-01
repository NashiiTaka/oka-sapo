<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $genre_id 楽天ジャンルID
 * @property string $genre_name 楽天ジャンル名
 * @property int $genre_level ジャンル階層、1:トップカテゴリ、以下、最大5層まで。
 * @property int|null $parent_genre_id 親ジャンルID。トップカテゴリの場合はNULL。
 * @method static \Illuminate\Database\Eloquent\Builder|MRakutenGenre whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MRakutenGenre whereGenreLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MRakutenGenre whereGenreName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MRakutenGenre whereParentGenreId($value)
 * @mixin \Eloquent
 */
class MRakutenGenre extends Model
{
    use HasFactory;
}
