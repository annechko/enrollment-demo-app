<?php

declare(strict_types=1);

namespace App\Controller\Api\Admin;

use App\Controller\Api\AbstractJsonApiController;
use App\Core\School\Common\RoleEnum;
use App\ReadModel\Admin\Statistics\Filter\Filter;
use App\ReadModel\Admin\Statistics\StatsFetcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

#[Route('/api/admin')]
class StatisticsController extends AbstractJsonApiController
{
    #[Route('/stats', name: 'api_admin_stats', methods: ['POST'])]
    public function getStats(Request $request, StatsFetcher $fetcher): Response
    {
        $this->denyAccessUnlessGranted(RoleEnum::ADMIN_USER->value);
        try {
            $filter = $this->serializer->deserialize(
                $request->getContent(),
                Filter::class,
                JsonEncoder::FORMAT,
            );
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'error' => 'Invalid report type.',
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $yearAgo = (new \DateTimeImmutable('first day of this month last year'))->setTime(0, 0, 0);
        $now = new \DateTimeImmutable();
        $dateToAmountResults = $fetcher->fetchSchoolRegistrations(
            new Filter($filter->type, $yearAgo)
        );
        $datePointer = clone $yearAgo;
        $labels = [];
        $data = [];

        while ($datePointer < $now) {
            $curMonth = $datePointer->format('y-m');
            $labels[$curMonth] = $datePointer->format('M y');
            $datePointer = $datePointer->add(new \DateInterval('P1M'));
        }
        foreach ($labels as $label => $date) {
            $data[] = $dateToAmountResults[$label] ?? 0;
        }

        $maxY = round(max($data) + round(max($data)) / 2);

        return new JsonResponse([
            'labels' => array_values($labels),
            'data' => $data,
            'maxY' => $maxY,
        ]);
    }
}
