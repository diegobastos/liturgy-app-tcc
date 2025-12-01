<?php
namespace Src\Domain\Scale;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class ScaleType extends Model
{
    protected $table = 'scale_types';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'slug'
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento: um tipo de escala pode estar associado a várias escalas.
     */
    public function scales(): HasMany
    {
        return $this->hasMany(Scale::class, 'scale_type_id');
    }
}
