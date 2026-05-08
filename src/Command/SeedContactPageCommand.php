<?php

declare(strict_types=1);

namespace App\Command;

use OpenDxp\Model\DataObject\ContentPage;
use OpenDxp\Model\DataObject\Data\BlockElement;
use OpenDxp\Model\DataObject\Data\Link;
use OpenDxp\Model\DataObject\Fieldcollection;
use OpenDxp\Model\DataObject\Fieldcollection\Data\ContactAdvisor;
use OpenDxp\Model\DataObject\Fieldcollection\Data\ContactFaqShortcuts;
use OpenDxp\Model\DataObject\Fieldcollection\Data\ContactForm;
use OpenDxp\Model\DataObject\Fieldcollection\Data\ContactHero;
use OpenDxp\Model\DataObject\Fieldcollection\Data\ContactLocations;
use OpenDxp\Model\DataObject\Fieldcollection\Data\ContactQuickCards;
use OpenDxp\Model\DataObject\Fieldcollection\Data\ContactTrust;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed-contact-page',
    description: 'Populate Contacts (id 2838) ContentPage with the 7 Contact bricks based on the Figma design.'
)]
class SeedContactPageCommand extends Command
{
    private const string LANG = 'sk';
    private const int CONTACT_ID = 2838;

    private const string MAP_BRNO = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2607.5!2d16.6082!3d49.2058!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47129450e2ef3bff%3A0x8e6e1acf0c86adee!2sKopern%C3%ADkova%201%2C%20615%2000%20Brno%2C%20Czechia!5e0!3m2!1ssk!2ssk!4v1700000000000!5m2!1ssk!2ssk';
    private const string MAP_ZM = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2640.0!2d18.3957!3d48.3831!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x476b3e5c0e8aa1c3%3A0x400f7d1c69779a0!2sDob%C5%A1insk%C3%A9ho%2034%2C%20953%2001%20Zlat%C3%A9%20Moravce%2C%20Slovakia!5e0!3m2!1ssk!2ssk!4v1700000000001!5m2!1ssk!2ssk';
    private const string NAV_BRNO = 'https://www.google.com/maps/dir//Kopern%C3%ADkova+1,+615+00+Brno,+Czechia';
    private const string NAV_ZM = 'https://www.google.com/maps/dir//Dob%C5%A1insk%C3%A9ho+34,+953+01+Zlat%C3%A9+Moravce,+Slovakia';

    protected function configure(): void
    {
        $this->addOption('dry-run', null, InputOption::VALUE_NONE, 'Do not save');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dryRun = (bool) $input->getOption('dry-run');

        $page = ContentPage::getById(self::CONTACT_ID);
        if (!$page instanceof ContentPage) {
            $io->error(sprintf('ContentPage %d not found', self::CONTACT_ID));
            return Command::FAILURE;
        }
        $io->section(sprintf('CONTACTS — ContentPage %d (%s)', self::CONTACT_ID, $page->getKey()));

        $elements = new Fieldcollection();
        $elements->add($this->makeHero());
        $elements->add($this->makeQuickCards());
        $elements->add($this->makeLocations());
        $elements->add($this->makeForm());
        $elements->add($this->makeAdvisor());
        $elements->add($this->makeFaqShortcuts());
        $elements->add($this->makeTrust());

        $page->setElements($elements);

        if ($dryRun) {
            $io->note('[dry-run] would save ' . $elements->getCount() . ' elements');
        } else {
            $page->save();
            $io->success('Saved ' . $elements->getCount() . ' elements');
        }

        return Command::SUCCESS;
    }

    private function makeHero(): ContactHero
    {
        $h = new ContactHero();
        $h->setBreadcrumb('Kontakty', self::LANG);
        $h->setTitle('Kontaktujte nás', self::LANG);
        $h->setSubtitle('Sme tu pre Vás! Či už máte otázku k produktom, objednávke alebo potrebujete poradiť s výberom filtra do vírivky – neváhajte sa ozvať.', self::LANG);
        return $h;
    }

