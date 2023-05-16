<?php
namespace App\Controller;

use App\Entity\Balance;
use App\Repository\BalanceRepository;
use App\Repository\PoolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;


class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="base", methods={"GET"})
     */
    public function base(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('dashboard');
        }
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/dashboard", name="dashboard", methods={"GET"})
     */
    public function dashboard(BalanceRepository $balanceRepository, Breadcrumbs $breadcrumbs, PoolRepository $poolRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $participaciones = $user->getParticipaciones();
        $breadcrumbs->addItem("Dashboard", $this->generateUrl('dashboard'));

        $balance = $balanceRepository->findBy(['user' => $this->getUser()]);
        return $this->render('dashboard.html.twig', [
            'balance' => $balance[0],
            'pools' => $poolRepository->findAll(),
            'participaciones' => $participaciones,
        ]);
    }
}