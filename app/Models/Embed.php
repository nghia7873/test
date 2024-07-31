<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Embed extends Model
{
   protected $table = 'embed';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'embed_id',
        'embed_url'
    ];
    public $timestamps = false;

}
