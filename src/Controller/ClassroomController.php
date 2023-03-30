<?php

namespace App\Controller;

use App\Repository\ClassroomRepository;
use App\Entity\Classroom;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ClassroomFormType;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(): Response
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }
    #[Route('/classrooms', name:'list_class')]
    public function afficher(ClassroomRepository $repo): Response 
    {
        $list=$repo->findAll();
        return $this->render('classroom/list.html.twig',[
            'list' => $list,
        ]);
    }
    #[Route('/addclassroom',name:'classroom_add')]
    public function ajouter(ManagerRegistry $doctrine,Request $req)
    {   $classroom = new Classroom();
        $form = $this->createForm(ClassroomFormType::class,$classroom);
        $form->handleRequest($req);
        $entitymanager =$doctrine->getManager();
        if($form->isSubmitted()){
            $entitymanager->persist($classroom);
            $entitymanager->flush();
            return $this->redirectToRoute('list_class');
        }
        return $this->render('classroom/addClassroom.html.twig',[
            'form'=> $form->createView()
        ]);
    }
    #[Route('/modclassroom/{id}',name:'classroom_mod')]
    public function modifier(ManagerRegistry $doctrine,$id,Request $req,ClassroomRepository $repo)
    {
        $classroom=$repo->find($id);
        $form=$this->createForm(ClassroomFormType::class,$classroom);
        $form->handleRequest($req);
        $entitymanager=$doctrine->getManager();
        if($form->isSubmitted()){
            $entitymanager->flush();
            return $this->redirectToRoute('list_class');
        }
        return $this->render('classroom/modclassroom.html.twig',[
            'form'=> $form->createView()
        ]);
    }
    #[Route('/delclassroom/{id}',name:"classroom_del")]
    public function suprimmer(ClassroomRepository $repo,$id)
    {
        $classroom=$repo->find($id);
        $repo->remove($classroom,true);
        return $this->redirectToRoute("list_class");
    }
}
