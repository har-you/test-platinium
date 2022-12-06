<?php

declare(strict_types=1);

namespace App\Core\Auction\Domain;

use function count;

class SecondPriceSealedBidAuction implements AuctionInterface
{
    /**
     * @param list<Buyer> $buyers
     */
    public function __construct(
        private readonly array $buyers,
        private readonly Price $reservePrice,
    ) {
    }

    public function getWinner(): ?Buyer
    {
        $winner = null;
        $maxPrice = $this->reservePrice->toDecimal();

        foreach ($this->buyers as $byer) {
            if ($byer->getMaxPrice() >= $maxPrice) {
                $winner = $byer;
                $maxPrice = $byer->getMaxPrice();
            }
        }

        return $winner;
    }

    public function getWinningPrice(): Price
    {
        $winner = $this->getWinner();

        if ($winner === null) {
            return new Price(0);
        }

        $nonWinningBuyer = array_filter(
            $this->buyers,
            fn (Buyer $buyer) => $buyer !== $winner && count($buyer->bidByReservedPrice($this->reservePrice)) > 0,
        );

        if (count($nonWinningBuyer) > 0) {
            $auction = new self($nonWinningBuyer, $this->reservePrice);

            return new Price($auction->getWinner()->getMaxPrice());
        }

        return $this->reservePrice;
    }
}
