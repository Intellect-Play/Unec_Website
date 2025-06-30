<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Social;

class SocialNetwork extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon_path'];

    public function socials()
    {
        return $this->hasMany(Social::class);
    }
}
