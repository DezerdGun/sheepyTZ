<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskComment;
use App\Jobs\SendTaskNotificationJob;

class TaskController extends Controller
{
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
    public function index(Request $request)
    {
        $query = Task::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $tasks = $query->orderBy('created_at', 'desc')->get();

        return response()->json($tasks, 200);
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
  public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:100',
            'description' => 'nullable|string',
            'user_id' => 'nullable|integer|exists:users,id',
            'priority' => 'nullable|string|in:high,normal,low',
        ]);

        $user = null;
        if (!empty($validated['user_id'])) {
            $user = User::find($validated['user_id']);
        }
        if (!$user) {
            $user = User::where('position', 'manager')->first();
        }

        $status = ($validated['priority'] ?? 'normal') === 'high' ? 'in_progress' : 'new';

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'user_id' => $user->id,
            'priority' => $validated['priority'] ?? 'normal',
            'status' => $status,
        ]);

        if ($task->priority === 'high') {
            SendTaskNotificationJob::dispatch($task->id, 'task_assigned');
        }

        return response()->json($task, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}/status",
     *     summary="Изменить статус задачи",
     *     description="Изменение статуса задачи. При смене статуса на completed автоматически добавляется комментарий. Также запускается job уведомлений.",
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
  public function updateStatus(Request $request, $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $validated = $request->validate([
            'status' => 'required|string|in:new,in_progress,completed,cancelled',
            'user_id' => 'nullable|integer|exists:users,id',
        ]);

        $task->status = $validated['status'];
        $task->save();

        if ($validated['status'] === 'completed') {
            $user = $validated['user_id'] ? User::find($validated['user_id']) : null;
            $userName = $user ? $user->name : 'Unknown';

            TaskComment::create([
                'task_id' => $task->id,
                'user_id' => $user ? $user->id : $task->user_id,
                'comment' => "Task completed by {$userName}",
            ]);
        }

        SendTaskNotificationJob::dispatch($task->id, 'status_changed');

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
   public function addComment(Request $request, $id)
    {
        $validated = $request->validate([
            'comment' => 'required|string|min:3',
            'user_id' => 'required|exists:users,id',
        ]);

        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        if ($task->status === 'cancelled') {
            return response()->json(['message' => 'Cannot add comment to a cancelled task'], 422);
        }

        $comment = TaskComment::create([
            'task_id' => $task->id,
            'user_id' => $validated['user_id'],
            'comment' => $validated['comment'],
        ]);

        return response()->json($comment, 201);
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
    public function show($id)
    {
        $task = Task::with([
            'user:id,name,position',
            'comments.user:id,name,position'
        ])->find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json($task, 200);
    }
}
