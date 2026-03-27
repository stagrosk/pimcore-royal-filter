<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Service\DeeplProviderService;
use Pimcore\Bundle\AdminBundle\Controller\AdminAbstractController;
use Pimcore\Tool;
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

    #[Route('/translate-config', name: 'admin_object_translate_config', methods: ['GET'])]
    public function configAction(): JsonResponse
    {
        return $this->adminJson([
            'sourceLang' => Tool::getDefaultLanguage(),
        ]);
    }

    #[Route('/translate-text', name: 'admin_object_translate_text', methods: ['POST'])]
    public function translateTextAction(Request $request): JsonResponse
    {
        try {
            $text = $request->get('text', '');
            $lang = $request->get('lang', '');
            $sourceLang = $request->get('sourceLang', '');
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

            // Only hint source_lang if it differs from target (avoids EN→EN no-op)
            $effectiveSource = (!empty($sourceLang) && strtolower($sourceLang) !== strtolower(substr($lang, 0, 2)))
                ? $sourceLang
                : null;

            $translated = $this->deeplProvider->translate($text, $lang, $effectiveSource);

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
