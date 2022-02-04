<?php

namespace App\Controller;

use App\Exceptions\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Throwable;

class ErrorController extends AbstractController
{
    public function show(
        Throwable $exception,
    ): JsonResponse {
        $e = $exception;

        return new JsonResponse(array_filter([
            'code' => $e->getCode(),
            'message' => $this->getMessage($e),
            'errors' => $this->getErrors($e),
        ]), $this->getStatusCode($e));
    }

    private function getStatusCode(Throwable $e): int
    {
        return match(get_class($e)) {
            NotFoundHttpException::class => 404,
            ValidationException::class, ValidationFailedException::class => 400,
            default => 500,
        };
    }

    private function getMessage(Throwable $e): string
    {
        return match(get_class($e)) {
            NotFoundHttpException::class => 'Not found',
            ValidationException::class, ValidationFailedException::class => 'Validation error',
            default => $e->getMessage() ?: 'Something goes wrong ¯\_(ツ)_/¯',
        };
    }
    private function getErrors(Throwable $e): ?array
    {
        return match(get_class($e)) {
            ValidationException::class => [
                $e->getField() => [$e->getMessage()],
            ],
            ValidationFailedException::class => (static function () use ($e) {
                $result = [];
                $list = array_map(
                    static fn (ConstraintViolationInterface $c) => [
                        $c->getPropertyPath() => [$c->getMessage()],
                    ], iterator_to_array($e->getViolations())
                );

                foreach ($list as $item) {
                    foreach ($item as $k => $v) {
                        $result[$k] = $v;
                    }
                }

                return $result;
            })(),
            default => null,
        };
    }
}
