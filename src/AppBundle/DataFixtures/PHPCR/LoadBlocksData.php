<?php

namespace AppBundle\DataFixtures\PHPCR;

use Doctrine\Common\Persistence\ObjectManager;
use PHPCR\Util\NodeHelper;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;

class LoadBlocksData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $session = $manager->getPhpcrSession();

        $basepath = $this->container->getParameter('cmf_block.persistence.phpcr.block_basepath');
        NodeHelper::createPath($session, $basepath);

        $parent = $manager->find(null, $basepath);
        $factory = $this->container->get('sylius.factory.string_block');

        $productsListBlock = $factory->createNew();
        $productsListBlock->setParentDocument($parent);
        $productsListBlock->setName('productsList1');
        $productsListBlock->setBody(
'<div class="text-instead-of-image same-height-always">
    <h3 class="hidden-md hidden-sm">Other New Releases</h3>
    <ul>
        <li><a>Dice Masters Faerun Under Siege St<span class="hidden-md">arter</span><span class="visible-md-inline">&hellip;</span></a></li>
        <li><a>Pokemon TCG : XY 2016 Spring Tin</a></li>
        <li><a>Its Your Fault: Smash Up Expansion</a></li>
        <li><a>Pretense</a></li>
        <li><a>Encampment Tile Set : B-Sieged</a></li>
        <li><a>Sculpted Mulfin Set : B-Sieged</a></li>
    </ul>
</div>
<div class="text">
    <h3><a href="#">View all New Releases</a></h3>
    <p class="price"><a href="#"><i class="fa fa-arrow-circle-right"></i></a></p>
</div>'
        );

        $manager->persist($productsListBlock);

        $productsListBlock = $factory->createNew();
        $productsListBlock->setParentDocument($parent);
        $productsListBlock->setName('productsList2');
        $productsListBlock->setBody(
'<div class="text-instead-of-image same-height-always">
    <ul>
        <li><a href="#">Dice Masters Faerun Under&hellip;</a></li>
        <li><a href="#">Pokemon TCG : XY 2016 Spr&hellip;</a></li>
        <li><a href="#">Its Your Fault: Smash Up</a></li>
    </ul>
</div>
<div class="text">
    <h3><a href="#">View all Best Sellers</a></h3>
    <p class="price"><a href="#"><i class="fa fa-arrow-circle-right"></i></a></p>
</div>'
        );

        $manager->persist($productsListBlock);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
