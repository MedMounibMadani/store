<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Offer;
use Illuminate\Database\Eloquent\SoftDeletes;


class Message extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'messages';

    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'entreprise',
        'message',
        'seen',
        'offer_id'
    ];
    public function offer() {
        return $this->belongsTo(Offer::class, 'offer_id');
    }
}
