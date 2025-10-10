<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes; // <-- Tambahkan ini

class File extends Model
{
    use HasFactory, SoftDeletes; // <-- Tambahkan ini

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'folder_id',
        'name',
        'path',
        'size',
        'mime_type',
        'is_starred',
    ];

    /**
     * Mendefinisikan relasi bahwa setiap file dimiliki oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }
}