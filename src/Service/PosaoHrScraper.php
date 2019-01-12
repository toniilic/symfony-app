<?php

namespace App\Service;

/*
$client = new Client();
$guzzleClient = new GuzzleClient(array(
    'timeout' => 60,
));
$client->setClient($guzzleClient);

$posaoScraper = new PosaoHrScraper($client);
$pageUrls = $posaoScraper->scrapeForUrls(2,
    "https://www.posao.hr/poslovi/djelatnost/administrativna-zanimanja/zupanija/primorsko-goranska/"
);
dump($pageUrls);*/

class PosaoHrScraper {

    protected $client;
    protected $crawler;
    protected $url;
    protected $currentPage;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function scrapeForUrls($pagesToTraverse = 10, $url)
    {
        $this->url = $url;

        $pageUrls = array();

        $this->crawler = $this->client->request('GET', $this->url);

        for($i = 1; $i != $pagesToTraverse + 1; $i++) {

            $this->currentPage = $i;
            $this->selectNextPage($i);

            array_push($pageUrls, $this->scrapePageUrls());
        }

        $pageUrls = mergeArray($pageUrls);

        return $pageUrls;
    }

    protected function selectNextPage($i)
    {
        if($i > 1) {
            $url = $this->url . 'stranica/' . $i;
        } else {
            $url = $this->url;
        }

        $this->crawler = $this->client->request('GET', $url);
    }

    protected function scrapePageUrls()
    {
        $filter = 'td.title a';

        $pageUrls = array();
        $this->crawler->filter($filter)->each(function ($node) use (&$pageUrls) {

            $title = $node->text();
            $linksCrawler = $this->crawler->selectLink(trim($node->text()));
            $link = $linksCrawler->link();

            $pageUrls[$title] = $link->getUri();
        });

        return $pageUrls;
    }
}

class PosaoPageScraper {
    protected $client;
    protected $crawler;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function scrapeUrls($pageUrls, $region, $profession)
    {
        $data = array();

        foreach($pageUrls as $key => $value) {
            $data[] = $this->scrapePage($key, $value, $region, $profession);
        }

        return $data;
    }

    public function scrapePage($key, $value, $region, $profession)
    {
        $title = $key;

        $this->crawler = $this->client->request('GET', $value);
        /*$title = $this->crawler->filter('#page-title h1')->text();*/

        $data = [
            'title' => $title,
            'region' => $region,
            'profession' => $profession
        ];

        return $data;
    }
}

function mergeArray(array $array) {
    $return = array();
    array_walk_recursive($array, function($a, $b) use (&$return) { $return[$b] = $a; });
    return $return;
}
