<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $user_name
 * @property string $last_search_word
 * @property float $last_latitude
 * @property float $last_longitude
 * @property int $last_search_radius
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @mixin Eloquent
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name',
        'last_search_word',
        'last_latitude',
        'last_longitude',
        'last_search_radius',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * @param User $user
     * @param array $data
     * @return void
     */
    public static function updateUserLocation(User $user, array $data): void
    {
        $user->update([
            'last_latitude'  => $data['latitude'],
            'last_longitude' => $data['longitude']
        ]);
    }

    /**
     * @param string $nickName
     * @return mixed
     */
    public static function getUser(string $nickName): User
    {
        return self::firstOrCreate(['user_name' => $nickName]);
    }

    /**
     * @param User $user
     * @param string $searchWord
     * @return bool
     */
    public static function setLastSearchWord(User $user, string $searchWord)
    {
        return $user->update(['last_search_word' => $searchWord]);
    }

    /**
     * @param User $user
     * @param int $radius
     * @return bool
     */
    public static function setLastSearchRadius(User $user, int $radius)
    {
        return $user->update(['last_search_radius' => $radius]);
    }
}
