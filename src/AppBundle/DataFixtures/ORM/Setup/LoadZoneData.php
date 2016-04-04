<?php

namespace AppBundle\DataFixtures\Setup;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Addressing\Model\ZoneMemberInterface;

class LoadZoneData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $uk = $this->createZone('UK', ZoneInterface::TYPE_COUNTRY, array('GB-ENG'));
        $manager->persist($uk);
        $manager->flush();

        $settingsManager = $this->get('sylius.settings.manager');
        $settings = $settingsManager->loadSettings('sylius_taxation');
        $settings->set('default_tax_zone', $uk);
        $settingsManager->saveSettings('sylius_taxation', $settings);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 20;
    }

    /**
     * Create a new zone instance of given type.
     *
     * @param string $name
     * @param string $type
     * @param array  $members
     *
     * @return ZoneInterface
     */
    protected function createZone($name, $type, array $members)
    {
        /* @var $zone ZoneInterface */
        $zone = $this->getZoneFactory()->createNew();
        $zone->setCode($name);
        $zone->setName($name);
        $zone->setType($type);

        foreach ($members as $id) {
            /* @var $zoneMember ZoneMemberInterface */
            $zoneMember = $this->getZoneMemberFactory($type)->createNew();
            $zoneMember->setCode($id);

            if ($this->hasReference('App.'.ucfirst($type).'.'.$id)) {
                $zoneMember->{'set'.ucfirst($type)}($this->getReference('App.'.ucfirst($type).'.'.$id));
            }

            $zone->addMember($zoneMember);
        }

        $this->setReference('App.Zone.'.$name, $zone);

        return $zone;
    }
}
