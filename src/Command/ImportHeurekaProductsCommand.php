<?php

declare(strict_types=1);

namespace App\Command;

use App\Enum\ProductStatusEnum;
use App\OpenDxp\Helpers\VersionHelper;
use OpenDxp\Model\Asset;
use OpenDxp\Model\DataObject\AbstractObject;
use OpenDxp\Model\DataObject\Fieldcollection;
use OpenDxp\Model\DataObject\Fieldcollection\Data\Price;
use OpenDxp\Model\DataObject\Data\Hotspotimage;
use OpenDxp\Model\DataObject\Data\ImageGallery;
use OpenDxp\Model\DataObject\PriceList;
use OpenDxp\Model\DataObject\Product;
use OpenDxp\Model\DataObject\Service;
use OpenDxp\Tool;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-heureka-products',
    description: 'Import products from Heureka XML export (skips filters)'
)]
class ImportHeurekaProductsCommand extends Command
{
    private const SKIP_CATEGORY = 'Bazénové filtrace';
    private const CHEMISTRY_CATEGORY = 'Bazénová chemie';
    private const XML_PATH = 'public/heurekaProducts.xml';
    private const PRODUCT_FOLDER = 'Products/Import';
    private const ASSET_FOLDER_CHEMISTRY = 'Products/Chemistry';
    private const ASSET_FOLDER_PRODUCT = 'Products/Import';

    protected function configure(): void
    {
        $this
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Preview import without saving')
            ->addOption('limit', null, InputOption::VALUE_REQUIRED, 'Limit number of products to import');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dryRun = $input->getOption('dry-run');
        $limit = $input->getOption('limit') ? (int) $input->getOption('limit') : null;

        $xmlPath = OPENDXP_PROJECT_ROOT . '/' . self::XML_PATH;
        if (!file_exists($xmlPath)) {
            $io->error('XML file not found: ' . $xmlPath);
            return Command::FAILURE;
        }

        $czkPriceList = $this->findCzkPriceList();
        if (!$czkPriceList) {
            $io->warning('No CZK PriceList found. Prices will be skipped.');
        }

        $basePriceList = $this->findBasePriceList();
        if (!$basePriceList) {
            $io->warning('No base PriceList found. Products may fail validation.');
        } elseif ($czkPriceList && $basePriceList->getId() === $czkPriceList->getId()) {
            $basePriceList = null; // same list, no need to add twice
        }

        $xml = simplexml_load_file($xmlPath);
        if (!$xml) {
            $io->error('Failed to parse XML file');
            return Command::FAILURE;
        }

        $items = $this->filterItems($xml);
        $io->info(sprintf('Found %d products to import (filtered out filters)', count($items)));

        // group by ITEMGROUP_ID for variant detection
        $groups = $this->groupItems($items);
        $singleCount = count(array_filter($groups, fn($g) => count($g) === 1));
        $variantGroupCount = count(array_filter($groups, fn($g) => count($g) > 1));
        $io->info(sprintf('Single products: %d, Variant groups: %d', $singleCount, $variantGroupCount));

        if ($dryRun) {
            $this->previewAll($io, $groups, $czkPriceList);
            $io->success('[DRY RUN] Preview complete. No changes made.');
            return Command::SUCCESS;
        }

        $imported = 0;
        $errors = 0;

        foreach ($groups as $groupId => $groupItems) {
            if ($limit !== null && $imported >= $limit) {
                break;
            }

            try {
                if (count($groupItems) === 1) {
                    $this->importSingleProduct($io, $groupItems[0], $czkPriceList, $basePriceList);
                    $imported++;
                } else {
                    $this->importVariantGroup($io, $groupItems, $czkPriceList, $basePriceList);
                    $imported++;
                }
            } catch (\Throwable $e) {
                $errors++;
                $io->error(sprintf('Failed group %s: %s', $groupId, $e->getMessage()));
            }
        }

        $io->success(sprintf('Import complete. Groups imported: %d, Errors: %d', $imported, $errors));

        return Command::SUCCESS;
    }