    private function makeQuickCards(): ContactQuickCards
    {
        $items = [
            ['Phone',  'blue',  'Telefón',          '+421 911 924 841',       'Po – Pi: 8:00 – 16:00',         'tel',    '+421911924841'],
            ['Mail',   'teal',  'E-mail',           'martin@stagro.sk',       'Odpovieme do 24 hodín',         'mailto', 'martin@stagro.sk'],
            ['Clock',  'amber', 'Otváracie hodiny', 'Po – Pi: 8:00 – 16:00',  'Víkendy a sviatky: zatvorené',  'none',   ''],
            ['Truck',  'green', 'Expedícia',        'Do 24 hodín',            'Pri objednávke do 14:00',       'none',   ''],
        ];

        $blocks = [];
        foreach ($items as [$icon, $color, $label, $value, $sub, $hrefType, $hrefValue]) {
            $blocks[] = [
                'iconName'    => new BlockElement('iconName',    'input',  $icon),
                'colorScheme' => new BlockElement('colorScheme', 'select', $color),
                'label'       => new BlockElement('label',       'input',  $label),
                'cardValue'   => new BlockElement('cardValue',   'input',  $value),
                'sub'         => new BlockElement('sub',         'input',  $sub),
                'hrefType'    => new BlockElement('hrefType',    'select', $hrefType),
                'hrefValue'   => new BlockElement('hrefValue',   'input',  $hrefValue),
            ];
        }

        $c = new ContactQuickCards();
        $c->setItems($blocks, self::LANG);
        return $c;
    }

    private function makeLocations(): ContactLocations
    {
        $brno = [
            'badgeLabel'        => 'Prevádzka',
            'badgeColor'        => 'green',
            'iconName'          => 'Building2',
            'iconColor'         => 'primary',
            'title'             => 'Prevádzka Brno',
            'subtitle'          => 'Výdaj tovaru a osobný odber',
            'mapEmbedUrl'       => self::MAP_BRNO,
            'addressStreet'     => 'Koperníkova 1',
            'addressCityZip'    => '615 00 Brno, Česko',
            'openingHoursTitle' => 'Otváracie hodiny',
            'openingHours'      => "Pondelok – Piatok=8:00 – 16:00\nSobota – Nedeľa=Zatvorené=red",
            'businessInfoTitle' => '',
            'businessInfo'      => '',
            'phone'             => '+421 911 924 841',
            'website'           => '',
            'websiteLabel'      => '',
            'navigationUrl'     => self::NAV_BRNO,
            'navigationLabel'   => 'Navigovať na prevádzku',
            'primaryButton'     => true,
        ];

        $zm = [
            'badgeLabel'        => 'Sídlo firmy',
            'badgeColor'        => 'blue',
            'iconName'          => 'FileText',
            'iconColor'         => 'blue',
            'title'             => 'Sídlo spoločnosti',
            'subtitle'          => 'Fakturačná adresa',
            'mapEmbedUrl'       => self::MAP_ZM,
            'addressStreet'     => 'Dobšinského 34',
            'addressCityZip'    => '953 01 Zlaté Moravce, Slovensko',
            'openingHoursTitle' => '',
            'openingHours'      => '',
            'businessInfoTitle' => 'Firemné údaje',
            'businessInfo'      => "IČO=55 403 590\nDIČ=2121962498\nIČ DPH=SK2121962498",
            'phone'             => '',
            'website'           => 'https://royal-filter.com',
            'websiteLabel'      => 'www.royal-filter.com',
            'navigationUrl'     => self::NAV_ZM,
            'navigationLabel'   => 'Navigovať na sídlo',
            'primaryButton'     => false,
        ];

        $blocks = [];
        foreach ([$brno, $zm] as $loc) {
            $entry = [];
            foreach ($loc as $name => $value) {
                $type = match (true) {
                    in_array($name, ['badgeColor', 'iconColor'], true) => 'select',
                    $name === 'mapEmbedUrl', $name === 'openingHours', $name === 'businessInfo' => 'textarea',
                    $name === 'primaryButton' => 'checkbox',
                    default => 'input',
                };
                $entry[$name] = new BlockElement($name, $type, $value);
            }
            $blocks[] = $entry;
        }

        $l = new ContactLocations();
        $l->setHeading('Naše prevádzky', self::LANG);
        $l->setItems($blocks, self::LANG);
        return $l;
    }

