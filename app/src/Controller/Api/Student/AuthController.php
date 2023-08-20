<?php

declare(strict_types=1);

namespace App\Controller\Api\Student;

use App\Controller\Api\AbstractApiController;
use App\Core\Common\FeatureToggleService;
use App\Core\Common\FeatureToggleType;
use App\Infrastructure\RouteEnum;
use App\Security\Student\StudentReadModel;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/student')]
class AuthController extends AbstractApiController
{
    #[Route('/register', name: RouteEnum::API_STUDENT_REGISTER)]
    public function register(
        Request $request,
        \App\Core\Student\UseCase\Student\Register\Handler $handler,
        FeatureToggleService $featureToggleService,
        LoggerInterface $logger,
        Security $security,
    ): Response {
        $command = new \App\Core\Student\UseCase\Student\Register\Command();
        try {
            $user = $this->handle($command, \App\Core\Student\UseCase\Student\Register\Form::class, $handler, $request);

            $emailVerificationEnabled = $featureToggleService->isEnabled(
                FeatureToggleType::STUDENT_EMAIL_VERIFICATION
            );
            if (!$emailVerificationEnabled) {
                $security->login(StudentReadModel::createFromStudent($user));
            }

            return new JsonResponse(['emailVerificationEnabled' => $emailVerificationEnabled]);
        } catch (\Throwable $exception) {
            // todo catch different types.
            $logger->error($exception->getMessage(), ['exception' => $exception]);
            return new JsonResponse([
                'error' => 'Something went wrong.',
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
