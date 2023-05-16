<?php

namespace App\Controller;

use App\Entity\Movimientos;
use App\Entity\Participaciones;
use App\Entity\Pool;
use App\Form\ParticipacionesType;
use App\Repository\BalanceRepository;
use App\Repository\MovimientosRepository;
use App\Repository\ParticipacionesRepository;
use App\Repository\PoolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

/**
 * @Route("/participaciones")
 */
class ParticipacionesController extends AbstractController
{
    /**
     * @Route("/", name="app_participaciones_index", methods={"GET"})
     */
    public function index(ParticipacionesRepository $participacionesRepository): Response
    {
        return $this->render('participaciones/index.html.twig', [
            'participaciones' => $participacionesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_participaciones_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ParticipacionesRepository $participacionesRepository, MovimientosRepository $movimientosRepository, BalanceRepository $balanceRepository, PoolRepository $poolRepository, Breadcrumbs $breadcrumbs): Response
    {
        $breadcrumbs->addItem("Dashboard", $this->generateUrl('dashboard'));
        $breadcrumbs->addItem("Participaciones");
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        $balance = $user->getBalance();
        $participacione = new Participaciones();
        $participacione->setUser($user);
        $participacione->setFecha(new \DateTime());
        $participacione->setStatus(1);
        $form = $this->createForm(ParticipacionesType::class, $participacione, ['saldo' => $balance->getSaldo()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movimiento = new Movimientos();
            $movimiento->setUser($user);
            $movimiento->setTipo(2);
            $movimiento->setDate(new \DateTime());
            $movimiento->setMonto($participacione->getMonto());
            $movimientosRepository->add($movimiento, true);

            $balance->setSaldo($balance->getSaldo() - $movimiento->getMonto());
            $balanceRepository->add($balance, true);

            $pool = $poolRepository->find($participacione->getPool());
            $pool->setInversionActual($pool->getInversionActual() + $participacione->getMonto());

            $poolRepository->add($pool, true);
            $participacionesRepository->add($participacione, true);

            return $this->redirectToRoute('dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participaciones/new.html.twig', [
            'participacione' => $participacione,
            'form' => $form,
            'balance' => $user->getBalance(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_participaciones_show", methods={"GET"})
     */
    public function show(Participaciones $participacione): Response
    {
        return $this->render('participaciones/show.html.twig', [
            'participacione' => $participacione,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_participaciones_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Participaciones $participacione, ParticipacionesRepository $participacionesRepository): Response
    {
        $form = $this->createForm(ParticipacionesType::class, $participacione);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $participacionesRepository->add($participacione, true);

            return $this->redirectToRoute('app_participaciones_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participaciones/edit.html.twig', [
            'participacione' => $participacione,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_participaciones_delete", methods={"POST"})
     */
    public function delete(Request $request, Participaciones $participacione, ParticipacionesRepository $participacionesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participacione->getId(), $request->request->get('_token'))) {
            $participacionesRepository->remove($participacione, true);
        }

        return $this->redirectToRoute('app_participaciones_index', [], Response::HTTP_SEE_OTHER);
    }
}
