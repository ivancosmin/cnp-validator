<?php

namespace App\Controller;

use App\Form\CnpForm;
use App\Service\CnpValidatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{
    private CnpValidatorService $cnpValidatorService;

    public function __construct(CnpValidatorService $cnpValidatorService)
    {
        $this->cnpValidatorService = $cnpValidatorService;
    }

    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(CnpForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->cnpValidatorService->isCnpValid($form->getData()['cnp'])) {
                $this->addFlash('success', 'CNP-ul este valid.');
            } else {
                $this->addFlash('error', 'CNP-ul este invalid.');
            }
        }

        return $this->render('public/index.html.twig', [
            'controller_name' => 'PublicController',
            'form' => $form->createView()
        ]);
    }
}