    private function filterItems(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->SHOPITEM as $item) {
            $category = (string) $item->CATEGORYTEXT;
            if (str_contains($category, self::SKIP_CATEGORY)) {
                continue;
            }
            $items[] = $item;
        }
        return $items;
    }

    /**
     * Group items by ITEMGROUP_ID. Items without group or unique group = standalone.
     * @return array<string, \SimpleXMLElement[]>
     */
    private function groupItems(array $items): array
    {
        $groups = [];
        foreach ($items as $item) {
            $groupId = (string) $item->ITEMGROUP_ID;
            $itemId = (string) $item->ITEM_ID;
            $key = $groupId ?: $itemId;
            $groups[$key][] = $item;
        }
        return $groups;
    }

    private function isChemistry(\SimpleXMLElement $item): bool
    {
        return str_contains((string) $item->CATEGORYTEXT, self::CHEMISTRY_CATEGORY);
    }

    private function getAssetFolder(string $productType): string
    {
        return $productType === 'chemistry' ? self::ASSET_FOLDER_CHEMISTRY : self::ASSET_FOLDER_PRODUCT;
    }

    // ── Preview ──

    private function previewAll(SymfonyStyle $io, array $groups, ?PriceList $priceList): void
    {
        foreach ($groups as $groupId => $groupItems) {
            if (count($groupItems) === 1) {
                $this->previewProduct($io, $groupItems[0], $priceList);
            } else {
                $this->previewVariantGroup($io, $groupItems, $priceList);
            }
        }
    }

    private function previewProduct(SymfonyStyle $io, \SimpleXMLElement $item, ?PriceList $priceList): void
    {
        $productType = $this->isChemistry($item) ? 'chemistry' : 'product';
        $io->section(sprintf('Product: %s', (string) $item->PRODUCTNAME));
        $io->definitionList(
            ['SKU' => (string) $item->ITEM_ID],
            ['Type' => $productType],
            ['Price' => $this->parsePrice((string) $item->PRICE_VAT) . ' CZK (with VAT)'],
            ['Images' => count($this->collectImageUrls($item)) . ' → ' . $this->getAssetFolder($productType)],
            ['PriceList' => $priceList ? $priceList->getName() : 'N/A'],
            ['Folder' => self::PRODUCT_FOLDER],
        );
    }

    private function previewVariantGroup(SymfonyStyle $io, array $items, ?PriceList $priceList): void
    {
        $parsed = $this->parseVariantGroup($items);
        $productType = $this->isChemistry($items[0]) ? 'chemistry' : 'product';

        $io->section(sprintf('Variant Group: %s', $parsed['masterTitle']));
        $io->definitionList(
            ['Master title' => $parsed['masterTitle']],
            ['Type' => $productType],
            ['Variants' => count($parsed['variants'])],
            ['Images (master)' => count($this->collectImageUrls($items[0])) . ' → ' . $this->getAssetFolder($productType)],
            ['PriceList' => $priceList ? $priceList->getName() : 'N/A'],
        );

        $rows = [];
        foreach ($parsed['variants'] as $v) {
            $rows[] = [$v['itemId'], $v['variantValue'], $this->parsePrice((string) $v['item']->PRICE_VAT) . ' CZK'];
        }
        $io->table(['SKU', 'Variant', 'Price'], $rows);
    }

    // ── Import ──

    private function importSingleProduct(SymfonyStyle $io, \SimpleXMLElement $item, ?PriceList $priceList, ?PriceList $basePriceList): void
    {
        $sku = (string) $item->ITEM_ID;
        $name = (string) $item->PRODUCTNAME;
        $productType = $this->isChemistry($item) ? 'chemistry' : 'product';

        $parentFolder = Service::createFolderByPath(self::PRODUCT_FOLDER);
        $key = Service::getValidKey($this->slugify($name) . '-' . $sku, 'object');
        $path = $parentFolder->getFullPath() . '/' . $key;

        $existing = Product::getByPath($path);
        $isUpdate = $existing instanceof Product;
        $product = $isUpdate ? $existing : $this->createBaseProduct($item, $sku, $name, $productType, $priceList, $basePriceList);

        if ($isUpdate) {
            $this->updateProductData($product, $item, $sku, $name, $productType, $priceList, $basePriceList);
        }

        $product->setParent($parentFolder);
        $product->setKey($key);

        $imageGallery = $this->downloadImages($io, $item, $productType);
        if ($imageGallery) {
            $product->setImageGallery($imageGallery);
        }

        VersionHelper::useVersioning(fn() => $product->save(), false);
        $io->text(sprintf('  %s: %s [%s]', $isUpdate ? 'Updated' : 'Imported', $name, $productType));
    }

    private function importVariantGroup(SymfonyStyle $io, array $items, ?PriceList $priceList, ?PriceList $basePriceList): void
    {
        $parsed = $this->parseVariantGroup($items);
        $masterTitle = $parsed['masterTitle'];
        $productType = $this->isChemistry($items[0]) ? 'chemistry' : 'product';
        $firstItem = $items[0];
        $groupId = (string) $firstItem->ITEMGROUP_ID;

        $masterKey = Service::getValidKey($this->slugify($masterTitle) . '-' . $groupId, 'object');
        $parentFolder = Service::createFolderByPath(self::PRODUCT_FOLDER);
        $masterPath = $parentFolder->getFullPath() . '/' . $masterKey;

        $existingMaster = Product::getByPath($masterPath);
        $isUpdate = $existingMaster instanceof Product;
        $master = $isUpdate ? $existingMaster : $this->createBaseProduct($firstItem, $groupId, $masterTitle, $productType, $priceList, $basePriceList);

        if ($isUpdate) {
            $this->updateProductData($master, $firstItem, $groupId, $masterTitle, $productType, $priceList, $basePriceList);
        }

        $master->setParent($parentFolder);
        $master->setKey($masterKey);

        $imageGallery = $this->downloadImages($io, $firstItem, $productType);
        if ($imageGallery) {
            $master->setImageGallery($imageGallery);
        }

        VersionHelper::useVersioning(fn() => $master->save(), false);
        $io->text(sprintf('  Master %s: %s [%s]', $isUpdate ? '(updated)' : '(created)', $masterTitle, $productType));

        // variants
        foreach ($parsed['variants'] as $v) {
            $variantItem = $v['item'];
            $variantSku = $v['itemId'];
            $variantValue = $v['variantValue'];

            $variantKey = Service::getValidKey($this->slugify($variantValue) . '-' . $variantSku, 'object');
            $variantPath = $master->getFullPath() . '/' . $variantKey;
            $existingVariant = Product::getByPath($variantPath);
            $isVariantUpdate = $existingVariant instanceof Product;

            $variant = $isVariantUpdate ? $existingVariant : new Product();

            $variant->setPublished(false);
            $variant->setStatus(ProductStatusEnum::DRAFT->value);
            $variant->setProductType($productType);
            $variant->setSku($variantSku);
            $this->setLocalizedFields($variant, $variantValue, (string) $variantItem->DESCRIPTION);

            $price = $this->parsePrice((string) $variantItem->PRICE_VAT);
            $pricesFC = $this->buildPricesCollection($price, $priceList, $basePriceList);
            if ($pricesFC) {
                $variant->setPrices($pricesFC);
            }

            $variantImageUrls = $this->collectImageUrls($variantItem);
            $masterImageUrls = $this->collectImageUrls($firstItem);
            if ($variantImageUrls !== $masterImageUrls) {
                $variantGallery = $this->downloadImages($io, $variantItem, $productType);
                if ($variantGallery) {
                    $variant->setImageGallery($variantGallery);
                }
            }

            $variant->setParent($master);
            $variant->setType(AbstractObject::OBJECT_TYPE_VARIANT);
            $variant->setKey($variantKey);

            VersionHelper::useVersioning(fn() => $variant->save(), false);
            $io->text(sprintf('    Variant %s: %s → %s', $isVariantUpdate ? '(updated)' : '(created)', $variantSku, $variantValue));
        }
    }

    /**
     * Parse variant group: extract common master title and per-variant values.
     * Splits on last ": " occurrence in the product name.
     */
    private function parseVariantGroup(array $items): array
    {
        $variants = [];

        // find common prefix of all names
        $names = array_map(fn($i) => (string) $i->PRODUCTNAME, $items);
        $prefix = $names[0];
        foreach ($names as $name) {
            while ($prefix !== '' && !str_starts_with($name, $prefix)) {
                $prefix = mb_substr($prefix, 0, mb_strlen($prefix) - 1);
            }
        }

        // clean up prefix: trim to last ": " boundary for clean master title
        $lastColon = mb_strrpos($prefix, ':');
        if ($lastColon !== false) {
            $masterTitle = trim(mb_substr($prefix, 0, $lastColon));
        } else {
            $masterTitle = trim($prefix);
        }

        foreach ($items as $item) {
            $fullName = (string) $item->PRODUCTNAME;
            // extract variant value: everything after the last ": "
            $lastColonPos = mb_strrpos($fullName, ':');
            $variantValue = $lastColonPos !== false
                ? trim(mb_substr($fullName, $lastColonPos + 1))
                : trim(mb_substr($fullName, mb_strlen($prefix)));

            $variants[] = [
                'itemId' => (string) $item->ITEM_ID,
                'variantValue' => $variantValue,
                'item' => $item,
            ];
        }

        return [
            'masterTitle' => $masterTitle,
            'variants' => $variants,
        ];
    }

    // ── Shared helpers ──

    private function updateProductData(
        Product $product,
        \SimpleXMLElement $item,
        string $sku,
        string $title,
        string $productType,
        ?PriceList $priceList,
        ?PriceList $basePriceList
    ): void {
        $product->setStatus(ProductStatusEnum::DRAFT->value);
        $product->setProductType($productType);
        $product->setSku($sku);
        $this->setLocalizedFields($product, $title, (string) $item->DESCRIPTION);

        $price = $this->parsePrice((string) $item->PRICE_VAT);
        $pricesFC = $this->buildPricesCollection($price, $priceList, $basePriceList);
        if ($pricesFC) {
            $product->setPrices($pricesFC);
        }
    }

    private function createBaseProduct(
        \SimpleXMLElement $item,
        string $sku,
        string $title,
        string $productType,
        ?PriceList $priceList,
        ?PriceList $basePriceList
    ): Product {
        $product = new Product();
        $product->setPublished(false);
        $product->setStatus(ProductStatusEnum::DRAFT->value);
        $product->setProductType($productType);
        $product->setSku($sku);
        $this->setLocalizedFields($product, $title, (string) $item->DESCRIPTION);

        $price = $this->parsePrice((string) $item->PRICE_VAT);
        $pricesFC = $this->buildPricesCollection($price, $priceList, $basePriceList);
        if ($pricesFC) {
            $product->setPrices($pricesFC);
        }

        return $product;
    }

    /**
     * Set title/description for cs. Set title for all other languages
     * so SlugGenerator doesn't crash on getName() fallback.
     */
    private function setLocalizedFields(Product $product, string $title, string $description): void
    {
        foreach (Tool::getValidLanguages() as $language) {
            $product->setTitle($title, $language);
        }
        $product->setDescription($description, 'cs');
    }

    private function downloadImages(SymfonyStyle $io, \SimpleXMLElement $item, string $productType): ?ImageGallery
    {
        $urls = $this->collectImageUrls($item);
        if (empty($urls)) {
            return null;
        }

        $assetFolderPath = '/' . $this->getAssetFolder($productType);
        $assetFolder = Asset\Service::createFolderByPath($assetFolderPath);
        $itemId = (string) $item->ITEM_ID;
        $productName = (string) $item->PRODUCTNAME;

        $hotspotImages = [];

        foreach ($urls as $index => $url) {
            try {
                $imageData = $this->downloadFile($url);
                if ($imageData === null) {
                    $io->warning(sprintf('  Failed to download: %s', $url));
                    continue;
                }

                $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                $filename = sprintf('%s-%s-%d.%s', $this->slugify($productName), $itemId, $index + 1, $extension);
                $filename = Asset\Service::getValidKey($filename, 'asset');

                $existingAsset = Asset::getByPath($assetFolder->getFullPath() . '/' . $filename);
                if ($existingAsset) {
                    $hotspotImages[] = new Hotspotimage($existingAsset);
                    continue;
                }

                $asset = new Asset\Image();
                $asset->setParent($assetFolder);
                $asset->setFilename($filename);
                $asset->setData($imageData);
                $asset->save();

                $hotspotImages[] = new Hotspotimage($asset);
            } catch (\Throwable $e) {
                $io->warning(sprintf('  Image error (%s): %s', $url, $e->getMessage()));
            }
        }

        return !empty($hotspotImages) ? new ImageGallery($hotspotImages) : null;
    }

    private function downloadFile(string $url): ?string
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; PimcoreImporter/1.0)',
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($data === false || $httpCode !== 200) {
            return null;
        }

        return $data;
    }

    private function collectImageUrls(\SimpleXMLElement $item): array
    {
        $urls = [];

        $mainImg = (string) $item->IMGURL;
        if ($mainImg) {
            $urls[] = $this->fixImageUrl($mainImg);
        }

        foreach ($item->IMGURL_ALTERNATIVE as $altImg) {
            $url = (string) $altImg;
            if ($url) {
                $urls[] = $this->fixImageUrl($url);
            }
        }

        return $urls;
    }

    /**
     * Use spa3d.cz direct /orig/ URLs — CDN serves watermarked/cropped versions.
     */
    private function fixImageUrl(string $url): string
    {
        return str_replace('https://cdn.myshoptet.com/usr/www.spa3d.cz', 'https://www.spa3d.cz', $url);
    }

    private function parsePrice(string $priceStr): float
    {
        $cleaned = str_replace([' ', "\xc2\xa0"], '', $priceStr);
        $cleaned = str_replace(',', '.', $cleaned);
        return (float) $cleaned;
    }

    private function buildPricesCollection(float $czkPrice, ?PriceList $czkPriceList, ?PriceList $basePriceList): ?Fieldcollection
    {
        $fc = new Fieldcollection();
        $hasItems = false;

        if ($basePriceList) {
            $basePrice = new Price();
            $basePrice->setPriceList($basePriceList);
            $basePrice->setPrice(0);
            $fc->add($basePrice);
            $hasItems = true;
        }

        if ($czkPriceList && $czkPrice > 0) {
            $priceFC = new Price();
            $priceFC->setPriceList($czkPriceList);
            $priceFC->setPrice($czkPrice);
            $fc->add($priceFC);
            $hasItems = true;
        }

        return $hasItems ? $fc : null;
    }

    private function findCzkPriceList(): ?PriceList
    {
        $listing = PriceList::getList();
        $listing->setCondition('currency = ?', ['CZK']);
        $listing->setLimit(1);
        $results = $listing->load();

        return $results[0] ?? null;
    }

    private function findBasePriceList(): ?PriceList
    {
        $listing = PriceList::getList();
        $listing->setCondition('basePricelist = 1');
        $listing->setLimit(1);
        $results = $listing->load();

        return $results[0] ?? null;
    }

    private function slugify(string $text): string
    {
        $text = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $text = trim($text, '-');
        if (strlen($text) > 60) {
            $text = substr($text, 0, 60);
            $text = rtrim($text, '-');
        }
        return $text;
    }
}
