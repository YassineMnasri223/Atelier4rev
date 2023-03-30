<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Student;
use App\Entity\Classroom;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\StudentFormType;
use App\Form\StudentSearchFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\StudentRepository;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }
    #[Route('/students', name:'list_students')]
    public function afficher(ManagerRegistry $doctrine,StudentRepository $repo,Request $req): Response 
    {
        $form = $this->createForm(StudentSearchFormType::class);
        $form -> handleRequest($req);
        $list=$repo->findAll();
        if ($form->isSubmitted())
        {
            $list = $repo->findByNSC($form->getData('search'));
            return $this->render('student/list.html.twig',[
                'list' => $list,
                'form' => $form->createView()
            ]);
        }
        return $this->render('student/list.html.twig',[
            'list' => $list,
            'form' => $form->createView()
        ]);
    }
    #[Route('/addstudent',name:'student_add')]
    public function ajouter(ManagerRegistry $doctrine,Request $req,StudentRepository $repo)
    {   $student = new Student();
        $form = $this->createForm(StudentFormType::class,$student);
        $form->handleRequest($req);
        $entitymanager =$doctrine->getManager();
        if($form->isSubmitted()){
            $entitymanager->persist($student);
            $entitymanager->flush();
            return $this->redirectToRoute('list_students');
        }
        return $this->render('student/addstudent.html.twig',[
            'form'=> $form->createView()
        ]);
    }
    #[Route('/modstudent/{id}',name:'student_mod')]
    public function modifier(ManagerRegistry $doctrine,$id,Request $req,StudentRepository $repo)
    {
        $student=$repo->find($id);
        $form=$this->createForm(StudentFormType::class,$student);
        $form->handleRequest($req);
        $entitymanager=$doctrine->getManager();
        if($form->isSubmitted()){
            $entitymanager->flush();
            return $this->redirectToRoute('list_students');
        }
        return $this->render('student/modstudent.html.twig',[
            'form'=> $form->createView()
        ]);
    }
    #[Route('/delstudent/{id}',name:"student_del")]
    public function suprimmer(StudentRepository $repo,$id)
    {
        $student=$repo->find($id);
        $repo->remove($student,true);
        return $this->redirectToRoute("list_students");
    }
    #[Route('/studentsbymail', name:'list_students_bymail')]
    public function list_students(StudentRepository $repo)
    {
        $students =$repo->findByMail();
        return $this->render('student/list.html.twig',[
            'list'=> $students
        ]);
    }
    #[Route('/class/student',name:'class_student')]
    public function afficheStudentByClass(StudentRepository $repo,Request $req): Response 
    {
        $form = $this->createForm(StudentSearchFormType::class);
        $form -> handleRequest($req);
        $list=$repo->findAll();
        if ($form->isSubmitted())
        {
            $list = $repo->findByClassroom($form->getData('search'));
            return $this->render('student/afichebyclass.html.twig',[
                'list' => $list,
                'form' => $form->createView()
            ]);
        }
        return $this->render('student/afichebyclass.html.twig',[
            'list' => $list,
            'form' => $form->createView()
        ]);
    }
}
