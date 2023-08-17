<?php
require_once('./vendor/autoload.php');

use Symfony\Component\DomCrawler\Crawler as Crawler;

ini_set('max_execution_time', 0);

/**
 * A class containing the logic of site parsing https://www.santech.ru
 * Class Parser
 */
class Parser
{
    private string $url = "https://www.santech.ru";

    /**
     * function for getting all categories
     * the data is written to a file
     * @return void
     */
    public function getCategoriesLinks(): void
    {
        file_put_contents('./santech/categories.txt', '');

        $html = file_get_contents($this->url . '/catalog/');
        $crawler = new Crawler($html);

        $crawler->filter('.main-col')->filter('.main-catalog-types')->filter('.col-1')->each(function ($node) {
            $url = $this->url . $node->filter('.info')->filter('a')->attr('href') . PHP_EOL;
            file_put_contents('./santech/categories.txt', $url, FILE_APPEND);
        });
    }

    /**
     * function for getting all groups within categories
     * the data is written to a file
     * @return void
     */
    public function getGroupLinks(): void
    {
        file_put_contents('./santech/groups.txt', '');
        $links = file('./santech/categories.txt');
        array_pop($links);

        foreach ($links as $link) {
            $html = file_get_contents($link);
            $crawler = new Crawler($html);

            $crawler->filter('.main-col')->filter('.main-catalog-types')->filter('.no-touch-hover')->each(function ($node) {
                $url = $this->url . $node->filter('.info')->filter('a')->attr('href') . PHP_EOL;
                file_put_contents('./santech/groups.txt', $url, FILE_APPEND);
            });
        }
    }

    /**
     * function for getting all products within groups
     * the data is written to a file
     * @return void
     */
    public function getProductsLinks(): void
    {
        file_put_contents('./santech/products.txt', '');
        $links = file('./santech/groups.txt');

        foreach ($links as $link) {
            $html = file_get_contents(str_replace(array("\n", "\r"), '', $link));
            $crawler = new Crawler($html);
            $crawler->filter('.products')->filter('.products__table')->filter('.js-products-item')->each(function ($node) {
                $url = $this->url . $node->filter('.products__info')->filter('.products__info-block')->filter('a')->attr('href') . PHP_EOL;
                file_put_contents('./santech/products.txt', $url, FILE_APPEND);
            });
        }
    }

    /**
     * function to get the necessary information about the product
     * the data is written to a file
     * @return void
     */
    public
    function getProductsInfo(): void
    {
        file_put_contents('./santech/santech.txt', '');
        $links = file('./santech/products.txt');
        array_pop($links);
        $result = array();

        foreach ($links as $link) {
            $html = file_get_contents(str_replace(array("\n", "\r"), '', $link));
            $crawler = new Crawler($html);
            $result[] = $crawler->filter('.catalog-item')->filter('.js-variants-item')->filter('.variant-list')->filter('.js-item-block')->each(function ($node) {
                return ['name' => $node->filter('.js-variant-block')->filter('.variant-list__info')->filter('.js-variant_title')->text(),
                    'price' => $node->filter('.variant-list__price-val')->attr('data-price'),
                ];
            });
        }

        array_pop($result);
        $json = json_encode($result);
        file_put_contents('./santech/santech.txt', $json);
    }
}
