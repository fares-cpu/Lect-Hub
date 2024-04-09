<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Lecture;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'filepath',
        'filetype'
    ];

    /**
     * Get the lecture that owns the File
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lecture(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
