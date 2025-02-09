<?php

namespace apiUtilisateur\application\actions\user;

use apiUtilisateur\application\actions\AbstractAction;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;

class RegisterAction extends AbstractAction
{
    private UserServiceInterface $utilisateurService;

    public function __construct(UserServiceInterface $serviceUtilisateur)
    {
        $this->utilisateurService = $serviceUtilisateur;
    }

    /**
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $params = $rq->getParsedBody() ?? null;

        if (!isset($params['email']) || !isset($params['mdp']) ) {
            throw new HttpBadRequestException($rq, 'Paramètres manquants');
        }

        $email = filter_var($params['email'], FILTER_SANITIZE_EMAIL);

        try{
            $this->utilisateurService->createUser(new InputUserDTO($email, $params['mdp']));
        }catch (Exception $e){
            throw new HttpBadRequestException($rq, $e->getMessage());
        }
        return $rs->withStatus(200);
    }
}