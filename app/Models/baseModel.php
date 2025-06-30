<?php
// app/Models/BaseModel.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseModel extends Model
{
    // Permitimos fillable masivo para todos los campos (ojo en producción)
    protected $guarded = [];
    public $timestamps = false;
    /**
     * Crear un nuevo registro.
     *
     * @param  array  $attributes
     * @return static
     */
    public static function createRecord(array $attributes): static
    {
        return static::create($attributes);
    }

    /**
     * Obtener todos los registros.
     *
     * @param  array  $columns
     * @return Collection
     */
    public static function listRecords(array $columns = ['*']): Collection
    {
        return static::all($columns);
    }

    /**
     * Buscar uno por PK y lanzar excepción si no existe.
     *
     * @param  mixed  $id
     * @return static
     */
    public static function findRecordOrFail(mixed $id): static
    {
        return static::findOrFail($id);
    }

    /**
     * Actualizar este modelo con nuevos atributos.
     *
     * @param  array  $attributes
     * @return $this
     */
    public function updateRecord(array $attributes): static
    {
        $this->fill($attributes)->save();
        return $this;
    }

    /**
     * Eliminar este modelo.
     *
     * @return bool|null
     */
    public function deleteRecord(): ?bool
    {
        return $this->delete();
    }
}
