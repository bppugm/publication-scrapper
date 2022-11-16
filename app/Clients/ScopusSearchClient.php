<?php

namespace App\Clients;

use GuzzleHttp\Client;

class ScopusSearchClient
{
    protected $client;

    public $queries = [
        'food' => 'TITLE-ABS-KEY ( ( {land tenure rights}  OR  ( smallholder  AND  ( farm  OR  forestry  OR  pastoral  OR  agriculture  OR  fishery  OR  {food producer}  OR  {food producers} ) )  OR  malnourish*  OR  malnutrition  OR  undernourish*  OR  {undernutrition}  OR  {agricultural production}  OR  {agricultural productivity}  OR  {agricultural practices}  OR  {agricultural management}  OR  {food production}  OR  {food productivity}  OR  {food security}  OR  {food insecurity}  OR  {land right}  OR  {land rights}  OR  {land reform}  OR  {land reforms}  OR  {resilient agricultural practices}  OR  ( agriculture  AND  potassium )  OR  fertili?er  OR  {food nutrition improvement}  OR  {hidden hunger}  OR  {genetically modified food}  OR  ( gmo  AND  food )  OR  {agroforestry practices}  OR  {agroforestry management}  OR  {agricultural innovation}  OR  ( {food security}  AND  {genetic diversity} )  OR  ( {food market}  AND  ( restriction  OR  tariff  OR  access  OR  {north south divide}  OR  {development governance} ) )  OR  {food governance}  OR  {food supply chain}  OR  {food value chain}  OR  {food commodity market}  AND NOT  {disease} ) )',

        'health' => 'TITLE-ABS-KEY ( ( {land tenure rights}  OR  ( smallholder  AND  ( farm  OR  forestry  OR  pastoral  OR  agriculture  OR  fishery  OR  {food producer}  OR  {food producers} ) )  OR  malnourish*  OR  malnutrition  OR  undernourish*  OR  {undernutrition}  OR  {agricultural production}  OR  {agricultural productivity}  OR  {agricultural practices}  OR  {agricultural management}  OR  {food production}  OR  {food productivity}  OR  {food security}  OR  {food insecurity}  OR  {land right}  OR  {land rights}  OR  {land reform}  OR  {land reforms}  OR  {resilient agricultural practices}  OR  ( agriculture  AND  potassium )  OR  fertili?er  OR  {food nutrition improvement}  OR  {hidden hunger}  OR  {genetically modified food}  OR  ( gmo  AND  food )  OR  {agroforestry practices}  OR  {agroforestry management}  OR  {agricultural innovation}  OR  ( {food security}  AND  {genetic diversity} )  OR  ( {food market}  AND  ( restriction  OR  tariff  OR  access  OR  {north south divide}  OR  {development governance} ) )  OR  {food governance}  OR  {food supply chain}  OR  {food value chain}  OR  {food commodity market}  AND NOT  {disease} ) )',

        'disaster' => 'TITLE-ABS-KEY((disaster AND management) OR (disaster AND mitigation) OR (disaster AND risk AND assessment) OR (disaster AND risk AND management) OR (disaster AND risk AND reduction) OR (hazard AND mapping) OR {hazard modelling} OR {susceptibility mapping} OR {vulnerability mapping} OR {coping capacity} OR (community AND resilience) OR preparedness OR {early warning system} OR (geological AND hazard) OR (volcan* AND hazard) OR (tectonic AND hazard) OR (meteorological AND hazard) OR (hydrological AND hazard) OR (hydrometeorological AND hazard) OR earthquake OR (eruption AND disaster) OR landslide OR flood OR tsunami OR ((plague OR pandemic OR endemic OR epidemic) AND disaster) OR (drought AND disaster) OR cyclone OR {storm surge})',

        'energy' => 'TITLE-ABS-KEY ( ( {energy efficiency}  OR  {energy consumption}  OR  {energy transition}  OR  {clean energy technology}  OR  {energy equity}  OR  {energy justice}  OR  {energy poverty}  OR  {energy policy}  OR  renewable*  OR  {2000 Watt society}  OR  {smart micro-grid}  OR  {smart grid}  OR  {smart microgrid}  OR  {smart micro-grids}  OR  {smart grids}  OR  {smart microgrids}  OR  {smart meter}  OR  {smart meters}  OR  {affordable electricity}  OR  {electricity consumption}  OR  {reliable electricity}  OR  {clean fuel}  OR  {clean cooking fuel}  OR  {fuel poverty}  OR  energiewende  OR  {life-cycle assessment}  OR  {life cycle assessment}  OR  {life-cycle assessments}  OR  {life cycle assessments}  OR  ( {photochemistry}  AND  {renewable energy} )  OR  photovoltaic  OR  {photocatalytic water splitting}  OR  {hydrogen production}  OR  {water splitting}  OR  {lithium-ion batteries}  OR  {lithium-ion battery}  OR  {heat network}  OR  {district heat}  OR  {district heating}  OR  {residential energy consumption}  OR  {domestic energy consumption}  OR  {energy security}  OR  {rural electrification}  OR  {energy ladder}  OR  {energy access}  OR  {energy conservation}  OR  {low-carbon society}  OR  {hybrid renewable energy system}  OR  {hybrid renewable energy systems}  OR  {fuel switching}  OR  ( {foreign development aid}  AND  {renewable energy} )  OR  {energy governance}  OR  ( {official development assistance}  AND  {electricity} )  OR  ( {energy development}  AND  {developing countries} ) )  AND NOT  ( {wireless sensor network}  OR  {wireless sensor networks} ) )',

        'climate' => "TITLE-ABS-KEY ( ( {climate action}  OR  {climate adaptation}  OR  {climate change}  OR  {climate capitalism}  OR  ipcc  OR  {climate effect}  OR  {climate equity}  OR  {climate feedback}  OR  {climate finance}  OR  {climate change financing}  OR  {climate forcing}  OR  {climate governance}  OR  {climate impact}  OR  {climate investment}  OR  {climate justice}  OR  {climate mitigation}  OR  {climate model}  OR  {climate models}  OR  {climate modeling}  OR  {climate modelling}  OR  {climate policy}  OR  {climate policies}  OR  {climate risk}  OR  {climate risks}  OR  {climate services}  OR  {climate service}  OR  {climate prediction}  OR  {climate predictions}  OR  {climate signal}  OR  {climate signals}  OR  {climate tipping point}  OR  {climate variation}  OR  {climate variations}  OR  ecoclimatology  OR  eco-climatology  OR  {Green Climate Fund}  OR  {regional climate}  OR  {regional climates}  OR  {urban climate}  OR  {urban climates}  OR  ( climate  AND  ( {adaptive management}  OR  awareness  OR  bioeconomy  OR  carbon  OR  {decision-making}  OR  {disaster risk reduction}  OR  {environmental education}  OR  {sustainable development education}  OR  {energy conservation}  OR  emission*  OR  extreme  OR  {food chain}  OR  {food chains}  OR  framework  OR  hazard*  OR  island*  OR  {land use}  OR  megacit*  OR  consumption  OR  production  OR  {small island developing states}  OR  anthropocene  OR  atmospher*  OR  {clean development mechanism}  OR  {glacier retreat}  OR  warming  OR  greenhouse  OR  {ice-ocean interaction}  OR  {ice-ocean interactions}  OR  {nitrogen cycle}  OR  {nitrogen cycles}  OR  {ocean acidification}  OR  {radiative forcing}  OR  {sea ice}  OR  {sea level}  OR  {sea levels}  OR  {thermal expansion}  OR  unfccc  OR  ozone ) ) )  AND NOT  ( {drug}  OR  {geomorphology} ) )"
    ];

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'https://api.elsevier.com/content/search/scopus',
            'headers' => [
                'X-ELS-APIKey' => env('ELSEVIER_API_KEY')
            ]
        ]);
    }

    /**
     * Returns Guzzle Http Client
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Get pregenerated query
     *
     * @param string $key
     * @return string
     */
    public function getQuery($key)
    {
        if (key_exists($key, $this->queries)) {
            return $this->queries[$key];
        }

        return "";
    }
}
