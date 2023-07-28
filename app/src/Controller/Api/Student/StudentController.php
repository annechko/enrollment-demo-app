<?php

declare(strict_types=1);

namespace App\Controller\Api\Student;

use App\Controller\Api\AbstractApiController;
use App\Domain\School\Common\RoleEnum;
use App\ReadModel\Student\Filter;
use App\ReadModel\Student\SchoolFetcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/student')]
class StudentController extends AbstractApiController
{
    private const MAX_ITEMS = 20;

    #[Route('/application/schools', name: 'api_student_application_school_list')]
    public function applicationSchoolList(
        Request $request,
        SchoolFetcher $fetcher
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::STUDENT_USER->value);

        $filter = new Filter\Filter();

        $form = $this->createForm(Filter\Form::class, $filter);
        $form->handleRequest($request);
        // todo remove pagination.
        $pagination = $fetcher->fetch(
            $filter,
            $request->query->getInt('page', 1),
            self::MAX_ITEMS,
        );

        $result = [];
        foreach ($pagination->getItems() as $item) {
            $result[] = [
                'id' => $item['id'],
                'name' => $item['name'],
            ];
        }

        return new JsonResponse($result);
    }
}
