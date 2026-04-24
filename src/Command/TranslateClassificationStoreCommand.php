<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\ClassificationStoreTranslationService;
use OpenDxp\Model\DataObject\Classificationstore\GroupConfig;
use OpenDxp\Model\DataObject\Classificationstore\KeyConfig;
use OpenDxp\Model\Translation;
use OpenDxp\Tool;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:translate-classification-store',
    description: 'Translate classification store labels using DeepL'
)]
class TranslateClassificationStoreCommand extends Command
{
    public function __construct(
        private readonly ClassificationStoreTranslationService $translationService,
        private readonly string $deeplApiKey = '',
        private readonly string $sourceLanguage = 'en'
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Show what would be translated without actually translating')
            ->addOption('language', 'l', InputOption::VALUE_OPTIONAL, 'Translate only to specific language')
            ->addOption('generate-only', 'g', InputOption::VALUE_NONE, 'Only generate translation entries without DeepL translation');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dryRun = $input->getOption('dry-run');
        $targetLanguage = $input->getOption('language');
        $generateOnly = $input->getOption('generate-only');

        $io->title('Classification Store Translation');

        // Generate-only mode: scan classification store and create translation entries
        if ($generateOnly) {
            return $this->executeGenerateOnly($io, $dryRun);
        }

        if (empty($this->deeplApiKey)) {
            $io->error('DeepL API key is not configured. Set DEEPL_API_KEY in your environment or use --generate-only.');
            return Command::FAILURE;
        }

        // Get all languages or just the specified one
        $languages = $targetLanguage ? [$targetLanguage] : Tool::getValidLanguages();
        $languages = array_filter($languages, fn($lang) => $lang !== $this->sourceLanguage);

        $io->info(sprintf('Source language: %s', $this->sourceLanguage));
        $io->info(sprintf('Target languages: %s', implode(', ', $languages)));

        // Get all classification store translations
        $listing = new Translation\Listing();
        $listing->setDomain(ClassificationStoreTranslationService::TRANSLATION_DOMAIN);
        $listing->addConditionParam(
            '`key` LIKE ? OR `key` LIKE ?',
            [
                ClassificationStoreTranslationService::PREFIX_GROUP . '%',
                ClassificationStoreTranslationService::PREFIX_KEY . '%'
            ]
        );

        $translations = $listing->load();
        $totalTranslations = count($translations);

        if ($totalTranslations === 0) {
            $io->warning('No classification store translations found. Run with --generate-only first.');
            return Command::SUCCESS;
        }

        $io->info(sprintf('Found %d classification store translation entries', $totalTranslations));

        $translated = 0;
        $skipped = 0;
        $errors = 0;

        $io->progressStart($totalTranslations * count($languages));

        foreach ($translations as $translation) {
            $sourceText = $translation->getTranslation($this->sourceLanguage);

            if (empty($sourceText)) {
                $io->progressAdvance(count($languages));
                $skipped += count($languages);
                continue;
            }

            foreach ($languages as $language) {
                $existingTranslation = $translation->getTranslation($language);

                // Skip if already translated (different from source)
                if (!empty($existingTranslation) && $existingTranslation !== $sourceText) {
                    $io->progressAdvance();
                    $skipped++;
                    continue;
                }

                if ($dryRun) {
                    $io->progressAdvance();
                    $translated++;
                    continue;
                }

                try {
                    $translatedText = $this->translateWithDeepL($sourceText, $language);

                    if ($translatedText) {
                        $translation->addTranslation($language, $translatedText);
                        $translated++;
                    } else {
                        $errors++;
                    }
                } catch (\Exception $e) {
                    $io->error(sprintf('Error translating "%s" to %s: %s', $sourceText, $language, $e->getMessage()));
                    $errors++;
                }

                $io->progressAdvance();
            }

            // Save translation after all languages are processed
            if (!$dryRun) {
                $translation->save();
            }
        }

        $io->progressFinish();

        $io->newLine(2);

        if ($dryRun) {
            $io->note('DRY RUN - No changes were made');
        }

        $io->success(sprintf(
            'Translation complete: %d translated, %d skipped, %d errors',
            $translated,
            $skipped,
            $errors
        ));

        return Command::SUCCESS;
    }

