<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Message;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;
    protected $table = 'offers';

    protected $fillable = [
        'title',
        'status',
        'description',
    ];
    public function messages() {
        return $this->hasMany(Message::class);
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('OfferImages')
                ->useDisk('public');
    }
}
