<?php

declare(strict_types=1);

namespace App\Command;

use OpenDxp\Model\DataObject\ContentPage;
use OpenDxp\Model\DataObject\Data\BlockElement;
use OpenDxp\Model\DataObject\Data\Link;
use OpenDxp\Model\DataObject\Fieldcollection;
use OpenDxp\Model\DataObject\Fieldcollection\Data\AboutCtaBanner;
use OpenDxp\Model\DataObject\Fieldcollection\Data\AboutHero;
use OpenDxp\Model\DataObject\Fieldcollection\Data\AboutReviewsTeaser;
use OpenDxp\Model\DataObject\Fieldcollection\Data\AboutStatsBar;
use OpenDxp\Model\DataObject\Fieldcollection\Data\AboutTeam;
use OpenDxp\Model\DataObject\Fieldcollection\Data\AboutTimeline;
use OpenDxp\Model\DataObject\Fieldcollection\Data\AboutValues;
use OpenDxp\Model\DataObject\Fieldcollection\Data\AboutWhyUs;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed-about-page',
    description: 'Populate About (id 2837) ContentPage with the 8 About bricks based on the Figma design.'
)]
class SeedAboutPageCommand extends Command
{
    private const string LANG = 'sk';
    private const int ABOUT_ID = 2837;

    protected function configure(): void
    {
        $this->addOption('dry-run', null, InputOption::VALUE_NONE, 'Do not save');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dryRun = (bool) $input->getOption('dry-run');

        $page = ContentPage::getById(self::ABOUT_ID);
        if (!$page instanceof ContentPage) {
            $io->error(sprintf('ContentPage %d not found', self::ABOUT_ID));
            return Command::FAILURE;
        }
        $io->section(sprintf('ABOUT — ContentPage %d (%s)', self::ABOUT_ID, $page->getKey()));

        $elements = new Fieldcollection();
        $elements->add($this->makeHero());
        $elements->add($this->makeTimeline());
        $elements->add($this->makeStatsBar());
        $elements->add($this->makeValues());
        $elements->add($this->makeWhyUs());
        $elements->add($this->makeTeam());
        $elements->add($this->makeReviews());
        $elements->add($this->makeCta());

        $page->setElements($elements);

        if ($dryRun) {
            $io->note('[dry-run] would save ' . $elements->getCount() . ' elements');
        } else {
            $page->save();
            $io->success('Saved ' . $elements->getCount() . ' elements');
        }

        return Command::SUCCESS;
    }

    private function makeHero(): AboutHero
    {
        $h = new AboutHero();
        $h->setBreadcrumb('O nás', self::LANG);
        $h->setBadgeLabel('Od 2020 pre čistú vodu vo Vašej vírivke', self::LANG);
        $h->setTitle("Sme Royal Filter –\nšpecialisti na filtre\ndo víriviek", self::LANG);
        $h->setSubtitle('To, čo začalo ako hľadanie kvalitného filtra do vlastnej vírivky, sa premenilo na e-shop, ktorému dôveruje viac ako 5 000 zákazníkov v 4 krajinách. Ponúkame overené filtre, bezchlorovú chémiu a osobný prístup ku každému zákazníkovi.', self::LANG);
        $h->setImageAlt('Vírivka v záhrade', self::LANG);
        $h->setPrimaryButtonLabel('Prezrieť produkty', self::LANG);
        $h->setPrimaryButtonHref($this->directLink('/category'), self::LANG);
        $h->setSecondaryButtonLabel('Kontaktujte nás', self::LANG);
        $h->setSecondaryButtonHref($this->directLink('/contacts'), self::LANG);

        $stats = [
            ['5 000+', 'zákazníkov'],
            ['4.8★',   'hodnotenie'],
            ['4',      'krajiny'],
        ];
        $blocks = [];
        foreach ($stats as [$value, $label]) {
            $blocks[] = [
                'statsValue'  => new BlockElement('statsValue', 'input', $value),
                'statsLlabel' => new BlockElement('statsLlabel', 'input', $label),
            ];
        }
        $h->setFloatingStats($blocks, self::LANG);
        return $h;
    }

    private function directLink(string $href): Link
    {
        $link = new Link();
        $link->setLinktype('direct');
        $link->setDirect($href);

        return $link;
    }

