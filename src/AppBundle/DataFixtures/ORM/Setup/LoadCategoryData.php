<?php

namespace AppBundle\DataFixtures\Setup;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

class LoadCategoryData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $gameLength = $this->createTaxon($manager, 'Game Length');

        $this->createTaxon($manager, 'Quick (<15 mins)', $gameLength);
        $this->createTaxon($manager, 'Short (15-30 mins)', $gameLength);
        $this->createTaxon($manager, 'Basic (30-60 mins)', $gameLength);
        $this->createTaxon($manager, 'Long (1-2 hours)', $gameLength);
        $this->createTaxon($manager, 'Epic (3+ hours)', $gameLength);

        $gameLength = $this->createTaxon($manager, 'Number of Players');

        $this->createTaxon($manager, 'Solitaire (1)', $gameLength);
        $this->createTaxon($manager, '2', $gameLength);
        $this->createTaxon($manager, '3', $gameLength);
        $this->createTaxon($manager, '4', $gameLength);
        $this->createTaxon($manager, '5', $gameLength);
        $this->createTaxon($manager, '6', $gameLength);
        $this->createTaxon($manager, 'Party Games (7+)', $gameLength);

        $gameLength = $this->createTaxon($manager, 'Publisher');
        $this->createTaxon($manager, 'Mayfair', $gameLength);

        $gameLength = $this->createTaxon($manager, 'Game System');
        $this->createTaxon($manager, 'Catan', $gameLength);

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
     * @param ObjectManager $manager
     * @param TaxonInterface $parentTaxon
     * 
     * @return TaxonInterface
     */
    private function createTaxon(ObjectManager $manager, $name, TaxonInterface $parentTaxon = null)
    {
        /* @var TaxonInterface $taxon */
        $taxon = $this->get('sylius.factory.taxon')->createNew();
        $taxon->setCode($this->getCode($name));
        $taxon->setName($name);
        $taxon->setParent($parentTaxon);

        $manager->persist($taxon);
        $this->setReference('App.Taxon.'.$name, $taxon);
        
        return $taxon;
    }

    private function getCode($name)
    {
        return str_replace(' ', '-', $name);
    }
}
