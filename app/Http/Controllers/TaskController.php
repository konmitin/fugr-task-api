<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;

class TaskController extends Controller
{
    #[
        OA\Get(
            path: '/tasks',
            description: '',
            summary: 'Список задач',
            tags: ['Task'],
            parameters: [
                new OA\Parameter(
                    parameter: 'page',
                    name: 'page',
                    description: 'Страница списка',
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(type: 'integer', example: 2)
                ),
                new OA\Parameter(
                    parameter: 'per_page',
                    name: 'per_page',
                    description: 'Кол-во задач на странице',
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(type: 'integer', example: 20)
                ),
                new OA\Parameter(
                    parameter: 'search',
                    name: 'search',
                    description: 'Строка поиска по названию задачи',
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(type: 'string', example: 'Задача для поиска')
                ),
                new OA\Parameter(
                    parameter: 'sort',
                    name: 'sort',
                    description: 'Поле для сортировки задачи',
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(type: 'string', enum: ['due_date'])
                ),
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'OK',
                    content: new OA\JsonContent(
                        type: 'array',
                        items: new OA\Items(ref: '#components/schemas/TaskResource'),
                    )
                )
            ]
        )
    ]
    public function index(TaskRequest $taskRequest): Response
    {

        $validatedData = $taskRequest->validated();

        $search =  $validatedData['search'] ?? null;
        $sort =  $validatedData['sort'] ?? null;
        $per_page =  $validatedData['per_page'] ?? 20;

        $tasks = Task::query();

        if (isset($sort)) {
            $tasks = $tasks->orderBy($sort);
        }

        if (isset($search)) {
            $tasks = $tasks->whereLike('title', "%$search%");
        }

        $tasks = TaskResource::collection($tasks->paginate($per_page));

        return response($tasks);
    }

    #[
        OA\Post(
            path: '/tasks',
            description: '',
            summary: 'Создание задачи',
            requestBody: new OA\RequestBody(
                required: true,
                content: new OA\JsonContent(
                    ref: '#components/schemas/StoreTaskRequest'
                )
            ),
            tags: ['Task'],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'OK',
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(
                                property: 'id',
                                description: 'Идентификатор задачи',
                                type: 'integer',
                                example: 1
                            ),
                            new OA\Property(
                                property: 'message',
                                description: 'Сообщение в ответ на запрос',
                                type: 'string',
                                example: 'Task created'
                            ),
                        ]
                    )
                ),
                new OA\Response(
                    response: 422,
                    description: 'Validation exception'
                )
            ]
        )

    ]
    public function store(StoreTaskRequest $storeRequest): Response
    {
        $data = $storeRequest->validated();

        $task = new Task;
        $task->fill($data);

        $task->save();

        return response([
            'id' => $task->id,
            'message' => 'Task created',
        ]);
    }
}
