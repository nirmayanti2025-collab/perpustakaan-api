<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = ['nama_kategori'];

    // Relasi: 1 kategori punya banyak buku
    public function buku()
    {
        return $this->hasMany(Buku::class);
    }
}
