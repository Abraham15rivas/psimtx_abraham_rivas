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

    static public function getRelatedUsers($user)
    {
        $users_interests = collect();
        $my_interests = $user->interests->map(function($interest) {
            return $interest->id;
        });
        $users = User::where('id', '!=', $user->id)
                        ->where('id', '!=', 1)
                        ->get()
                        ->load('interests');
        if($users->count() > 0) {
            foreach($users as $user) {
                $interests_two = [];
                if($user->interests != '') {
                    if($user->interests->count() > 1) {
                        foreach($my_interests as $interest) {
                            $selected = $user->interests->where('id', $interest)->first();
                            if(!empty($selected)) {
                                array_push($interests_two, 1);
                            }
                            if(count($interests_two) >= 2) {
                                $users_interests->push($user);
                                break;
                            }                            
                        }
                    }
                }
            }
        }
        return $users_interests->toJson();
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
