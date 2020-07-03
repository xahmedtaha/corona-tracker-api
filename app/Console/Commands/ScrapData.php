<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;
use Goutte\Client;

class ScrapData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:scrap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrap The Required Data From Worldometer';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://www.worldometers.info/coronavirus/');
        $keys = [];
        $countries = [];
        $this->info("Checking For Required Data Headers");
        $crawler->filter('#main_table_countries_today tr:first-child th')->each(function($node, $key) use (&$keys){
            if(strpos($node->text(), 'Country') !== false){
                $keys['name'] = $key;
            }else if(strpos($node->text(), 'TotalCases') !== false){
                $keys['confirmed'] = $key;
            }else if(strpos($node->text(), 'TotalRecovered') !== false){
                $keys['recovered'] = $key;
            }else if(strpos($node->text(), 'TotalDeaths') !== false){
                $keys['deaths'] = $key;
            }else if(strpos($node->text(), 'NewCases') !== false){
                $keys['new_confirmed'] = $key;
            }else if(strpos($node->text(), 'NewDeaths') !== false){
                $keys['new_deaths'] = $key;
            }else if(strpos($node->text(), 'TotalTests') !== false){
                $keys['tests'] = $key;
            }else if(strpos($node->text(), 'Population') !== false){
                $keys['population'] = $key;
            }
        });
        $this->info("Extracting Required Data Values");
        $crawler->filter('#main_table_countries_today tr:not(:first-child):not([style*="display: none"])')->each(function($node, $key) use (&$countries, $keys){
            $country = [];
            foreach ($keys as $name => $index){
                $index += 1;
                $country[$name] = $name === 'name' ? $node->filter("td:not(:first-child):nth-child({$index})")->text() : (($value = intval(str_replace('+', '', str_replace(',', '', $node->filter("td:not(:first-child):nth-child({$index})")->text())))) !== 0 ? $value : null);
            }
            $countries[] = $country;
        });
        $this->comment("Sample Of The Extracted Data");
        var_dump($countries[0]);
        $this->info("\n\nInserting Extracted Data In DB");
        $bar = $this->output->createProgressBar(count($countries));
        $bar->start();
        foreach ($countries as $country){
            Country::updateOrCreate(['name' => $country['name']], array_diff_key($country, array_flip(["name"])));
            $bar->advance();
        }
        $bar->finish();
        $this->info("\n\nDONE");
    }
}
