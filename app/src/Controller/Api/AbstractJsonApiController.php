<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Core\Common\NotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractJsonApiController extends AbstractController
{
    public function __construct(
        protected readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly LoggerInterface $logger,
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    protected function handle(
        string $commandClass,
        Request $request,
        callable $commandCallback = null,
    ) {
        $command = $this->serializer->deserialize(
            $request->getContent(),
            $commandClass,
            JsonEncoder::FORMAT
        );
        if ($commandCallback) {
            $commandCallback($command);
        }
        $violationList = $this->validator->validate($command);
        if ($violationList->count() > 0) {
            $errors = [];
            foreach ($violationList as $violation) {
                $errors[] = $violation->getMessage();
            }
            throw new \DomainException(implode(', ', $errors));
        }

        return $this->messageBus->dispatch($command);
    }

    protected function handleWithResponse(
        string $commandClass,
        Request $request,
        callable $responseSuccessBuilder = null,
        callable $commandCallback = null,
    ): JsonResponse {
        try {
            $result = $this->handle($commandClass, $request, $commandCallback);
            $response = $responseSuccessBuilder ? $responseSuccessBuilder($result) : null;

            return new JsonResponse($response);
        } catch (\DomainException|NotFoundException $exception) {
            return new JsonResponse([
                'error' => $exception->getMessage(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);

            return new JsonResponse([
                'error' => 'Something went wrong',
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
