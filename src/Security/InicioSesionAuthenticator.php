<?php

namespace App\Security;

;

use App\Entity\Empleado;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class InicioSesionAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    private $dc;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator, ManagerRegistry $dc)
    {
        $this->dc = $dc;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        $user = $token->getUser();
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return new RedirectResponse('home/administrador');
        }elseif(in_array('ROLE_USER', $user->getRoles(), true)){
            
                $repository = $this->dc->getRepository(Empleado::class);
                $empleado = $repository->findOneBy(['mail'=>$user]);
                $cargo = $empleado->getCargo()->getClaveCargo();

                if($cargo == "DOC"){
                    return new RedirectResponse('home/docente');

                }else if($cargo == "JAD"){
                    return new RedirectResponse('home/jefe_academico');
                }
        }
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
