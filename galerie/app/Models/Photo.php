<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
    ];
    public function convertToMo(int $bytes): string
    {
        //Convertion de Bytes en Mo
        return round($this->size / 1000 ** 2, 1). 'Mo';
    }
}
