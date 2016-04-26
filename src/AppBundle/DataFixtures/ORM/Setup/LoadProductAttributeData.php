<?php

namespace AppBundle\DataFixtures\Setup;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;
use Sylius\Component\Product\Model\AttributeInterface;
use Sylius\Component\Attribute\AttributeType\TextareaAttributeType;

class LoadProductAttributeData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $contentAttribute = $this->createAttribute('Contents', TextAreaAttributeType::TYPE);

        $manager->persist($contentAttribute);
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
     * @param string $name
     * @param string $type
     *
     * @return AttributeInterface
     */
    protected function createAttribute($name, $type)
    {
        $code = str_replace(' ', '-', strtolower($name));

        /** @var AttributeInterface $attribute */
        $attribute = $this->get('sylius.factory.product_attribute')->createNew();
        $attribute->setName($name);
        $attribute->setCode($code);
        $attribute->setType($type);

        $this->addReference('App.Attribute.'.$code, $attribute);

        return $attribute;
    }
}
