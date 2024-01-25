<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function convertToMo(int $bytes) : string {
        return round($this->size/1000**2, 2). 'Mo';
    }

    public function photo() {
        return $this->belongsTo(Photo::class);
    }
}
