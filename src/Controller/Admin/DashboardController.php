<?php

namespace App\Controller\Admin;

use App\Entity\Envoi;
use App\Entity\Order;
use App\Entity\Letter;
use App\Entity\Service;
use App\Entity\Category;
use App\Entity\Document;
use App\Entity\ApiExchange;
use App\Entity\StripeTransaction;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('AmadeusMaileva');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Envois', 'fas fa-file-import', Envoi::class);
        yield MenuItem::linkToCrud('Documents', 'fas fa-folder', Document::class);
        yield MenuItem::linkToCrud('Logs', 'fas fa-code', ApiExchange::class);
        yield MenuItem::linkToCrud('Services à résilier', 'fas fa-list', Service::class);
        yield MenuItem::linkToCrud('Catégories', 'fas fa-tag', Category::class);
        yield MenuItem::linkToCrud('Transactions', 'fas fa-money-check', StripeTransaction::class);
        yield MenuItem::linkToCrud('Commandes', 'fas fa-tags', Order::class);
        yield MenuItem::linkToCrud('Modèles de lettre', 'fas fa-edit', Letter::class);
    }
}
