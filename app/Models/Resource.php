<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use SoftDeletes;

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->with(['children'])
            ->select(['id', 'name', 'type', 'parent_id'])
            ->orderBy('type');
    }
}