    private function makeForm(): ContactForm
    {
        $subjects = [
            ['general',     'Všeobecný dotaz'],
            ['order',       'Otázka k objednávke'],
            ['product',     'Poradenstvo k produktom'],
            ['claim',       'Reklamácia'],
            ['cooperation', 'Spolupráca / B2B'],
        ];
        $subjectBlocks = [];
        foreach ($subjects as [$value, $label]) {
            $subjectBlocks[] = [
                'optionValue' => new BlockElement('optionValue', 'input', $value),
                'label'       => new BlockElement('label',       'input', $label),
            ];
        }

        $f = new ContactForm();
        $f->setSubmitEndpoint('');
        $f->setHeading('Napíšte nám', self::LANG);
        $f->setSubheading('Vyplňte formulár a odpovieme Vám čo najskôr.', self::LANG);
        $f->setNameLabel('Meno a priezvisko *', self::LANG);
        $f->setNamePlaceholder('Vaše meno', self::LANG);
        $f->setEmailLabel('E-mail *', self::LANG);
        $f->setEmailPlaceholder('vas@email.sk', self::LANG);
        $f->setPhoneLabel('Telefón', self::LANG);
        $f->setPhonePlaceholder('+421 ...', self::LANG);
        $f->setSubjectLabel('Predmet', self::LANG);
        $f->setSubjects($subjectBlocks, self::LANG);
        $f->setMessageLabel('Správa *', self::LANG);
        $f->setMessagePlaceholder('Napíšte nám svoju otázku alebo požiadavku...', self::LANG);
        $f->setSubmitLabel('Odoslať správu', self::LANG);
        $f->setSuccessHeading('Správa odoslaná!', self::LANG);
        $f->setSuccessMessage('Ďakujeme za Vašu správu. Odpovieme Vám najneskôr do 24 hodín.', self::LANG);
        return $f;
    }

    private function makeAdvisor(): ContactAdvisor
    {
        $a = new ContactAdvisor();
        $a->setPhone('+421911924841');
        $a->setEmail('martin@stagro.sk');
        $a->setInitials('MG', self::LANG);
        $a->setName('Martin Grolmus', self::LANG);
        $a->setRole('Váš poradca', self::LANG);
        $a->setBio('Potrebujete poradiť s výberom filtra alebo chémie do vírivky? Rád Vám pomôžem!', self::LANG);
        $a->setPhoneLabel('+421 911 924 841', self::LANG);
        $a->setEmailLabel('martin@stagro.sk', self::LANG);
        return $a;
    }

    private function makeFaqShortcuts(): ContactFaqShortcuts
    {
        $items = [
            'Ako zistím aký filter potrebujem?',
            'Aké sú spôsoby doručenia?',
            'Ako funguje bezchlorová chémia?',
            'Aká je záručná doba?',
            'Ako podať reklamáciu?',
        ];
        $blocks = [];
        foreach ($items as $q) {
            $blocks[] = [
                'question'  => new BlockElement('question',  'input',  $q),
                'linkType'  => new BlockElement('linkType',  'select', 'none'),
                'linkValue' => new BlockElement('linkValue', 'input',  ''),
            ];
        }
        $f = new ContactFaqShortcuts();
        $f->setHeading('Časté otázky', self::LANG);
        $f->setItems($blocks, self::LANG);
        return $f;
    }

    private function makeTrust(): ContactTrust
    {
        $t = new ContactTrust();
        $t->setStarsCount(5);
        $t->setScore('4.8 / 5', self::LANG);
        $t->setCount('847', self::LANG);
        $t->setReviewLabel('Hodnotenie od <strong>{count} zákazníkov</strong>', self::LANG);
        $t->setLinkLabel('Zobraziť recenzie →', self::LANG);
        $t->setLinkUrl($this->directLink('/store-rating'), self::LANG);
        return $t;
    }

    private function directLink(string $href): Link
    {
        $link = new Link();
        $link->setLinktype('direct');
        $link->setDirect($href);

        return $link;
    }
}
