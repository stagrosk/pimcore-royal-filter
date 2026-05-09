<?php

declare(strict_types=1);

namespace App\Command;

use OpenDxp\Model\DataObject\Fieldcollection;
use OpenDxp\Model\DataObject\Fieldcollection\Data\OpeningHours;
use OpenDxp\Model\DataObject\WebsiteConfiguration;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed-website-configuration',
    description: 'Populate WebsiteConfiguration (id 2623) with footer data sourced from royal-filter.com (STAGRO s.r.o.).'
)]
class SeedWebsiteConfigurationCommand extends Command
{
    private const int CONFIG_ID = 2623;
    private const array LANGUAGES = ['sk', 'cs', 'en', 'de', 'pl', 'hu'];

    private const array TAGLINE = [
        'sk' => 'PRANIE NAMIESTO PLYTVANIA',
        'cs' => 'PRANÍ MÍSTO PLÝTVÁNÍ',
        'en' => 'WASHING INSTEAD OF WASTING',
        'de' => 'WASCHEN STATT VERSCHWENDEN',
        'pl' => 'PRANIE ZAMIAST MARNOTRAWSTWA',
        'hu' => 'MOSÁS PAZARLÁS HELYETT',
    ];

    private const array DESCRIPTION = [
        'sk' => 'Infivea prináša prateľné vírivkové filtre Royal Filter z PETG materiálu. Jeden filter vydrží toľko ako 5 jednorazových — šetríme vaše peniaze aj planétu.',
        'cs' => 'Infivea přináší pratelné vířivkové filtry Royal Filter z PETG materiálu. Jeden filtr vydrží tolik jako 5 jednorázových — šetříme vaše peníze i planetu.',
        'en' => 'Infivea brings washable Royal Filter hot-tub filters made of PETG. One filter lasts as long as five disposable ones — saving your money and the planet.',
        'de' => 'Infivea bietet waschbare Whirlpool-Filter Royal Filter aus PETG. Ein Filter ersetzt fünf Einwegfilter — gut für Ihren Geldbeutel und den Planeten.',
        'pl' => 'Infivea oferuje pralne filtry do wanien z hydromasażem Royal Filter z materiału PETG. Jeden filtr zastępuje pięć jednorazowych — oszczędzasz pieniądze i chronisz planetę.',
        'hu' => 'Az Infivea PETG-ből készült mosható Royal Filter pezsgőfürdő-szűrőket kínál. Egy szűrő öt egyszer használatos szűrőt vált ki — pénzt és bolygót takarít meg.',
    ];

    private const array WELCOME = [
        'sk' => 'Vitajte v Infivea — prateľné vírivkové filtre Royal Filter',
        'cs' => 'Vítejte v Infivea — pratelné vířivkové filtry Royal Filter',
        'en' => 'Welcome to Infivea — washable Royal Filter hot-tub filters',
        'de' => 'Willkommen bei Infivea — waschbare Royal Filter Whirlpool-Filter',
        'pl' => 'Witamy w Infivea — pralne filtry Royal Filter do jacuzzi',
        'hu' => 'Üdvözöljük az Infivea-ban — mosható Royal Filter pezsgőfürdő-szűrők',
    ];

    // CZ pobočka má vlastné lokálne číslo (zdroj: royal-filter.com), ostatné locale používajú SK mobil.
    private const array PHONE = [
        'sk' => '+421 911 924 841',
        'cs' => '+420 702 908 905',
        'en' => '+421 911 924 841',
        'de' => '+421 911 924 841',
        'pl' => '+421 911 924 841',
        'hu' => '+421 911 924 841',
    ];

    private const string EMAIL = 'info@royal-filter.com';
    private const string ADDRESS_STREET = 'Dobšinského 34';
    private const string ADDRESS_ZIP = '953 01';
    private const string ADDRESS_CITY = 'Zlaté Moravce';

    private const array ADDRESS_COUNTRY = [
        'sk' => 'Slovensko',
        'cs' => 'Slovensko',
        'en' => 'Slovakia',
        'de' => 'Slowakei',
        'pl' => 'Słowacja',
        'hu' => 'Szlovákia',
    ];

