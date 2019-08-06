<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\DependencyInjection\ContainerInterface AS Container;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface AS PasswordEncoder;
use App\Repository\UserRepository;
use App\Utils\PasswordGenerator;
use Doctrine\ORM\EntityManagerInterface AS EM;

class SecurityController extends AbstractController
{
    protected $container;
    private $authToken;
    private $user;
    private $password;
    private $encoder;
    private $em;

    public function __construct(Container $container, UserRepository $user, PasswordGenerator $password, PasswordEncoder $encoder, EM $em)
    {
        $this->container = $container;
        $this->authToken = $container->getParameter('auth_token');
        $this->user = $user;
        $this->password = $password;
        $this->encoder = $encoder;
        $this->em = $em;
    }

    /**
     * Вход в личный кабинет
     *
     * @Route("/auth/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $token = $this->get('security.token_storage')->getToken();
        if ($token instanceof PostAuthenticationGuardToken) {
            return $this->redirectToRoute('tasks');
        }
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Автоматическая авторизация в менеджере задач
     * и добавление нового пользователя.
     *
     * @Route("/auth/auto", methods={"POST"}, name="auto_login")
     * @param Request $request
     * @return RedirectResponse
     */
    public function autoLogin(Request $request)
    {
        $headers = $request->headers;
        $content = json_decode($request->getContent(), 1);
        $token = $headers->get('authtoken');

        if (empty($token)) {
            throw new AccessDeniedHttpException('Токен отсутствует...');
        }
        else {
            if ($token !== $this->authToken) {
                throw new AccessDeniedHttpException('Некорректный токен!');
            }

            $user = $this->user->findOneBy(['auth_id' => $content['personal_id']]);
            if (is_null($user)) {
                // Добавляем такого
                try {
                    $user = new User();
                    $user->setUuid($content['personal_phone']);
                    $user->setAuthId($content['personal_id']);
                    $user->setFullname($content['personal_name']);
                    $user->setRoles(['ROLE_AUTHOR']);
                    $plainPassword = $this->password->generatePassword();
                    $encoded = $this->encoder->encodePassword($user, $plainPassword);
                    $user->setPassword($encoded);
                    $this->em->persist($user);
                    $this->em->flush();
                }
                catch (UniqueConstraintViolationException $e) {
                    throw new AccessDeniedHttpException('Такой телефон существует...');
                }
            }
            // Авторизуем
            $token = new PostAuthenticationGuardToken($user, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);
        }

        return $this->redirectToRoute('tasks');
    }

    /**
     * Назначить ID сессии, для авторизации
     *
     * @Route("/auth/set-session/{sessionID}", methods={"GET"}, name="auth_setsession")
     * @param string $sessionID
     * @return RedirectResponse
     */
    public function setSessionID(string $sessionID): RedirectResponse
    {
        $cookie = new Cookie('PHPSESSID', $sessionID);
        $response = $this->redirectToRoute('tasks');
        $response->headers->setCookie($cookie);
        return $response;
    }

    /**
     * Выход
     *
     * @Route("/auth/logout", name="logout")
     */
    public function logout()
    {
        return $this->redirectToRoute('login');
    }
}
