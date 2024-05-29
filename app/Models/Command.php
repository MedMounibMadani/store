<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Command extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'status',
        'checkout',
        'delivery',
        'installation',
        'email',
        'phone',
        'first_name',
        'last_name',
        'address',
        'city',
        'zip_code',
        'country',
        'user_id'
    ];
    protected $table = 'commands';
    public function articles() {
        return $this->belongsToMany(Article::class, 'command_details', 'command_id', 'article_id')->withPivot('count');
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
