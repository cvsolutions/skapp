<?php
namespace User\Model;

use AuthModule\Identity\ModelInterface as AuthModelInterface;
use User\Model\Criteria\UserCollectionCriteria;
use Matryoshka\Model\ObservableModel;

class UserModel extends ObservableModel implements AuthModelInterface
{
	
    /**
     * {@inheritdoc}
     */
    public function findByIdentity($identity)
    {
        return $this->find((new UserCollectionCriteria())->setEmail($identity));
    }

    /**
     * 
     * @param string $from
     * @param string $to
     * @return Ambigous <\Matryoshka\Model\ResultSet\ResultSetInterface, boolean, NULL, \Zend\EventManager\mixed, mixed>
     */
    public function findByRegistrationDateRange($from, $to)
    {
    	return $this->find((new UserCollectionCriteria())->setCreatedDateFrom($from)->setCreatedDateTo($to));
    }
    
	/**
	 * @param string $userId
	 * @return ResultSetInterface
	 */    
    public function findItemsByUser($userId)
    {
    	$itemModel = $this->getServiceLocator()->get('Application\Model\ItemModel');
    	return $itemModel->findItemsByUser($userId);
    }
}