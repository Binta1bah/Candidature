<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
        'duree',
        'cloturee',
        'description'
    ];

    public function candidatures()
    {
        return $this->belongsToMany(Candidature::class);
    }
}
