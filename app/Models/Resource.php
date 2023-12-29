<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed $id
 * @property mixed $user_id
 * @property mixed $parent_id
 * @property mixed $type
 * @property mixed $name
 */
class Resource extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'parent_id',
        'type',
        'name',
    ];

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->with(['children'])
            ->select(['id', 'name', 'type', 'parent_id'])
            ->orderBy('type');
    }

    public function markdown()
    {
        return $this->hasOne(Markdown::class);
    }

    protected static function boot()
    {
        parent::boot(); // TODO: 更改自动生成的存根

        self::deleting(function ($resource) {
            $resource->markdown->delete();
        });
    }
}
