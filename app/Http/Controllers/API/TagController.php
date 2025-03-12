<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Tag\StoreTagRequest;
use App\Http\Requests\API\Tag\UpdateTagRequest;
use App\Http\Resources\TagCollection;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *      schema="Tag",
 *      required={"id", "name", "color", "user_id"},
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="name", type="string", example="Work"),
 *      @OA\Property(property="color", type="string", example="#3498db"),
 *      @OA\Property(property="user_id", type="integer", example=1),
 *      @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-15T12:00:00Z"),
 *      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-10-15T12:00:00Z")
 * )
 */
class TagController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Get(
     *      path="/api/tags",
     *      operationId="getTagsList",
     *      tags={"Tags"},
     *      summary="Get list of tags",
     *      description="Returns list of tags",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="search",
     *          in="query",
     *          description="Search tags by name",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          description="Number of tags per page",
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
     *              @OA\Property(property="message", type="string", example="Tags retrieved successfully"),
     **              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/Tag")
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
        $tags = $request->user()->tags();

        if ($request->has('search')) {
            $tags->where('name', 'like', "%{$request->search}%");
        }

        $perPage = $request->per_page ?? 15;
        $tagsPaginated = $tags->latest()->paginate($perPage);

        return $this->successResponse(
            $tagsPaginated,
            'Tags retrieved successfully'
        );
    }

    /**
     * @OA\Post(
     *      path="/api/tags",
     *      operationId="storeTag",
     *      tags={"Tags"},
     *      summary="Store new tag",
     *      description="Creates a new tag",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="Work"),
     *              @OA\Property(property="color", type="string", example="#3498db")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Tag created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Tag created successfully"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  ref="#/components/schemas/Tag"
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
     *                  example={"name": {"The name field is required."}}
     *              )
     *          )
     *      )
     * )
     */
    public function store(StoreTagRequest $request): JsonResponse
    {
        $tag = $request->user()->tags()->create($request->validated());

        return $this->createdResponse(
            new TagResource($tag),
            'Tag created successfully'
        );
    }

    /**
     * @OA\Get(
     *      path="/api/tags/{id}",
     *      operationId="getTagById",
     *      tags={"Tags"},
     *      summary="Get tag information",
     *      description="Returns tag data",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Tag ID",
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
     *              @OA\Property(property="message", type="string", example="Tag retrieved successfully"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  ref="#/components/schemas/Tag"
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
     *          description="Tag not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Tag not found")
     *          )
     *      )
     * )
     */
    public function show(Request $request, $id): JsonResponse
    {
        try {
            $tag = $request->user()->tags()->findOrFail($id);
            return $this->successResponse(
                new TagResource($tag),
                'Tag retrieved successfully'
            );
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse('Tag not found');
        }
    }

    /**
     * @OA\Put(
     *      path="/api/tags/{id}",
     *      operationId="updateTag",
     *      tags={"Tags"},
     *      summary="Update existing tag",
     *      description="Updates a tag and returns it",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Tag ID",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="Updated tag name"),
     *              @OA\Property(property="color", type="string", example="#e74c3c")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Tag updated successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Tag updated successfully"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  ref="#/components/schemas/Tag"
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
     *          description="Tag not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Tag not found")
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
     *                  example={"name": {"The name field is required."}}
     *              )
     *          )
     *      )
     * )
     */
    public function update(UpdateTagRequest $request, $id): JsonResponse
    {
        try {
            $tag = $request->user()->tags()->findOrFail($id);
            $tag->update($request->validated());

            return $this->successResponse(
                new TagResource($tag),
                'Tag updated successfully'
            );
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse('Tag not found');
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/tags/{id}",
     *      operationId="deleteTag",
     *      tags={"Tags"},
     *      summary="Delete existing tag",
     *      description="Deletes a tag and returns no content",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Tag ID",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Tag deleted successfully"
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
     *          description="Tag not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Tag not found")
     *          )
     *      ),
     *      @OA\Response(
     *          response=409,
     *          description="Tag is in use",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Cannot delete this tag because it is associated with one or more tasks")
     *          )
     *      )
     * )
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $tag = $request->user()->tags()->findOrFail($id);

            // Check if tag is associated with any tasks
            if ($tag->tasks()->count() > 0) {
                return $this->errorResponse(
                    'Cannot delete this tag because it is associated with one or more tasks',
                    409
                );
            }

            $tag->delete();

            return $this->noContentResponse();
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse('Tag not found');
        }
    }
}