    /**
     * Generate translation entries for all classification store groups and keys
     */
    private function executeGenerateOnly(SymfonyStyle $io, bool $dryRun): int
    {
        $io->info('Scanning classification store for groups and keys...');

        // Load existing translation keys from database
        $existingKeys = $this->getExistingTranslationKeys();
        $io->info(sprintf('Found %d existing translation entries in database', count($existingKeys)));

        $created = 0;
        $skipped = 0;

        // Get all groups
        $groupListing = new GroupConfig\Listing();
        $groups = $groupListing->load();

        $io->info(sprintf('Found %d groups in classification store', count($groups)));

        foreach ($groups as $group) {
            $groupName = $group->getName();
            $groupTitle = $group->getDescription() ?: $groupName;
            $translationKey = ClassificationStoreTranslationService::PREFIX_GROUP . $groupName;

            if (in_array($translationKey, $existingKeys, true)) {
                $skipped++;
                continue;
            }

            if ($dryRun) {
                $io->writeln(sprintf('  <info>[NEW GROUP]</info> %s -> %s', $groupName, $groupTitle));
            } else {
                $this->translationService->getGroupTranslations($groupName, $groupTitle);
            }
            $created++;
        }

        // Get all keys
        $keyListing = new KeyConfig\Listing();
        $keys = $keyListing->load();

        $io->info(sprintf('Found %d keys in classification store', count($keys)));

        foreach ($keys as $key) {
            $keyName = $key->getName();
            $keyTitle = $key->getTitle() ?: $keyName;
            $translationKey = ClassificationStoreTranslationService::PREFIX_KEY . $keyName;

            if (in_array($translationKey, $existingKeys, true)) {
                $skipped++;
                continue;
            }

            if ($dryRun) {
                $io->writeln(sprintf('  <info>[NEW KEY]</info> %s -> %s', $keyName, $keyTitle));
            } else {
                $this->translationService->getKeyTranslations($keyName, $keyTitle);
            }
            $created++;
        }

        $io->newLine(2);

        if ($dryRun) {
            $io->note('DRY RUN - No changes were made');
        }

        if ($created === 0) {
            $io->success('All translation entries already exist. Nothing to add.');
        } else {
            $io->success(sprintf(
                'Generation complete: %d new entries %s, %d already existed',
                $created,
                $dryRun ? 'would be created' : 'created',
                $skipped
            ));
        }

        return Command::SUCCESS;
    }

    /**
     * Get all existing classification store translation keys from database
     */
    private function getExistingTranslationKeys(): array
    {
        $listing = new Translation\Listing();
        $listing->setDomain(ClassificationStoreTranslationService::TRANSLATION_DOMAIN);
        $listing->addConditionParam(
            '`key` LIKE ? OR `key` LIKE ?',
            [
                ClassificationStoreTranslationService::PREFIX_GROUP . '%',
                ClassificationStoreTranslationService::PREFIX_KEY . '%'
            ]
        );

        $keys = [];
        foreach ($listing->load() as $translation) {
            $keys[] = $translation->getKey();
        }

        return $keys;
    }

    /**
     * Translate text using DeepL API
     *
     * @param string $text
     * @param string $targetLanguage
     *
     * @return string|null
     */
    private function translateWithDeepL(string $text, string $targetLanguage): ?string
    {
        // Map Pimcore language codes to DeepL language codes
        $languageMap = [
            'en' => 'EN',
            'de' => 'DE',
            'sk' => 'SK',
            'cs' => 'CS',
            'pl' => 'PL',
            'hu' => 'HU',
            'fr' => 'FR',
            'it' => 'IT',
            'es' => 'ES',
            'nl' => 'NL',
            'pt' => 'PT-PT',
            'ru' => 'RU',
            'zh' => 'ZH',
            'ja' => 'JA',
        ];

        $deeplLang = $languageMap[$targetLanguage] ?? strtoupper($targetLanguage);

        $client = new \GuzzleHttp\Client();

        $response = $client->post('https://api-free.deepl.com/v2/translate', [
            'headers' => [
                'Authorization' => 'DeepL-Auth-Key ' . $this->deeplApiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'text' => [$text],
                'source_lang' => strtoupper($this->sourceLanguage),
                'target_lang' => $deeplLang,
            ],
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return $result['translations'][0]['text'] ?? null;
    }
}
