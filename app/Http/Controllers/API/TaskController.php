<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Task\StoreTaskRequest;
use App\Http\Requests\API\Task\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *      schema="Task",
 *      required={"id", "title", "status", "priority", "user_id"},
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="title", type="string", example="Complete project documentation"),
 *      @OA\Property(property="description", type="string", nullable=true, example="Need to finish the API documentation for the project"),
 *      @OA\Property(property="status", type="string", enum={"pending", "in_progress", "completed"}, example="pending"),
 *      @OA\Property(property="due_date", type="string", format="date", nullable=true, example="2023-10-30"),
 *      @OA\Property(property="priority", type="string", enum={"low", "medium", "high"}, example="medium"),
 *      @OA\Property(property="user_id", type="integer", example=1),
 *      @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-15T12:00:00Z"),
 *      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-10-15T12:00:00Z"),
 *      @OA\Property(
 *          property="tags",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/Tag")
 *      )
 * )
 */
class TaskController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Get(
     *      path="/api/tasks",
     *      operationId="getTasksList",
     *      tags={"Tasks"},
     *      summary="Get list of tasks",
     *      description="Returns list of tasks",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="status",
     *          in="query",
     *          description="Filter tasks by status",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              enum={"pending", "in_progress", "completed"}
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="priority",
     *          in="query",
     *          description="Filter tasks by priority",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              enum={"low", "medium", "high"}
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="tag_id",
     *          in="query",
     *          description="Filter tasks by tag ID",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="due_date",
     *          in="query",
     *          description="Filter tasks by due date (YYYY-MM-DD)",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              format="date"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="overdue",
     *          in="query",
     *          description="Filter overdue tasks (1 for overdue, 0 for not)",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *              enum={0, 1}
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="search",
     *          in="query",
     *          description="Search tasks by title or description",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          description="Number of tasks per page",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *              default=15
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Tasks retrieved successfully"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/Task")
     *              ),
     *              @OA\Property(
     *                  property="pagination",
     *                  type="object",
     *                  @OA\Property(property="total", type="integer", example=30),
     *                  @OA\Property(property="per_page", type="integer", example=15),
     *                  @OA\Property(property="current_page", type="integer", example=1),
     *                  @OA\Property(property="last_page", type="integer", example=2),
     *                  @OA\Property(property="from", type="integer", example=1),
     *                  @OA\Property(property="to", type="integer", example=15)
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Unauthenticated")
     *          )
     *      )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $tasks = $request->user()->tasks()->with('tags');

        // Apply filters
        if ($request->has('status')) {
            $tasks->status($request->status);
        }

        if ($request->has('priority')) {
            $tasks->priority($request->priority);
        }

        if ($request->has('tag_id')) {
            $tasks->whereHas('tags', function ($query) use ($request) {
                $query->where('tags.id', $request->tag_id);
            });
        }

        if ($request->has('due_date')) {
            $tasks->whereDate('due_date', $request->due_date);
        }

        if ($request->has('overdue') && $request->overdue) {
            $tasks->overdue();
        }

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $tasks->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        $perPage = $request->per_page ?? 15;
        $tasksPaginated = $tasks->latest()->paginate($perPage);

        return $this->successResponse(
            new TaskCollection($tasksPaginated),
            'Tasks retrieved successfully'
        );
    }

    /**
     * @OA\Post(
     *      path="/api/tasks",
     *      operationId="storeTask",
     *      tags={"Tasks"},
     *      summary="Store new task",
     *      description="Creates a new task",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"title"},
     *              @OA\Property(property="title", type="string", example="Complete project documentation"),
     *              @OA\Property(property="description", type="string", nullable=true, example="Need to finish the API documentation for the project"),
     *              @OA\Property(property="status", type="string", enum={"pending", "in_progress", "completed"}, example="pending"),
     *              @OA\Property(property="due_date", type="string", format="date", nullable=true, example="2023-10-30"),
     *              @OA\Property(property="priority", type="string", enum={"low", "medium", "high"}, example="medium"),
     *              @OA\Property(property="tag_ids", type="array", @OA\Items(type="integer"), example={1,2})
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Task created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Task created successfully"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  ref="#/components/schemas/Task"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Unauthenticated")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Validation errors"),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  example={"title": {"The title field is required."}}
     *              )
     *          )
     *      )
     * )
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $request->user()->tasks()->create($request->validated());

        if ($request->has('tag_ids')) {
            $task->tags()->attach($request->tag_ids);
        }

        $task->load('tags');

        return $this->createdResponse(
            new TaskResource($task),
            'Task created successfully'
        );
    }

    /**
     * @OA\Get(
     *      path="/api/tasks/{id}",
     *      operationId="getTaskById",
     *      tags={"Tasks"},
     *      summary="Get task information",
     *      description="Returns task data",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Task ID",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Task retrieved successfully"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  ref="#/components/schemas/Task"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Unauthenticated")
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Forbidden")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Task not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Task not found")
     *          )
     *      )
     * )
     */
    public function show(Request $request, $id): JsonResponse
    {
        $task = $request->user()->tasks()->with('tags')->findOrFail($id);

        return $this->successResponse(
            new TaskResource($task),
            'Task retrieved successfully'
        );
    }

    /**
     * @OA\Put(
     *      path="/api/tasks/{id}",
     *      operationId="updateTask",
     *      tags={"Tasks"},
     *      summary="Update existing task",
     *      description="Updates a task and returns it",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Task ID",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="title", type="string", example="Updated task title"),
     *              @OA\Property(property="description", type="string", nullable=true, example="Updated task description"),
     *              @OA\Property(property="status", type="string", enum={"pending", "in_progress", "completed"}, example="in_progress"),
     *              @OA\Property(property="due_date", type="string", format="date", nullable=true, example="2023-11-15"),
     *              @OA\Property(property="priority", type="string", enum={"low", "medium", "high"}, example="high"),
     *              @OA\Property(property="tag_ids", type="array", @OA\Items(type="integer"), example={1,3})
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Task updated successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Task updated successfully"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  ref="#/components/schemas/Task"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Unauthenticated")
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Forbidden")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Task not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Task not found")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Validation errors"),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  example={"title": {"The title field is required."}}
     *              )
     *          )
     *      )
     * )
     */
    public function update(UpdateTaskRequest $request, $id): JsonResponse
    {
        $task = $request->user()->tasks()->findOrFail($id);
        $task->update($request->validated());

        if ($request->has('tag_ids')) {
            $task->tags()->sync($request->tag_ids);
        }

        $task->load('tags');

        return $this->successResponse(
            new TaskResource($task),
            'Task updated successfully'
        );
    }

    /**
     * @OA\Delete(
     *      path="/api/tasks/{id}",
     *      operationId="deleteTask",
     *      tags={"Tasks"},
     *      summary="Delete existing task",
     *      description="Deletes a task and returns no content",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Task ID",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Task deleted successfully"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Unauthenticated")
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Forbidden")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Task not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Task not found")
     *          )
     *      )
     * )
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $task = $request->user()->tasks()->findOrFail($id);
        $task->tags()->detach();
        $task->delete();

        return $this->noContentResponse();
    }
}
