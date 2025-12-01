<?php
namespace Src\Domain\Music;

use Illuminate\Database\Eloquent\Model;
use Src\Domain\User\User;

final class Music extends Model
{
    protected $table = 'musics';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'uuid',
        'name',
        'author',
        'tone',
        'time_signature',
        'lyrics',
        'music_score'
    ];
    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        //'is_published' => 'boolean',
    ];

    /**
     * Relação com o usuário que cadastrou a música
     */
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

}
