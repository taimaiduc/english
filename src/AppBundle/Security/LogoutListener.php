<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 5/31/2017
 * Time: 10:24 PM
 */

namespace AppBundle\Security;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class LogoutListener implements LogoutSuccessHandlerInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onLogoutSuccess(Request $request)
    {
        $url = $this->router->generate('lessons_list');

        $response = new RedirectResponse($url);
        $response->headers->clearCookie('logged_in');

        return $response;
    }
}