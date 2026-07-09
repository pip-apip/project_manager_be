<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubSpektek extends Model
{
    protected $table = 'tp_9_sub_spekteks';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;

    protected $hidden = [
        'deleted_at'
    ];

    protected $fillable = [
        'spektek_id',
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
        'progress_updated_at',
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

    public function spektek(): BelongsTo
    {
        return $this->belongsTo(Spektek::class, 'spektek_id', 'id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    protected function arrivalPercentage(): Attribute
    {
        return Attribute::make(
            get: function () {

                if ($this->qty_total == 0) {
                    return 0;
                }

                return round(
                    ($this->qty_received / $this->qty_total) * 100,
                    2
                );
            }
        );
    }
}
