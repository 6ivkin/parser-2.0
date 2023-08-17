<?php
require_once('./vendor/autoload.php');

use Symfony\Component\DomCrawler\Crawler as Crawler;

ini_set('max_execution_time', 0);

/**
 * A class containing the logic of site parsing https://www.santech.ru
 * Class Parser
 */
class ArmsnabParser
{
    private string $url = "https://armsnabrf.ru";

    /**
     * function for getting all categories
     * the data is written to a file
     * @return void
     */
    public function getGroupsLinks(): void
    {
        file_put_contents('./armsnab/groups.txt', '');

        $html = file_get_contents($this->url);
        $crawler = new Crawler($html);

        $crawler->filter('#zone15')->filter('.container')->filter('.blk_body_wrap')->filter('.subdivision-items')->filter('li')->each(function ($node) {
            $url = $this->url . $node->filter('.wrapper')->filter('.name')->filter('a')->attr('href') . PHP_EOL;
            echo $url;
            file_put_contents('./armsnab/groups.txt', $url, FILE_APPEND);
        });
    }

    /**
     * function to get the necessary information about the product
     * the data is written to a file
     * @return void
     */
    public
    function getProductsInfo(): void
    {
        file_put_contents('./armsnab/products.txt', '');
        $links = file('./armsnab/groups.txt');
        array_pop($links);
        $result = array();

        foreach ($links as $link) {
            $html = file_get_contents(str_replace(array("\n", "\r"), '', $link));
            $crawler = new Crawler($html);
            $result[] = $crawler->filter('#center')->filter('#content')->filter('.zone4')->filter('.zone-content')->filter('.catalog-items')->
            filter('.catalog-item')->each(function ($node) {
                return ['name' => $node->filter('.blk_info')->filter('.blk_first')->filter('.blk_name')->filter('a')->filter('span')->text(),
                    'price' => $node->filter('.blk_info')->filter('.blk_first')->filter('.blk_priceblock')->filter('.blk_price')->filter('.cen')->text(),
                ];
            });
        }

        array_pop($result);
        $json = json_encode($result);
        file_put_contents('./armsnab/products.txt', $json);
    }
}
