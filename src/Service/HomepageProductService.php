<?php

declare(strict_types=1);

namespace App\Service;

use OpenDxp\Model\DataObject\Fieldcollection\Data\ProductGrid;
use OpenDxp\Model\DataObject\Product;

class HomepageProductService
{
    public function resolveProducts(ProductGrid $tab, string $language, int $max): array
    {
        $manualProducts = $this->getManualProducts($tab);

        if (!$tab->getAutoFill() || count($manualProducts) >= $max) {
            return array_slice($manualProducts, 0, $max);
        }

        $remaining = $max - count($manualProducts);
        $excludeIds = array_map(fn(Product $p) => $p->getId(), $manualProducts);

        $autoProducts = $this->getAutoProducts($tab, $excludeIds, $remaining);

        return array_merge($manualProducts, $autoProducts);
    }

    private function getManualProducts(ProductGrid $tab): array
    {
        $products = $tab->getManualProducts() ?? [];

        return array_filter($products, fn($p) => $p instanceof Product && $p->isPublished());
    }

    private function getAutoProducts(ProductGrid $tab, array $excludeIds, int $limit): array
    {
        $listing = new Product\Listing();
        $conditions = ['published = 1', "type = 'object'"];
        $params = [];

        if (!empty($excludeIds)) {
            $placeholders = implode(',', array_fill(0, count($excludeIds), '?'));
            $conditions[] = "oo_id NOT IN ($placeholders)";
            $params = array_merge($params, $excludeIds);
        }

        $flag = $tab->getFillByFlag();
        if ($flag) {
            $conditions[] = 'oo_id IN (SELECT src_id FROM object_relations_product WHERE dest_id = ? AND fieldname = ?)';
            $params[] = $flag->getId();
            $params[] = 'flags';
        }

        $category = $tab->getFillFromCategory();
        if ($category) {
            $conditions[] = 'oo_id IN (SELECT src_id FROM object_relations_product WHERE dest_id = ? AND fieldname = ?)';
            $params[] = $category->getId();
            $params[] = 'collections';
        }

        if ($tab->getFillOnlyAvailable()) {
            $conditions[] = "status = 'active'";
        }

        $listing->setCondition(implode(' AND ', $conditions), $params);

        if ($tab->getFillByAge()) {
            $listing->setOrderKey('creationDate');
            $listing->setOrder('DESC');
        } elseif ($tab->getFillRandom()) {
            $listing->setOrderKey('RAND()', false);
        }

        $listing->setLimit($limit);

        return $listing->load();
    }
}
