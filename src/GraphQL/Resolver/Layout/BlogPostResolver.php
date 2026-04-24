<?php

namespace App\GraphQL\Resolver\Layout;

use App\Exception\TranslatableException;
use App\GraphQL\Resolver\AbstractResolver;
use GraphQL\Type\Definition\ResolveInfo;
use OpenDxp\Model\DataObject\BlogPost;
use OpenDxp\Tool;
use OpendxpHeadlessContentBundle\Model\SlugAwareInterface;

class BlogPostResolver extends AbstractResolver
{
    public function resolve($source, $args, $context, ResolveInfo $info): array
    {
        $handle = $args['handle'] ?? '';
        $slug = $args['slug'] ?? '';

        if (empty($handle) && empty($slug)) {
            throw new \Exception('One of argument (handle or slug) must be filled in!');
        }

        $language = $args['language'] ?: Tool::getDefaultLanguage();

        $list = BlogPost::getList();
        $list->setLocale($language);
        $list->addConditionParam('status = ?', 'published');

        if (!empty($handle)) {
            $list->addConditionParam('handle = ?', $handle);
        }

        if (!empty($slug)) {
            $list->addConditionParam('slug = ?', $slug);
        }

        $list->setLimit(1);

        $data = $list->getObjects();
        $blogPost = $list->getTotalCount() > 0 ? reset($data) : null;

        if (!$blogPost instanceof BlogPost) {
            throw new TranslatableException(
                sprintf('Blog post with handle [%s] / slug [%s] could not be found', $handle, $slug),
                'frontend-error-blog-post-not-found',
                'BLOG_POST_NOT_FOUND'
            );
        }

        return [
            'id' => $blogPost->getId(),
            'defaultLanguage' => $language,
            'language' => $language,
            'canonicals' => $this->getCanonicals($blogPost),
        ];
    }

    private function getCanonicals(BlogPost $blogPost): array
    {
        $canonicals = [];

        foreach (Tool::getValidLanguages() as $lang) {
            $slug = $blogPost->getSlug($lang);
            $handle = $blogPost->getHandle($lang);
            if (!empty($handle) || !empty($slug)) {
                $canonicals[] = [
                    'language' => $lang,
                    'handle' => $handle,
                    'slug' => $slug,
                ];
            }
        }

        return $canonicals;
    }
}
