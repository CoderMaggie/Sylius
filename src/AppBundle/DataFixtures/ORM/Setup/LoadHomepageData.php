<?php

namespace AppBundle\DataFixtures\Setup;

use AppBundle\Entity\CarouselItemInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;

class LoadHomepageData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->createCarouselItem(1, 'http://placehold.it/437x150', $manager);
        $this->createCarouselItem(2, 'http://placehold.it/437x150/990000', $manager);
        $this->createCarouselItem(3, 'http://placehold.it/437x150', $manager);
        $this->createCarouselItem(4, 'http://placehold.it/437x150', $manager);
        $this->createCarouselItem(5, 'http://placehold.it/437x150/990000', $manager);

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
     * @param int $position
     * @param string $path
     * @param ObjectManager $manager
     */
    private function createCarouselItem($position, $path, ObjectManager $manager)
    {
        /** @var CarouselItemInterface $item */
        $item = $this->get('app.factory.carousel_item')->createNew();

        $item->setPosition($position);
        $item->setPath($path);

        $manager->persist($item);
    }
}
