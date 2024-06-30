<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $maker_id 取得元のメーカーID
 * @property string $maker_name メーカー名
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TMaker newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TMaker newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TMaker query()
 * @method static \Illuminate\Database\Eloquent\Builder|TMaker whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TMaker whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TMaker whereMakerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TMaker whereMakerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TMaker whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TMaker extends Model
{
    use HasFactory;
}
