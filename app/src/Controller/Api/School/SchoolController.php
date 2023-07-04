<?php

declare(strict_types=1);

namespace App\Controller\Api\School;

use App\Domain\Core\NotFoundException;
use App\Domain\Core\UuidPattern;
use App\Domain\School\Common\RoleEnum;
use App\Domain\School\Entity\Course\CourseId;
use App\Domain\School\Repository\CampusRepository;
use App\Domain\School\Repository\CourseRepository;
use App\Domain\School\UseCase\School;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Validation;
use Webmozart\Assert\InvalidArgumentException;

#[Route('/api/school')]
class SchoolController extends AbstractController
{
    #[Route('/campuses/{campusId}/edit', name: 'api_school_campus_edit',
        requirements: ['campusId' => UuidPattern::PATTERN_WITH_TEMPLATE],
        methods: ['POST'])]
    public function campusEdit(
        Request $request,
        string $campusId,
        School\Campus\Edit\Handler $handler
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        $command = new School\Campus\Edit\Command($campusId);

        return $this->handle($command, School\Campus\Edit\Form::class, $handler, $request);
    }

    #[Route('/campuses', name: 'api_school_campus_add', methods: ['POST'])]
    public function campusAdd(
        Request $request,
        School\Campus\Add\Handler $handler
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        $command = new School\Campus\Add\Command();

        return $this->handle($command, School\Campus\Add\Form::class, $handler, $request);
    }

    #[Route('/courses/{courseId}/intakes', name: 'api_school_course_intake_list',
        requirements: [
            'courseId' => UuidPattern::PATTERN_WITH_TEMPLATE,
        ],
        methods: ['GET'],
    )]
    public function intakeListGet(
        string $courseId,
        CourseRepository $repository,
    ): Response {
        $c = $repository->get(new CourseId($courseId));
        $res = [];
        foreach ($c->getIntakes() as $intake) {
            $res[] = [
                'id' => $intake->getId()->getValue(),
                'name' => $intake->getName(),
                'classSize' => $intake->getClassSize(),
                'campus' => $intake->getCampus()->getName(),
                'startDate' => $intake->getStartDate()->format('Y-m-d'),
                'endDate' => $intake->getEndDate()->format('Y-m-d'),
            ];
        }

        return new JsonResponse($res);
    }

    #[Route('/campuses', name: 'api_school_campus_list', methods: ['GET'])]
    public function campusListGet(CampusRepository $repository): Response
    {
        $c = $repository->findAllOrderedByName();
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

    #[Route('/campuses/{campusId}', name: 'api_school_campus',
        requirements: ['campusId' => UuidPattern::PATTERN_WITH_TEMPLATE],
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

    #[Route('/courses', name: 'api_school_course_list', methods: ['GET'])]
    public function courseListGet(CourseRepository $repository): Response
    {
        $courses = $repository->findAllOrderedByName();
        $res = [];
        foreach ($courses as $course) {
            $res[] = [
                'id' => $course->getId()->getValue(),
                'name' => $course->getName(),
                'description' => $course->getDescription(),
            ];
        }

        return new JsonResponse($res);
    }

    #[Route('/courses', name: 'api_school_course_add', methods: ['POST'])]
    public function courseAdd(
        Request $request,
        School\Course\Add\Handler $handler
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        $command = new School\Course\Add\Command();

        return $this->handle(
            $command,
            School\Course\Add\Form::class,
            $handler,
            $request,
            fn (CourseId $result) => ['id' => $result->getValue()]
        );
    }

    #[Route('/courses/courseData', name: 'api_school_course', methods: ['GET'])]
    public function courseGet(
        CourseRepository $repository,
        Request $request
    ): Response {
        $result = ['course' => null];

        $courseId = $request->get('courseId', null);
        if ($courseId !== null) {
            $validator = Validation::createValidator();
            $violations = $validator->validate($courseId, [
                new Constraints\Regex(UuidPattern::PATTERN_REG_EXP),
            ]);
            if (count($violations) > 0) {
                $errors = [];
                foreach ($violations as $violation) {
                    $errors[] = $violation->getMessage();
                }

                return new JsonResponse(['error' => implode(';', $errors)],
                    Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            try {
                $course = $repository->get(new CourseId($courseId));
            } catch (NotFoundException $e) {
                return new JsonResponse([], Response::HTTP_NOT_FOUND);
            }

            $result['course']['id'] = $course->getId()->getValue();
            $result['course']['name'] = $course->getName();
            $result['course']['description'] = $course->getDescription();
        }

        return new JsonResponse($result);
    }

    #[Route('/courses/{courseId}', name: 'api_school_course_edit',
        requirements: [
            'courseId' => UuidPattern::PATTERN_WITH_TEMPLATE,
        ])]
    public function courseEdit(
        Request $request,
        string $courseId,
        School\Course\Edit\Handler $handler,
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        $command = new School\Course\Edit\Command($courseId);

        return $this->handle($command, School\Course\Edit\Form::class, $handler, $request);
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

    // public function index(): Response
    // {
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
    // }
    private function handle(
        object $command,
        string $class,
        object $handler,
        Request $request,
        callable $responseSuccessBuilder = null
    ): JsonResponse {
        $form = $this->createForm($class, $command);
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
                $result = $handler->handle($command);
                $response = $responseSuccessBuilder ? $responseSuccessBuilder($result) : null;
                return new JsonResponse($response);
            } catch (InvalidArgumentException $exception) {
                return new JsonResponse([
                    'error' => $exception->getMessage(),
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        return new JsonResponse([]);
    }
}
