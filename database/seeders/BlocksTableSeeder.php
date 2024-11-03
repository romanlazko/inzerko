<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BlocksTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('blocks')->delete();
        
        \DB::table('blocks')->insert(array (
            0 => 
            array (
                'id' => 1,
                'slug' => '1-uvodni-ustanoveni',
                'title' => '1. Úvodní ustanovení',
            'content' => 'Tento dokument upravuje smluvní podmínky pro využívání služby inzerko.cz (dále jen „Služba“), kterou provozuje [Jméno provozovatele]. Tyto podmínky jsou závazné pro všechny uživatele, kteří využívají Službu pro vkládání inzerátů či jiné činnosti spojené se zprostředkováním nabídky a poptávky.


',
                'is_active' => NULL,
                'page_id' => 1,
                'created_at' => '2024-11-03 18:24:51',
                'updated_at' => '2024-11-03 18:49:41',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'slug' => '2-registrace-a-uzivatelsky-ucet',
                'title' => '2. Registrace a uživatelský účet',
                'content' => 'Každý uživatel, který chce plně využívat možnosti Služby, je povinen se registrovat. Uživatel při registraci poskytuje pravdivé a úplné informace o své osobě, včetně skutečného jména a příjmení. Provozovatel si vyhrazuje právo odstranit inzeráty uživatele nebo zablokovat účet, pokud zjistí, že uživatel poskytl falešné údaje. 

Uživatel je odpovědný za ochranu svého přístupového hesla a zavazuje se, že nebude svůj účet poskytovat třetím stranám. Provozovatel nenese odpovědnost za zneužití účtu v důsledku nedbalosti uživatele.',
                'is_active' => NULL,
                'page_id' => 1,
                'created_at' => '2024-11-03 18:31:08',
                'updated_at' => '2024-11-03 18:36:14',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'slug' => '3-pravidla-pro-vkladani-inzeratu',
                'title' => '3. Pravidla pro vkládání inzerátů',
                'content' => 'Uživatel má právo vkládat inzeráty za účelem prodeje zboží či nabídky služeb. Obsah inzerátů musí být pravdivý, nesmí uvádět nepravdivé či zavádějící informace a musí být v souladu s platnými právními předpisy, zejména zákonem č. 480/2004 Sb., o některých službách informační společnosti.

Uživatel není oprávněn prostřednictvím inzerátů propagovat svou podnikatelskou činnost. Komerční prodej zboží a služeb je zpoplatněn podle dohody s provozovatelem webu v souladu s dokumentem o reklamě (odkaz). Každý inzerát musí obsahovat pouze jeden konkrétní produkt v dané konfiguraci. Není povoleno nabízet jeden a ten samý produkt v množném čísle.

**Seznam zakázaných položek a služeb:** webové stránky a loga firmy, nadměrné množství hvězdiček, vykřičníků a podobných znaků (v textu, v obrázku nebo přes obrázek), orámování obrázků, hubnutí, Multilevel (MLM) a provizní systémy, emailing a jiné bezpracné výdělky, práce s podmínkou poplatku předem, kopírování disket, CD, DVD, firemní činnosti a zboží na objednávku (s výjimkou nabízení služeb), opakovaná inzerce stejného nového zboží, neomezené množství zboží, zboží koupené za účelem dalšího prodeje, padělky, kopie, napodobeniny, chip tuning, powerboxy, diagnostické sady, antiradary, doklady a technické průkazy, pohonné hmoty, livechaty a práce tanečnice, společnice, konzumentky a hostesky v zahraničí, výkupy, poptávky v realitách, erotické inzeráty, erotické pomůcky, eKnihy, půjčky, úvěry a hypotéky, exekuce, oddlužení, seznamy klíčových slov, léky a potravinové doplňky, cigarety, e-cigarety, tabák, alkohol, bitcoiny, litecoiny, minery, herní účty, mrtvá zvířata, maso, automatické zbraně (útočné zbraně, samopaly, kulomety), respirátory, roušky (kromě ručně šitých roušek).
',
                'is_active' => NULL,
                'page_id' => 1,
                'created_at' => '2024-11-03 18:31:08',
                'updated_at' => '2024-11-03 18:36:14',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'slug' => '4-prava-a-povinnosti-provozovatele',
                'title' => '4. Práva a povinnosti provozovatele',
                'content' => 'Provozovatel má právo kdykoliv odstranit inzerát, který porušuje tyto podmínky, nebo je v rozporu s platnými zákony. Provozovatel je oprávněn zablokovat uživatelský účet, pokud uživatel opakovaně porušuje tyto podmínky nebo existuje důvodné podezření na zneužití účtu.

Provozovatel poskytuje Službu „tak, jak je“, a neodpovídá za technické problémy, výpadky nebo ztrátu dat spojenou s používáním Služby. Provozovatel si vyhrazuje právo kdykoliv upravit funkce a nabídku Služby bez předchozího upozornění.
',
                'is_active' => NULL,
                'page_id' => 1,
                'created_at' => '2024-11-03 18:35:27',
                'updated_at' => '2024-11-03 18:36:14',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'slug' => '5-ochrana-osobnich-udaju',
                'title' => '5. Ochrana osobních údajů',
            'content' => 'Provozovatel zpracovává osobní údaje uživatelů v souladu s Nařízením Evropského parlamentu a Rady (EU) 2016/679 (GDPR) a zákonem č. 101/2000 Sb., o ochraně osobních údajů. Osobní údaje jsou zpracovávány pouze za účelem poskytování Služby a nebudou předávány třetím stranám bez souhlasu uživatele, s výjimkou případů vyžadovaných zákonem.
',
                'is_active' => NULL,
                'page_id' => 1,
                'created_at' => '2024-11-03 18:38:50',
                'updated_at' => '2024-11-03 18:38:50',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'slug' => '6-odpovednost-a-nahrada-skody',
                'title' => '6. Odpovědnost a náhrada škody',
                'content' => 'Každý uživatel samostatně rozhoduje o prodeji či nákupu konkrétního zboží a sám uzavírá dohodu s prodávajícím či kupujícím. Služba poskytuje pouze možnost vkládat a prohlížet inzeráty a propojuje uživatele mezi sebou. Uživatel bere na vědomí riziko možného podvodu ze strany ostatních uživatelů a jedná na vlastní nebezpečí.',
                'is_active' => NULL,
                'page_id' => 1,
                'created_at' => '2024-11-03 18:38:50',
                'updated_at' => '2024-11-03 18:38:50',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'slug' => '7-ukonceni-smlouvy',
                'title' => '7. Ukončení smlouvy',
                'content' => 'Uživatel může kdykoliv smazat svůj účet přímo ve svém osobním kabinetu. Provozovatel si vyhrazuje právo zrušit uživatelský účet bez předchozího upozornění, pokud uživatel poruší tyto podmínky nebo zneužije Službu.',
                'is_active' => NULL,
                'page_id' => 1,
                'created_at' => '2024-11-03 18:38:50',
                'updated_at' => '2024-11-03 18:38:50',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'slug' => '8-zmena-smluvnich-podminek',
                'title' => '8. Změna smluvních podmínek',
                'content' => 'Provozovatel je oprávněn kdykoliv jednostranně změnit tyto podmínky. O takových změnách bude uživatel informován prostřednictvím e-mailu nebo zveřejněním na webových stránkách Služby. Pokračování v užívání Služby po provedení změn znamená souhlas se změněnými podmínkami.',
                'is_active' => NULL,
                'page_id' => 1,
                'created_at' => '2024-11-03 18:38:50',
                'updated_at' => '2024-11-03 18:38:50',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'slug' => '-1',
                'title' => NULL,
                'content' => 'Na webových stránkách inzerko.cz je implementován reklamní systém Google AdWords. Momentálně nejsou nabízeny žádné reklamní bannery. Provozovatel webových stránek si vyhrazuje právo kdykoliv změnit podmínky reklamy.
Pro uživatele, kteří využívají server pro své komerční účely, může být zavedena poplatek za používání služby.
',
                'is_active' => NULL,
                'page_id' => 2,
                'created_at' => '2024-11-03 18:39:22',
                'updated_at' => '2024-11-03 18:39:22',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}