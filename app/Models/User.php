<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\User
 *
 * @property string $id
 * @property string $login
 * @property float $balance
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Payment $user
 * @method static Builder|User query()
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereLogin($value)
 * @method static Builder|User whereBalance($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User create(array $attributes = [])
 * @method static Builder|User find($id, $columns = ['*'])
 * @method static Builder|User findOrFail($id, $columns = ['*'])
 * @method static Builder|User first($columns = ['*'])
 * @method static Builder|User firstOrCreate(array $attributes, array $values = [])
 * @method static Paginator paginate(int $perPage = 15, array $columns = ['*'], string $pageName = 'page', int $page = 1)
 */
class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'login',
        'password',
        'balance',
    ];

    protected $hidden = [
        'password',
    ];


    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'login', 'login');
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
