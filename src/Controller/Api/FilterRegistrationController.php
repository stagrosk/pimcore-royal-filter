<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Service\FilterRegistrationNotificationService;
use App\Service\FilterRegistrationService;
use OpendxpVendureBridgeBundle\Security\CheckConsumerPermissionsService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class FilterRegistrationController extends AbstractController
{
    public function __construct(
        private readonly CheckConsumerPermissionsService $checkConsumerPermissionsService,
        private readonly FilterRegistrationService $registrationService,
        private readonly FilterRegistrationNotificationService $notificationService,
        private readonly LoggerInterface $logger
    ) {
    }

    #[Route('/filter-registration', name: 'api_filter_registration', methods: ['POST'])]
    public function submitAction(Request $request): JsonResponse
    {
        if (!$this->checkConsumerPermissionsService->performSecurityCheck($request)) {
            return new JsonResponse(['error' => 'Permission denied'], Response::HTTP_FORBIDDEN);
        }

        try {
            $data = $this->extractFormData($request);

            $errors = $this->validate($data);
            if (!empty($errors)) {
                return new JsonResponse(['error' => 'Validation failed', 'details' => $errors], Response::HTTP_BAD_REQUEST);
            }

            $uploadedFiles = $this->extractPhotos($request);

            $registration = $this->registrationService->createRegistration($data, $uploadedFiles);

            $this->notificationService->notifyAdmin($registration);

            return new JsonResponse([
                'success' => true,
                'id' => $registration->getId(),
            ], Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            $this->logger->error(sprintf('[FilterRegistration] Error: %s', $e->getMessage()));
            return new JsonResponse(
                ['error' => 'Failed to process registration'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    private function extractFormData(Request $request): array
    {
        $contentType = $request->getContentTypeFormat();

        if ($contentType === 'json') {
            return json_decode($request->getContent(), true) ?? [];
        }

        // multipart/form-data
        return $request->request->all();
    }

    private function extractPhotos(Request $request): array
    {
        $photos = [];
        $allFiles = $request->files->all();

        // Support both flat (photos[]) and zoned (photos[sideView][], photos[top][]) uploads
        if (isset($allFiles['photos'])) {
            $photos = $allFiles['photos'];
        }

        // Also collect any zone-named file fields directly
        $zones = FilterRegistrationService::PHOTO_ZONES;
        foreach ($zones as $zone) {
            if (isset($allFiles[$zone])) {
                $photos[$zone] = is_array($allFiles[$zone]) ? $allFiles[$zone] : [$allFiles[$zone]];
            }
        }

        return $photos;
    }

    private function validate(array $data): array
    {
        $errors = [];

        if (empty($data['email'])) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }

        if (empty($data['originalLabel'])) {
            $errors[] = 'Original filter label is required';
        }

        if (!isset($data['filterHeight']) || $data['filterHeight'] === '') {
            $errors[] = 'Filter height is required';
        }

        if (!isset($data['filterDiameter']) || $data['filterDiameter'] === '') {
            $errors[] = 'Filter diameter is required';
        }

        if (empty($data['topType'])) {
            $errors[] = 'Top end type is required';
        } elseif (!in_array($data['topType'], FilterRegistrationService::ALLOWED_TOP_TYPES)) {
            $errors[] = 'Invalid top end type';
        }

        if (empty($data['bottomType'])) {
            $errors[] = 'Bottom end type is required';
        } elseif (!in_array($data['bottomType'], FilterRegistrationService::ALLOWED_BOTTOM_TYPES)) {
            $errors[] = 'Invalid bottom end type';
        }

        // If brand is provided, model is required
        if (!empty($data['brand']) && empty($data['model'])) {
            $errors[] = 'Hot tub model is required when brand is provided';
        }

        return $errors;
    }
}
