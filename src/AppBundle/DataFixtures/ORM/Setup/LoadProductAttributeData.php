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
        $contentsAttribute = $this->createAttribute('Contents', TextAreaAttributeType::TYPE);
        $resourcesAttribute = $this->createAttribute('Resources', TextareaAttributeType::TYPE);

        $manager->persist($contentsAttribute);
        $manager->persist($resourcesAttribute);
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
        $code = $this->getCodeFromName($name);

        /** @var AttributeInterface $attribute */
        $attribute = $this->get('sylius.factory.product_attribute')->createNew();
        $attribute->setName($name);
        $attribute->setCode($code);
        $attribute->setType($type);

        $this->addReference('App.Attribute.'.$code, $attribute);

        return $attribute;
    }

    protected function getCodeFromName($name)
    {
        return strtolower(str_replace(' ', '_', $name));
    }
}
