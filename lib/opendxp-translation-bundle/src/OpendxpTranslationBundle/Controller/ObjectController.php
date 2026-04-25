<?php

declare(strict_types=1);

namespace OpendxpTranslationBundle\Controller;

use OpendxpTranslationBundle\Provider\ProviderFactory;
use OpenDxp\Bundle\AdminBundle\Controller\AdminAbstractController;
use OpenDxp\Model\DataObject;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/object')]
final class ObjectController extends AdminAbstractController
{
    private string $sourceLanguage;
    private string $provider;

    public function __construct(string $sourceLanguage, string $provider)
    {
        $this->sourceLanguage = $sourceLanguage;
        $this->provider = $provider;
    }

    #[Route('/translate-field', methods: ['GET'])]
    public function translateFieldAction(Request $request, ProviderFactory $providerFactory): JsonResponse
    {
        try {
            $sourceId = $request->get('sourceId');
            $object = DataObject::getById($sourceId);
            if (!$object) {
                return $this->adminJson([
                    'success' => false,
                    'message' => 'Cannot find object with id: ' . ($sourceId ?: 'empty'),
                ]);
            }

            $lang = $request->get('lang');
            $fieldName = 'get' . ucfirst($request->get('fieldName'));

            $data = $object->$fieldName($lang) ?: $object->$fieldName($this->sourceLanguage);
            if (!$data) {
                return $this->adminJson([
                    'success' => false,
                    'message' => 'Data are empty',
                ]);
            }

            $provider = $providerFactory->get($this->provider);
            if ($request->get('formality') && ($this->provider === 'deepl' || $this->provider === 'deepl_free')) {
                $provider->setFormality($request->get('formality'));
            }

            $data = strip_tags($data);
            $data = $provider->translate($data, $lang);
        } catch (\Throwable $exception) {
            return $this->adminJson([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }

        return $this->adminJson([
            'success' => true,
            'data' => $data,
        ]);
    }
}
