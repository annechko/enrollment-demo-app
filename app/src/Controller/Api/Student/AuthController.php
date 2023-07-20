<?php

declare(strict_types=1);

namespace App\Controller\Api\Student;

use App\Controller\Api\AbstractApiController;
use App\Domain\Core\FeatureToggleService;
use App\Domain\Core\FeatureToggleType;
use App\Domain\Student\UseCase\Student\Register;
use App\Infrastructure\RouteEnum;
use App\Security\StudentReadModel;
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
        Register\Handler $handler,
        FeatureToggleService $featureToggleService,
        Security $security,
    ): Response {
        $command = new Register\Command();
        try {
            $user = $this->handle($command, Register\Form::class, $handler, $request);
        } catch (\Throwable $exception) {
            // todo catch different types.
            return new JsonResponse([
                'error' => $exception->getMessage(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!$featureToggleService->isActivated(
            FeatureToggleType::STUDENT_EMAIL_VERIFICATION
        )) {
            $security->login(StudentReadModel::createFromStudent($user));
        }
        return new JsonResponse();
    }

    #[Route(path: '/login', name: RouteEnum::API_STUDENT_LOGIN)]
    public function login(): Response
    {
        //todo???????
        if ($this->getUser()) {
            return $this->redirectToRoute(RouteEnum::STUDENT_HOME);
        }
        return $this->render('base.html.twig');
    }

}
