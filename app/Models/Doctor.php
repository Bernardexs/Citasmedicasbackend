<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function user()
{
    return $this->belongsTo(User::class);
}

public function especialidad()
{
    return $this->belongsTo(Especialidad::class);
}

public function citas()
{
    return $this->hasMany(Cita::class);
}
}