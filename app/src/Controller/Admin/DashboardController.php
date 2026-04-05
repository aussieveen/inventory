<?php

namespace App\Controller\Admin;

use App\Entity;
use App\Repository\ZoneRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/', routeName: 'home')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly ZoneRepository $zoneRepository
    ) {
    }

    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'zones' => $this->zoneRepository->findAll()
        ]);
    }

    /** @SuppressWarnings(PHPMD.StaticAccess) */
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Inventory management')
            ->setDefaultColorScheme('dark');
    }

    /** @SuppressWarnings(PHPMD.StaticAccess) */
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkTo(ItemCrudController::class, 'Items', 'fas fa-qrcode');
        yield MenuItem::linkTo(ContainerCrudController::class, 'Containers', 'fas fa-box');
        yield MenuItem::linkTo(ZoneCrudController::class, 'Zones', 'fas fa-warehouse');
        yield MenuItem::linkTo(CategoryCrudController::class, 'Categories', 'fas fa-list');
    }
}
