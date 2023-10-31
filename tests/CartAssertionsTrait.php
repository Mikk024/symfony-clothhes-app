<?php 


namespace App\Tests;

use App\Entity\Product;
use PHPUnit\Framework\Assert;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\DomCrawler\Crawler;

trait CartAssertionsTrait
{
    public static function assertCartIsEmpty(Crawler $crawler)
    {
        $infoText = $crawler
            ->filter('.text-4xl')
            ->getNode(1)
            ->textContent;
        
        $infoText = self::normalizeWhitespace($infoText);

        Assert::assertEquals(
            'Your cart is empty',
            $infoText,
            'The cart should be empty'
        );
    }

    public static function assertCartTotalEquals(Crawler $crawler, $expectedTotal)
    {
        //dd($crawler);

        $actualTotal = $crawler
            ->filter('p.text-3xl')
            ->getNode(1)
            ->textContent;

        Assert::assertEquals(
            $expectedTotal,
            $actualTotal,
            "The cart total should be equal to \"$expectedTotal $\". Actual: \"$actualTotal \"."
        );
    }

    public static function assertCartItemsCountEquals(Crawler $crawler, $expectedCount): void
    {
        $actualCount = $crawler
            ->filter('.product-cart')
            ->count()
            ;

        Assert::assertEquals(
            $expectedCount,
            $actualCount,
            "The cart should have \"$expectedCount €\" items . Actual: \"$actualCount €\"."
        );
    }

    public static function assertCartContainsProductWithQuantity(Crawler $crawler, string $productName, int $expectedQuantity): void
    {
        $actualQuantity = (int)self::getItemByProductName($crawler, $productName)
            ->filter('input[type="number"]')
            ->attr('value');

        Assert::assertEquals($expectedQuantity, $actualQuantity);
    }

    private static function getItemByProductName(Crawler $crawler, string $productName)
    {
        $items = $crawler->filter('.product-cart .text-xl')->reduce(
            function (Crawler $node) use ($productName) {
                if ($node->filter('p.text-xl')->getNode(0) === $productName) {
                    return $node;
                }

                return false;
            }
        );

        return empty($items) ? null : $items->eq(0);
    }

    private function addRandomProductToCart(AbstractBrowser $client, int $quantity = 1)
    {
        $product = $this->getRandomProduct();

        $crawler = $client->request('GET', '/products/' . $product->getId());
        $form = $crawler->filter('form')->form();
        $form->setValues(['add_to_cart[quantity]' => $quantity]);

        $client->submit($form);

        return $product;
    }

    private function getRandomProduct()
    {
        return $this->entityManager->getRepository(Product::class)
            ->findOneBy([]);
    }

    private static function normalizeWhitespace(string $value): string
    {
        return trim(preg_replace('/(?:\s{2,}+|[^\S ])/', ' ', $value));
    }
}





?>