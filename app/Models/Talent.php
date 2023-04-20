<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Talent extends Model
{
    use HasFactory;

    protected $table ='talents';

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'birth_date',
        'mediaKit_src',
        'email',
        'phone'
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

    ];

    public function verticalities(): BelongsToMany
    {
        return $this->belongsToMany(Verticality::class, 'talent_verticalities', 'talent_id', 'verticality_id');
    }

    public function proposed_offers(): BelongsToMany
    {
        return $this->belongsToMany(Offer::class, 'offer_talents', 'talent_id', 'offer_id');
    }

    public function social_infos(): HasMany
    {
        return $this->hasMany(SocialInfo::class, 'talent_id', 'id');
    }
}
