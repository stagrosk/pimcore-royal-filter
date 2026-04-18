<?php

declare(strict_types=1);

namespace App\Command;

use App\Vendure\WebhookClient;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\ProductFlag;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:manage-product-news',
    description: 'Automatically manage "novinka" flag on products based on creation date'
)]
class ManageProductNewsCommand extends Command
{
    public function __construct(
        private readonly WebhookClient $webhookClient
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('dry-run', null, InputOption::VALUE_NONE, 'Show changes without applying them');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dryRun = $input->getOption('dry-run');

        $novinkaFlag = $this->findNovinkaFlag();
        if (!$novinkaFlag) {
            $io->error('ProductFlag with code "novinka" not found');
            return Command::FAILURE;
        }

        $newsForDays = (int) ($novinkaFlag->getNewsForDays() ?: 14);
        $cutoff = time() - ($newsForDays * 86400);

        $io->info(sprintf('Using newsForDays=%d (cutoff: %s)', $newsForDays, date('Y-m-d H:i:s', $cutoff)));

        $listing = new Product\Listing();
        $listing->setCondition('published = 1 AND type = ?', ['object']);
        $products = $listing->load();

        $added = 0;
        $removed = 0;

        foreach ($products as $product) {
            $hasFlag = $this->hasFlag($product, $novinkaFlag);

            if ($product->getCreationDate() >= $cutoff && !$hasFlag) {
                $io->text(sprintf('+ Adding flag to: %s (created %s)', $product->getFullPath(), date('Y-m-d', $product->getCreationDate())));

                if (!$dryRun) {
                    $flags = $product->getFlags();
                    $flags[] = $novinkaFlag;
                    $product->setFlags($flags);
                    $product->save();
                    $this->sendWebhook($product);
                }
                $added++;
            } elseif ($product->getCreationDate() < $cutoff && $hasFlag) {
                $io->text(sprintf('- Removing flag from: %s (created %s)', $product->getFullPath(), date('Y-m-d', $product->getCreationDate())));

                if (!$dryRun) {
                    $flags = array_filter($product->getFlags(), fn($f) => $f->getId() !== $novinkaFlag->getId());
                    $product->setFlags(array_values($flags));
                    $product->save();
                    $this->sendWebhook($product);
                }
                $removed++;
            }
        }

        $prefix = $dryRun ? '[DRY RUN] ' : '';
        $io->success(sprintf('%sAdded: %d, Removed: %d', $prefix, $added, $removed));

        return Command::SUCCESS;
    }

    private function findNovinkaFlag(): ?ProductFlag
    {
        $listing = ProductFlag::getList();
        $listing->setCondition('code = ?', ['novinka']);
        $listing->setLimit(1);
        $flags = $listing->load();

        return $flags[0] ?? null;
    }

    private function hasFlag(Product $product, ProductFlag $flag): bool
    {
        foreach ($product->getFlags() as $f) {
            if ($f->getId() === $flag->getId()) {
                return true;
            }
        }
        return false;
    }

    private function sendWebhook(Product $product): void
    {
        $this->webhookClient->sendToVendureWebhook([
            'class' => Product::class,
            'type' => $product->getType(),
            'id' => $product->getId(),
            'action' => 'update',
        ]);
    }
}
