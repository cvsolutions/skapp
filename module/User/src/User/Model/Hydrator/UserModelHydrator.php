<?php
namespace User\Model\Hydrator;

use Matryoshka\Model\Wrapper\Mongo\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\Filter\MethodMatchFilter;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;
use Matryoshka\Model\Wrapper\Mongo\Hydrator\Strategy\MongoDateStrategy;
/**
 * Class UserModelHydrator
 *
 * This hydrator is used by the model to
 * hydrate data from the DB to the entity object (reading) and
 * to extract data from the entity in order to be saved into the DB (writing)
 */
class UserModelHydrator extends ClassMethods
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct(true);

        // Convert DateTime to MongoDate and viceversa
        $this->addStrategy('date_created', new MongoDateStrategy());
        $this->addStrategy('date_modified', new MongoDateStrategy());

        // Do not save password
        $this->filterComposite->addFilter(
            'getPassword',
            new MethodMatchFilter('getPassword'),
            FilterComposite::CONDITION_AND
        );
    }
}