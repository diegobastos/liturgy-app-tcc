<?php
namespace Src\Domain\Grant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;
use Src\Domain\User\User;


final class Grant extends Model
{
    protected $table = 'grants';

    protected $primaryKey = 'id';

    protected $keyType = 'string';
    
    public $incrementing = false;    

    public $timestamps = true;

    protected $fillable = ['id', 'module_id', 'description'];

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'user_grants',
            'grant_id',
            'user_id'
        )->withTimestamps()
        ->withPivot('id');
    }    
}