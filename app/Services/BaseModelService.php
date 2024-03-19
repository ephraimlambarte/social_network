<?php

namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
abstract class BaseModelService
{
    public function store(array $props): Model {
        $model = $this->model();
        foreach ($props as $key => $value) {
            $model->{$key} = $value;
        }
        $model->save();
        return $model;
    }
    public function update(array $newProps, Model $model): Model {
        foreach ($newProps as $key => $value) {
            $model->{$key} = $value;
        }
        $model->save();
        return $model;
    }
    public function delete(Model $model): Model {
        $model->delete();
        return $model;
    }

    public function list(array $query): Collection {
        return $this->model()->all();
    }
    
    abstract public function model(): Model;
}
