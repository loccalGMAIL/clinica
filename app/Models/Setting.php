<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'group', 'value'];

    // Scope para settings del centro
    public function scopeCenter($query)
    {
        return $query->where('group', 'center');
    }
}
