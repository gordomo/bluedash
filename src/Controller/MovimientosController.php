<?php

namespace App\Controller;

use App\Entity\Movimientos;
use App\Entity\User;
use App\Form\MovimientosType;
use App\Repository\BalanceRepository;
use App\Repository\MovimientosRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

/**
 * @Route("/movimientos")
 */
class MovimientosController extends AbstractController
{
    /**
     * @Route("/", name="app_movimientos_index", methods={"GET"})
     */
    public function index(MovimientosRepository $movimientosRepository, Breadcrumbs $breadcrumbs): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $breadcrumbs->addItem("Dashboard", $this->generateUrl('dashboard'));
        $breadcrumbs->addItem("Movimientos");
        return $this->render('movimientos/index.html.twig', [
            'movimientos' => $movimientosRepository->findAll(),
            'balance' => $user->getBalance(),
        ]);
    }

    /**
     * @Route("/new", name="app_movimientos_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MovimientosRepository $movimientosRepository, BalanceRepository $balanceRepository, Breadcrumbs $breadcrumbs, UserRepository $userRepository): Response
    {
        $breadcrumbs->addItem("Dashboard", $this->generateUrl('dashboard'));
        $breadcrumbs->addItem("Movimientos", $this->generateUrl('app_movimientos_index'));
        $breadcrumbs->addItem("Agregar Saldo");
        $user = $this->getUser();
        $balance = $user->getBalance();

        if ($user) {
            $movimiento = new Movimientos();
            $movimiento->setUser($user);
            $movimiento->setDate(new \DateTime());
            $movimiento->setTipo(1);
            $form = $this->createForm(MovimientosType::class, $movimiento);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $movimientosRepository->add($movimiento, true);
                $balance->setSaldo($balance->getSaldo() + $movimiento->getMonto());
                $balanceRepository->add($balance, true);
                $user->setBalance($balance);
                $userRepository->add($user, true);
                return $this->redirectToRoute('app_movimientos_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('movimientos/new.html.twig', [
                'movimiento' => $movimiento,
                'balance' => $balance,
                'form' => $form,
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/{id}", name="app_movimientos_show", methods={"GET"})
     */
    public function show(Movimientos $movimiento): Response
    {
        return $this->render('movimientos/show.html.twig', [
            'movimiento' => $movimiento,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_movimientos_edit", methods={"GET", "POST"})
     */
   /* public function edit(Request $request, Movimientos $movimiento, MovimientosRepository $movimientosRepository): Response
    {
        $form = $this->createForm(MovimientosType::class, $movimiento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movimientosRepository->add($movimiento, true);

            return $this->redirectToRoute('app_movimientos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('movimientos/edit.html.twig', [
            'movimiento' => $movimiento,
            'form' => $form,
        ]);
    }*/

    /**
     * @Route("/{id}", name="app_movimientos_delete", methods={"POST"})
     */
   /* public function delete(Request $request, Movimientos $movimiento, MovimientosRepository $movimientosRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$movimiento->getId(), $request->request->get('_token'))) {
            $movimientosRepository->remove($movimiento, true);
        }

        return $this->redirectToRoute('app_movimientos_index', [], Response::HTTP_SEE_OTHER);
    }*/
}
