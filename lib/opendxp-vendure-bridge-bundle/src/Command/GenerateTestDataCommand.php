<?php

namespace OpendxpVendureBridgeBundle\Command;

use OpenDxp\Model\Asset\Listing;
use OpenDxp\Model\DataObject\Category;
use OpenDxp\Model\DataObject\Objectbrick\Data\ProductAttributes;
use OpenDxp\Model\DataObject\Price;
use OpenDxp\Model\DataObject\Product;
use OpenDxp\Model\DataObject\Service;
use OpenDxp\Tool;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateTestDataCommand extends Command
{
    public const ARG_COUNT = 'arg_count';
    public const IMAGES_FOLDER = 'images';

    private array $categoryNames = [
        'category1',
        'category2',
        'category3' => [
            'subCategory3-1',
            'subCategory3-2',
        ],
        'category4',
        'category5' => [
            'subCategory5-1',
            'subCategory5-2',
            'subCategory5-3',
            'subCategory5-4',
        ],
        'category6',
    ];

    private array $colors = [
        'red', 'black', 'orange', 'white', 'yellow', 'green'
    ];

    protected function configure()
    {
        $this
            ->setName('pimcore-vendure:data:generate')
            ->setDescription('Generate test data')
            ->addOption(
                self::ARG_COUNT,
                'c',
                InputOption::VALUE_REQUIRED,
                'Count of test data to be generated',
                null
            );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Exception
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = $input->getOption(self::ARG_COUNT);

        $taxes = [
            'tax0', 'tax1', 'tax2', 'tax3'
        ];

        $categories = $this->generateCategories();
        $images = $this->getImages();

        if (empty($images)) {
            $output->writeln('Before start please add folder "images" in assets (administration) and put there few images -> this images will be random assigned to generated products');
            return 1;
        }

        for ($i = 0; $i < $count; $i++) {
            $parentProduct = $this->generateProduct($categories, $taxes, $images);
            for ($x = 0; $x < rand(0, 3); $x++) {
                $this->generateProduct($categories, $taxes, $images, $parentProduct);
            }
        }

        return 0;
    }

    /**
     * @param array $categories
     * @param array $taxes
     * @param array $images
     * @param \OpenDxp\Model\DataObject\Product|null $parentProduct
     *
     * @throws \Exception
     * @return \OpenDxp\Model\DataObject\Product
     */
    private function generateProduct(
        array    $categories,
        array    $taxes,
        array    $images,
        ?Product $parentProduct = null
    ): Product {
        $productName = 'Product-' . uniqid();

        $productFolder = Service::createFolderByPath('/products/');
        $key = Service::getValidKey($productName, 'object');
        if ($parentProduct instanceof Product) {
            $productPath = sprintf('%s/%s', $parentProduct->getFullPath(), $key);
        } else {
            $productPath = sprintf('%s/%s', $productFolder->getFullPath(), $key);
        }

        $product = new Product();
        $product->setPublished(true);
        if ($parentProduct instanceof Product) {
            $product->setParent($parentProduct);
        } else {
            $product->setParent($productFolder);
        }

        $product->setKey($key);
        $product->setEnabled(rand(0, 1) === 1);

        foreach (Tool::getValidLanguages() as $language) {
            $product->setName(sprintf('%s - %s', $language, $productName), $language);
            $description = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book';
            $product->setDescription(sprintf('%s - %s', $language, $description), $language);
        }

        $count = rand(1, ceil(count($categories)/3));
        $categoryRandomIds = $this->generateRandomUniqueKeys($count, array_keys($categories));

        $productCategories = [];
        foreach ($categoryRandomIds as $categoryRandomId) {
            $productCategories[] = $categories[$categoryRandomId];
        }
        $product->setCategories($productCategories);

        // attributes
        $productAttributes = new ProductAttributes($product);
        $colorRand = rand(0, count($this->colors) - 1);
        $productAttributes->setColor($this->colors[$colorRand]);
        $productAttributes->setSize(rand(60, 180));
        $product->getAttributes()->setProductAttributes($productAttributes);

        $taxRand = rand(0, count($taxes) - 1);
        $product->setTaxCategory($taxes[$taxRand]);
        $product->save();

        $productPrices = $this->generateProductPrices($productPath);
        $product->setPrices($productPrices);

        // assets
        $this->appendRandomImages($images, $product);

        $product->save();

        return $product;
    }

    /**
     * @param array $images
     * @param \OpenDxp\Model\DataObject\Product $product
     *
     * @return void
     */
    private function appendRandomImages(array $images, Product $product): void
    {
        $productImages = [];
        $count = rand(1, count($images));
        $ids = $this->generateRandomUniqueKeys($count, array_keys($images));
        foreach ($ids as $id) {
            $productImages[] = $images[$id];
        }

        $product->setImages($productImages);
    }

    /**
     * @return array
     */
    private function getImages(): array
    {
        // get images from assets folder
        $list = new Listing();
        $list->setCondition('path like "%' . self::IMAGES_FOLDER . '%"');

        return $list->getData();
    }

    /**
     * @param int $count
     * @param array $array
     * @param array $cache
     *
     * @return array
     */
    private function generateRandomUniqueKeys(int $count, array $array, array &$cache = []): array
    {
        while (count($cache) < $count) {
            $randomId = rand(0, count($array) - 1);

            if (in_array($randomId, $cache)) {
                $this->generateRandomUniqueKeys(1, $array, $cache);
            } else {
                $cache[] = $randomId;
            }
        }

        return $cache;
    }

    /**
     * @param string $productPath
     *
     * @throws \Exception
     * @return \OpenDxp\Model\DataObject\Price[]
     */
    private function generateProductPrices(string $productPath): array
    {
        $prices = [];
        $channels = ['ch1', 'ch2', 'ch3'];
        $currencies = ['USD', 'EUR'];

        $priceFolder = Service::createFolderByPath($productPath . '/prices');

        foreach ($currencies as $currency) {
            $countPricesForCurrency = rand(0, 3);
            for ($i = 0; $i < $countPricesForCurrency; $i++) {
                $price = new Price();
                $price->setParent($priceFolder);
                $price->setKey(uniqid());
                $price->setNetPrice(rand(50, 1000));
                $price->setCurrency($currency);
                $price->setPublished(true);

                $channelRand = rand(0, count($channels) - 1);
                $price->setChannel($channelRand);
                $price->save();

                $prices[] = $price;
            }
        }

        return $prices;
    }

    /**
     * @throws \Exception
     * @return array
     */
    private function generateCategories(): array
    {
        $categories = [];
        foreach ($this->categoryNames as $parentCategoryName => $categoryItem) {
            if (is_array($categoryItem)) {
                $parentCategory = $this->createCategory($parentCategoryName);
                $categories[] = $parentCategory;
                foreach ($categoryItem as $subCategoryName) {
                    $categories[] = $this->createCategory($subCategoryName, $parentCategory);
                }
            } else {
                $categories[] = $this->createCategory($categoryItem);
            }
        }

        return $categories;
    }

    /**
     * @param string $categoryName
     * @param \OpenDxp\Model\DataObject\Category|null $categoryParent
     *
     * @throws \Exception
     * @return \OpenDxp\Model\DataObject\Category
     */
    private function createCategory(string $categoryName, ?Category $categoryParent = null): Category
    {
        $categoryFolder = Service::createFolderByPath('/categories/');
        $key = Service::getValidKey($categoryName, 'object');

        if ($categoryParent instanceof Category) {
            $categoryPath = sprintf('%s/%s', $categoryParent->getFullPath(), $key);
        } else {
            $categoryPath = sprintf('%s/%s', $categoryFolder->getFullPath(), $key);
        }
        $category = Category::getByPath($categoryPath);

        if (!$category instanceof Category) {
            $category = new Category();
            $category->setPublished(true);
            $category->setKey($key);

            if ($categoryParent instanceof Category) {
                $category->setParent($categoryParent);
            } else {
                $category->setParent($categoryFolder);
            }

            foreach (Tool::getValidLanguages() as $language) {
                $category->setName(sprintf('%s - %s', $language, $categoryName), $language);
                $description = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book';
                $category->setDescription(sprintf('%s - %s', $language, $description), $language);
            }

            $category->save();
        }

        return $category;
    }
}
