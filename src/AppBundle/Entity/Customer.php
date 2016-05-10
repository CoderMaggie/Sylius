<?php

namespace AppBundle\Entity;

use Sylius\Component\Core\Model\Customer as BaseCustomer;

/**
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class Customer extends BaseCustomer
{
    /**
     * @var string
     */
    private $secondPhoneNumber;

    /**
     * @return string
     */
    public function getSecondPhoneNumber()
    {
        return $this->secondPhoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setSecondPhoneNumber($phoneNumber)
    {
        $this->secondPhoneNumber = $phoneNumber;
    }
}
