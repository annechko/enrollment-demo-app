<?php

declare(strict_types=1);

namespace App\Controller\Api\Admin;

use App\Controller\Api\AbstractJsonApiController;
use App\Core\School\Common\RoleEnum;
use App\Infrastructure\Service\StatisticsService;
use App\ReadModel\Admin\Statistics\Filter\Filter;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

#[Route('/api/admin')]
class StatisticsController extends AbstractJsonApiController
{
    #[Route('/stats', name: 'api_admin_stats', methods: ['POST'])]
    public function getStats(
        Request $request,
        StatisticsService $service,
        LoggerInterface $logger
    ): Response {
        $this->denyAccessUnlessGranted(RoleEnum::ADMIN_USER->value);

        try {
            $filter = $this->serializer->deserialize(
                $request->getContent(),
                Filter::class,
                JsonEncoder::FORMAT,
            );
            $results = $service->getByType($filter);
        } catch (\Throwable $exception) {
            $logger->error($exception->getMessage(), ['exception' => $exception]);

            return new JsonResponse([
                'error' => 'Invalid report type.',
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new JsonResponse([
            'labels' => $results->labels,
            'data' => $results->data,
            'maxY' => $results->maxY,
        ]);
    }
}
