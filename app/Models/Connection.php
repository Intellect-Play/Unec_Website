<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Connection extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'connected_user_id', 'status'];

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }

    // public function connectedUser()
    // {
    //     return $this->belongsTo(User::class, 'connected_user_id');
    // }


    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'connected_user_id');
    }
}
