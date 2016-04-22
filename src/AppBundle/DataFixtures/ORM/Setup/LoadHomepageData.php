<?php

namespace AppBundle\DataFixtures\Setup;

use AppBundle\Entity\CarouselItemInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Magdalena Banasiak <magdalena.banasiak@lakion.com>
 */
class LoadHomepageData extends DataFixture
{
    protected $path = '/../../../Resources/fixtures/CarouselItems/';

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $finder = new Finder();
        $uploader = $this->get('sylius.image_uploader');

        foreach ($finder->files()->in(__DIR__.$this->path) as $img) {
            /** @var CarouselItemInterface $item */
            $item = $this->get('app.factory.carousel_item')->createNew();
            $item->setFile(new UploadedFile($img->getRealPath(), $img->getFilename()));

            $uploader->upload($item);
            $manager->persist($item);

            $this->setReference('App.Image.'.$img->getBasename('.jpg'), $item);
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
