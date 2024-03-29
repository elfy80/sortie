<?php


namespace App\Controller;

use App\Entity\sortie;

use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;




class SortiesController extends AbstractController
{
    /**
     * @Route("/sortie/list", name="list")
     */
    public function list(SortieRepository  $sortieRepository): Response
    {
        $sortie = $sortieRepository->findAll();

        return $this->render('sortie/list.html.twig', [
            "sortie" => $sortie
        ]);
    }


    /**
     * @Route ("/sortie/details/{id}", name="details")
     */


    public function details(int $id, SortieRepository $sortieRepository): Response
    {
        $sortie = $sortieRepository->find($id);

        if(!$sortie){
            throw  $this-> createNotFoundException("oups cette sortie n'existe pas!!");
        }

        return $this->render("sortie/details.html.twig", [
            "sortie" => $sortie
        ]);

    }


    /**
     * @Route ("/sortie/create", name="create")
     */


    public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $sortie = new sortie();
        $sortieForm = $this->createForm(sortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()){


            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'sortie');
            return $this->redirectToRoute('details', ['id'=> $sortie->getId()]);
        }

        return $this-> render('sortie/create.html.twig',[
            'sortieForm' => $sortieForm-> createView()
        ]);

    }



    /**
     * @Route("/sortie", name="main_sorties")
     */

    public function sorties(
    Request $request,
    EntityManagerInterface $entityManager
    ): Response


{
    $sortie = new sortie();
    $sortieForm = $this->createForm(SortieType::class, $sortie);

    $sortieForm->handleRequest($request);

    if ($sortieForm->isSubmitted()) {
        $sortie->setDateCreated(new \DateTime());
        $entityManager->persist($sortie);
        $entityManager->flush();

        $this->addFlash('success', 'validé!');
        return $this->redirectToRoute('details', ['id' => $sortie->getId()]);
    }

    return $this->render("main/sorties.html.twig", [
        "sortieForm" => $sortieForm->createView()
    ]);
}







}