    private function makeTimeline(): AboutTimeline
    {
        $milestones = [
            ['2019', 'Začiatok', 'Prvé skúsenosti s vírivkami a hľadanie kvalitných filtrov na európskom trhu.'],
            ['2020', 'Založenie Royal Filter', 'Spustenie e-shopu so zameraním na filtre do víriviek pre slovenský a český trh.'],
            ['2021', 'Rozšírenie sortimentu', 'Pridanie bezchlorovej chémie a príslušenstva. Prvých 1 000 spokojných zákazníkov.'],
            ['2022', 'Vlastný sklad v Brne', 'Otvorenie prevádzky a skladu na Koperníkovej ulici v Brne pre rýchlejšiu expedíciu.'],
            ['2023', 'Medzinárodná expanzia', 'Rozšírenie na nemecký a rakúsky trh. Partnerstvá s výrobcami víriviek.'],
            ['2024', '5 000+ zákazníkov', 'Prekročenie hranice 5 000 obslúžených zákazníkov s hodnotením 4.8/5.'],
            ['2025', 'Bezchlorová revolúcia', 'Uvedenie vlastnej línie bezchlorovej chémie Royal Pure. Zameranie na ekológiu.'],
        ];
        $blocks = [];
        foreach ($milestones as [$year, $title, $desc]) {
            $blocks[] = [
                'year'  => new BlockElement('year',  'input',    $year),
                'title' => new BlockElement('title', 'input',    $title),
                'desc'  => new BlockElement('desc',  'textarea', $desc),
            ];
        }
        $t = new AboutTimeline();
        $t->setBadgeLabel('Náš príbeh', self::LANG);
        $t->setHeading('Od vírivky v záhrade po medzinárodný e-shop', self::LANG);
        $t->setSubheading('Všetko sa začalo jednoduchou otázkou: „Prečo je tak ťažké nájsť správny filter do vírivky?"', self::LANG);
        $t->setMilestones($blocks, self::LANG);
        return $t;
    }

    private function makeStatsBar(): AboutStatsBar
    {
        $stats = [
            ['Users',   '5 000+',  'Spokojných zákazníkov'],
            ['Package', '12 000+', 'Odoslaných objednávok'],
            ['Star',    '4.8 / 5', 'Priemerné hodnotenie'],
            ['Truck',   '< 24h',   'Expedícia objednávky'],
            ['Target',  '850+',    'Produktov v ponuke'],
            ['MapPin',  '4 krajiny', 'Doručujeme do'],
        ];
        $blocks = [];
        foreach ($stats as [$icon, $value, $label]) {
            $blocks[] = [
                'icon'      => new BlockElement('icon',      'select', $icon),
                'itemValue' => new BlockElement('itemValue', 'input',  $value),
                'itemLabel' => new BlockElement('itemLabel', 'input',  $label),
            ];
        }
        $s = new AboutStatsBar();
        $s->setItems($blocks, self::LANG);
        return $s;
    }

    private function makeValues(): AboutValues
    {
        $values = [
            ['Shield',    'blue',  'Kvalita bez kompromisov',     'Každý filter testujeme a overujeme. Ponúkame len produkty, za ktorými si stojíme.'],
            ['Heart',     'rose',  'Zákazník na prvom mieste',    'Osobný prístup, rýchle odpovede a ochota poradiť – to je náš štandard.'],
            ['Leaf',      'green', 'Ekologický prístup',          'Bezchlorová chémia šetrí Vaše zdravie aj životné prostredie. Budúcnosť je zelená.'],
            ['Lightbulb', 'amber', 'Odbornosť a inovácie',        'Sledujeme najnovšie trendy vo filtrácii a prinášame osvedčené riešenia.'],
        ];
        $blocks = [];
        foreach ($values as [$icon, $color, $title, $desc]) {
            $blocks[] = [
                'icon'        => new BlockElement('icon',        'select',   $icon),
                'colorScheme' => new BlockElement('colorScheme', 'select',   $color),
                'title'       => new BlockElement('title',       'input',    $title),
                'desc'        => new BlockElement('desc',        'textarea', $desc),
            ];
        }
        $v = new AboutValues();
        $v->setBadgeLabel('Čomu veríme', self::LANG);
        $v->setHeading('Naše hodnoty', self::LANG);
        $v->setItems($blocks, self::LANG);
        return $v;
    }

    private function makeWhyUs(): AboutWhyUs
    {
        $reasons = [
            ['Award',     'Overené filtre pre 500+ modelov víriviek', 'Náš vyhľadávač Vám nájde presný filter pre Vašu vírivku. Žiadne hádanie.'],
            ['Droplets',  'Bezchlorová chémia Royal Pure',            'Šetrná k pokožke, účinná voči baktériám. Bez zápachu, bez podráždenia.'],
            ['Truck',     'Rýchla expedícia do 24 hodín',             'Objednávky prijaté do 14:00 expedujeme v ten istý deň. Doručenie 1-3 dni.'],
            ['Headphones', 'Osobný poradca zadarmo',                  'Neviete si vybrať? Zavolajte nám alebo napíšte – poradíme s výberom filtra aj chémie.'],
            ['Shield',    'Záruka spokojnosti 30 dní',                'Ak Vám produkt nevyhovuje, vráťte ho do 30 dní. Bez otázok, bez starostí.'],
            ['TrendingUp', 'Výhodné sety a vernostný program',        'Filter + chémia spolu lacnejšie. Stáli zákazníci získavajú extra zľavy.'],
        ];
        $blocks = [];
        foreach ($reasons as [$icon, $title, $desc]) {
            $blocks[] = [
                'icon'  => new BlockElement('icon',  'select',   $icon),
                'title' => new BlockElement('title', 'input',    $title),
                'desc'  => new BlockElement('desc',  'textarea', $desc),
            ];
        }
        $w = new AboutWhyUs();
        $w->setImageAlt('Filtrácia vody', self::LANG);
        $w->setQuoteText('„Každý si zaslúži čistú vodu vo vírivke – bez chloru a bez starostí."', self::LANG);
        $w->setQuoteAuthor('— Martin Grolmus, zakladateľ', self::LANG);
        $w->setBadgeLabel('Prečo Royal Filter', self::LANG);
        $w->setHeading('6 dôvodov, prečo nám zákazníci dôverujú', self::LANG);
        $w->setItems($blocks, self::LANG);
        return $w;
    }

