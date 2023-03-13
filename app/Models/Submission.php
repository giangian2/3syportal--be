<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\User;
use App\Enums\SubmissionStatus;

class Submission extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'document_type',
        'status',
        'notes',
        'document_name',
        'document_path',
        'to_user',
        'last_update_from',
        'from_user'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'docuemnt_uploaded_at' => 'datetime',
        'status' => SubmissionStatus::class
    ];

    public function from_user()
    {
        $this->hasOne('user', 'from_user');
    }

    public function to_user()
    {
        $this->hasOne('user', 'to_user');
    }

}
