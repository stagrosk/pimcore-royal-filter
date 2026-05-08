<?php

declare(strict_types=1);

namespace App\Command;

use OpenDxp\Model\DataObject\ContentPage;
use OpenDxp\Model\DataObject\Data\BlockElement;
use OpenDxp\Model\DataObject\Fieldcollection;
use OpenDxp\Model\DataObject\Fieldcollection\Data\LegalHero;
use OpenDxp\Model\DataObject\Fieldcollection\Data\LegalHighlights;
use OpenDxp\Model\DataObject\Fieldcollection\Data\LegalSection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed-legal-pages',
    description: 'Populate Privacy Policy (id 2854) and Terms & Conditions (id 2853) ContentPages with the LegalHero/LegalHighlights/LegalSection bricks based on the Figma design.'
)]
class SeedLegalPagesCommand extends Command
{
    private const string LANG = 'sk';
    private const string MAIL_HREF = 'mailto:martin@stagro.sk';

    private const int PRIVACY_ID = 2854;
    private const int TERMS_ID = 2853;

    protected function configure(): void
    {
        $this
            ->addOption('only', null, InputOption::VALUE_OPTIONAL, 'Only one of: privacy|terms')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Do not save');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $only = $input->getOption('only');
        $dryRun = (bool) $input->getOption('dry-run');

        $targets = [];
        if (!$only || $only === 'privacy') {
            $targets[] = ['id' => self::PRIVACY_ID, 'kind' => 'privacy'];
        }
        if (!$only || $only === 'terms') {
            $targets[] = ['id' => self::TERMS_ID, 'kind' => 'terms'];
        }

        foreach ($targets as $t) {
            $page = ContentPage::getById($t['id']);
            if (!$page instanceof ContentPage) {
                $io->error(sprintf('ContentPage %d not found', $t['id']));
                return Command::FAILURE;
            }
            $io->section(sprintf('%s — ContentPage %d (%s)', strtoupper($t['kind']), $t['id'], $page->getKey()));

            $elements = new Fieldcollection();
            if ($t['kind'] === 'privacy') {
                $this->buildPrivacy($elements);
            } else {
                $this->buildTerms($elements);
            }

            $page->setElements($elements);

            if ($dryRun) {
                $io->note('[dry-run] would save ' . $elements->getCount() . ' elements');
            } else {
                $page->save();
                $io->success('Saved ' . $elements->getCount() . ' elements');
            }
        }

        return Command::SUCCESS;
    }

    private function buildPrivacy(Fieldcollection $fc): void
    {
        $fc->add($this->makeHero(
            variant: 'privacy',
            iconName: 'Shield',
            badgeLabel: 'GDPR',
            version: '3.2',
            effectiveDate: '1. mája 2026',
            title: 'Ochrana osobných údajov',
            subtitle: 'Vaše súkromie je pre nás prioritou. Tento dokument vysvetľuje, aké údaje spracúvame, prečo, ako dlho a aké máte práva podľa GDPR.',
            breadcrumb: 'Ochrana osobných údajov',
        ));

        $fc->add($this->makeHighlights([
            ['Lock', 'Šifrovaná komunikácia', 'TLS 1.3, AES-256 pri ukladaní citlivých údajov.'],
            ['Eye', 'Žiaden predaj údajov', 'Údaje nikdy nepredávame tretím stranám.'],
            ['UserCheck', 'Plné práva GDPR', 'Prístup, oprava, výmaz alebo prenos kedykoľvek.'],
            ['Shield', 'EÚ servery', 'Údaje sú primárne spracúvané v EÚ/EHP.'],
        ]));

        foreach ($this->privacySections() as $sec) {
            $fc->add($this->makeSection($sec['id'], $sec['title'], $sec['blocks']));
        }
    }

