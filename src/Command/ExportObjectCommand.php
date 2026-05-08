<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\ObjectExporter;
use OpenDxp\Model\DataObject\Concrete;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:object:export',
    description: 'Export a Pimcore DataObject (any class) to a portable JSON file. Pass numeric ID or full path; multiple targets allowed.'
)]
class ExportObjectCommand extends Command
{
    public function __construct(private readonly ObjectExporter $exporter)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('targets', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'Object IDs or full paths (e.g. 2837 /Website/ContentPages/Home/Contacts)')
            ->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Output directory (default: var/exports)', 'var/exports')
            ->addOption('stdout', null, InputOption::VALUE_NONE, 'Print to stdout instead of writing files')
            ->addOption('languages', null, InputOption::VALUE_OPTIONAL, 'Comma-separated language codes (default: sk,cs,en,de,pl,hu)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $targets = (array) $input->getArgument('targets');
        $stdout = (bool) $input->getOption('stdout');
        $outputDir = (string) $input->getOption('output');
        $languagesOption = $input->getOption('languages');

        if (is_string($languagesOption) && $languagesOption !== '') {
            $exporter = new ObjectExporter(array_map('trim', explode(',', $languagesOption)));
        } else {
            $exporter = $this->exporter;
        }

        if (!$stdout) {
            if (!is_dir($outputDir) && !@mkdir($outputDir, 0775, true) && !is_dir($outputDir)) {
                $io->error(sprintf('Cannot create output directory "%s"', $outputDir));

                return Command::FAILURE;
            }
        }

        $exitCode = Command::SUCCESS;

        foreach ($targets as $target) {
            $object = $this->resolveTarget($target);
            if (!$object) {
                $io->error(sprintf('Object not found: %s', $target));
                $exitCode = Command::FAILURE;

                continue;
            }

            $payload = $exporter->export($object);
            $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            if ($json === false) {
                $io->error(sprintf('Failed to JSON-encode export for %s', $target));
                $exitCode = Command::FAILURE;

                continue;
            }

            if ($stdout) {
                $output->writeln($json);

                continue;
            }

            $filename = sprintf(
                '%s/%s_%s_%d.json',
                rtrim($outputDir, '/'),
                strtolower((string) $object->getClassName()),
                $this->slug($object->getKey()),
                $object->getId()
            );
            if (file_put_contents($filename, $json) === false) {
                $io->error(sprintf('Failed to write file %s', $filename));
                $exitCode = Command::FAILURE;

                continue;
            }
            $io->success(sprintf('Exported %s [%d] → %s', $object->getFullPath(), $object->getId(), $filename));
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

    private function slug(string $key): string
    {
        $slug = preg_replace('~[^A-Za-z0-9]+~', '-', $key) ?? '';

        return trim(strtolower($slug), '-') ?: 'object';
    }
}
