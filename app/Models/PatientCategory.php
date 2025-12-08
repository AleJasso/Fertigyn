<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientCategory extends Model
{
    protected $table = 'patient_categories';

    protected $fillable = ['name','code'];

    // GIN / OBS / FER (códigos del seeder)
    public const GINECOLOGIA = 'GIN';
    public const OBSTETRICIA = 'OBS';
    public const FERTILIDAD  = 'FER';

    public function patients()
    {
        return $this->hasMany(Patient::class, 'category_id');
    }
}
