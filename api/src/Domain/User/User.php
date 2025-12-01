<?php
namespace Src\Domain\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;
use Src\Domain\Grant\Grant;
use Src\Domain\User\UserStatus;


final class User extends Model
{
    protected $table = 'users';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'uuid', 'name', 'email', 'username', 'position', 'password_hash',
        'salt', 'active', 'roles', 'timezone' 
    ];

    protected $guarded = ['id', 'uuid']; //bloqueia atualização em massa

    protected $casts = [
        //'status' => UserStatus::class, // Converte string para enum
        'created_at' => 'datetime',    // Usa Carbon para manipulação de data
        'updated_at' => 'datetime',
    ];

    // protected $appends = ['grant_names'];

    // public function grants()
    // {
    //     return $this->belongsToMany(
    //         Grant::class,
    //         'user_grants',        // tabela pivô
    //         'user_id', // chave estrangeira do usuário
    //         'grant_id'  // chave estrangeira da Grant
    //     )->withTimestamps()
    //     ->withPivot('id');     // garante acesso ao ID da relação, se necessário
    // }    

    // public function getGrantNamesAttribute(): array
    // {
    //     return $this->grants()
    //         ->pluck('grant_id') // ou 'name' se você mudar o campo futuramente
    //         ->unique()
    //         ->toArray();
    // }    
}