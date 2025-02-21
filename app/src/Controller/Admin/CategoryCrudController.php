<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\SearchMode;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Generator;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function configureFields(string $pageName): Generator
    {
        yield TextField::new('name');
        yield AssociationField::new('parent', 'Parent')
            ->setRequired(false)
            ->setQueryBuilder(function (QueryBuilder $qb) {
                return $qb->andWhere('entity.parent IS NULL');
            });
        yield AssociationField::new('children', 'Children')->hideOnForm()->hideOnIndex();
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setSearchFields(['name','parent.name'])
            ->setSearchMode(SearchMode::ANY_TERMS)
            ->setDefaultSort(['parent' => 'ASC', 'name' => 'ASC']);
    }
}
