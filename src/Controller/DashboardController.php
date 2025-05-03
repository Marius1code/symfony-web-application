<?php

namespace App\Controller;

use App\Repository\InvoiceRepository;
use App\Repository\ArticleRepository;
use App\Repository\ClientRepository;
use App\Repository\MovementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(
        InvoiceRepository $invoiceRepository,
        ArticleRepository $articleRepository,
        ClientRepository $clientRepository,
        MovementRepository $movementRepository
    ): Response {
        // Calculate total revenue
        $invoices = $invoiceRepository->findAll();
        $totalRevenue = 0;
        foreach ($invoices as $invoice) {
            $totalRevenue += $invoice->getTotal();
        }

        // Get best selling products (by quantity sold)
        $bestSellers = $articleRepository->createQueryBuilder('a')
            ->select('a, SUM(il.quantity) AS totalSold')
            ->join('App\Entity\InvoiceLine', 'il', 'WITH', 'il.article = a')
            ->groupBy('a.id')
            ->orderBy('totalSold', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        // Calculate margins (simplified as total revenue minus cost, assuming cost is 70% of price)
        $totalMargin = $totalRevenue * 0.3;

        // Alerts: critical stock (stockQuantity < 5)
        $criticalStocks = $articleRepository->createQueryBuilder('a')
            ->where('a.stockQuantity < :threshold')
            ->setParameter('threshold', 5)
            ->getQuery()
            ->getResult();

        // Alerts: clients with late payments (for simplicity, invoices older than 30 days)
        $lateClients = $clientRepository->createQueryBuilder('c')
            ->join('App\Entity\Invoice', 'i', 'WITH', 'i.client = c')
            ->where('i.date < :dateLimit')
            ->setParameter('dateLimit', new \DateTime('-30 days'))
            ->getQuery()
            ->getResult();

        return $this->render('dashboard/index.html.twig', [
            'totalRevenue' => $totalRevenue,
            'bestSellers' => $bestSellers,
            'totalMargin' => $totalMargin,
            'criticalStocks' => $criticalStocks,
            'lateClients' => $lateClients,
        ]);
    }
}
