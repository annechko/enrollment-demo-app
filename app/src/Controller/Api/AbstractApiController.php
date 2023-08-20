<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Assert\InvalidArgumentException;

abstract class AbstractApiController extends AbstractController
{
    protected function handle(
        object $command,
        string $formClass,
        object $handler,
        Request $request,
    ) {
        $form = $this->createForm($formClass, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $violationList = $form->getErrors(true);
                $error = 'Invalid data.';
                foreach ($violationList as $violation) {
                    if ($violation instanceof FormError) {
                        $error = $violation->getMessage();
                        break;
                    }
                }
                throw new FormErrorException($error);
            }

            return $handler->handle($command);
        }
        throw new \LogicException('Form is not submitted.');
    }

    protected function handleWithResponse(
        object $command,
        string $formClass,
        object $handler,
        Request $request,
        callable $responseSuccessBuilder = null
    ): JsonResponse {
        try {
            $result = $this->handle($command, $formClass, $handler, $request);
            $response = $responseSuccessBuilder ? $responseSuccessBuilder($result) : null;

            return new JsonResponse($response);
        } catch (FormErrorException|InvalidArgumentException $exception) {
            return new JsonResponse([
                'error' => $exception->getMessage(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $exception) {
            // todo add logs
            return new JsonResponse([
                'error' => 'Something went wrong',
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
