<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Article extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasSlug, SoftDeletes;
    protected $table = 'articles';

    protected $fillable = [
        'name',
        'description',
        'price',
        'count',
        'discount',
        'installation_fees',
        'delivery_fees',
        'days_to_delivery',
        'category_id'
    ];
    public function priceWithDiscount() {
        return number_format( round( $this->price * (1 - $this->discount / 100) ) , 2, ',', ' ' );
    }
    public function priceTtc() {
        return number_format( round( $this->price * 1.2 ) , 2, ',', ' ' );
    }
    public function priceTtcWithDiscount() {
        return number_format( round( $this->price * 1.2 * (1 - $this->discount / 100) ) , 2, ',', ' ' );
    }
    public function commands() {
        return $this->belongsToMany(Command::class, 'command_details', 'article_id', 'command_id')->withPivot('count');
    }
    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('ArticleImages')
                ->useDisk('public');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(368)
              ->height(232)
              ->sharpen(10);
              
        $this->addMediaConversion('extra_thumb')
              ->fit(Manipulations::FIT_CROP, 100, 100);
    }
}
