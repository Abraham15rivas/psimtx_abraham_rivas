<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    use HasFactory;
    
    protected $table = 'interests';

    protected $fillable = [
        'name', 'description'
    ];

    static public function getRelatedUsers($my_interests)
    {
        return ['jose', 'abraham'];
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
