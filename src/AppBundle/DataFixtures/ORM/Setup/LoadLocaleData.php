<?php

namespace AppBundle\DataFixtures\Setup;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;

class LoadLocaleData extends DataFixture
{
    protected $locales = array(
        'en_GB',
    );

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $localeRepository = $this->getLocaleFactory();

        foreach ($this->locales as $code) {
            $locale = $localeRepository->createNew();
            $locale->setCode($code);

            $this->setReference('App.Locale.'.$code, $locale);

            $manager->persist($locale);
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
