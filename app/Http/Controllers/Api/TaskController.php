<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Services\TaskService;
use App\Http\Requests\TaskIndexRequest;
use App\Filters\TaskFilter;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\AddTaskCommentRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService) {}


    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="Получить список задач",
     *     description="Возвращает список задач с фильтрами по статусу, приоритету и пользователю. Сортировка по дате создания (новые первыми).",
     *     operationId="getTasks",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Фильтр по статусу задачи",
     *         required=false,
     *         @OA\Schema(type="string", enum={"new","in_progress","completed","cancelled"})
     *     ),
     *     @OA\Parameter(
     *         name="priority",
     *         in="query",
     *         description="Фильтр по приоритету задачи",
     *         required=false,
     *         @OA\Schema(type="string", enum={"high","normal","low"})
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Фильтр по ID пользователя",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список задач",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Task"))
     *     ),
     *     @OA\Response(response=400, description="Некорректный запрос")
     * )
     */

    public function index(TaskIndexRequest $request, TaskFilter $filter): JsonResponse
    {
        $tasks = $filter
            ->apply(Task::query(), $request->validated())
            ->latest()
            ->get();

        return response()->json($tasks);
    }


    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     summary="Создать новую задачу",
     *     description="Создание задачи с обязательным полем title. Если user_id не указан или пользователь не найден, назначаем менеджера. Статус по умолчанию = 'new', если priority = 'high' → status = 'in_progress'.",
     *     operationId="createTask",
     *     tags={"Tasks"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Fix login bug"),
     *             @OA\Property(property="description", type="string", example="Login fails on Safari"),
     *             @OA\Property(property="user_id", type="integer", example=2),
     *             @OA\Property(property="priority", type="string", enum={"high","normal","low"}, example="high")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Задача успешно создана",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask($request->validated());
        return response()->json($task, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}/status",
     *     summary="Изменить статус задачи",
     *     description="Изменение статуса задачи. При смене статуса на completed автоматически добавляется комментарий. Также запускается job уведомлений для всех менеджеров.",
     *     operationId="updateTaskStatus",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID задачи",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"new","in_progress","completed","cancelled"}, example="completed"),
     *             @OA\Property(property="user_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Статус задачи обновлён",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(response=404, description="Задача не найдена"),
     *     @OA\Response(response=422, description="Ошибка валидации")
     * )
     */


    public function updateStatus(UpdateTaskStatusRequest $request, int $id): JsonResponse
    {
        try {
            $task = $this->taskService->updateStatus($id, $request->validated());
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json($task, 200);
    }



    /**
     * @OA\Post(
     *     path="/api/tasks/{id}/comments",
     *     summary="Добавить комментарий к задаче",
     *     description="Добавление комментария к задаче. Поля: comment (обязательно), user_id (обязательно). Нельзя добавлять комментарии к cancelled задачам.",
     *     operationId="addComment",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID задачи",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"comment","user_id"},
     *             @OA\Property(property="comment", type="string", example="This is a comment"),
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Комментарий успешно создан"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Нельзя добавлять комментарии к cancelled задачам"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Задача не найдена"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации"
     *     )
     * )
     */


  
    public function addComment(AddTaskCommentRequest $request, $id): JsonResponse
    {
        try {
            $comment = $this->taskService->addComment($id, $request->validated());
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Task not found'], 404);
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400); 
        }

        return response()->json($comment->toArray(), 201); 
    }






    /**
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     summary="Получить задачу с комментариями",
     *     description="Возвращает задачу по ID вместе с комментариями и информацией о пользователях (name, position)",
     *     operationId="getTask",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID задачи",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Задача успешно получена"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Задача не найдена"
     *     )
     * )
     */

    public function show(int $id): JsonResponse
    {
        try {
            $task = $this->taskService->getTaskWithRelations($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json($task, 200);
    }

}
