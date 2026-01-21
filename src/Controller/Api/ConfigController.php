<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Pimcore\Tool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ConfigController extends AbstractController
{
    #[Route('/languages', name: 'api_languages', methods: ['GET'])]
    public function languagesAction(): JsonResponse
    {
        $languages = Tool::getValidLanguages();
        $defaultLanguage = Tool::getDefaultLanguage();

        return $this->json([
            'languages' => $languages,
            'defaultLanguage' => $defaultLanguage,
        ]);
    }
}