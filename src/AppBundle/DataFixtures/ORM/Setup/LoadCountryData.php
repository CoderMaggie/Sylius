<?php

namespace AppBundle\DataFixtures\Setup;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;
use Sylius\Component\Addressing\Model\CountryInterface;

class LoadCountryData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $countryFactory = $this->get('sylius.factory.country');
        $countries = array('GB');

        foreach ($countries as $isoCode) {
            /** @var CountryInterface $country */
            $country = $countryFactory->createNew();
            $country->setCode($isoCode);

            $manager->persist($country);

            $this->setReference('App.Country.'.$isoCode, $country);
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
