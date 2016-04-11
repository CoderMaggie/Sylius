<?php

namespace AppBundle\DataFixtures\Setup;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;
use Sylius\Component\Core\Model\ShippingMethodInterface;
use Sylius\Component\Shipping\Calculator\DefaultCalculators;
use Sylius\Component\Shipping\Model\ShippingCategoryInterface;

class LoadShippingData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $manager->persist($this->createShippingMethod('DHL', 'UK', DefaultCalculators::FLAT_RATE, array('amount' => 0)));
        $manager->persist($this->createShippingMethod('UPS', 'UK', DefaultCalculators::FLAT_RATE, array('amount' => 1500)));
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 30;
    }

    /**
     * Create shipping method.
     *
     * @param string                    $name
     * @param string                    $zoneName
     * @param string                    $calculator
     * @param array                     $configuration
     * @param ShippingCategoryInterface $category
     *
     * @return ShippingMethodInterface
     */
    protected function createShippingMethod($name, $zoneName, $calculator = DefaultCalculators::FLAT_RATE, array $configuration = array(), ShippingCategoryInterface $category = null)
    {
        /* @var $method ShippingMethodInterface */
        $method = $this->getShippingMethodFactory()->createNew();
        $method->setCode($name);
        $method->setName($name);
        $method->setZone($this->getZoneByName($zoneName));
        $method->setCalculator($calculator);
        $method->setConfiguration($configuration);
        $method->setCategory($category);

        $this->setReference('App.ShippingMethod.'.$name, $method);

        return $method;
    }

    protected function getZoneByName($zoneName)
    {
        return $this->getReference('App.Zone.'.$zoneName);
    }
}
