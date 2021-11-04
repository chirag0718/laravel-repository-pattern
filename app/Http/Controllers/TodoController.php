<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Repository\TodoRepository;
use App\Repository\TodoRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TodoController extends Controller
{
    private TodoRepository $todoRepo;

    public function __construct(TodoRepositoryInterface $todoRepository)
    {
        $this->todoRepo = $todoRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Application|Factory|View
     */
    public function index()
    {
        $todos = $this->todoRepo->getAll();
        return view("todo.index", compact(['todos', $todos]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse|void
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'task_name' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        try {
            $this->todoRepo->upserts($request);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'page' => '/todo']);
        }
        return response()->json(['message' => 'New Task is added', 'page' => '/todo']);
    }

    /**
     * Display the specified resource.
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $todo = $this->todoRepo->findById((int) $id);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'page' => '/todo'],Response::HTTP_NOT_FOUND);
        }

        return response()->json(['html' => $todo->task_name], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Todo $todo
     * @return void
     */
    public function edit(Todo $todo)
    {
        //
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'task_name' => ['required', 'string'],
            'edit_id' => ['integer', 'required']
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }


        try {
            $this->todoRepo->upserts($request, (int)$request->get('edit_id'));
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'page' => '/todo']);
        }
        return response()->json(['message' => 'Task is updated', 'page' => '/todo']);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            $todo = Todo::findOrFail((int)$request->only('delete_id'));
            $todo->delete();
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'page' => '/todo']);
        }
        return response()->json(['message' => 'Task is deleted', 'page' => '/todo']);
    }
}
