<?php
namespace Src\Domain\Scale;

use Illuminate\Database\Eloquent\Model;
use Src\Domain\User\User;

final class ScaleMember extends Model
{
    protected $table = 'scale_members';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'uuid',
        'scale_id',
        'user_id',
        'role',
        'status'
    ];

    protected $hidden = ['scale_id', 'user_id'];    

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $with = ['user'];

    public function scale()
    {
        return $this->belongsTo(Scale::class, 'scale_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->select(['id', 'name']);
    }
}
