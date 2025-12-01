<?php
namespace Src\Domain\Scale;

use Illuminate\Database\Eloquent\Model;
use Src\Domain\Event\Event;

final class Scale extends Model
{
    protected $table = 'scales';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'uuid',
        'start_at',
        'end_at',
        'event_id',
        'scale_type_id',
        'notes'
    ];

    protected $hidden = ['event_id', 'scale_type_id'];

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'start_at'   => 'datetime',
        'end_at'     => 'datetime'
    ];

    protected $with = ['type', 'event', 'members'];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function members()
    {
        return $this->hasMany(ScaleMember::class, 'scale_id');
    }
    
    public function type()
    {
        return $this->belongsTo(ScaleType::class, 'scale_type_id')
            ->select(['id', 'name', 'slug']);
    }
}    
