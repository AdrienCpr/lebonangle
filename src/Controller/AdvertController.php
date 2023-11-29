<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Entity\Picture;
use App\Form\AdvertType;
use App\Repository\AdvertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Workflow\WorkflowInterface;

//#[Route('/advert')]
class AdvertController extends AbstractController
{
    public function __construct(
        private readonly WorkflowInterface $advertWorkflow,
    )
    {
    }

    #[Route('/admin/advert', name: 'app_advert_index', methods: ['GET'])]
    public function index(AdvertRepository $advertRepository): Response
    {
        return $this->render('advert/index.html.twig', [
            'adverts' => $advertRepository->findAll(),
        ]);
    }

    #[Route('/advert/new', name: 'app_advert_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $advert = new Advert();
        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($advert);
            $entityManager->flush();

            return $this->redirectToRoute('app_advert_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('advert/new.html.twig', [
            'advert' => $advert,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/advert/{id}', name: 'app_advert_show', methods: ['GET'])]
    public function show(Advert $advert): Response
    {
        return $this->render('advert/show.html.twig', [
            'advert' => $advert,
        ]);
    }

    #[Route('/admin/advert/state/{id}/{transition}', name: 'app_advert_change_state', methods: ['GET', 'POST'])]
    public function changeState(Advert $advert, Request $request, EntityManagerInterface $entityManager,MailerInterface $mailer): Response
    {
        $transition = $request->attributes->get('transition');
        $this->advertWorkflow->apply($advert, $transition);

        if ($transition === 'publish') {
            $advert->setPublishedAt(new \DateTime());
            (new MailerController)->sendEmail($advert->getEmail(), $mailer);
        } else {
            $advert->setPublishedAt(null);
        }

        $entityManager->persist($advert);
        $entityManager->flush();

        $this->addFlash('success', 'State changed successfully');
        return $this->redirectToRoute('app_advert_index');
    }

//    #[Route('/admin/advert/{id}/edit', name: 'app_advert_edit', methods: ['GET', 'POST'])]
//    public function edit(Request $request, Advert $advert, EntityManagerInterface $entityManager): Response
//    {
//        $form = $this->createForm(AdvertType::class, $advert,['is_edit' => true]);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_advert_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('advert/edit.html.twig', [
//            'advert' => $advert,
//            'form' => $form,
//        ]);
//    }
//
//    #[Route('/{id}', name: 'app_advert_delete', methods: ['POST'])]
//    public function delete(Request $request, Advert $advert, EntityManagerInterface $entityManager): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$advert->getId(), $request->request->get('_token'))) {
//            $entityManager->remove($advert);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('app_advert_index', [], Response::HTTP_SEE_OTHER);
//    }
}
