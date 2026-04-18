<?php

namespace App\Controller;

use Pimcore\Controller\FrontendController;
use Pimcore\Model\Translation;
use Pimcore\Tool;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TranslationController
 */
class TranslationController extends FrontendController
{
    /**
      #[Route('/translations', name: 'translations')]
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function defaultAction(Request $request): JsonResponse
    {
        $locale = $request->get('locale', Tool::getDefaultLanguage());

        $mappedTranslations = [];
        $list = new Translation\Listing();
        foreach ($list->getTranslations() as $translation) {
            $key = strtolower(str_replace('.', '-', $translation->getKey()));
            $value = $translation->getTranslation($locale);
            if (!$value) {
                foreach (Tool::getFallbackLanguagesFor($locale) as $fallback) {
                    $value = $translation->getTranslation($fallback);

                    if (is_string($value)) {
                        break;
                    }
                }
            }

            if (!$value) {
                // set the key, so that we recognize it in the frontend
                $value = $key;
            }

            $mappedTranslations = array_merge($mappedTranslations, [
                $key => $value,
            ]);
        }

        ksort($mappedTranslations);

        return $this->json($mappedTranslations, 200, [
            'Access-Control-Allow-Origin' => '*',
        ]);
    }
}
