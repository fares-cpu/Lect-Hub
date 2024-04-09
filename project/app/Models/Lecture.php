<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\File;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lecture extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'course',
        'faculty', 
        'university',
        'type',
        'info',
        'uuid'
    ];

    /**
     * Get the user that owns the Lecture
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the File  associated with the Lecture
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function File (): HasOne
    {
        return $this->hasOne(File::class);
    }
}
