<?php

declare(strict_types=1);

namespace App\Controller\Api\Student;

use App\Controller\Api\AbstractJsonApiController;
use App\Domain\Core\UuidPattern;
use App\Domain\School\Common\RoleEnum;
use App\Domain\Student\Entity\Student\StudentId;
use App\Domain\Student\UseCase\Application;
use App\ReadModel\Student\Filter;
use App\ReadModel\Student\SchoolFetcher;
use App\Security\StudentReadModel;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
        requirements: ['schoolId' => UuidPattern::PATTERN_WITH_TEMPLATE],
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
            'schoolId' => UuidPattern::PATTERN_WITH_TEMPLATE,
            'courseId' => UuidPattern::PATTERN_WITH_TEMPLATE,
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

    #[Route('/applications', name: 'api_student_application',format: 'json')]
    public function applicationAdd(
        Request $request,
        Application\Add\Handler $handler,
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::STUDENT_USER->value);

        return $this->handleWithResponse(
            Application\Add\Command::class,
            $handler,
            $request,
            commandCallback: function (Application\Add\Command $command) {
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
