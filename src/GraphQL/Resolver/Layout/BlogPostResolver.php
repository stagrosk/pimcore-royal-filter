<?php

namespace App\GraphQL\Resolver\Layout;

use App\Exception\TranslatableException;
use App\GraphQL\Resolver\AbstractResolver;
use GraphQL\Type\Definition\ResolveInfo;
use Pimcore\Model\DataObject\BlogPost;
use Pimcore\Tool;
use PimcoreHeadlessContentBundle\Model\SlugAwareInterface;

class BlogPostResolver extends AbstractResolver
{
    public function resolve($source, $args, $context, ResolveInfo $info): array
    {
        $slug = $args['slug'] ?? '';

        if (empty($slug)) {
            throw new \Exception('Slug argument is required');
        }

        $language = $args['language'] ?: Tool::getDefaultLanguage();

        $list = BlogPost::getList();
        $list->setLocale($language);
        $list->addConditionParam('slug = ?', $slug);
        $list->addConditionParam('status = ?', 'published');
        $list->setLimit(1);

        $data = $list->getObjects();
        $blogPost = $list->getTotalCount() > 0 ? reset($data) : null;

        if (!$blogPost instanceof BlogPost) {
            throw new TranslatableException(
                sprintf('Blog post with slug [%s] could not be found', $slug),
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
            if (!empty($slug)) {
                $canonicals[] = [
                    'language' => $lang,
                    'handle' => null,
                    'slug' => $slug,
                ];
            }
        }

        return $canonicals;
    }
}
