<?php

declare(strict_types=1);

namespace App\Controller\School;

use App\Domain\Core\UuidPattern;
use App\Domain\School\Common\RoleEnum;
use App\Domain\School\UseCase\Member;
use App\Domain\School\UseCase\School\Register;
use App\Infrastructure\RouteEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\InvalidArgumentException;

#[Route('/school')]
class SchoolController extends AbstractController
{
    #[Route('/register', name: 'school_register')]
    public function register(
        Request $request,
        Register\Handler $handler
    ): Response {
        $command = new Register\Command();
        $form = $this->createForm(Register\Form::class, $command);
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

                return new JsonResponse([
                    'error' => $error,
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
            try {
                $handler->handle($command);

                return new JsonResponse([]);
            } catch (InvalidArgumentException $exception) {
                return new JsonResponse([
                    'error' => $exception->getMessage(),
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        return $this->render('base.html.twig');
    }

    // todo rename to invite
    #[Route('/{schoolId}/invitation/{invitationToken}', name: 'school_member_register',
        requirements: [
            'schoolId' => UuidPattern::PATTERN,
            'invitationToken' => UuidPattern::PATTERN,
        ])]
    public function memberRegister(
        Request $request,
        Member\Register\Handler $handler,
        string $schoolId,
        string $invitationToken,
    ): Response {
        $command = new Member\Register\Command($schoolId, $invitationToken);
        $form = $this->createForm(Member\Register\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);

                return $this->redirectToRoute('school_login');
            } catch (InvalidArgumentException $exception) {
                $form->addError(new FormError($exception->getMessage()));
                $this->addFlash('error', $exception->getMessage());
            }
        }

        return $this->render('base.html.twig');
    }

    #[Route('/campuses', name: 'school_campus_list_show', methods: ['GET'])]
    public function campusListShow(): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        return $this->render('base.html.twig');
    }

    #[Route('/students', name: 'school_student_list_show', methods: ['GET'])]
    public function studentListShow(): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        return $this->render('base.html.twig');
    }

    #[Route('/courses', name: 'school_course_list_show', methods: ['GET'])]
    public function courseListShow(): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        return $this->render('base.html.twig');
    }

    #[Route('/campuses/add', name: 'school_campus_add', methods: ['GET'])]
    public function campusAddShow(): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        return $this->render('base.html.twig');
    }

    #[Route('/courses/add', name: 'school_course_add', methods: ['GET'])]
    public function courseAddShow(): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        return $this->render('base.html.twig');
    }

    #[Route('/campuses/{campusId}/edit', name: 'school_campus_edit',
        requirements: ['campusId' => UuidPattern::PATTERN_WITH_TEMPLATE],
        methods: ['GET'])]
    public function campusEditShow(string $campusId): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        return $this->render('base.html.twig');
    }

    #[Route('/courses/{courseId}/edit', name: 'school_course_edit',
        requirements: ['courseId' => UuidPattern::PATTERN_WITH_TEMPLATE],
        methods: ['GET'])]
    public function courseEditShow(string $courseId): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        return $this->render('base.html.twig');
    }

    #[Route('/', name: RouteEnum::SCHOOL_HOME)]
    public function home(): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        return $this->render('base.html.twig');
    }
}
