<?php

namespace AppBundle\DataFixtures\Setup;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;
use Sylius\Component\Payment\Calculator\DefaultFeeCalculators;
use Sylius\Component\Payment\Model\PaymentMethodInterface;

class LoadPaymentMethodsData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $manager->persist($this->createPaymentMethod('Offline', 'offline'));
        $manager->persist($this->createPaymentMethod('Paypal', 'paypal_express_checkout'));

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 10;
    }

    /**
     * @param string  $name
     * @param string  $gateway
     * @param boolean $enabled
     *
     * @return PaymentMethodInterface
     */
    protected function createPaymentMethod($name, $gateway, $enabled = true)
    {
        $method = $this->getPaymentMethodFactory()->createNew();
        $method->setCode($name);
        $method->setName($name);
        $method->setGateway($gateway);
        $method->setEnabled($enabled);

        $this->setReference('App.PaymentMethod.'.$name, $method);

        return $method;
    }
}
