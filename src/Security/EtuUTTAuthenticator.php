<?php

namespace App\Security;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class EtuUTTAuthenticator extends AbstractGuardAuthenticator
{
    private $client;
    private $urlGenerator;

    /**
     * EtuUTTAuthenticator constructor.
     * @param $client
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
        $this->client = HttpClient::create();
    }


    public function supports(Request $request)
    {
        return 'connexion' === $request->attributes->get('_route');
    }

    public function getCredentials(Request $request)
    {
        $baseUrl = "https://etu.utt.fr";
        $donnees = array(
            "grant_type" => "authorization_code",
            "scopes" => "public",
            "code" => $request->query->get("code"),
            "client_id" => $_ENV['CLIENT_ID'],
            "client_secret" => $_ENV['CLIENT_SECRET'],
        );
        $response = $this->client->request("POST", $baseUrl . "/api/oauth/token?" . http_build_query($donnees));
        $contenu = json_decode($response->getContent(), true);

        $donnees = array(
            "access_token" => $contenu["access_token"]
        );
        $response = $this->client->request("GET", $baseUrl . "/api/public/user/account?" . http_build_query($donnees));
        $donneesEtu = json_decode($response->getContent(), true);
        return array("uuid" => $donneesEtu["data"]["login"], "token" => $contenu["access_token"], "nom" => $donneesEtu["data"]["fullName"]);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return new User($credentials["uuid"], $credentials["token"], $credentials["nom"]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $credentials["uuid"] == $user->getUsername();
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // todo
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return NULL;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return $this->urlGenerator->generate("etuUtt");
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