    private const array REGISTRATION_NOTE = [
        'sk' => 'Zapísané v Obchodnom registri Okresného súdu Nitra, oddiel: Sro, vložka č. 23105/N',
        'cs' => 'Zapsané v Obchodním rejstříku Okresního soudu Nitra, oddíl: Sro, vložka č. 23105/N',
        'en' => 'Registered in the Commercial Register of the District Court Nitra, section: Sro, insert no. 23105/N',
        'de' => 'Eingetragen im Handelsregister des Bezirksgerichts Nitra, Abteilung: Sro, Einlage Nr. 23105/N',
        'pl' => 'Wpisana do Rejestru Handlowego Sądu Rejonowego w Nitrze, sekcja: Sro, wpis nr 23105/N',
        'hu' => 'Bejegyezve a Nyitrai Járásbíróság Cégjegyzékében, Sro szakasz, betétszám: 23105/N',
    ];

    private const array COPYRIGHT = [
        'sk' => '© {year} Infivea · Všetky práva vyhradené',
        'cs' => '© {year} Infivea · Všechna práva vyhrazena',
        'en' => '© {year} Infivea · All rights reserved',
        'de' => '© {year} Infivea · Alle Rechte vorbehalten',
        'pl' => '© {year} Infivea · Wszelkie prawa zastrzeżone',
        'hu' => '© {year} Infivea · Minden jog fenntartva',
    ];

    /**
     * @var array<string, array{0: string, 1: string}>  language => [weekdays, saturday]
     */
    private const array OPENING_DAYS = [
        'sk' => ['Pondelok – Piatok', 'Sobota'],
        'cs' => ['Pondělí – Pátek', 'Sobota'],
        'en' => ['Monday – Friday', 'Saturday'],
        'de' => ['Montag – Freitag', 'Samstag'],
        'pl' => ['Poniedziałek – Piątek', 'Sobota'],
        'hu' => ['Hétfő – Péntek', 'Szombat'],
    ];

    protected function configure(): void
    {
        $this->addOption('dry-run', null, InputOption::VALUE_NONE, 'Do not save');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dryRun = (bool) $input->getOption('dry-run');

        $config = WebsiteConfiguration::getById(self::CONFIG_ID);
        if (!$config instanceof WebsiteConfiguration) {
            $io->error(sprintf('WebsiteConfiguration %d not found', self::CONFIG_ID));
            return Command::FAILURE;
        }
        $io->section(sprintf('WebsiteConfiguration %d (%s)', self::CONFIG_ID, $config->getKey()));

        $config->setLegalName('STAGRO s.r.o.');
        $config->setCompanyId('44392524');
        $config->setTaxId('2022682860');
        $config->setVatId('SK2022682860');

        foreach (self::LANGUAGES as $lang) {
            $config->setBrandTagline(self::TAGLINE[$lang], $lang);
            $config->setBrandDescription(self::DESCRIPTION[$lang], $lang);
            $config->setWelcomeMessage(self::WELCOME[$lang], $lang);
            $config->setEmail(self::EMAIL, $lang);
            $config->setPhone(self::PHONE[$lang], $lang);
            $config->setAddressStreet(self::ADDRESS_STREET, $lang);
            $config->setAddressZip(self::ADDRESS_ZIP, $lang);
            $config->setAddressCity(self::ADDRESS_CITY, $lang);
            $config->setAddressCountry(self::ADDRESS_COUNTRY[$lang], $lang);
            $config->setRegistrationNote(self::REGISTRATION_NOTE[$lang], $lang);
            $config->setCopyrightText(self::COPYRIGHT[$lang], $lang);
        }

        $config->setOpeningHours($this->buildOpeningHours());

        if ($dryRun) {
            $io->note('[dry-run] would save WebsiteConfiguration');
        } else {
            $config->save();
            $io->success('Saved WebsiteConfiguration');
        }

        return Command::SUCCESS;
    }

    private function buildOpeningHours(): Fieldcollection
    {
        $fc = new Fieldcollection();

        $weekdays = new OpeningHours();
        $weekdays->setTimeFrom('09:00');
        $weekdays->setTimeTo('20:00');
        foreach (self::LANGUAGES as $lang) {
            $weekdays->setDay(self::OPENING_DAYS[$lang][0], $lang);
        }
        $fc->add($weekdays);

        $saturday = new OpeningHours();
        $saturday->setTimeFrom('10:00');
        $saturday->setTimeTo('16:00');
        foreach (self::LANGUAGES as $lang) {
            $saturday->setDay(self::OPENING_DAYS[$lang][1], $lang);
        }
        $fc->add($saturday);

        return $fc;
    }
}
