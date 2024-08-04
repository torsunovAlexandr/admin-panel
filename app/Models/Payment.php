<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Payment
 *
 * @property string $id
 * @property string $login
 * @property string $details
 * @property float $amount
 * @property string $currency
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @method static Builder|Payment query()
 * @method static Builder|Payment whereId($value)
 * @method static Builder|Payment whereLogin($value)
 * @method static Builder|Payment whereDetails($value)
 * @method static Builder|Payment whereAmount($value)
 * @method static Builder|Payment whereCurrency($value)
 * @method static Builder|Payment whereStatus($value)
 * @method static Builder|Payment whereCreatedAt($value)
 * @method static Builder|Payment whereUpdatedAt($value)
 * @method static Builder|Payment create(array $attributes = [])
 * @method static Builder|Payment find($id, $columns = ['*'])
 * @method static Builder|Payment findOrFail($id, $columns = ['*'])
 * @method static Builder|Payment first($columns = ['*'])
 * @method static Builder|Payment firstOrCreate(array $attributes, array $values = [])
 */
class Payment extends Model
{
    use HasFactory;

    public $incrementing = false; // Указываем, что 'id' не является автоинкрементным

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'login',
        'details',
        'amount',
        'currency',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'login', 'login');
    }
}
