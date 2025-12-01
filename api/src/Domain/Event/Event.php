<?php
namespace Src\Domain\Event;

use Illuminate\Database\Eloquent\Model;
use Src\Domain\Music\Music;

final class Event extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'uuid',
        'name',
        'start_at',
        'end_at'
    ];
    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $with = ['activities'];

    public function activities()
    {
        return $this->hasMany(EventActivity::class, 'event_id')
            ->orderBy('position');
    }

    public function scales()
    {
        return $this->hasMany(\Src\Domain\Scale\Scale::class, 'event_id');
    }
}
