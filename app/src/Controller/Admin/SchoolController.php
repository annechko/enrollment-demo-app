<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Domain\Core\UuidPattern;
use App\Domain\School\Common\RoleEnum;
use App\Domain\School\Entity\School\School;
use App\Domain\School\UseCase\School\Confirm\Command;
use App\Domain\School\UseCase\School\Confirm\Handler;
use App\ReadModel\Admin\School\Filter;
use App\ReadModel\Admin\School\SchoolFetcher;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/school')]
class SchoolController extends AbstractController
{
    private const MAX_ITEMS = 20;

    #[Route('', name: 'admin_school_list')]
    public function list(Request $request, SchoolFetcher $fetcher): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::ADMIN->value);
        $filter = new Filter\Filter();

        $form = $this->createForm(Filter\Form::class, $filter);
        $form->handleRequest($request);

        $pagination = $fetcher->fetch(
            $filter,
            $request->query->getInt('page', 1),
            self::MAX_ITEMS,
            $request->query->get('sort', 'id'),
            $request->query->get('direction', 'desc')
        );

        return $this->render('admin/school/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
            'statusesCanBeConfirmed' => [School::STATUS_NEW],
        ]);
    }

    #[Route('/{id}/confirm', name: 'admin_school_confirm', requirements: ["id" => UuidPattern::PATTERN])]
    public function confirm(string $id, Request $request, Handler $handler): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::ADMIN->value);
        if (!$this->isCsrfTokenValid('school_confirm', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_school_list');
        }
        $command = new Command($id);

        try {
            $handler->handle($command);
        } catch (DomainException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('admin_school_list');
    }
}
