<?php

namespace App\Controller;

use App\Entity\Creneau;
use App\Entity\Reservation;
use App\Form\CreneauType;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
    /**
     * @Route("/connexion", name="connexion")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     * @param Security $security
     * @return RedirectResponse
     */
    public function connexion(Security $security)
    {
        return $this->redirectToRoute("addResa", ["uuid" => $security->getUser()->getUsername()]);
    }

    /**
     * @Route("/",name="accueil")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function accueil(EntityManagerInterface $entityManager)
    {
        $creneaux = $entityManager->getRepository(Creneau::class)->findAll();
        return $this->render('index.html.twig', [
            'creneaux' => $creneaux,
        ]);
    }

    /**
     * @Route("/jemange/{uuid}", name="addResa")
     * @IsGranted("ROLE_USER")
     */
    public function editReservation($uuid, EntityManagerInterface $entityManager, Security $security, Request $request)
    {
        $user = $security->getUser();
        if ($user->getUsername() === $uuid) {
            $reservation = $entityManager->getRepository(Reservation::class)->findOneBy(["uuid" => $uuid]);
            if (is_null($reservation)) {
                $reservation = new Reservation();
                $reservation->setNom($user->getNom());
                $reservation->setUuid($user->getUsername());
            }
            $form = $this->createForm(ReservationType::class, $reservation);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($reservation);
                $entityManager->flush();
                return $this->redirectToRoute("accueil");
            }
            return $this->render('formulaire_basique.html.twig', [
                "creation" => is_null($reservation->getId()),
                'titre' => "Edition d'une reservation",
                "route_post" => $this->generateUrl("addResa", ["uuid" => $uuid]),
                'formulaire' => $form->createView(),
                "description" => "Edition d'une reservation",
                "route_delete" => $this->generateUrl('supprimerResa', ["id" => $reservation->getId()]),
                "route_retour" => $this->generateUrl("accueil"),
            ]);
        } else {
            return $this->redirectToRoute("addResa", ["uuid" => $security->getUser()->getUsername()]);
        }
    }

    /**
     *
     * @Route("/supprimerResa/{id?}", name="supprimerResa")
     * @IsGranted("ROLE_USER")
     * @param Reservation $reservation
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     * @return RedirectResponse
     */
    public function supprimerResa(Reservation $reservation, EntityManagerInterface $entityManager, Security $security)
    {
        $user = $security->getUser();
        if ($user->getUsername() === $reservation->getUuid()) {
            $entityManager->remove($reservation);
            $entityManager->flush();
            return $this->redirectToRoute("accueil");
        }
    }

    /**
     * @Route("/editCreneau/{id?}", name="creneau")
     * @IsGranted("ROLE_USER")
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     */
    public function addCreneau($id, EntityManagerInterface $entityManager, Request $request)
    {
        if (is_null($id))
            $creneau = new Creneau();
        if (is_int($id))
            $creneau = $entityManager->getRepository(Creneau::class)->find($id);
        if ( !is_null($creneau)) {
            $form = $this->createForm(CreneauType::class, $creneau);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($creneau);
                $entityManager->flush();
                return $this->redirectToRoute("accueil");
            }
            return $this->render('formulaire_basique.html.twig', [
                "creation" => is_null($creneau->getId()),
                'titre' => "Edition d'un créneau",
                "route_post" => $this->generateUrl("creneau", ["id" => $id]),
                'formulaire' => $form->createView(),
                "description" => "Edition d'un créneau",
                "route_delete" => $this->generateUrl('supprimerCreneau', ["id" => $id]),
                "route_retour" => $this->generateUrl("accueil"),
            ]);
        }
    }

    /**
     *
     * @Route("/supprimerCreneau/{id?}", name="supprimerCreneau")
     * @IsGranted("ROLE_USER")
     * @param Creneau $creneau
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function supprimerCreneau(Creneau $creneau, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($creneau);
        $entityManager->flush();
        return $this->redirectToRoute("accueil");
    }


    /**
     * @Route("/etuUTT", name="etuUtt")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     */
    public function goEtuUTT()
    {
        $baseUrl = "https://etu.utt.fr";
        $donnees = array(
            "client_id" => $_ENV['CLIENT_ID'],
            "response_type" => "code",
            "state" => "xyz"
        );
        return $this->redirect($baseUrl . "/api/oauth/authorize?" . http_build_query($donnees));
    }

    /**
     * @return RedirectResponse
     * @Route("/deconnexion", name="deconnexion")
     */
    public function deconnexion()
    {
        return $this->redirectToRoute("accueil");
    }
}
