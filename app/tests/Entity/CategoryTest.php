<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testAddChildSetsBidirectionalRelationship(): void
    {
        $parent = new Category();
        $parent->setName('Parent');

        $child = new Category();
        $child->setName('Child');

        $parent->addChild($child);

        $this->assertTrue($parent->getChildren()->contains($child));
        $this->assertSame($parent, $child->getParent());
    }

    public function testAddChildDoesNotDuplicate(): void
    {
        $parent = new Category();
        $child = new Category();

        $parent->addChild($child);
        $parent->addChild($child);

        $this->assertCount(1, $parent->getChildren());
    }

    public function testRemoveChildClearsParent(): void
    {
        $parent = new Category();
        $child = new Category();

        $parent->addChild($child);
        $parent->removeChild($child);

        $this->assertFalse($parent->getChildren()->contains($child));
        $this->assertNull($child->getParent());
    }

    public function testToStringReturnsName(): void
    {
        $category = new Category();
        $category->setName('Electronics');

        $this->assertSame('Electronics', (string) $category);
    }
}
