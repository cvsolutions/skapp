<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Response;

/**
 * @method \AuthModule\Controller\Plugin\InteractiveAuth interactiveAuth()
 */
class UserController extends AbstractActionController
{
    /**
     * @var string
     */
    protected $defaultRedirectRouteName = 'home';
    protected $profileRouteName = 'user/profile';

	/**
	 * @var \User\Model\UserModel
	 */
	protected $userModel;

	/**
	 * @var \User\Model\Entity\UserEntity
	 */
	protected $user;

	public function indexAction()
	{
		return true;
	}

    //unauthenticated actions
    public function loginAction()
    {
        $prg = $this->prg();
        if ($prg instanceof Response) {
            return $prg;
        }

        // post login redirect url
        $next = $this->params()->fromQuery('next', $this->url()->fromRoute(
            $this->profileRouteName
        ));

        if ($this->identity()) {
            //User logged in already.
            return $this->redirect()->toUrl($next);
        }

        $loginForm = $this->serviceLocator->get('FormElementManager')->get('User\Form\LoginForm');

        if (is_array($prg)) {
            $loginForm->setData($prg);
            if ($loginForm->isValid()) {
                //ModelAdapter::authenticate -> modelObject::findByIdentity -> $identityObject::validateCredential
            	$result = $this->interactiveAuth()->login(
                    $loginForm->get('email')->getValue(),
                    $loginForm->get('password')->getValue()
                );

                if ($result->isValid()) {
                    //OK! User logged in successfully
                    return $this->redirect()->toUrl($next);
                }
                // else, authentication error
                $errors = [];
                foreach ($result->getMessages() as $k => $v) {
                    $errors[$k] = $this->getServiceLocator()->get('translator')->translate($v);
                }

                // FIXME: find a better way to handle errors
                $loginForm->setMessages(array('email' => $errors));
            }
        }

        return new ViewModel([
           'loginForm' => $loginForm
        ]);

    }

    public function registrationAction()
    {
        $prg = $this->prg();
        if ($prg instanceof Response) {
            return $prg;
        }
        /* @var $registrationForm \User\Form\RegistrationForm */
    	$registrationForm = $this->serviceLocator->get('FormElementManager')->get('User\Form\RegistrationForm');
    	/* @var $user \User\Model\EntityUserEntity */
    	$user = $this->userModel = $this->model()->get('User\Model\UserModel')->create();
    	$registrationForm->bind($user);

    	if (is_array($prg)) {
    	    $registrationForm->setData($prg);
    	    if ($registrationForm->isValid()) {
                $user->save();
               $viewModel = new ViewModel();
                $viewModel->setTemplate('user/user/thank-you');
               return $viewModel;
                
                /*return $this->redirect()->toUrl($this->url()->fromRoute(
            		$this->profileRouteName
        		));*/
    	    }
    	    // else... show errors
    	}

    	return new ViewModel([
           'registrationForm' => $registrationForm
    	]);
    }

    public function recoverPasswordAction()
    {
    	return new ViewModel();
    }

    //authenticated actions
    public function logoutAction()
    {
        if (!$this->identity()) {
            return $this->redirect()->toRoute($this->defaultRedirectRouteName);
        }

        $this->interactiveAuth()->logout();
        return $this->redirect()->toRoute($this->defaultRedirectRouteName);
    }

    public function profileAction()
    {
        $prg = $this->prg();
        if ($prg instanceof Response) {
            return $prg;
        }

        $userModel = $this->model()->get('User\Model\UserModel');
        $identity = $this->identity();
        $user = $userModel->findByIdentity($identity)->current();

        $profileForm = $this->serviceLocator->get('FormElementManager')->get('User\Form\ProfileForm');
        $profileForm->bind($user);

        if (is_array($prg)) {
            $profileForm->setData($prg);
            if ($profileForm->isValid()) {
                $user->save();
                var_dump($prg);
            }

        }

        return new ViewModel([
            'profileForm' => $profileForm
        ]);
    }

    public function profileEditAction()
    {
    	return new ViewModel();
    }

    public function adminOnlyAction()
    {
    	return new ViewModel();
    }

}

