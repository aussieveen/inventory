<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Container;
use App\Entity\Item;
use App\Entity\Zone;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testAddItemSetsBidirectionalRelationship(): void
    {
        $zone = new Zone();

        $container = new Container();
        $container->setZone($zone);
        $container->setCode('BX01');

        $item = new Item();

        $container->addItem($item);

        $this->assertTrue($container->getItem()->contains($item));
        $this->assertSame($container, $item->getContainer());
    }

    public function testAddItemDoesNotDuplicate(): void
    {
        $zone = new Zone();

        $container = new Container();
        $container->setZone($zone);

        $item = new Item();

        $container->addItem($item);
        $container->addItem($item);

        $this->assertCount(1, $container->getItem());
    }

    public function testRemoveItemClearsContainer(): void
    {
        $zone = new Zone();

        $container = new Container();
        $container->setZone($zone);

        $item = new Item();

        $container->addItem($item);
        $container->removeItem($item);

        $this->assertFalse($container->getItem()->contains($item));
        $this->assertNull($item->getContainer());
    }

    public function testToStringReturnsCode(): void
    {
        $zone = new Zone();

        $container = new Container();
        $container->setZone($zone);
        $container->setCode('SH-42');

        $this->assertSame('SH-42', (string) $container);
    }
}
