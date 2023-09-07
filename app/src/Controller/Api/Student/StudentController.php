<?php

declare(strict_types=1);

namespace App\Controller\Api\Student;

use App\Controller\Api\AbstractJsonApiController;
use App\Core\Common\RegexEnum;
use App\Core\School\Common\RoleEnum;
use App\Core\Student\Entity\Student\StudentId;
use App\ReadModel\Student\ApplicationFetcher;
use App\ReadModel\Student\Filter;
use App\ReadModel\Student\SchoolFetcher;
use App\Security\Student\StudentReadModel;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/student')]
class StudentController extends AbstractJsonApiController
{
    private const MAX_ITEMS = 20;

    #[Route('/applications/schools', name: 'api_student_application_school_list')]
    public function applicationSchoolList(
        Request $request,
        SchoolFetcher $fetcher
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::STUDENT_USER->value);

        $filter = new Filter\SchoolFilter();

        $form = $this->createForm(Filter\SchoolForm::class, $filter);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
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
        $schools = $fetcher->fetchSchools(
            $filter,
            self::MAX_ITEMS,
        );

        $result = [];
        foreach ($schools as $item) {
            $result[] = [
                'id' => $item['id'],
                'name' => $item['name'],
            ];
        }

        return new JsonResponse($result);
    }

    #[Route('/applications/schools/{schoolId}/courses',
        name: 'api_student_application_course_list',
        requirements: ['schoolId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE],
    )]
    public function applicationSchoolCoursesList(
        Request $request,
        string $schoolId,
        SchoolFetcher $fetcher
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::STUDENT_USER->value);

        $filter = new Filter\CourseFilter($schoolId);

        $form = $this->createForm(Filter\CourseForm::class, $filter);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
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
        $courses = $fetcher->fetchSchoolCourses(
            $filter,
            self::MAX_ITEMS,
        );

        $result = [];
        foreach ($courses as $item) {
            $result[] = [
                'id' => $item['id'],
                'name' => $item['name'],
            ];
        }

        return new JsonResponse($result);
    }

    #[Route('/applications/schools/{schoolId}/courses/{courseId}/intakes',
        name: 'api_student_application_intake_list',
        requirements: [
            'schoolId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE,
            'courseId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE,
        ],
    )]
    public function applicationCourseIntakesList(
        Request $request,
        string $schoolId,
        string $courseId,
        SchoolFetcher $fetcher
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::STUDENT_USER->value);

        $filter = new Filter\IntakeFilter($schoolId, $courseId);

        $form = $this->createForm(Filter\IntakeForm::class, $filter);
        $form->handleRequest($request);
        $intakes = $fetcher->fetchCourseIntakes(
            $filter,
        );

        $result = [];
        foreach ($intakes as $item) {
            $result[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'start' => $item['start_date'],
                'end' => $item['end_date'],
            ];
        }

        return new JsonResponse($result);
    }

    #[Route('/applications', name: 'api_student_application_list',
        methods: ['GET'], format: 'json')]
    public function applicationList(ApplicationFetcher $fetcher): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::STUDENT_USER->value);

        $applications = $fetcher->findAll($this->getCurrentStudentId());
        $result = [];
        $format = 'M d, Y';
        foreach ($applications as $item) {
            $intake = [
                'startDate' => (new \DateTimeImmutable($item['intake_start_date']))->format($format),
                'endDate' => (new \DateTimeImmutable($item['intake_end_date']))->format($format),
            ];
            $result[] = [
                'id' => $item['id'],
                'createdAt' => (new \DateTimeImmutable($item['created_at']))->format($format),
                'school' => [
                    'id' => $item['school_id'],
                    'name' => $item['school_name'],
                ],
                'course' => [
                    'id' => $item['course_id'],
                    'name' => $item['course_name'],
                ],
                'intake' => $intake,
                'status' => $item['status'],
            ];
        }

        return new JsonResponse($result);
    }

    #[Route('/applications', name: 'api_student_application',
        methods: ['POST'], format: 'json')]
    public function applicationAdd(
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::STUDENT_USER->value);

        return $this->handleWithResponse(
            \App\Core\Student\UseCase\Application\Add\Command::class,
            $request,
            commandCallback: function (\App\Core\Student\UseCase\Application\Add\Command $command) {
                $command->studentId = $this->getCurrentStudentId()->getValue();
            }
        );
    }

    private function getCurrentUser(): StudentReadModel
    {
        if (!$this->getUser() instanceof StudentReadModel) {
            throw new \LogicException();
        }

        return $this->getUser();
    }

    private function getCurrentStudentId(): StudentId
    {
        return new StudentId($this->getCurrentUser()->id);
    }
}
