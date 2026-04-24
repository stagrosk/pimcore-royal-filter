<?php

namespace App\GraphQL\Resolver\Layout;

use App\Exception\TranslatableException;
use App\GraphQL\Resolver\AbstractResolver;
use Exception;
use GraphQL\Type\Definition\ResolveInfo;
use JetBrains\PhpStorm\ArrayShape;
use OpenDxp\Model\DataObject\ContentPage;
use OpenDxp\Tool;
use OpendxpHeadlessContentBundle\Model\SlugAwareInterface;

class ContentPageResolver extends AbstractResolver
{
    #[ArrayShape(['id' => 'mixed', 'defaultLanguage' => 'mixed', 'language' => 'mixed', 'canonicals' => 'array'])]
    /**
     * @param mixed $source
     * @param mixed $args
     * @param mixed $context
     * @param \GraphQL\Type\Definition\ResolveInfo $info
     *
     * @throws \App\Exception\TranslatableException
     * @throws \Exception
     *
     * @return array
     */
    public function resolve($source, $args, $context, ResolveInfo $info): array
    {
        $handle = $args['handle'] ?? '';
        $slug = $args['slug'] ?? '';

        if (empty($handle) && empty($slug)) {
            throw new Exception('One of argument (handle or slug) must be filled in!');
        }

        $language = $args['language'] ?: Tool::getDefaultLanguage();
        $contentPage = null;

        $list = ContentPage::getList();
        $list->setLocale($language);

        if (!empty($handle)) {
            $list->addConditionParam('handle = ?', $handle);
        }

        if (!empty($slug)) {
            $list->addConditionParam('slug = ?', $slug);
        }

        $list->setLimit(1);
        $data = $list->getObjects();
        if ($list->getTotalCount() > 0) {
            $contentPage = reset($data);
        }

        if (!$contentPage instanceof ContentPage) {
            throw new TranslatableException(
                sprintf('content page with handle [%s] could not be found', $handle),
                'frontend-error-content-page-not-found',
                'CONTENT_PAGE_NOT_FOUND'
            );
        }

        return [
            'id' => $contentPage->getId(),
            'defaultLanguage' => $args['language'],
            'language' => $args['language'],
            'canonicals' => $this->getCanonicals($contentPage),
        ];
    }

    /**
     * Get canonicals with handles for all language mutations
     *
     * @param \OpenDxp\Model\DataObject\ContentPage $contentPage
     *
     * @return array
     */
    private function getCanonicals(ContentPage $contentPage): array
    {
        $canonicals = [];
        $validLanguages = Tool::getValidLanguages();

        foreach ($validLanguages as $lang) {
            if ($contentPage instanceof SlugAwareInterface) {
                $handle = $contentPage->getHandle($lang);
                $slug = $contentPage->getSlug($lang);

                if (!empty($handle) || !empty($slug)) {
                    $canonicals[] = [
                        'language' => $lang,
                        'handle' => $handle,
                        'slug' => $slug,
                    ];
                }
            }
        }

        return $canonicals;
    }
}
