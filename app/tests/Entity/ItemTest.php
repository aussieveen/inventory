<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Container;
use App\Entity\Item;
use App\Entity\Zone;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function testGetZoneReturnsDirectZone(): void
    {
        $zone = new Zone();
        $item = new Item();
        $item->setZone($zone);

        $this->assertSame($zone, $item->getZone());
    }

    public function testGetZoneReturnsContainerZoneAsFallback(): void
    {
        $zone = new Zone();
        $container = new Container();
        $container->setZone($zone);

        $item = new Item();
        $item->setContainer($container);

        $this->assertSame($zone, $item->getZone());
    }

    public function testGetZoneReturnsNullWhenNeitherSet(): void
    {
        $item = new Item();

        $this->assertNull($item->getZone());
    }

    public function testGetZoneIgnoresContainerZoneWhenDirectZoneIsSet(): void
    {
        $directZone = new Zone();
        $containerZone = new Zone();

        $container = new Container();
        $container->setZone($containerZone);

        $item = new Item();
        $item->setZone($directZone);
        $item->setContainer($container);

        $this->assertSame($directZone, $item->getZone());
    }

    public function testSetImageDoesNotUpdateWhenNull(): void
    {
        $item = new Item();
        $item->setImage('original.jpg');
        $item->setImage(null);

        $this->assertSame('original.jpg', $item->getImage());
    }

    public function testSetImageUpdatesWhenValueProvided(): void
    {
        $item = new Item();
        $item->setImage('first.jpg');
        $item->setImage('second.jpg');

        $this->assertSame('second.jpg', $item->getImage());
    }
}
