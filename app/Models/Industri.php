<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industri extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_perusahaan', 'alamat', 'bidang_usaha', 'contact_person', 'telepon', 'email',
    ];

    public function pkls()
    {
        return $this->hasMany(Pkl::class);
    }

    public function kerjasamas()
    {
        return $this->hasMany(Kerjasama::class);
    }
}
