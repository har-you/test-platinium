<?php

declare(strict_types=1);

namespace App\Core\Auction\Domain;


use Decimal\Decimal;
use DomainException;

final class Price
{
    private Decimal $price;

    /**
     * @param Decimal|string|int $value
     */
    public function __construct($value)
    {
        if (!$value instanceof Decimal) {
            $value = new Decimal($value);
        }

        $price = $value->round(2, Decimal::ROUND_TRUNCATE);

        if (!$price->equals($value)) {
            throw new DomainException("prices cannot have more than 2 significant figures behind the decimal point, {$value->toString()} given.");
        }

        $this->price = $price;
    }

    public function toDecimal(): Decimal
    {
        return $this->price;
    }

    public function toString(): string
    {
        return $this->toDecimal()->toString();
    }
}
