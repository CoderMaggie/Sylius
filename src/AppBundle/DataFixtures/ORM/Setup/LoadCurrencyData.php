<?php

namespace AppBundle\DataFixtures\Setup;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;

class LoadCurrencyData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $currencyFactory = $this->get('sylius.factory.currency');

        $currency = $currencyFactory->createNew();
        $currency->setCode('GBP');
        $currency->setExchangeRate(1.00);
        $currency->setBase(true);

        $this->setReference('App.Currency.GBP', $currency);

        $manager->persist($currency);
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 10;
    }
}
