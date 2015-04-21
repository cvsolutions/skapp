<?php
namespace User\Form;

use User\Form\FieldSet\UserFieldSet;
//use User\Form\Traits\UserNotRequiredInputTrait;
use User\Model\Validator\Equal;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Validator\Identical;

class ProfileForm extends Form implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    const NAME = 'profile-form';

    const INPUT_NAME_PASSWORD_RE = 'passwordRe';
    const INPUT_NAME_PRIVACY     = 'privacy';
    const INPUT_PRIVACY_ACCEPT     = 'yes';
    const INPUT_PRIVACY_NOT_ACCEPT = 'no';

    /**
     * Construct
     */
    public function __construct()
    {
        // Config
        parent::__construct(self::NAME);
        $this->setAttribute('method', 'POST');
    }

    /**
     * Init fieldsets, elements, inputFilter and validationGroup
     */
    public function init()
    {
        $this->add(
            [
                'name' => UserFieldSet::NAME,
                'type' => 'User\Form\FieldSet\UserFieldSet'
            ]
        );

        $this->initValidationGroup();
    }


    public function getInputFilter()
    {
        if(!$this->filter){
            $filter = parent::getInputFilter();
            $this->initInputFilter();
            return $filter;
        }else{
            return parent::getInputFilter();
        }
    }

    protected function initInputFilter()
    {
        $inputFilter = $this->getInputFilter();
        if ($inputFilter->has(UserFieldSet::NAME)) {

            $inputFilterFieldSet = $inputFilter->get(UserFieldSet::NAME);//get UserFieldSet

            if ($inputFilterFieldSet->has(UserFieldSet::INPUT_NAME_USERNAME)) {
                $input = $inputFilterFieldSet->get(UserFieldSet::INPUT_NAME_USERNAME);
                $input->setRequired(true);
            }

            if ($inputFilterFieldSet->has(UserFieldSet::INPUT_NAME_EMAIL)) {
                $input = $inputFilterFieldSet->get(UserFieldSet::INPUT_NAME_EMAIL);
                $input->setRequired(true);
                $noIdentityValidator = $this->getServiceLocator()->getServiceLocator()->get('ValidatorManager')->get('User\Model\Validator\NoIdentityExclude');
                $input->getValidatorChain()->attach($noIdentityValidator);
            }
        }
    }

    /**
     * Set the validation group for this form
     */
    protected function initValidationGroup()
    {
    	$this->setValidationGroup([
    			'user-fieldset' => [
					'email',
    				'user_name'
    			]
    	]);
    }
    
}
