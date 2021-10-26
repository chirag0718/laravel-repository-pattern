<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function upserts(Request $request, int $id = null): bool
    {
        $todo = new $this->model;
        if ($id !== null) {
            $todo = $this->model::find($id);
        }
        $todo->task_name = $request->task_name;
        if ($todo->save()) {
            return true;
        }
        return false;
    }
}