    private function makeTeam(): AboutTeam
    {
        $members = [
            ['MG', 'Martin Grolmus',   'Zakladateľ & CEO',     'Vášnivý majiteľ vírivky, ktorý sa rozhodol vyriešiť problém s dostupnosťou kvalitných filtrov na SK a CZ trhu.', '#2d9596', '#1a3c5e'],
            ['TK', 'Tomáš Kovács',     'Zákaznícka podpora',   'Poradí Vám s výberom filtra aj chémie. Odborník na kompatibilitu filtrov s 500+ modelmi víriviek.',           '#1a3c5e', '#2d6596'],
            ['LP', 'Lucia Petrášová',  'Logistika & expedícia','Stará sa o to, aby Vaša objednávka dorazila včas a v perfektnom stave. Expedícia do 24h.',                   '#2d9596', '#48bb78'],
        ];
        $blocks = [];
        foreach ($members as [$initials, $name, $role, $desc, $from, $to]) {
            $blocks[] = [
                'initials'     => new BlockElement('initials',     'input',    $initials),
                'name'         => new BlockElement('name',         'input',    $name),
                'role'         => new BlockElement('role',         'input',    $role),
                'desc'         => new BlockElement('desc',         'textarea', $desc),
                'gradientFrom' => new BlockElement('gradientFrom', 'input',    $from),
                'gradientTo'   => new BlockElement('gradientTo',   'input',    $to),
            ];
        }
        $t = new AboutTeam();
        $t->setBadgeLabel('Za Royal Filter stojí', self::LANG);
        $t->setHeading('Náš tím', self::LANG);
        $t->setMembers($blocks, self::LANG);
        return $t;
    }

    private function makeReviews(): AboutReviewsTeaser
    {
        $reviews = [
            ['Peter M.', 'Bratislava', 'Konečne som našiel presný filter pre moju Intex vírivku. Expedícia do 24h, všetko sedí. Odporúčam!', 5, true],
            ['Jana K.',  'Brno',       'Bezchlorová chémia je fantastická – žiadny zápach, voda je krištáľovo čistá. Sme nadšení.',          5, true],
            ['Marek H.', 'Košice',     'Super komunikácia, Martin mi poradil po telefóne s výberom setu. Filter + chémia za super cenu.',    5, true],
        ];
        $blocks = [];
        foreach ($reviews as [$name, $loc, $text, $rating, $verified]) {
            $blocks[] = [
                'name'     => new BlockElement('name',     'input',    $name),
                'location' => new BlockElement('location', 'input',    $loc),
                'text'     => new BlockElement('text',     'textarea', $text),
                'rating'   => new BlockElement('rating',   'numeric',  $rating),
                'verified' => new BlockElement('verified', 'checkbox', $verified),
            ];
        }
        $r = new AboutReviewsTeaser();
        $r->setBadgeLabel('Čo hovoria zákazníci', self::LANG);
        $r->setHeading('Hodnotenia od overených zákazníkov', self::LANG);
        $r->setViewAllLabel('Zobraziť všetkých 847 recenzií', self::LANG);
        $r->setViewAllHref($this->directLink('/store-rating'), self::LANG);
        $r->setItems($blocks, self::LANG);
        return $r;
    }

    private function makeCta(): AboutCtaBanner
    {
        $c = new AboutCtaBanner();
        $c->setHeading('Neviete aký filter potrebujete?', self::LANG);
        $c->setSubheading('Zadajte značku a model Vašej vírivky a my Vám nájdeme kompatibilný filter. Alebo nám jednoducho zavolajte.', self::LANG);
        $c->setPrimaryButtonLabel('Nájsť filter pre moju vírivku', self::LANG);
        $c->setPrimaryButtonHref($this->directLink('/hottub/verified'), self::LANG);
        $c->setSecondaryButtonLabel('Zavolať nám', self::LANG);
        $c->setSecondaryButtonIconName('Phone', self::LANG);
        $c->setSecondaryButtonHref($this->directLink('/contacts'), self::LANG);
        return $c;
    }
}
