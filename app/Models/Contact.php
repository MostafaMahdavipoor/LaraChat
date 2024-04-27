<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $connection = "mysql";
    protected $table = 'contacts';
    protected $fillable = ['phone', 'name', 'deleted_at'];

    public static function insertContact($data)
    {
        $model = self::create($data);
        return $model;
    }

    public static function getContact()
    {
        $data = self::get();
        return $data;
    }

}
