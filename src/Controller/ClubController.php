<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClubRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Club;
use App\Form\ClubFormType;
use Doctrine\Persistence\ManagerRegistry;

class ClubController extends AbstractController
{
    #[Route('/club', name: 'app_club')]
    public function index(): Response
    {
        return $this->render('club/index.html.twig', [
            'controller_name' => 'ClubController',
        ]);
    }
    #[Route('/clubs', name:'list_clubs')]
    public function afficher(ClubRepository $repo): Response 
    {
        $list=$repo->findAll();
        return $this->render('club/list.html.twig',[
            'list' => $list,
        ]);
    }
    #[Route('/addclub',name:'club_add')]
    public function ajouter(ManagerRegistry $doctrine,Request $req,ClubRepository $repo)
    {   $club = new Club();
        $form = $this->createForm(ClubFormType::class,$club);
        $form->handleRequest($req);
        $entitymanager =$doctrine->getManager();
        if($form->isSubmitted()){
            $entitymanager->persist($club);
            $entitymanager->flush();
            return $this->redirectToRoute('list_clubs');
        }
        return $this->render('club/addclub.html.twig',[
            'form'=> $form->createView()
        ]);
    }
    #[Route('/modclub/{id}',name:'club_mod')]
    public function modifier(ManagerRegistry $doctrine,$id,Request $req,ClubRepository $repo)
    {
        $club=$repo->find($id);
        $form=$this->createForm(ClubFormType::class,$club);
        $form->handleRequest($req);
        $entitymanager=$doctrine->getManager();
        if($form->isSubmitted()){
            $entitymanager->flush();
            return $this->redirectToRoute('list_clubs');
        }
        return $this->render('club/modclub.html.twig',[
            'form'=> $form->createView()
        ]);
    }
    #[Route('/delclub/{id}',name:"club_del")]
    public function suprimmer(ClubRepository $repo,$id)
    {
        $club=$repo->find($id);
        $repo->remove($club,true);
        return $this->redirectToRoute("list_clubs");
    }
}
