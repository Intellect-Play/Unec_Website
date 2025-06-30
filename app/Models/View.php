<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class View extends Model
{
    use HasFactory;

    protected $fillable = ['viewer_id', 'viewable_type', 'viewable_id'];

    public function viewer()
    {
        return $this->belongsTo(User::class, 'viewer_id');
    }

    public function viewable()
    {
        return $this->morphTo();
    }
}
