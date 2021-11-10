<?php

namespace App\Service\Api\General;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class ApiService
{
    /**
     * HTTP status code - 200 (OK) by default
     *
     * @var integer
     */
    protected $statusCode = 200;

    /**
     * @var string
     */
    private string $type = '';

    /**
     * Gets the value of statusCode.
     *
     * @return integer
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Sets the value of statusCode.
     *
     * @param integer $statusCode the status code
     *
     * @return self
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Sets the value of type.
     *
     * @param string $type
     *
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Returns a JSON response
     *
     * @param mixed $data
     * @param array $headers
     *
     * @return JsonResponse
     */
    public function response($data, $headers = []): JsonResponse
    {
        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Sets an error message and returns a JSON response
     *
     * @param string|null $errors
     * @param array $headers
     * @return JsonResponse
     */
    public function respondWithErrors(?string $errors, $headers = []): JsonResponse
    {
        $data = [
            'code' => $this->getStatusCode(),
            'message' => $errors,
        ];

        if ($this->getType()) {
            $data['type'] = $this->getType();
        }

        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Sets an error message and returns a JSON response
     *
     * @param string $success
     * @param array $headers
     * @return JsonResponse
     */
    public function respondWithSuccess(string $success, array $headers = []): JsonResponse
    {
        $data = [
            'code' => $this->getStatusCode(),
            'message' => $success,
        ];

        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Returns a 401 Unauthorized http response
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondUnauthorized(string $message = 'Not authorized!'): JsonResponse
    {
        return $this->setStatusCode(401)->respondWithErrors($message);
    }

    /**
     * Returns a 422 Unprocessable Entity
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondValidationError(string $message = 'Validation errors'): JsonResponse
    {
        return $this->setStatusCode(422)->respondWithErrors($message);
    }

    /**
     * Returns a 404 Not Found
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondNotFound(string $message = 'Not found!'): JsonResponse
    {
        return $this->setStatusCode(404)->respondWithErrors($message);
    }

    /**
     * Returns a 201 Created
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    public function respondCreated(array $data = []): JsonResponse
    {
        return $this->setStatusCode(201)->response($data);
    }

    /**
     * Transform JSON body.
     *
     * @param Request $request
     * @return Request
     */
    public function transformJsonBody(Request $request): Request
    {
        $jsonEncoder = new JsonEncoder();
        if (empty($requestContent = $request->getContent())) {
            return $request;
        }

        /** @var string $requestContent */
        $data = $jsonEncoder->decode($requestContent, JsonEncoder::FORMAT);
        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }
}
