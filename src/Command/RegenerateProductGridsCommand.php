<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\HomepageProductService;
use OpenDxp\Model\DataObject\ContentPage;
use OpenDxp\Model\DataObject\Fieldcollection\Data\ProductGrid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:regenerate-product-grids',
    description: 'Pre-generate resolved products for all ProductGrid fieldcollections on ContentPages'
)]
class RegenerateProductGridsCommand extends Command
{
    public function __construct(
        private readonly HomepageProductService $homepageProductService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Show changes without saving')
            ->addOption('content-page-id', null, InputOption::VALUE_OPTIONAL, 'Regenerate only for specific ContentPage ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dryRun = $input->getOption('dry-run');
        $contentPageId = $input->getOption('content-page-id');

        if ($contentPageId) {
            $contentPage = ContentPage::getById((int) $contentPageId);
            if (!$contentPage instanceof ContentPage) {
                $io->error(sprintf('ContentPage %d not found', $contentPageId));
                return Command::FAILURE;
            }
            $contentPages = [$contentPage];
        } else {
            $listing = new ContentPage\Listing();
            $listing->setCondition('published = 1');
            $contentPages = $listing->load();
        }

        $totalGrids = 0;
        $totalUpdated = 0;

        foreach ($contentPages as $contentPage) {
            $elements = $contentPage->getElements();
            if ($elements === null) {
                continue;
            }

            $changed = false;

            foreach ($elements->getItems() as $item) {
                if (!$item instanceof ProductGrid) {
                    continue;
                }

                $totalGrids++;
                $tabTitle = $item->getTabTitle('sk') ?: $item->getTabTitle();
                $max = (int) ($item->getMaxProducts() ?: 12);

                $products = $this->homepageProductService->resolveProducts($item, 'sk', $max);
                $productIds = array_map(fn($p) => $p->getId(), $products);

                $currentIds = array_map(fn($p) => $p->getId(), $item->getResolvedProducts());

                if ($productIds === $currentIds) {
                    $io->text(sprintf('  [=] "%s" - no change (%d products)', $tabTitle, count($products)));
                    continue;
                }

                $io->text(sprintf('  [~] "%s" - %d -> %d products', $tabTitle, count($currentIds), count($products)));
                $totalUpdated++;

                if (!$dryRun) {
                    $item->setResolvedProducts($products);
                    $changed = true;
                }
            }

            if ($changed) {
                $contentPage->setOmitMandatoryCheck(true);
                $contentPage->save();
                $io->text(sprintf('  Saved ContentPage "%s" (ID: %d)', $contentPage->getKey(), $contentPage->getId()));
            }
        }

        $prefix = $dryRun ? '[DRY RUN] ' : '';
        $io->success(sprintf('%sProcessed %d grids, updated %d', $prefix, $totalGrids, $totalUpdated));

        return Command::SUCCESS;
    }
}
