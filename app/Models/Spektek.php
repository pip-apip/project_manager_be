<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Spektek extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tp_8_spekteks';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;

    protected $hidden = [
        'deleted_at'
    ];

    protected $fillable = [
        'project_id',
        'name',
        'type',
        'qty_total',
        'qty_received',
        'qty_nominal',
        'total_nominal',
        'progress_percentage',
        'detail',
        'note',
        'qty_updated_at',
        'progress_updated_at'
    ];

    protected $casts = [
        'qty_total' => 'integer',
        'qty_received' => 'integer',
        'qty_nominal' => 'integer',

        'total_nominal' => 'decimal:2',
        'progress_percentage' => 'decimal:2',

        'qty_updated_at' => 'datetime',
        'progress_updated_at' => 'datetime',
    ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::deleting(function ($spektek) {
    //         // Add any additional logic if needed when deleting a spektek
    //     });
    // }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function subSpekteks(): HasMany
    {
        return $this->hasMany(SubSpektek::class, 'spektek_id', 'id');
    }

    // protected function arrivalPercentage(): Attribute
    // {
    //     return Attribute::make(
    //         get: function () {

    //             if ($this->qty_total == 0) {
    //                 return 0;
    //             }

    //             return round(
    //                 ($this->qty_received / $this->qty_total) * 100,
    //                 2
    //             );
    //         }
    //     );
    // }
}
