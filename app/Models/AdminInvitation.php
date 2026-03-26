<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminInvitation extends Model
{
    protected $fillable = ['email', 'ic', 'token', 'used'];

    protected function casts(): array
    {
        return ['used' => 'boolean'];
    }
}
