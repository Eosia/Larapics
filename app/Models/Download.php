<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function photo() {
        return $this->belongTo(Photo::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

}
