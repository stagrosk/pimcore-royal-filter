<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Same-origin proxy that returns Iconify SVGs so the admin UI can render them
 * without violating Pimcore's CSP (no script-src/img-src to api.iconify.design).
 *
 * On first hit per icon the SVG is fetched from api.iconify.design and stashed
 * under var/cache/icons/. Subsequent hits read from disk.
 */
class IconCatalogController
{
    private const ICONIFY_API = 'https://api.iconify.design/%s/%s.svg';

    public function __construct(private readonly KernelInterface $kernel)
    {
    }

    #[Route(
        path: '/admin/icon-svg/{prefix}/{name}',
        name: 'admin_icon_svg',
        requirements: ['prefix' => '[a-z0-9-]+', 'name' => '[a-z0-9-]+'],
        methods: ['GET']
    )]
    public function svg(string $prefix, string $name): Response
    {
        $cacheDir = $this->kernel->getCacheDir() . '/icons';
        $cacheFile = sprintf('%s/%s_%s.svg', $cacheDir, $prefix, $name);

        if (!is_file($cacheFile)) {
            if (!is_dir($cacheDir)) {
                @mkdir($cacheDir, 0775, true);
            }

            $svg = @file_get_contents(sprintf(self::ICONIFY_API, $prefix, $name));
            if ($svg === false || trim($svg) === '404' || $svg === '') {
                return new Response('', Response::HTTP_NOT_FOUND);
            }

            file_put_contents($cacheFile, $svg);
        }

        return new Response((string) file_get_contents($cacheFile), Response::HTTP_OK, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'public, max-age=86400, immutable',
        ]);
    }
}