    private function buildTerms(Fieldcollection $fc): void
    {
        $fc->add($this->makeHero(
            variant: 'terms',
            iconName: 'FileText',
            badgeLabel: 'VOP',
            version: '2.4',
            effectiveDate: '1. mája 2026',
            title: 'Všeobecné obchodné podmienky',
            subtitle: 'Pravidlá nákupu, dodania, reklamácií a vrátenia tovaru v internetovom obchode Royal-Filter.com. Stručne, prehľadne, v súlade so slovenským právom.',
            breadcrumb: 'Obchodné podmienky',
        ));

        $fc->add($this->makeHighlights([
            ['Truck', 'Doprava zdarma', 'Pri objednávke nad 30 € do 48 hodín.'],
            ['RotateCcw', '14 dní na vrátenie', 'Bez udania dôvodu, peniaze do 14 dní späť.'],
            ['FileText', '24 mesiacov záruka', 'Pre spotrebiteľov v zmysle Občianskeho zákonníka.'],
            ['CreditCard', 'Bezpečné platby', 'GoPay, Apple Pay, Google Pay a bankový prevod.'],
        ]));

        foreach ($this->termsSections() as $sec) {
            $fc->add($this->makeSection($sec['id'], $sec['title'], $sec['blocks']));
        }
    }

    private function makeHero(
        string $variant,
        string $iconName,
        string $badgeLabel,
        string $version,
        string $effectiveDate,
        string $title,
        string $subtitle,
        string $breadcrumb,
    ): LegalHero {
        $hero = new LegalHero();
        $hero->setVariant($variant);
        $hero->setIconName($iconName);
        $hero->setMailHref(self::MAIL_HREF);
        $hero->setShowPrintButton(true);
        $hero->setShowDownloadButton(true);
        $hero->setShowMailButton(true);
        $hero->setBadgeLabel($badgeLabel, self::LANG);
        $hero->setVersion($version, self::LANG);
        $hero->setEffectiveDate($effectiveDate, self::LANG);
        $hero->setTitle($title, self::LANG);
        $hero->setSubtitle($subtitle, self::LANG);
        $hero->setBreadcrumb($breadcrumb, self::LANG);
        $hero->setPrintButtonLabel('Tlačiť', self::LANG);
        $hero->setDownloadButtonLabel('Stiahnuť ako PDF', self::LANG);
        $hero->setMailButtonLabel('Otázka k dokumentu', self::LANG);

        return $hero;
    }

    /**
     * @param array<int, array{0:string,1:string,2:string}> $items
     */
    private function makeHighlights(array $items): LegalHighlights
    {
        $blocks = [];
        foreach ($items as [$icon, $title, $desc]) {
            $blocks[] = [
                'iconName' => new BlockElement('iconName', 'input', $icon),
                'title' => new BlockElement('title', 'input', $title),
                'desc' => new BlockElement('desc', 'textarea', $desc),
            ];
        }

        $h = new LegalHighlights();
        $h->setItems($blocks, self::LANG);

        return $h;
    }

    /**
     * @param array<int, array<string,mixed>> $blocks
     */
    private function makeSection(string $anchorId, string $title, array $blocks): LegalSection
    {
        $sec = new LegalSection();
        $sec->setAnchorId($anchorId);
        $sec->setTitle($title, self::LANG);

        $blockData = [];
        foreach ($blocks as $b) {
            $type = $b['blockType'];
            $blockData[] = [
                'blockType' => new BlockElement('blockType', 'select', $type),
                'paragraph' => new BlockElement('paragraph', 'wysiwyg', $b['paragraph'] ?? null),
                'listItems' => new BlockElement('listItems', 'textarea', $b['listItems'] ?? null),
                'tableJson' => new BlockElement('tableJson', 'textarea', $b['tableJson'] ?? null),
                'note' => new BlockElement('note', 'wysiwyg', $b['note'] ?? null),
            ];
        }
        $sec->setBlocks($blockData, self::LANG);

        return $sec;
    }

    private function paragraph(string $html): array
    {
        return ['blockType' => 'paragraph', 'paragraph' => $html];
    }

    /** @param string[] $items */
    private function listBlock(array $items): array
    {
        return ['blockType' => 'list', 'listItems' => implode("\n", $items)];
    }

    /** @param string[] $head @param string[][] $rows */
    private function table(array $head, array $rows): array
    {
        return ['blockType' => 'table', 'tableJson' => json_encode(['head' => $head, 'rows' => $rows], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)];
    }

    private function note(string $html): array
    {
        return ['blockType' => 'note', 'note' => $html];
    }

