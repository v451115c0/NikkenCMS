<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    use HasFactory;
    protected $connection = 'incorporacion';
    protected $table = 'signupfiles';
    protected $guarded = []; 
    public $timestamps = false;
    // protected $primaryKey = "id_contract";
}
