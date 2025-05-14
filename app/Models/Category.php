<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $fillable = ['id','name'];


    public function templates()
    {
        return $this->hasMany(Template::class, 'categorie_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'categorie_id');
    }
}