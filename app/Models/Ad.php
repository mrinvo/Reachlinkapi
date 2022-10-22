<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    protected $table    = 'ads';

    protected $fillable = [
		'title',
		'description',
        'type',
        'category_id',
		'advertiser_id',
		'start_date',
	];
    protected $casts = [
        'start_date' => 'datetime:d/m/y',
    ];



    public function tags()
    {
        return $this->belongsToMany(Tag::class)->as('tags');
    }

    public function advertiser(){

        return $this->belongsTo(Advertiser::class);
    }

    public static function AdsInitiatedTommorow(){

        return Ad::where('start_date','=',Carbon::tomorrow())->get();
    }


}
