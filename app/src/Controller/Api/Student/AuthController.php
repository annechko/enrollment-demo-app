<?php

declare(strict_types=1);

namespace App\Controller\Api\Student;

use App\Controller\Api\AbstractJsonApiController;
use App\Core\Student\UseCase\Student;
use App\Infrastructure\RouteEnum;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/student')]
class AuthController extends AbstractJsonApiController
{
    #[Route('/register', name: RouteEnum::API_STUDENT_REGISTER, methods: ["POST"])]
    public function register(
        Request $request,
    ): Response {
        return $this->handleWithResponse(
            Student\Register\Command::class,
            $request
        );
    }
}
