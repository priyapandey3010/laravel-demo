<?php declare(strict_types=1);

namespace App\Core;

use App\Traits\DataTable;

class BaseService 
{
    use DataTable;
    
    protected static $model;
    protected static $resource;

    public function create($payload)
    {
        return static::$model::create($payload);
    }

    public function update($payload, $id)
    {
        $model = static::$model::findOrFail($id);
        return $model->fill($payload)->save();
    }

    public function findById($id)
    {
        $model = static::$model::findOrFail($id);
        return static::$resource::make($model)->resolve();
    }

    public function destroy($id)
    {
        $model = static::$model::findOrFail($id);
        return $model->delete();
    }

    public function activate($id)
    {
        $model = static::$model::findOrFail($id);
        return $model->fill(['is_active' => true])->save();
    }

    public function deactivate($id)
    {
        $model = static::$model::findOrFail($id);
        return $model->fill(['is_active' => false])->save();
    }

    public function setPayload(&$payload, $key, $value)
    {
        $payload[$key] = $payload[$value];
    }
}