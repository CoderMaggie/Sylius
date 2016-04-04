<?php

namespace AppBundle\DataFixtures\Setup;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;

class LoadCountryData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $countryFactory = $this->get('sylius.factory.country');
        $countries = array('GB-ENG');

        foreach ($countries as $isoName => $name) {
            $country = $countryFactory->createNew();
            $country->setCode($isoName);

            $manager->persist($country);

            $this->setReference('App.Country.'.$isoName, $country);
        }

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
