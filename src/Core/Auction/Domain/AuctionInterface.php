<?php

declare(strict_types=1);

namespace App\Core\Auction\Domain;

interface AuctionInterface
{
    public function getWinner(): ?Buyer;

    public function getWinningPrice(): Price;
}