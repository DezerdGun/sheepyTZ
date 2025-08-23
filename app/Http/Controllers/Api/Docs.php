<?php

namespace App\Http\Controllers\Api;

/**
 * The file contains additional OA annotations that ensure certain schemas
 * are present in the generated OpenAPI spec.
 */
class Docs
{
    /**
     * Employee schema (explicit) so Swagger shows who makes requests
     *
     * @OA\Schema(
     *     schema="Employee",
     *     type="object",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="Иван Иванов"),
     *     @OA\Property(property="position_id", type="integer", example=1),
     *     @OA\Property(property="position", ref="#/components/schemas/Position")
     * )
     */
    private function employeeSchema() {}

    // Booking paths are documented on BookingController; avoid duplicate annotations here.
}
