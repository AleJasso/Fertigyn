<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Casts\AsEncryptedArrayObject;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name','last_name','birth_date','sex','phone','email','address',
        'category_id','allergies','medical_history','gyneco_obst_history',
    ];

    /** Campos sensibles no deben exponerse en JSON/array */
    protected $hidden = [
        'allergies','medical_history','gyneco_obst_history',
    ];

    /** Cargar categoría por defecto en listados */
    protected $with = ['category'];

    /** Atributos calculados */
    protected $appends = ['full_name','age'];

    /** Paginación por defecto */
    protected $perPage = 15;

    /** Casts (incluye cifrado en reposo) */
    protected $casts = [
        'birth_date'            => 'date',
        'created_at'            => 'datetime',
        'updated_at'            => 'datetime',

        // Si guardas estos campos como JSON/array:
        'allergies'             => AsEncryptedArrayObject::class,
        'medical_history'       => AsEncryptedArrayObject::class,
        'gyneco_obst_history'   => AsEncryptedArrayObject::class,

        // >>> Alternativa si los guardas como texto plano:
        // 'allergies'           => 'encrypted',
        // 'medical_history'     => 'encrypted',
        // 'gyneco_obst_history' => 'encrypted',
    ];

    /* =====================  Relaciones  ===================== */

    public function category()
    {
        return $this->belongsTo(PatientCategory::class, 'category_id');
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'patient_id');
    }

    public function latestConsultation()
    {
        return $this->hasOne(Consultation::class, 'patient_id')->latestOfMany();
    }

    public function vitals()
    {
        return $this->hasManyThrough(
            Vital::class,
            Consultation::class,
            'patient_id',
            'consultation_id',
            'id',
            'id'
        );
    }

    public function files()
    {
        return $this->hasMany(File::class, 'patient_id');
    }

    /* =====================  Accessors  ===================== */

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date ? Carbon::parse($this->birth_date)->age : null;
    }

    /* =====================  Mutators (limpieza)  ===================== */

    public function setFirstNameAttribute($value): void
    {
        $this->attributes['first_name'] = trim(mb_convert_case($value, MB_CASE_TITLE, 'UTF-8'));
    }

    public function setLastNameAttribute($value): void
    {
        $this->attributes['last_name'] = trim(mb_convert_case($value, MB_CASE_TITLE, 'UTF-8'));
    }

    public function setPhoneAttribute($value): void
    {
        $this->attributes['phone'] = $value ? preg_replace('/\D+/', '', $value) : null;
    }

    /* =====================  Scopes  ===================== */

    /** Búsqueda rápida por nombre, apellido, correo o teléfono (no incluye cifrados) */
    public function scopeQuickSearch($q, ?string $term)
    {
        $term = trim((string)$term);
        if ($term === '') return $q;

        return $q->where(function ($qq) use ($term) {
            $qq->where('first_name', 'like', "%{$term}%")
               ->orWhere('last_name',  'like', "%{$term}%")
               ->orWhere('email',      'like', "%{$term}%")
               ->orWhere('phone',      'like', "%".preg_replace('/\D+/', '', $term)."%");
        });
    }

    public function scopeCategoryId($q, $categoryId)
    {
        return $q->where('category_id', $categoryId);
    }

    public function scopeForListing($q)
    {
        return $q->select('id','first_name','last_name','email','phone','category_id','birth_date')
                 ->orderBy('last_name')->orderBy('first_name');
    }
}
