<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getByParentThenChild(): array
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.parent IS NULL')
            ->orderBy('c.parent', 'ASC') // Ensures parents are listed first
            ->addOrderBy('c.name', 'ASC');

        return $this->sortByParentThenChild($qb->getQuery()->getResult());
    }

    /**
     * @param array<Category> $categories
     * @return array
     */
    private function sortByParentThenChild(array $categories): array
    {
        $ordered = [];

        foreach ($categories as $category) {
            $ordered[] = $category;
            $orderedChildren = [];
            $children = $category->getChildren()->toArray();
            foreach ($children as $child) {
                $orderedChildren[$child->getName()] = $child;
            }
            ksort($orderedChildren);
            $ordered = array_merge($ordered, $orderedChildren);
        }

        return $ordered;
    }
}
