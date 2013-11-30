<?php

namespace Vokuro\Controllers;

use Vokuro\Forms\LoginForm,
    Vokuro\Forms\SignUpForm,
    Vokuro\Forms\ForgotPasswordForm,
    Vokuro\Auth\Auth,
    Vokuro\Auth\Exception as AuthException,
    Vokuro\Models\Users,
    Vokuro\Models\ResetPasswords;

class SessionController extends ControllerBase
{

    public function initialize()
    {
        $this->view->setTemplateBefore('public');
    }

    public function indexAction()
    {
        
    }

    public function signupAction()
    {
        $form = new SignUpForm();

        if ($this->request->isPost())
        {

            if ($form->isValid($this->request->getPost()) != false)
            {

                //if it first user, he is fated to be an administrator
                $profileId = !Users::count() ? 1 : 2;

                $user = new Users();
                $user->assign(array(
                    'name' => $this->request->getPost('name', 'striptags'),
                    'email' => $this->request->getPost('email'),
                    'password' => $this->security->hash($this->request->getPost('password')),
                    'profilesId' => $profileId
                ));

                if ($user->save())
                {
                    return $this->dispatcher->forward(array(
                            'controller' => 'index',
                            'action' => 'index'
                    ));
                }

                $this->flash->error($user->getMessages());
            }
        }

        $this->view->form = $form;
    }

    /**
     * Starts a session in the admin backend
     */
    public function loginAction()
    {

        $form = new LoginForm();

        try
        {

            if (!$this->request->isPost())
            {

                if ($this->auth->hasRememberMe())
                {
                    return $this->auth->loginWithRememberMe();
                }
            } else
            {

                if ($form->isValid($this->request->getPost()) == false)
                {
                    foreach ($form->getMessages() as $message)
                    {
                        $this->flash->error($message);
                    }
                } else
                {

                    $this->auth->check(array(
                        'email' => $this->request->getPost('email'),
                        'password' => $this->request->getPost('password'),
                        'remember' => $this->request->getPost('remember')
                    ));

                    return $this->response->redirect('users');
                }
            }
        } catch (AuthException $e)
        {
            $this->flash->error($e->getMessage());
        }

        $this->view->form = $form;
    }

    /**
     * Shows the forgot password form
     */
    public function forgotPasswordAction()
    {
        $form = new ForgotPasswordForm();

        if ($this->request->isPost())
        {

            if ($form->isValid($this->request->getPost()) == false)
            {
                foreach ($form->getMessages() as $message)
                {
                    $this->flash->error($message);
                }
            } else
            {

                $user = Users::findFirstByEmail($this->request->getPost('email'));
                if (!$user)
                {
                    $this->flash->success('There is no account associated to this email');
                } else
                {

                    $resetPassword = new ResetPasswords();
                    $resetPassword->usersId = $user->id;
                    if ($resetPassword->save())
                    {
                        $this->flash->success('Success! Please check your messages for an email reset password');
                    } else
                    {
                        foreach ($resetPassword->getMessages() as $message)
                        {
                            $this->flash->error($message);
                        }
                    }
                }
            }
        }

        $this->view->form = $form;
    }

    /**
     * Closes the session
     */
    public function logoutAction()
    {
        $this->auth->remove();

        return $this->response->redirect('index');
    }

}
