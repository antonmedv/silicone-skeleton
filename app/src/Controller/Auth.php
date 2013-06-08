<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Controller;

use Silicone\Route;
use Silicone\Controller;
use Entity\User;
use Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Request;

class Auth extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request)
    {
        $response = $this->render('login.twig', array(
            'error' => $this->app['security.last_error']($request),
            'last_username' => $this->app->session()->get('_security.last_username'),
        ));
        return $response;
    }

    /**
     * @Route("/register", name="register")
     */
    public function register()
    {
        $form = $this->app->formType(new RegistrationFormType());

        if ($this->request->isMethod('POST')) {
            $form->bind($this->request);

            if ($form->isValid()) {
                /** @var $user \Entity\User */
                $user = $form->getData();
                $user->encodePassword($this->app['security.encoder.digest']);
                $this->app->entityManager()->persist($user);
                $this->app->entityManager()->flush();

                return $this->app->redirect($this->app->url('login'));
            }
        }

        $response =  $this->render('register.twig', array(
            'form' => $form->createView(),
        ));
        $response->setSharedMaxAge(5);
        return $response;
    }

    /**
     * @Route("/success", name="register_success")
     */
    public function success()
    {
        return $this->render('users/register/success.twig');
    }
}
