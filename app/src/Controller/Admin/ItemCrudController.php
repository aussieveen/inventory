<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Item;
use App\Repository\CategoryRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\SearchMode;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Generator;

class ItemCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Item::class;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function configureFields(string $pageName): Generator
    {
        yield TextField::new('description');
        yield AssociationField::new('category', 'Category')
            ->setRequired(true)
            ->setFormTypeOption('choice_label', function ($category) {
                return $category->getParent() ?
                    "⮑ " . $category->getName() :
                    $category->getName();
            })
            ->setFormTypeOption('choice_loader', null)
            ->setFormTypeOption('choices', $this->categoryRepository->getByParentThenChild());
        yield IntegerField::new('quantity')
            ->setRequired(true);
        yield AssociationField::new('container', 'Container')
            ->setRequired(false)
            ->setQueryBuilder(fn (QueryBuilder $qb) => $qb->orderBy('entity.code', 'ASC'))
            ->setFormTypeOption('choice_label', function ($container) {
                return $container->getCode() ?? '';
            });
        yield AssociationField::new('zone', 'Zone')
            ->setRequired(false)
            ->setQueryBuilder(fn (QueryBuilder $qb) => $qb->orderBy('entity.name', 'ASC'));
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setDefaultSort(['description' => 'ASC'])
            ->setSearchFields(['description','category.name', 'zone.name', 'category.children.name'])
            ->setSearchMode(SearchMode::ANY_TERMS);
    }
}
