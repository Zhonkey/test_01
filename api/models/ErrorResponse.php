<?php
namespace api\models;

/**
 * @SWG\Definition()
 *
 * @SWG\Property(property="message", type="string")
 * @SWG\Property(property="details", type="mixed")

 */
class ErrorResponse
{
    public function build(string $message, array $details): array
    {
        return [
            'message' => $message,
            'details' => $details,
        ];
    }
}