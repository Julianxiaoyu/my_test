<?php

namespace ProjetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ProjetBundle\Entity\Compteur;
use ProjetBundle\Entity\Document;
use ProjetBundle\Repository\Autocomp;
use ProjetBundle\Form\DocumentType;
use Symfony\Component\HttpFoundation\JsonResponse;
use DateTime;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $compteur = new Compteur();
        $form = $this->createForm('ProjetBundle\Form\CompteurType', $compteur);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $lists = $em->getRepository('ProjetBundle:Compteur')->findAll();
        
        foreach ($lists as $list){
            $now = New DateTime();
            $titre = $list->getTitre();
            $datedebut = $list->getDateDebut();
            $interval = $datedebut->diff($now);
            $nbdays = $interval->days;
            $nbhours = $interval->h;

            $taskslist[]=array(
                "titre"=>$titre,
                "datedebut"=>$datedebut,
                "interval"=>$interval,
                "nbdays"=>$nbdays,
                "nbhours"=>$nbhours,
                );
        }
        if(empty($taskslist))
            {
                $taskslist=1;
            }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($compteur);
            $em->flush();

            return $this->redirectToRoute('projet_homepage');
        }

        return $this->render('ProjetBundle:Default:index.html.twig', array(
        	'lists' => $lists,
            'form' => $form->createView(),
            'taskslist' => $taskslist,
        ));
    }
    
    public function articleAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('ProjetBundle:Compteur')->findOneBy($id);

        return $this->render('ProjetBundle:Default:article.html.twig', array(
            'article' => $article,
        ));
    }

    public function uploadAction(Request $request)
    {
        $document = new Document();
        $form = $this->createForm(new DocumentType(), $document);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $uploads = $em->getRepository('ProjetBundle:Document')->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $document->getDocument();
            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            // Move the file to the directory where brochures are stored
            $file->move(
                $this->container->getParameter('document_directory'),
                $fileName
            );

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $document->setDocument($fileName);

            // ... persist the $product variable or any other work
            $em = $this->getDoctrine()->getManager();
            $em->persist($document);
            $em->flush();

            return $this->redirectToRoute('upload');;
        }
        return $this->render('ProjetBundle:Default:upload.html.twig', array(
            'uploads' => $uploads,
            'form' => $form->createView(),
        ));
    }

    public function datatableAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        return $this->render('ProjetBundle:Default:datatable.html.twig');
    }
     
    public function gameAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        return $this->render('ProjetBundle:Default:game.html.twig');
    }

    public function sudokuAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        return $this->render('ProjetBundle:Default:sudoku.html.twig');
    }

    public function demineurAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        return $this->render('ProjetBundle:Default:demineur.html.twig');
    }

    public function geolocAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        return $this->render('ProjetBundle:Default:geoloc.html.twig');
    }
    
    
    public function ajaxAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        return $this->render('ProjetBundle:Default:ajax.html.twig');
    }

    public function chartAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        return $this->render('ProjetBundle:Default:chart.html.twig');
    }

    public function villeAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        
        $cp = $request->request->get('cp');
        $ville = $em->getRepository('ProjetBundle:Ville')->findOneByCodepostal($cp);

        if ($ville){
            $maville = $ville->getVille();
            } else {
            $maville = "Non trouve" ;
            } 
    
        $response = new JsonResponse();
        return $response->setData(array('maville' => $maville));
    }

    public function autocompAction(Request $request)
    { 
    
    $em = $this->getDoctrine()->getManager();
    $autocomp = $request->request->get('autocomp');
    $listname = $em->getRepository('ProjetBundle:Autocomp')->recherche($autocomp);

        foreach ($listname as $myname){
            $name = $myname->getName();

            $listreturn[]=array(
                "name"=>$name,
                );
        }

    $response = new JsonResponse();
    return $response->setData(array('listreturn' => $listreturn));
    }

    public function highchartsAction(Request $request)
    { 
    
        for ($i=1; $i<5; $i++){

            $cat="cat".$i;
            $data=20;

            $listcat[]=array(
                "name"=>$cat,
                "y"=> 20,
                );
        }
    
    $response = new JsonResponse();
    return $response->setData(array('listcat' => $listcat));
    }
}