    /** @return array<int, array{id:string,title:string,blocks:array<int,array<string,mixed>>}> */
    private function privacySections(): array
    {
        return [
            [
                'id' => 'prevadzkovatel',
                'title' => 'Prevádzkovateľ a kontaktné údaje',
                'blocks' => [
                    $this->paragraph('Prevádzkovateľom internetového obchodu <strong>Royal-Filter.com</strong> a správcom Vašich osobných údajov je spoločnosť <strong>STAGRO s.r.o.</strong>, so sídlom Koperníkova 12, 602 00 Brno, Česká republika, IČO: 12345678, DIČ: CZ12345678, zapísaná v obchodnom registri Mestského súdu v Brne, oddiel C, vložka 98765 (ďalej len „Prevádzkovateľ").'),
                    $this->listBlock([
                        '<strong>E-mail:</strong> martin@stagro.sk',
                        '<strong>Telefón:</strong> +421 911 924 841',
                        '<strong>Korešpondenčná adresa:</strong> Koperníkova 12, 602 00 Brno',
                    ]),
                    $this->paragraph('Prevádzkovateľ neurčil zodpovednú osobu (DPO), nakoľko mu túto povinnosť neukladá zákon.'),
                ],
            ],
            [
                'id' => 'rozsah-udajov',
                'title' => 'Rozsah spracúvaných osobných údajov',
                'blocks' => [
                    $this->paragraph('Pri nákupe a prevádzke účtu spracúvame nasledujúce kategórie osobných údajov:'),
                    $this->table(
                        ['Kategória', 'Konkrétne údaje'],
                        [
                            ['Identifikačné údaje', 'meno, priezvisko, titul'],
                            ['Kontaktné údaje', 'e-mail, telefón, adresa doručenia a fakturačná adresa'],
                            ['Firemné údaje', 'názov firmy, IČO, DIČ, IČ DPH (ak ste podnikateľ)'],
                            ['Údaje o objednávkach', 'číslo objednávky, obsah košíka, suma, spôsob platby a dopravy'],
                            ['Platobné údaje', 'iba čiastočné údaje (posledné 4 čísla karty, typ karty); plné údaje spracúva platobná brána'],
                            ['Technické údaje', 'IP adresa, typ prehliadača, cookies, ID relácie, zariadenie, log súbory'],
                            ['Správanie na stránke', 'navštívené produkty, kliknutia, čas strávený na stránke (anonymne / pseudonymne)'],
                        ],
                    ),
                ],
            ],
            [
                'id' => 'ucely',
                'title' => 'Účely a právne základy spracúvania',
                'blocks' => [
                    $this->paragraph('Vaše osobné údaje spracúvame výhradne na nasledujúce vopred definované účely:'),
                    $this->listBlock([
                        '<strong>Plnenie kúpnej zmluvy</strong> (čl. 6 ods. 1 písm. b) GDPR) – spracovanie objednávky, doručenie tovaru, fakturácia, riešenie reklamácií a odstúpenia od zmluvy.',
                        '<strong>Plnenie zákonných povinností</strong> (čl. 6 ods. 1 písm. c) GDPR) – účtovníctvo, daňové povinnosti, archivácia faktúr po dobu 10 rokov.',
                        '<strong>Oprávnený záujem</strong> (čl. 6 ods. 1 písm. f) GDPR) – ochrana pred podvodmi, vymáhanie pohľadávok, priame ponuky obdobných produktov existujúcim zákazníkom.',
                        '<strong>Súhlas</strong> (čl. 6 ods. 1 písm. a) GDPR) – zasielanie newslettera, marketingové cookies, hodnotenie obchodu, programy lojality.',
                    ]),
                    $this->note('Súhlas je dobrovoľný a kedykoľvek odvolateľný kliknutím na odkaz <em>„Odhlásiť sa"</em> v každom marketingovom e-maile alebo zaslaním žiadosti na martin@stagro.sk. Odvolanie súhlasu nemá vplyv na zákonnosť spracúvania pred jeho odvolaním.'),
                ],
            ],
            [
                'id' => 'doba-uchovavania',
                'title' => 'Doba uchovávania údajov',
                'blocks' => [
                    $this->table(
                        ['Údaj', 'Doba uchovávania'],
                        [
                            ['Údaje z objednávok a faktúr', '10 rokov (zákonná povinnosť)'],
                            ['Reklamačné záznamy', '5 rokov od vybavenia'],
                            ['Údaje k zákazníckemu účtu', 'po dobu existencie účtu + 1 rok po jeho zrušení'],
                            ['Marketingové súhlasy', 'do odvolania, max. 5 rokov od posledného nákupu'],
                            ['Cookies', 'podľa typu cookies (1 deň – 24 mesiacov)'],
                            ['Záznamy z kamier (predajňa)', '7 dní'],
                        ],
                    ),
                    $this->paragraph('Po uplynutí týchto lehôt sú údaje bezpečne zlikvidované, prípadne anonymizované na štatistické účely.'),
                ],
            ],
            [
                'id' => 'prijemcovia',
                'title' => 'Príjemcovia a sprostredkovatelia',
                'blocks' => [
                    $this->paragraph('Vaše údaje môžu byť odovzdané týmto kategóriám príjemcov, výhradne v rozsahu nevyhnutnom na splnenie účelu:'),
                    $this->listBlock([
                        '<strong>Doručovacie spoločnosti:</strong> GLS, DPD, Packeta, Slovenská pošta',
                        '<strong>Platobné brány:</strong> GoPay s.r.o., Stripe Payments Europe Ltd.',
                        '<strong>Účtovné a daňové služby:</strong> externý účtovník (zmluva o spracúvaní)',
                        '<strong>IT a hosting:</strong> Shoptet a.s., Cloudflare Inc.',
                        '<strong>Marketing a analytika:</strong> Google Ireland Ltd. (Analytics, Ads), Meta Platforms Ireland Ltd.',
                        '<strong>Štátne orgány:</strong> finančný úrad, súdy, polícia – iba na zákonnú výzvu',
                    ]),
                    $this->paragraph('Údaje neodovzdávame do tretích krajín mimo EÚ/EHP, s výnimkou služieb postavených na štandardných zmluvných doložkách EÚ (Google, Meta).'),
                ],
            ],
            [
                'id' => 'prava',
                'title' => 'Vaše práva ako dotknutej osoby',
                'blocks' => [
                    $this->paragraph('V súlade s GDPR máte voči nám tieto práva, ktoré môžete kedykoľvek uplatniť:'),
                    $this->listBlock([
                        '<strong>Právo na prístup</strong> – získať potvrdenie a kópiu spracúvaných údajov.',
                        '<strong>Právo na opravu</strong> – opraviť nesprávne alebo doplniť neúplné údaje.',
                        '<strong>Právo na vymazanie</strong> („právo na zabudnutie") – ak sú splnené zákonné podmienky.',
                        '<strong>Právo na obmedzenie spracúvania</strong> – v prípade sporu o správnosť údajov.',
                        '<strong>Právo na prenosnosť</strong> – získať údaje v štruktúrovanom strojovo čitateľnom formáte.',
                        '<strong>Právo namietať</strong> proti spracúvaniu na základe oprávneného záujmu alebo priameho marketingu.',
                        '<strong>Právo podať sťažnosť</strong> dozornému orgánu – Úrad na ochranu osobných údajov SR (dataprotection.gov.sk).',
                    ]),
                    $this->paragraph('Žiadosť môžete podať e-mailom na <strong>martin@stagro.sk</strong>, písomne na adresu sídla, alebo cez kontaktný formulár. Reagujeme do 30 dní bezplatne.'),
                ],
            ],
            [
                'id' => 'cookies',
                'title' => 'Cookies a sledovacie technológie',
                'blocks' => [
                    $this->paragraph('Naša stránka používa cookies a podobné technológie. Pri prvej návšteve si môžete zvoliť, ktoré kategórie akceptujete:'),
                    $this->table(
                        ['Kategória', 'Účel', 'Súhlas'],
                        [
                            ['Nevyhnutné', 'Funkčnosť košíka, prihlásenie, bezpečnosť', '<span style="color:#16a34a;font-weight:700">Nepotrebný</span>'],
                            ['Funkčné', 'Zapamätanie nastavení (jazyk, mena, dark mode)', '<span style="color:#f59e0b;font-weight:700">Voliteľný</span>'],
                            ['Analytické', 'Google Analytics, Hotjar – meranie návštevnosti', '<span style="color:#dc2626;font-weight:700">Vyžaduje</span>'],
                            ['Marketingové', 'Google Ads, Meta Pixel – personalizovaná reklama', '<span style="color:#dc2626;font-weight:700">Vyžaduje</span>'],
                        ],
                    ),
                    $this->paragraph('Nastavenia môžete kedykoľvek zmeniť kliknutím na odkaz <em>„Nastavenie cookies"</em> v pätičke webu.'),
                ],
            ],
            [
                'id' => 'zabezpecenie',
                'title' => 'Zabezpečenie údajov',
                'blocks' => [
                    $this->paragraph('Implementujeme technické a organizačné opatrenia primerané rizikám, najmä:'),
                    $this->listBlock([
                        'Šifrovaný prenos pomocou HTTPS / TLS 1.3 na celej stránke',
                        'Šifrované úložisko databázy (AES-256) a pravidelné zálohy',
                        'Riadenie prístupov s dvojfaktorovou autentifikáciou pre všetkých zamestnancov',
                        'Pravidelné bezpečnostné audity a penetračné testy',
                        'Mlčanlivosť všetkých zamestnancov a sprostredkovateľov',
                        'Postup pre hlásenie incidentov v zmysle čl. 33 GDPR (do 72 hodín)',
                    ]),
                ],
            ],
            [
                'id' => 'zmeny',
                'title' => 'Zmeny týchto zásad',
                'blocks' => [
                    $this->paragraph('Vyhradzujeme si právo aktualizovať tieto Zásady ochrany osobných údajov. Aktuálna verzia je vždy zverejnená na tejto adrese s dátumom účinnosti. O podstatných zmenách Vás budeme informovať e-mailom alebo notifikáciou v zákazníckom účte minimálne 30 dní pred ich účinnosťou.'),
                    $this->paragraph('V prípade akýchkoľvek otázok nás neváhajte kontaktovať. Sme tu pre Vás.'),
                ],
            ],
        ];
    }

