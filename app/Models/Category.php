<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Model;



class Category extends Model

{

    protected $dates = ['deleted_at'];



    protected $fillable = [

        'code', 'name', 'type', 'parent_id', 'lavel'

    ];
    public function parent()
    {
        return $this->belongsTo(Category::class);
    }

    public function childs()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }


}

