<?php

declare(strict_types=1);

namespace App\Tests\Auction;

use App\Core\Auction\Domain\{Buyer, Price, SecondPriceSealedBidAuction};
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Core\Auction\Domain\SecondPriceSealedBidAuction
 */
class SecondPriceSealedAuctionTest extends TestCase
{
    /**
     * @param array<Buyer> $buyers
     *
     * @dataProvider provideBidCases
     */
    public function test_it_search_winner_and_wining_price(
        array $buyers,
        string $reservePrice,
        string $winningPrice,
        string $winner,
    ): void {
        $secondPriceAuction = new SecondPriceSealedBidAuction(
            $buyers,
            new Price($reservePrice),
        );

        self::assertSame($winner, $secondPriceAuction->getWinner()->getName());
        self::assertSame($winningPrice, $secondPriceAuction->getWinningPrice()->toString());
    }

    public function test_it_no_winner_found(): void
    {
        $buyer1 = new Buyer('Buyer 1', [new Price(110), new Price(130)]);
        $buyer2 = new Buyer('Buyer 2', [new Price(0)]);
        $buyer3 = new Buyer('Buyer 3', [new Price(125)]);

        $secondPriceAuction = new SecondPriceSealedBidAuction(
            [$buyer1, $buyer2, $buyer3],
            new Price(200),
        );

        self::assertNull($secondPriceAuction->getWinner());
        self::assertSame('0', $secondPriceAuction->getWinningPrice()->toString());
    }

    /**
     * @return array<mixed>
     */
    public function provideBidCases(): iterable
    {
        $buyer1 = new Buyer(name: 'Buyer 1', bids: [new Price(110), new Price(130)]);
        $buyer2 = new Buyer(name: 'Buyer 2', bids: [new Price(0)]);
        $buyer3 = new Buyer(name: 'Buyer 3', bids: [new Price(125)]);
        $buyer4 = new Buyer(name: 'Buyer 4', bids: [new Price(105), new Price(115), new Price(90)]);
        $buyer5 = new Buyer(name: 'Buyer 5', bids: [new Price(132), new Price(135), new Price(140)]);

        yield 'with multiple non winning buyer' => [
            [$buyer1, $buyer2, $buyer3, $buyer4, $buyer5],
            '100',
            '130',
            'Buyer 5',
        ];

        yield 'apply reserve price' => [
            [$buyer1, $buyer2, $buyer3, $buyer4, $buyer5],
            '131',
            '131',
            'Buyer 5',
        ];

        yield 'apply reserve price with only one winning buyer' => [
            [$buyer1, $buyer3, $buyer5],
            '133',
            '133',
            'Buyer 5',
        ];
    }
}
