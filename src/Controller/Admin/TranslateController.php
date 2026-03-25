<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Service\DeeplProviderService;
use Pimcore\Bundle\AdminBundle\Controller\AdminAbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/object')]
class TranslateController extends AdminAbstractController
{
    public function __construct(
        private readonly DeeplProviderService $deeplProvider
    ) {
    }

    #[Route('/translate-text', name: 'admin_object_translate_text', methods: ['POST'])]
    public function translateTextAction(Request $request): JsonResponse
    {
        try {
            $text = $request->get('text', '');
            $lang = $request->get('lang', '');
            $formality = $request->get('formality', 'default');

            if (empty($text) || empty($lang)) {
                return $this->adminJson([
                    'success' => false,
                    'message' => 'Text and language are required',
                ]);
            }

            $text = strip_tags($text);

            if (!empty($formality)) {
                $this->deeplProvider->setFormality($formality);
            }

            $translated = $this->deeplProvider->translate($text, $lang);

            return $this->adminJson([
                'success' => true,
                'data' => $translated,
            ]);
        } catch (\Throwable $exception) {
            return $this->adminJson([
                'success' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
