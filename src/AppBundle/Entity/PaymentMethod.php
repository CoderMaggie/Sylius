<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ShippingMethodInterface;
use Sylius\Component\Payment\Model\PaymentMethod as BasePaymentMethod;

/**
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class PaymentMethod extends BasePaymentMethod
{
    /**
     * @var Collection|ShippingMethodInterface[]
     */
    private $availableForShippingMethods;

    public function __construct()
    {
        parent::__construct();

        $this->availableForShippingMethods = new ArrayCollection();
    }

    /**
     * @return Collection|ShippingMethodInterface[]
     */
    public function getAvailableForShippingMethods()
    {
        return $this->availableForShippingMethods;
    }

    /**
     * @param ShippingMethodInterface $shippingMethod
     *
     * @return bool
     */
    public function isAvailableForShippingMethod(ShippingMethodInterface $shippingMethod)
    {
        return $this->availableForShippingMethods->contains($shippingMethod);
    }

    /**
     * @param ShippingMethodInterface $shippingMethod
     */
    public function addAvailableForShippingMethod(ShippingMethodInterface $shippingMethod)
    {
        if (!$this->isAvailableForShippingMethod($shippingMethod)) {
            $this->availableForShippingMethods->add($shippingMethod);
        }
    }

    /**
     * @param ShippingMethodInterface $shippingMethod
     */
    public function removeAvailableForShippingMethod(ShippingMethodInterface $shippingMethod)
    {
        $this->availableForShippingMethods->removeElement($shippingMethod);
    }

    /**
     * {@inheritdoc}
     */
    public static function getTranslationClass()
    {
        return get_parent_class().'Translation';
    }
}
