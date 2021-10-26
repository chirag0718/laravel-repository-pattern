<?php
declare(strict_types=1);

namespace App\Repository;

use App\Models\Todo;
use Illuminate\Http\Request;


class TodoRepository extends BaseRepository implements TodoRepositoryInterface
{
    protected $model;

    public function __construct(Todo $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->select('id','task_name')->get();
    }

}
