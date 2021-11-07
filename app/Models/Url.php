<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Url
 *
 * @property string $id
 * @property string $url
 * @method static Builder|Url newModelQuery()
 * @method static Builder|Url newQuery()
 * @method static Builder|Url query()
 * @method static Builder|Url whereId($value)
 * @method static Builder|Url whereUrl($value)
 * @mixin Eloquent
 */
class Url extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'url'];
    protected $keyType = 'string';

    public $timestamps = false;
}
