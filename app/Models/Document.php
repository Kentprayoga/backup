<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{    public $timestamps = false; // Menonaktifkan created_at & updated_at

    protected $fillable = [
        'user_id',
        'categorie_id',
        'template_id',
        'document_number',
        'name',
        'alasan',
        'file_path',
        'tanggal_pengajuan',
        'tanggal_diajukan',  // Tambahkan kolom tanggal_diajukan di sini
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function approval()
    {
        return $this->hasOne(Approval::class);
    }
    public function profile()
{
    return $this->belongsTo(Profile::class, 'user_id', 'user_id');
}
}