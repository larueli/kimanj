<?php

namespace App\Controller;

use DateTime;
use Exception;
use App\Entity\Reponse;
use App\Entity\Question;
use App\Form\ReponseType;
use App\Form\QuestionType;
use App\Entity\ChoixPossible;
use App\Service\GestionEntite;
use App\Form\ChoixPossibleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/",name="accueil")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function accueil(EntityManagerInterface $entityManager)
    {
        return $this->render('index.html.twig', [
            'questions' => $entityManager->getRepository(Question::class)->findAll(),
            "heureRAZ"  => $_ENV[ "HEURE_RAZ" ],
        ]);
    }

    /**
     * @Route("/editQuestion/{id?}", name="editQuestion")
     * @param                        $id
     * @param EntityManagerInterface $entityManager
     * @param Request                $request
     * @IsGranted("ROLE_USER")
     * @param GestionEntite          $gestionEntite
     *
     * @return Response
     * @throws Exception
     */
    public function editQuestion($id, EntityManagerInterface $entityManager, Request $request,
                                 GestionEntite $gestionEntite)
    {
        /** @var Question $question */
        $question = $gestionEntite->creerOuRecuperer(Question::class, $id);
        $user     = $this->getUser();
        if ($this->isGranted("edit", $question)) {
            $question
                ->setUuid($user->getUsername())
                ->setAuteur($user->getNom());
            if (is_null($question->getId())) {
                $question
                    ->setChoixMultiple(true)
                    ->setReponsesPubliques(true)
                    ->setEstRAZQuotidien(true)
                    ->setPoseeLe(new DateTime("now"))
                    ->setReponsesAnonymes(false);
            }
            $form = $this->createForm(QuestionType::class, $question);
            $form->handleRequest($request);
            $creation = is_null($question->getId());
            if ($form->isSubmitted() && $form->isValid()) {
                $question->setPoseeLe(new DateTime("now"));
                $entityManager->persist($question);
                $entityManager->flush();
                if ($creation)
                    return $this->redirectToRoute("editChoix", ["id" => $question->getId()]);
                return $this->redirectToRoute("accueil");
            }
            return $this->render('formulaire_basique.html.twig', [
                "creation"     => $creation,
                'titre'        => "Edition d'une question",
                "route_post"   => $this->generateUrl("editQuestion", ["id" => $question->getId()]),
                'formulaire'   => $form->createView(),
                "description"  => "Ajouter une question",
                "route_delete" => $this->generateUrl('supprimerQuestion', ["id" => $question->getId()]),
                "route_retour" => $this->generateUrl("accueil"),
            ]);
        }
        return $this->redirectToRoute("accueil");
    }

    /**
     * @Route("/supprimerReponsesQuestion/{id}", name="supprimerReponsesQuestion")
     * @param Question               $question
     * @IsGranted("edit", subject="question")
     * @param EntityManagerInterface $entityManager
     *
     * @return RedirectResponse
     */
    public function supprimerReponsesQuestion(Question $question, EntityManagerInterface $entityManager)
    {
        foreach ($question->getReponses() as $reponse) {
            $entityManager->remove($reponse);
        }
        $entityManager->flush();
        return $this->redirectToRoute("accueil");
    }

    /**
     * @Route("/supprimerQuestion/{id?}", name="supprimerQuestion")
     * @IsGranted("edit", subject="question")
     * @param Question               $question
     * @param EntityManagerInterface $entityManager
     *
     * @return RedirectResponse
     */
    public function supprimerQuestion(Question $question, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($question);
        $entityManager->flush();
        return $this->redirectToRoute("accueil");
    }

    /**
     * @Route("/editChoix/{id}/{idChoix?}", name="editChoix")
     * @param                        $idChoix
     * @param Question               $question
     * @param GestionEntite          $gestionEntite
     * @param Request                $request
     * @IsGranted("edit", subject="question")
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function editChoix($idChoix, Question $question, GestionEntite $gestionEntite, Request $request,
                              EntityManagerInterface $entityManager)
    {
        /** @var ChoixPossible $choix */
        $choix = $gestionEntite->creerOuRecuperer(ChoixPossible::class, $idChoix);
        $choix->setQuestion($question);
        $form = $this->createForm(ChoixPossibleType::class, $choix);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($choix);
            $entityManager->flush();
            $this->addFlash("success", "L'option a été ajoutée, vous pouvez en ajouter une autre.");
            $this->redirectToRoute("editChoix", ["id" => $question->getId(), "idChoix" => ""]);
        }
        return $this->render('formulaire_basique.html.twig', [
            "creation"     => is_null($choix->getId()),
            'titre'        => "Edition d'un choix",
            "route_post"   => $this->generateUrl("editChoix", ["idChoix" => $idChoix, "id" => $question->getId()]),
            'formulaire'   => $form->createView(),
            "description"  => $question->getInterrogation(),
            "route_delete" => $this->generateUrl('supprimerChoix', ["id" => $choix->getId()]),
            "route_retour" => $this->generateUrl("accueil"),
        ]);
    }

    /**
     *
     * @Route("/supprimerChoix/id/{idChoix?}", name="supprimerChoix")
     * @IsGranted("edit", subject="question")
     * @param Question               $question
     * @param                        $idChoix
     * @param EntityManagerInterface $entityManager
     *
     * @return RedirectResponse
     */
    public function supprimerChoix(Question $question, $idChoix, EntityManagerInterface $entityManager)
    {
        $choixPossible = $entityManager->getRepository(ChoixPossible::class)->find($idChoix);
        $entityManager->remove($choixPossible);
        $entityManager->flush();
        return $this->redirectToRoute("accueil");
    }

    /**
     * @Route("/editReponse/{id}", name="editReponse")
     * @IsGranted("view", subject="question")
     * @param Question               $question
     * @param GestionEntite          $gestionEntite
     * @param EntityManagerInterface $entityManager
     * @param Request                $request
     *
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function editReponse(Question $question, GestionEntite $gestionEntite, EntityManagerInterface $entityManager,
                                Request $request)
    {
        $user               = $this->getUser();
        $idReponse          = NULL;
        $reponseUtilisateur = $entityManager->getRepository(Reponse::class)->findOneBy(["uuid"     => $user->getUsername(),
                                                                                        "question" => $question->getId()]);
        if ( !is_null($reponseUtilisateur))
            $idReponse = $reponseUtilisateur->getId();
        /** @var Reponse $reponse */
        $reponse = $gestionEntite->creerOuRecuperer(Reponse::class, $idReponse);
        if ($this->isGranted("edit", $reponse)) {
            $reponse
                ->setUuid($user->getUsername())
                ->setNom($user->getNom())
                ->setQuestion($question)
                ->setDeposeeLe(new DateTime("now"));
            $form = $this->createForm(ReponseType::class, $reponse, array("question" => $question));
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $reponse
                    ->setUuid($user->getUsername())
                    ->setNom($user->getNom())
                    ->setQuestion($question)
                    ->setDeposeeLe(new DateTime("now"));
                $entityManager->persist($reponse);
                $entityManager->flush();
                return $this->redirectToRoute("accueil");
            }
            return $this->render('formulaire_basique.html.twig', [
                "creation"     => is_null($reponse->getId()),
                'titre'        => "Edition d'une réponse",
                "route_post"   => $this->generateUrl("editReponse", ["id" => $question->getId()]),
                'formulaire'   => $form->createView(),
                "description"  => $question->getInterrogation(),
                "route_delete" => $this->generateUrl('supprimerReponse', ["id" => $reponse->getId()]),
                "route_retour" => $this->generateUrl("accueil"),
            ]);
        }
        return $this->redirectToRoute("accueil");
    }

    /**
     *
     * @Route("/supprimerReponse/{id?}", name="supprimerReponse")
     * @IsGranted("edit", subject="reponse")
     * @param Reponse                $reponse
     * @param EntityManagerInterface $entityManager
     *
     * @return RedirectResponse
     */
    public function supprimerReponse(Reponse $reponse, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($reponse);
        $entityManager->flush();
        return $this->redirectToRoute("accueil");
    }
}
