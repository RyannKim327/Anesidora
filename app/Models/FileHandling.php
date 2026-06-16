<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FileHandling extends Model
{
  /** @use HasFactory<\Database\Factories\FileHandlingFactory> */
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'file',
    'user_id',
    'private_url',
    'public_url',
    'description',
    'password',
    'expiration',
    'downloads',
  ];

  /**
   * Get the user that owns the file.
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
