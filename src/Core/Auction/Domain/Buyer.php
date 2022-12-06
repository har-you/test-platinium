<?php

declare(strict_types=1);

namespace App\Core\Auction\Domain;

use Decimal\Decimal;

final class Buyer
{
    /**
     * @param list<Price> $bids
     */
    public function __construct(
        private string $name,
        private array $bids,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Price[]
     */
    public function getBids(): array
    {
        return $this->bids;
    }

    public function getMaxPrice(): Decimal
    {
        return  count($this->bids) > 0 ?
            max(array_map(function (Price $price){return $price->toDecimal();}, $this->bids))
            : new Decimal(0);
    }

    /**
     * @return Price[]
     */
    public function bidByReservedPrice(Price $reservedPrice): array
    {

        return array_filter(
            $this->bids,
            static fn (Price $price) => $price->toDecimal() >= $reservedPrice->toDecimal(),
        );
    }
}
