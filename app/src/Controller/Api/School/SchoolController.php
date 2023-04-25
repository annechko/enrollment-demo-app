<?php

declare(strict_types=1);

namespace App\Controller\Api\School;

use App\Domain\Core\NotFoundException;
use App\Domain\Core\UuidGenerator;
use App\Domain\Core\UuidPattern;
use App\Domain\School\Common\RoleEnum;
use App\Domain\School\Repository\CampusRepository;
use App\Domain\School\UseCase\Member;
use App\Domain\School\UseCase\School\Campus\Add;
use App\Domain\School\UseCase\School\Campus\Edit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\InvalidArgumentException;
#[Route('/api/school')]
class SchoolController extends AbstractController
{
    #[Route('/campuses/{campusId}/edit', name: 'api_school_campus_edit',
        requirements: ['campusId' => UuidPattern::PATTERN_WITH_TEMPLATE,],
        methods: ['POST'])]
    public function campusEdit(Request $request, string $campusId, Edit\Handler $handler): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        $command = new Edit\Command($campusId);
        $form = $this->createForm(Edit\Form::class, $command);
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

                return new JsonResponse(
                    ['redirect' => $this->generateUrl('school_campus_list_show')]
                );
            } catch (InvalidArgumentException $exception) {
                return new JsonResponse([
                    'error' => $exception->getMessage(),
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        return new JsonResponse([]);
    }

    #[Route('/campuses', name: 'api_school_campus_add', methods: ['POST'])]
    public function campusAdd(
        Request $request,
        Add\Handler $handler
    ): Response {
        //todo
        $command = new Add\Command();
        $form = $this->createForm(Add\Form::class, $command);
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

                return new JsonResponse(
                    ['redirect' => $this->generateUrl('school_campus_list_show')]
                );
            } catch (InvalidArgumentException $exception) {
                return new JsonResponse([
                    'error' => $exception->getMessage(),
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        return new JsonResponse([]);
    }

    #[Route('/courses', name: 'api_school_course_add', methods: ['POST'])]
    public function courseAdd(
        Request $request,
        Add\Handler $handler
    ): Response {
        //todo
        $command = new Add\Command();
        $form = $this->createForm(Add\Form::class, $command);
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

                return new JsonResponse();
            } catch (InvalidArgumentException $exception) {
                return new JsonResponse([
                    'error' => $exception->getMessage(),
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        return new JsonResponse([]);
    }

    #[Route('/campuses', name: 'api_school_campus_list', methods: ['GET'])]
    public function campusListGet(CampusRepository $repository): Response
    {
        $c = $repository->findAll();
        $res = [];
        foreach ($c as $item) {
            $res[] = [
                'id' => $item->getId()->getValue(),
                'name' => $item->getName(),
                'address' => $item->getAddress(),
            ];
        }
        return new JsonResponse($res);
    }

    #[Route('/courses', name: 'api_school_course_list', methods: ['GET'])]
    public function courseListGet(): Response
    {
        //$c = $repository->findAll();
        //$res = [];
        //foreach ($c as $item) {
        //    $res[] = [
        //        'id' => $item->getId()->getValue(),
        //        'name' => $item->getName(),
        //        'address' => $item->getAddress(),
        //    ];
        //}
        return new JsonResponse([
            [
                'id' => (new UuidGenerator())->generate(),
                'name' => 'test',
                'campuses' => 'campuses',
                'startDates' => 'startDates',
            ]
        ]);
    }

    #[Route('/campuses/{campusId}', name: 'api_school_campus',
        requirements: ['campusId' => UuidPattern::PATTERN_WITH_TEMPLATE,],
        methods: ['GET'])]
    public function campusGet(CampusRepository $repository, string $campusId): Response
    {
        try {
            $campus = $repository->get($campusId);
        } catch (NotFoundException $exception) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $campus->getId()->getValue(),
            'name' => $campus->getName(),
            'address' => $campus->getAddress(),
        ]);
    }

    #[Route('/courses/{courseId}', name: 'api_school_course',
        requirements: ['courseId' => UuidPattern::PATTERN_WITH_TEMPLATE,],
        methods: ['GET'])]
    public function courseGet(CampusRepository $repository, string $courseId): Response
    {
        //try {
        //    $course = $repository->get($courseId);
        //} catch (NotFoundException $exception) {
        //    return new JsonResponse([], Response::HTTP_NOT_FOUND);
        //}

        //return new JsonResponse([
        //    'id' => $course->getId()->getValue(),
        //    'name' => $course->getName(),
        //    'campuses' => $course->getAddress(),
        //    'startDates' => $course->getAddress(),
        //]);
        return new JsonResponse([
            'id' => (new UuidGenerator())->generate(),
            'name' => '$course->getName()',
            'campuses' => '$course->getAddress()',
            'startDates' => '$course->getAddress()',
        ]);
    }

    #[Route('/sidebar', name: 'api_school_sidebar', methods: ['GET'])]
    public function getSidebar(): Response
    {
        return new JsonResponse([
            'navItems' => [
                [
                    'title' => 'Dashboard',
                    'to' => $this->generateUrl('school_home'),
                    'type' => 'home',
                ],
                [
                    'title' => 'Courses',
                    'to' => $this->generateUrl('school_course_list_show'),
                    'type' => 'courses',
                ],
                [
                    'title' => 'Campuses',
                    'to' => $this->generateUrl('school_campus_list_show'),
                    'type' => 'campuses',
                ],
                [
                    'title' => 'Students',
                    'to' => $this->generateUrl('school_student_list_show'),
                    'type' => 'students',
                ],
            ],
        ]);
    }

    //public function index(): Response
    //{
    //    $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);
    //
    //    $courses = [
    //        [
    //            'id' => (new UuidGenerator())->generate(),
    //            'name' => 'Inf Tech in hhsdfm ',
    //            'campuses' => ['Ackland drive', 'Wellington main'],
    //            'startDates' => [
    //                new \DateTimeImmutable('2022-01-01'),
    //                new \DateTimeImmutable('2022-07-01'),
    //            ],
    //        ],
    //        [
    //            'id' => (new UuidGenerator())->generate(),
    //            'name' => 'Inf Tech in hhsdfm dfvdfvdf ef efv ef (df dfg sdfsdsdfsdf)',
    //            'campuses' => ['Ackland drive sfdfsdfsdfsdfs', 'Wellington sdfsd sdmain'],
    //            'startDates' => [
    //                new \DateTimeImmutable('2022-01-01'),
    //                new \DateTimeImmutable('2022-07-01'),
    //            ],
    //        ],
    //    ];
    //
    //    return $this->render('school/index.html.twig', [
    //        'pagination' => $courses,
    //    ]);
    //}

    #[Route('/courses/{courseId}', name: 'api_school_course_edit',
        requirements: [
            'courseId' => UuidPattern::PATTERN_WITH_TEMPLATE,
        ])]
    public function courseEdit(
        Request $request,
        string $courseId,
    ): Response {

    }
}