    /** @return array<int, array{id:string,title:string,blocks:array<int,array<string,mixed>>}> */
    private function termsSections(): array
    {
        return [
            [
                'id' => 'uvodne-ustanovenia',
                'title' => 'Úvodné ustanovenia',
                'blocks' => [
                    $this->paragraph('Tieto všeobecné obchodné podmienky (ďalej len „VOP") upravujú práva a povinnosti zmluvných strán vyplývajúce z kúpnej zmluvy uzatvorenej medzi predávajúcim, ktorým je <strong>STAGRO s.r.o.</strong>, IČO: 12345678, so sídlom Koperníkova 12, 602 00 Brno (ďalej len „Predávajúci"), a kupujúcim, ktorej predmetom je kúpa a predaj tovaru prostredníctvom internetového obchodu <strong>Royal-Filter.com</strong>.'),
                    $this->paragraph('VOP sú neoddeliteľnou súčasťou kúpnej zmluvy. Odoslaním objednávky kupujúci potvrdzuje, že sa s VOP oboznámil a súhlasí s nimi.'),
                    $this->note('VOP sa primerane použijú aj pri nákupe na <em>Royal-Filter.cz</em>, <em>Royal-Filter.de</em> a ďalších regionálnych mutáciách obchodu prevádzkovaných tým istým Predávajúcim.'),
                ],
            ],
            [
                'id' => 'pojmy',
                'title' => 'Vymedzenie pojmov',
                'blocks' => [
                    $this->listBlock([
                        '<strong>Predávajúci</strong> – obchodná spoločnosť STAGRO s.r.o. uvedená vyššie.',
                        '<strong>Kupujúci spotrebiteľ</strong> – fyzická osoba, ktorá pri uzatváraní zmluvy nekoná v rámci svojej podnikateľskej činnosti.',
                        '<strong>Kupujúci podnikateľ</strong> – osoba uvádzajúca pri objednávke IČO alebo objednávajúca v rámci svojej obchodnej činnosti.',
                        '<strong>Tovar</strong> – produkty ponúkané v e-shope (filtre, chémia, sety, príslušenstvo).',
                        '<strong>Zmluva</strong> – kúpna zmluva uzatvorená medzi Predávajúcim a Kupujúcim na diaľku.',
                    ]),
                ],
            ],
            [
                'id' => 'uzavretie-zmluvy',
                'title' => 'Uzavretie kúpnej zmluvy',
                'blocks' => [
                    $this->paragraph('Návrhom na uzavretie zmluvy je objednávka odoslaná Kupujúcim cez webové rozhranie obchodu. Zmluva je uzavretá okamihom doručenia <strong>potvrdenia objednávky</strong> Kupujúcemu na jeho e-mailovú adresu.'),
                    $this->paragraph('Pred odoslaním má Kupujúci možnosť skontrolovať a meniť údaje, ktoré do objednávky vložil. Kupujúci súhlasí s použitím komunikačných prostriedkov na diaľku pri uzatváraní zmluvy.'),
                    $this->paragraph('Predávajúci si vyhradzuje právo neprijať objednávku, ak:'),
                    $this->listBlock([
                        'Tovar nie je dostupný a jeho dodanie by sa neúmerne predĺžilo',
                        'Cena sa zjavne výrazne odlišuje od bežnej trhovej ceny (zjavná chyba)',
                        'Existuje dôvodné podozrenie z podvodu alebo zneužitia',
                        'Kupujúci v predchádzajúcich obdobiach opakovane neprevzal objednaný tovar',
                    ]),
                ],
            ],
            [
                'id' => 'ceny-platba',
                'title' => 'Ceny tovaru a platobné podmienky',
                'blocks' => [
                    $this->paragraph('Všetky ceny v e-shope sú uvedené <strong>vrátane DPH</strong> a sú platné v okamihu odoslania objednávky. K cene tovaru sa pripočítava cena dopravy podľa zvolenej možnosti.'),
                    $this->paragraph('Predávajúci akceptuje tieto spôsoby platby:'),
                    $this->table(
                        ['Spôsob platby', 'Poplatok', 'Poznámka'],
                        [
                            ['Platba kartou online (GoPay)', '0 €', 'Okamžitá expedícia'],
                            ['Apple Pay / Google Pay', '0 €', 'Okamžitá expedícia'],
                            ['Bankový prevod', '0 €', 'Po pripísaní na účet (1 – 2 dni)'],
                            ['Dobierka', '1,90 €', 'Platba kuriérovi pri prevzatí'],
                            ['Hotovosť pri osobnom odbere', '0 €', 'V prevádzke v Brne'],
                        ],
                    ),
                    $this->paragraph('Faktúra je vystavená elektronicky a doručená Kupujúcemu e-mailom najneskôr pri expedícii zásielky.'),
                ],
            ],
            [
                'id' => 'doprava-dodanie',
                'title' => 'Doprava a dodanie tovaru',
                'blocks' => [
                    $this->paragraph('Tovar expedujeme zo skladu v Brne. Bežná doba expedície je <strong>do 24 hodín</strong> od pripísania platby (v pracovné dni). Doručenie podľa zvoleného dopravcu:'),
                    $this->table(
                        ['Dopravca', 'Doba doručenia', 'Cena (do 30 €)', 'Cena (nad 30 €)'],
                        [
                            ['GLS', '1 – 2 pracovné dni', '3,90 €', 'Zdarma'],
                            ['DPD', '1 – 2 pracovné dni', '4,20 €', 'Zdarma'],
                            ['Packeta (výdajné miesto)', '2 – 3 pracovné dni', '2,90 €', 'Zdarma'],
                            ['Slovenská pošta', '2 – 4 pracovné dni', '3,50 €', 'Zdarma'],
                            ['Osobný odber Brno', 'Nasledujúci pracovný deň', 'Zdarma', 'Zdarma'],
                        ],
                    ),
                    $this->paragraph('Pri prevzatí zásielky je Kupujúci povinný skontrolovať neporušenosť obalu. V prípade poškodenia odporúčame zásielku neprevziať alebo s kuriérom spísať zápisnicu o škode.'),
                ],
            ],
            [
                'id' => 'odstupenie',
                'title' => 'Odstúpenie od zmluvy (vrátenie tovaru)',
                'blocks' => [
                    $this->paragraph('Kupujúci spotrebiteľ má právo <strong>odstúpiť od zmluvy bez udania dôvodu do 14 dní</strong> odo dňa prevzatia tovaru, v zmysle § 7 zákona č. 102/2014 Z.z.'),
                    $this->paragraph('Pre odstúpenie postačí jednostranný prejav vôle zaslaný e-mailom na <strong>martin@stagro.sk</strong> alebo poštou. Môžete použiť aj formulár dostupný v sekcii <em>„Vrátenie tovaru"</em>.'),
                    $this->paragraph('Tovar musí byť vrátený:'),
                    $this->listBlock([
                        'Najneskôr do 14 dní od oznámenia o odstúpení',
                        'Nepoškodený, nepoužitý, kompletný (vrátane príslušenstva a dokumentácie)',
                        'V pôvodnom obale, ak je to možné',
                        'Na adresu: <strong>STAGRO s.r.o., Koperníkova 12, 602 00 Brno</strong>',
                    ]),
                    $this->paragraph('Predávajúci vráti uhradenú sumu vrátane nákladov na dopravu (najlacnejšieho ponúkaného spôsobu) do 14 dní od doručenia tovaru, rovnakým spôsobom, akým platba prebehla.'),
                    $this->note('<strong>Náklady na vrátenie tovaru znáša Kupujúci.</strong> Tovar zaslaný na dobierku Predávajúci nepreberá.'),
                ],
            ],
            [
                'id' => 'reklamacie',
                'title' => 'Reklamácie a záruka',
                'blocks' => [
                    $this->paragraph('Predávajúci zodpovedá za vady tovaru v zmysle § 619 a nasl. Občianskeho zákonníka. Záručná doba je <strong>24 mesiacov</strong> pre spotrebiteľov a <strong>12 mesiacov</strong> pre podnikateľov.'),
                    $this->paragraph('Reklamáciu možno uplatniť:'),
                    $this->listBlock([
                        'Online cez sekciu <em>„Reklamácie"</em> v zákazníckom účte',
                        'E-mailom na martin@stagro.sk s popisom vady a fotografiou',
                        'Poštou spolu s reklamovaným tovarom na adresu sídla',
                    ]),
                    $this->paragraph('O výsledku reklamácie informujeme Kupujúceho najneskôr <strong>do 30 dní</strong> od jej uplatnenia. Po uplynutí tejto lehoty má spotrebiteľ právo odstúpiť od zmluvy alebo požadovať výmenu tovaru.'),
                    $this->paragraph('Záruka sa nevzťahuje na vady spôsobené:'),
                    $this->listBlock([
                        'Bežným opotrebením v rámci životnosti filtra (5 – 6 mesiacov pri správnej údržbe)',
                        'Nesprávnym používaním v rozpore s návodom (napr. teplota nad 60 °C)',
                        'Použitím agresívnej chémie nekompatibilnej s materiálom PETG',
                        'Mechanickým poškodením po prevzatí',
                    ]),
                ],
            ],
            [
                'id' => 'alternativne-riesenie',
                'title' => 'Alternatívne riešenie sporov',
                'blocks' => [
                    $this->paragraph('Kupujúci spotrebiteľ má právo obrátiť sa na Predávajúceho so žiadosťou o nápravu, ak nie je spokojný so spôsobom vybavenia reklamácie.'),
                    $this->paragraph('Ak Predávajúci na žiadosť odpovie zamietavo alebo neodpovie do 30 dní, má spotrebiteľ právo podať návrh na začatie alternatívneho riešenia sporu (ARS) subjektu uvedenom v zozname Ministerstva hospodárstva SR:'),
                    $this->listBlock([
                        '<strong>Slovenská obchodná inšpekcia</strong> – soi.sk, ars@soi.sk',
                        '<strong>Európska platforma RSO</strong> – ec.europa.eu/consumers/odr',
                    ]),
                    $this->paragraph('ARS je bezplatné a nezáväzné. Kupujúci ho môže využiť aj v prípade cezhraničných sporov.'),
                ],
            ],
            [
                'id' => 'zaverecne',
                'title' => 'Záverečné ustanovenia',
                'blocks' => [
                    $this->paragraph('Vzťahy neupravené týmito VOP sa riadia právnym poriadkom Slovenskej republiky, najmä Občianskym zákonníkom (40/1964 Zb.) a zákonom o ochrane spotrebiteľa pri predaji na diaľku (102/2014 Z.z.).'),
                    $this->paragraph('Predávajúci si vyhradzuje právo VOP jednostranne meniť. Pre konkrétnu objednávku platí znenie účinné v deň jej odoslania.'),
                    $this->paragraph('Tieto VOP nadobúdajú účinnosť dňom uvedeným v hlavičke dokumentu a nahrádzajú všetky predchádzajúce verzie.'),
                    $this->paragraph('V prípade akýchkoľvek otázok k týmto podmienkam nás kontaktujte – sme tu pre Vás.'),
                ],
            ],
        ];
    }
}
