<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $thread_id ChatGPTのスレッドID
 * @property string $role 送信者の役割、user、botなどが入る
 * @property string $message メッセージ
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TChat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TChat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TChat query()
 * @method static \Illuminate\Database\Eloquent\Builder|TChat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TChat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TChat whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TChat whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TChat whereThreadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TChat whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TChat extends Model
{
    use HasFactory;
}
