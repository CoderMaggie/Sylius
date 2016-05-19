<?php

namespace AppBundle\DataFixtures\Setup;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;
use Sylius\Component\Payment\Model\PaymentMethodInterface;

class LoadPaymentMethodsData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $manager->persist($this->createPaymentMethod('Cash on Collection', 'offline', ['local_collection']));
        $manager->persist($this->createPaymentMethod('Pay by Bank Transfer', 'offline'));
        $manager->persist($this->createPaymentMethod('Pay by PayPal', 'paypal_express_checkout'));

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 40;
    }

    /**
     * @param string  $name
     * @param string  $gateway
     * @param array   $availableForShippingMethods
     * @param boolean $enabled
     *
     * @return PaymentMethodInterface
     */
    protected function createPaymentMethod(
        $name,
        $gateway,
        array $availableForShippingMethods = ['uk_standard_shipping', 'uk_express_shipping'],
        $enabled = true
    ) {
        $code = $this->getCodeFromName($name);

        $method = $this->getPaymentMethodFactory()->createNew();
        $method->setCode($code);
        $method->setName($name);
        $method->setGateway($gateway);
        $method->setEnabled($enabled);

        foreach($availableForShippingMethods as $shippingMethodCode) {
            $shippingMethod = $this->getReference('App.ShippingMethod.'. $shippingMethodCode);
            $method->addAvailableForShippingMethod($shippingMethod);
        }

        $this->setReference('App.PaymentMethod.'.$code, $method);

        return $method;
    }

    protected function getCodeFromName($name)
    {
        return strtolower(str_replace(' ', '_', $name));
    }
}
