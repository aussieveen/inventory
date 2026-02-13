<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Item;
use App\Repository\CategoryRepository;
use App\Service\ImageUploader;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\SearchMode;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Generator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ItemCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly ImageUploader      $imageUploader,
        #[Autowire('%s3.bucket.name%')]
        private string                      $s3BucketName,
        #[Autowire('%s3.bucket.region%')]
        private string                      $s3Region,
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
        yield ImageField::new('image')
            ->setUploadDir('public/uploads/images')
            ->setBasePath(sprintf(
                'https://%s.s3.%s.amazonaws.com/images/',
                $this->s3BucketName,
                $this->s3Region
            ))
            ->setRequired(false)
            ->setLabel('Image')
            ->setFormTypeOption('upload_new', function ($uploadedFile) {
                if ($uploadedFile instanceof UploadedFile) {
                    return $this->imageUploader->upload($uploadedFile);
                }
                return null;
            });
        yield NumberField::new('price')
            ->setRequired(false);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setDefaultSort(['description' => 'ASC'])
            ->setSearchFields(['description','category.name', 'zone.name', 'category.children.name'])
            ->setSearchMode(SearchMode::ANY_TERMS);
    }
}
