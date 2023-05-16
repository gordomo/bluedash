<?php

namespace App\Controller;

use App\Entity\Pool;
use App\Form\PoolType;
use App\Repository\PoolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pool")
 */
class PoolController extends AbstractController
{
    /**
     * @Route("/", name="app_pool_index", methods={"GET"})
     */
    public function index(PoolRepository $poolRepository): Response
    {
        $user = $this->getUser();
        if(!$user) {
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pool/index.html.twig', [
            'pools' => $poolRepository->findAll(),
            'balance' => $user->getBalance(),
        ]);
    }

    /**
     * @Route("/new", name="app_pool_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PoolRepository $poolRepository): Response
    {
        $user = $this->getUser();
        if(!$user) {
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }
        $pool = new Pool();
        $pool->setInversionActual(0);
        $form = $this->createForm(PoolType::class, $pool);
        $form->handleRequest($request);
        $pool->setStatus(1);
        $pool->setInversionActual(0);

        if ($form->isSubmitted() && $form->isValid()) {
            $poolRepository->add($pool, true);

            return $this->redirectToRoute('dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pool/new.html.twig', [
            'pool' => $pool,
            'form' => $form,
            'balance' => $user->getBalance(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_pool_show", methods={"GET"})
     */
    public function show(Pool $pool): Response
    {
        return $this->render('pool/show.html.twig', [
            'pool' => $pool,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_pool_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Pool $pool, PoolRepository $poolRepository): Response
    {
        $form = $this->createForm(PoolType::class, $pool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $poolRepository->add($pool, true);

            return $this->redirectToRoute('app_pool_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pool/edit.html.twig', [
            'pool' => $pool,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_pool_delete", methods={"POST"})
     */
    public function delete(Request $request, Pool $pool, PoolRepository $poolRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pool->getId(), $request->request->get('_token'))) {
            $poolRepository->remove($pool, true);
        }

        return $this->redirectToRoute('app_pool_index', [], Response::HTTP_SEE_OTHER);
    }
}
