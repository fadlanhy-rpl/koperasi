<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'key',
        'value',
        'type',
        'category',
        'description',
        'is_public',
        'is_encrypted',
        'updated_by',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_encrypted' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('app_settings');
            Cache::tags(['settings'])->flush();
        });

        static::deleted(function () {
            Cache::forget('app_settings');
            Cache::tags(['settings'])->flush();
        });
    }

    /**
     * Get the user who last updated this setting
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get history for this setting
     */

    /**
     * Scope for public settings
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope for specific category
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get the typed value attribute
     */
    public function getTypedValueAttribute()
    {
        if ($this->is_encrypted) {
            $value = decrypt($this->value);
        } else {
            $value = $this->value;
        }

        return match ($this->type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'float' => (float) $value,
            'array', 'json' => json_decode($value, true),
            default => $value,
        };
    }
}
