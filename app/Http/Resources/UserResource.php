<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      schema="User",
 *      required={"id", "name", "email", "email_verified_at", "created_at", "updated_at"},
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="name", type="string", example="John Doe"),
 *      @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *      @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, example="2023-10-15T12:00:00Z"),
 *      @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-15T12:00:00Z"),
 *      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-10-15T12:00:00Z")
 * )
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
