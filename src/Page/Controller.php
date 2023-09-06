<?php declare(strict_types=1);

namespace App\Page;

use App\Configuration;
use App\Data\Functions;
use App\Data\FunctionSeeker;
use App\Rendering\TemplateRenderer;
use App\Storage\Users\UsersDB;
use Doctrine\DBAL\Driver\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


final class Controller
{
    private $renderer;
    private $configuration;
    private $user_table;

    public function __construct(Configuration $configuration, TemplateRenderer $render, UsersDB $user_table)
    {
        $this->renderer = $render;
        $this->configuration = $configuration;
        $this->user_table = $user_table;
    }

    /**@used at route
     * @param SessionInterface $session
     * @return mixed
     */
    public function isAuth(SessionInterface $session){
        return $session->get('auth', false);
    }

    /**@used at route
     * @param Request $request
     * @return Response
     */
    public function authentication(Request $request): Response
    {

        $login = $request->get('login', '');
        $password = $request->get('password','');
        if($this->configuration->get('login') === $login && $this->configuration->get('password') === $password){
            $session = $request->getSession();
            $session->set('auth', true);
            return $this->home($request);
        }


        return new Response($this->renderer->render('Authentication.twig'));
    }

    /**@used at route
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('auth', false);
        return (new RedirectResponse('/'));
    }

    public function home(Request $request): Response
    {
        return new Response($this->renderer->render('Home.twig',['users' => $this->user_table->getList()]));
    }

    public function api(Request $request){

        $respArray = [
            'validityIndicator' => 0,
            'result' => 0,
            'status' => 'error',
            'statusCode' => 404,
        ];
        $functionName = $request->get('funcName','');

        if(empty($functionName)) {
            $respArray['status'] = 'function not found';
            return new Response(json_encode($respArray));
        }
        $data = $this->user_table->find($request->get('login'), $request->get('password'));
        if(empty($data['id'])){
            $respArray['status'] = 'user not found';
            return new Response(json_encode($respArray));
        }
        $respArray['validityIndicator'] = 1;

        $fs = new FunctionSeeker($functionName);
        $params = $fs->getParams();
        foreach ($params as $name => $value){
            $params[$name] = floatval($request->get($name, 0));
        }

        try {
            $respArray['result'] = $fs->getResult($params);
            $respArray['status'] = 'ok';
            $respArray['statusCode'] = 200;
        } catch (\Exception | \Error $e){
            $respArray['status'] = 'function error';
        }

        return new Response(json_encode($respArray));
    }

    /**@used at route
     * @param Request $request
     * @param array $vars
     * @return Response
     * @throws Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function edit_user(Request $request, array $vars): Response
    {
        $id = $vars['id'] ?? '';
        $user = [];
        if(!empty($id)){
            $user = $this->user_table->getRecord($id);
        }
        return new Response($this->renderer->render('EditView.twig', ['user' => $user]));
    }

    /**@used at route
     * @param Request $request
     * @param array $vars
     * @return Response
     */
    public function delete_user(Request $request, array $vars): Response
    {
        $this->user_table->delete($vars['id'] ?? '');
        return new RedirectResponse('/home');
    }

    /**@used at route
     * @param Request $request
     * @return Response
     */
    public function save_user(Request $request): Response
    {

        $fields = [
            'id' => $request->get('id', ''),
            'name' => $request->get('name', 'unknown name'),
            'login' => $request->get('login', ''),
            'password' => $request->get('password', ''),
            'param1' => $request->get('param1', 0),
            'param2' => $request->get('param2', 0),
        ];
        $this->user_table->updateRecord($fields);

        return (new RedirectResponse('/home'));
    }

    /**@used at route
     * @param string $message
     * @param int $code
     * @return Response
     */
    public function showErrorPage(string $message, int $code): Response
    {
        return new Response(
            $this->renderer->render(
                'Error.twig',
                array(
                    'message' => $message
                )
            ),
            $code
        );
    }
}
