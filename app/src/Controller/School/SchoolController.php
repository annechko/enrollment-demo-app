<?php

declare(strict_types=1);

namespace App\Controller\School;

use App\Domain\Core\UuidPattern;
use App\Domain\School\Common\RoleEnum;
use App\Domain\School\UseCase\Member;
use App\Domain\School\UseCase\School\Register;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
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

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);

                return $this->render('school/registration/after-register.html.twig', [
                    'schoolName' => $command->name,
                ]);
            } catch (InvalidArgumentException $exception) {
                $form->addError(new FormError($exception->getMessage()));
                $this->addFlash('error', $exception->getMessage());
            }
        }

        return $this->render('school/registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/', name: 'school_home')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::SCHOOL_ADMIN->value);

        return $this->render('school/index.html.twig', []);
    }

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

        return $this->render('school/member/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
