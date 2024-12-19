<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function missions()
    {
        return $this->hasMany(Mission::class); // Tambahkan relasi dengan Mission
    }
}
