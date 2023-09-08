<?php

declare(strict_types=1);

namespace App\Controller\School;

use App\Core\Common\RegexEnum;
use App\Core\School\Common\RoleEnum;
use App\Infrastructure\RouteEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/school')]
class SchoolController extends AbstractController
{
    #[Route('/register', name: RouteEnum::SCHOOL_REGISTER)]
    public function register(): Response
    {
        return $this->render('base.html.twig');
    }

    // todo rename to invite
    #[Route('/{schoolId}/invitation/{invitationToken}', name: 'school_member_register',
        requirements: [
            'schoolId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE,
            'invitationToken' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE,
        ], methods: ['GET'])]
    public function showMemberRegister(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/campuses', name: RouteEnum::SCHOOL_CAMPUS_LIST, methods: ['GET'])]
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

    #[Route('/applications', name: 'school_application_list_show', methods: ['GET'])]
    public function studentApplicationListShow(): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        return $this->render('base.html.twig');
    }

    #[Route('/applications/{applicationId}', name: 'school_application_edit',
        requirements: ['applicationId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE],
        methods: ['GET'])]
    public function studentApplicationEditShow(string $applicationId): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        return $this->render('base.html.twig');
    }

    #[Route('/profile', name: RouteEnum::SCHOOL_PROFILE, methods: ['GET'])]
    public function profileShow(): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        return $this->render('base.html.twig');
    }

    #[Route('/courses', name: RouteEnum::SCHOOL_COURSE_LIST, methods: ['GET'])]
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
        requirements: ['campusId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE],
        methods: ['GET'])]
    public function campusEditShow(string $campusId): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_USER->value);

        return $this->render('base.html.twig');
    }

    #[Route('/courses/{courseId}/edit', name: 'school_course_edit',
        requirements: ['courseId' => RegexEnum::UUID_PATTERN_WITH_TEMPLATE],
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
