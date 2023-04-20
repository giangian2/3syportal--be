<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Enums\OfferStatus;

class Offer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'offer_name',
        'budget',
        'requested_talents',
        'status',
        'long_description',
        'short_description',
        'closed_date',
        'client_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => OfferStatus::class,
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Offer::class,'client_id','id');
    }

    public function proposed_talents(): BelongsToMany
    {
        return $this->belongsToMany(Offer::class, 'offer_talents', 'offer_id', 'talent_id');
    }
}
