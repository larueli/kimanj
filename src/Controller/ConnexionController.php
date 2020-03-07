<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConnexionController extends AbstractController
{
    /**
     * @Route("/connexion", name="connexion")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     * @return RedirectResponse
     */
    public function connexion()
    {
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
            "client_id"     => $_ENV[ 'CLIENT_ID' ],
            "response_type" => "code",
            "state"         => "xyz",
        );
        return $this->redirect($baseUrl . "/api/oauth/authorize?" . http_build_query($donnees));
    }

    /**
     * @return RedirectResponse
     * @Route("/deconnexion", name="deconnexion")
     * @IsGranted("ROLE_USER")
     */
    public function deconnexion()
    {
        return $this->redirectToRoute("accueil");
    }
}
