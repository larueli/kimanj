<?php

namespace App\Security;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

class EtuUTTAuthenticator extends AbstractGuardAuthenticator
{
    private $client;
    private $urlGenerator;
    private $logger;

    private const baseUrl = "https://etu.utt.fr";

    /**
     * EtuUTTAuthenticator constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator
     * @param LoggerInterface       $logger
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, LoggerInterface $logger)
    {
        $this->urlGenerator = $urlGenerator;
        $this->client       = HttpClient::create();
        $this->logger       = $logger;
    }


    public function supports(Request $request)
    {
        return 'connexion' === $request->attributes->get('_route');
    }

    public function getCredentials(Request $request)
    {
        try {
            $donnees  = array(
                "grant_type"    => "authorization_code",
                "scopes"        => "public",
                "code"          => $request->query->get("code"),
                "client_id"     => $_ENV[ 'CLIENT_ID' ],
                "client_secret" => $_ENV[ 'CLIENT_SECRET' ],
            );
            $response =
                $this->client->request("POST", self::baseUrl . "/api/oauth/token?" . http_build_query($donnees));
            $contenu  = json_decode($response->getContent(), true);

            $donnees    = array(
                "access_token" => $contenu[ "access_token" ],
            );
            $response   =
                $this->client->request("GET", self::baseUrl . "/api/public/user/account?" . http_build_query($donnees));
            $donneesEtu = json_decode($response->getContent(), true);
            return array("uuid" => $donneesEtu[ "data" ][ "login" ], "token" => $contenu[ "access_token" ],
                         "nom"  => $donneesEtu[ "data" ][ "fullName" ]);
        } catch (Exception | TransportExceptionInterface |  ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface $e) {
            $this->logger->addCritical("Erreur site etu : " . $e->getMessage());
        }
        return NULL;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return new User($credentials[ "uuid" ], $credentials[ "token" ], $credentials[ "nom" ]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $donnees = array(
            "access_token" => $user->getToken(),
        );
        try {
            $response   =
                $this->client->request("GET", self::baseUrl . "/api/public/user/account?" . http_build_query($donnees));
            $donneesEtu = json_decode($response->getContent(), true);
            return $credentials[ "uuid" ] === $user->getUsername() && $credentials[ "uuid" ] === $donneesEtu[ "data" ][ "login" ];
        } catch (Exception | ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
            $this->logger->addCritical("Erreur site etu : " . $e->getMessage());
        }
        return false;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw new AccessDeniedHttpException("Accès refusé");
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return NULL;
    }

    public function start(Request $request, AuthenticationException $authException = NULL)
    {
        return $this->urlGenerator->generate("etuUtt");
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
