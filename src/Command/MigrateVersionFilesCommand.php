<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;

#[AsCommand(
    name: 'app:migrate-version-files',
    description: 'Migrate serialized version files from pimcore to opendxp namespace',
)]
class MigrateVersionFilesCommand extends Command
{
    private const SEARCH = 'Pimcore\\';
    private const REPLACE = 'OpenDxp\\';

    private const VERSIONS_DIR = OPENDXP_PROJECT_ROOT . '/var/versions';

    protected function configure(): void
    {
        $this
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Preview only')
            ->addOption('path', 'p', InputOption::VALUE_REQUIRED, 'Alternative path (default var/versions)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dryRun = (bool) $input->getOption('dry-run');
        $basePath = $input->getOption('path') ?: self::VERSIONS_DIR;

        if (!is_dir($basePath)) {
            $io->error(sprintf('Path "%s" does not exist.', $basePath));
            return Command::FAILURE;
        }

        if ($dryRun) {
            $io->note('Dry-run mode — no files will be modified.');
        }

        $io->title('Version files migration: Pimcore → OpenDxp');
        $io->text(sprintf('Searching: %s', $basePath));

        $finder = new Finder();
        $finder->files()->in($basePath)->sortByName();

        $total = 0;
        $migrated = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($finder as $file) {
            $total++;
            $filePath = $file->getRealPath();

            if (stripos($filePath, '.bin') !== false) {
                $skipped++;
                continue;
            }

            try {
                $content = file_get_contents($filePath);
                if ($content === false) {
                    $errors++;
                    continue;
                }

                if (!str_contains($content, self::SEARCH)) {
                    $skipped++;
                    continue;
                }

                // "Pimcore" and "OpenDxp" are both 7 chars — serialized lengths stay valid
                $migratedContent = str_replace(self::SEARCH, self::REPLACE, $content);

                if (!$dryRun && file_put_contents($filePath, $migratedContent) === false) {
                    $errors++;
                    continue;
                }

                $migrated++;
                if ($io->isVerbose()) {
                    $io->text(sprintf('  ✓ %s', $filePath));
                }
            } catch (\Throwable $e) {
                $io->error(sprintf('Error at %s: %s', $filePath, $e->getMessage()));
                $errors++;
            }
        }

        $io->newLine();
        $io->definitionList(
            ['Total' => $total],
            ['Migrated' => $migrated],
            ['Skipped' => $skipped],
            ['Errors' => $errors],
        );

        if ($errors > 0) {
            $io->warning('Errors occurred — see above.');
            return Command::FAILURE;
        }

        $io->success(sprintf(
            '%d of %d files %s.',
            $migrated,
            $total,
            $dryRun ? 'would be migrated' : 'migrated',
        ));

        return Command::SUCCESS;
    }
}
