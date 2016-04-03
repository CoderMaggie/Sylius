<?php

namespace AppBundle\DataFixtures\Setup;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;
use Sylius\Component\Core\Model\TaxRateInterface;
use Sylius\Component\Taxation\Model\TaxCategoryInterface;

class LoadTaxationData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $taxableGoods = $this->createTaxCategory('Taxable', 'Taxable products.');
        $manager->persist($taxableGoods);
        $manager->flush();

        $taxRate = $this->createTaxRate('VAT', 'UK', 0.10);
        $taxRate->setCategory($taxableGoods);

        $manager->persist($taxRate);
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
     * Create tax category.
     *
     * @param string $name
     * @param string $description
     *
     * @return TaxCategoryInterface
     */
    protected function createTaxCategory($name, $description)
    {
        /* @var $category TaxCategoryInterface */
        $category = $this->getTaxCategoryFactory()->createNew();
        $category->setCode($name);
        $category->setName($name);
        $category->setDescription($description);

        $this->setReference('App.TaxCategory.'.$name, $category);

        return $category;
    }

    /**
     * Create tax rate.
     *
     * @param string  $name
     * @param string  $zoneName
     * @param float   $amount
     * @param Boolean $includedInPrice
     * @param string  $calculator
     *
     * @return TaxRateInterface
     */
    protected function createTaxRate($name, $zoneName, $amount, $includedInPrice = false, $calculator = 'default')
    {
        /* @var $rate TaxRateInterface */
        $rate = $this->getTaxRateFactory()->createNew();
        $rate->setCode($name);
        $rate->setName($name);
        $rate->setZone($this->getZoneByName($zoneName));
        $rate->setAmount($amount);
        $rate->setIncludedInPrice($includedInPrice);
        $rate->setCalculator($calculator);

        $this->setReference('App.TaxRate.'.$name, $rate);

        return $rate;
    }

    protected function getZoneByName($zoneName)
    {
        return $this->getReference('App.Zone.'.$zoneName);
    }
}
