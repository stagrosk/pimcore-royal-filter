<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\ObjectImporter;
use OpenDxp\Model\DataObject\Concrete;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:object:import',
    description: 'Import a Pimcore DataObject from a JSON export. Target object is matched by --target ID or path, fallback to source.fullpath from the file.'
)]
class ImportObjectCommand extends Command
{
    public function __construct(private readonly ObjectImporter $importer)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('files', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'JSON file paths to import')
            ->addOption('target', 't', InputOption::VALUE_OPTIONAL, 'Override target object (ID or full path). Without it, uses source.fullpath from the file.')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Apply changes in memory but do not save')
            ->addOption('publish', null, InputOption::VALUE_NONE, 'Publish the object after save (sets published=true)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $files = (array) $input->getArgument('files');
        $override = $input->getOption('target');
        $dryRun = (bool) $input->getOption('dry-run');
        $publish = (bool) $input->getOption('publish');

        $exitCode = Command::SUCCESS;

        foreach ($files as $file) {
            if (!is_file($file)) {
                $io->error(sprintf('File not found: %s', $file));
                $exitCode = Command::FAILURE;

                continue;
            }
            $raw = file_get_contents($file);
            if ($raw === false) {
                $io->error(sprintf('Cannot read file: %s', $file));
                $exitCode = Command::FAILURE;

                continue;
            }
            $data = json_decode($raw, true);
            if (!is_array($data)) {
                $io->error(sprintf('Invalid JSON: %s', $file));
                $exitCode = Command::FAILURE;

                continue;
            }

            $targetSpec = is_string($override) && $override !== ''
                ? $override
                : ($data['source']['fullpath'] ?? null);
            if (!is_string($targetSpec) || $targetSpec === '') {
                $io->error(sprintf('No target object — pass --target or include source.fullpath in %s', $file));
                $exitCode = Command::FAILURE;

                continue;
            }
            $target = $this->resolveTarget($targetSpec);
            if (!$target) {
                $io->error(sprintf('Target not found: %s', $targetSpec));
                $exitCode = Command::FAILURE;

                continue;
            }

            $io->section(sprintf(
                '%s [%s] → %s [%d] (%s)',
                basename($file),
                $data['source']['fullpath'] ?? '?',
                $target->getFullPath(),
                $target->getId(),
                $target->getClassName()
            ));

            try {
                $this->importer->import($target, $data);
            } catch (\Throwable $e) {
                $io->error(sprintf('Import failed: %s', $e->getMessage()));
                $exitCode = Command::FAILURE;

                continue;
            }

            foreach ($this->importer->warnings as $w) {
                $io->warning($w);
            }

            if ($dryRun) {
                $io->note('[dry-run] not saving');

                continue;
            }

            if ($publish) {
                $target->setPublished(true);
            }

            try {
                $target->save();
                $io->success(sprintf('Saved %s [%d]', $target->getFullPath(), $target->getId()));
            } catch (\Throwable $e) {
                $io->error(sprintf('Save failed: %s', $e->getMessage()));
                $exitCode = Command::FAILURE;
            }
        }

        return $exitCode;
    }

    private function resolveTarget(string $target): ?Concrete
    {
        if (ctype_digit($target)) {
            return Concrete::getById((int) $target);
        }
        if (str_starts_with($target, '/')) {
            return Concrete::getByPath($target);
        }

        return null;
    }
}
