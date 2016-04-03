<?php

namespace AppBundle\DataFixtures\Setup;

use AppBundle\Entity\Channel;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;
use Sylius\Component\Core\Model\ChannelInterface;

class LoadChannelData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var Channel $channel */
        $channel = $this->createChannel(
            'WEB-UK',
            'Web-UK',
            'http://example.com/',
            array('en_GB'),
            array('GBP'),
            array('Free'),
            array('Offline')
        );

        $manager->persist($channel);
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 40;
    }

    /**
     * @param string $name
     * @param array  $locales
     * @param array  $currencies
     * @param array  $shippingMethods
     * @param array  $paymentMethods
     *
     * @return ChannelInterface
     */
    protected function createChannel($code, $name, $url, array $locales = array(), array $currencies = array(), array $shippingMethods = array(), array $paymentMethods = array())
    {
        $channel = $this->getChannelFactory()->createNew();
        $channel->setCode($code);
        $channel->setName($name);
        $channel->setHostname($url);
        $channel->setColor($this->faker->randomElement(array('Red', 'Green', 'Blue', 'Orange', 'Pink')));

        $this->setReference('App.Channel.'.$code, $channel);

        foreach ($locales as $locale) {
            $channel->addLocale($this->getReference('App.Locale.'.$locale));
        }
        foreach ($currencies as $currency) {
            $channel->addCurrency($this->getReference('App.Currency.'.$currency));
        }
        foreach ($shippingMethods as $shippingMethod) {
            $channel->addShippingMethod($this->getReference('App.ShippingMethod.'.$shippingMethod));
        }
        foreach ($paymentMethods as $paymentMethod) {
            $channel->addPaymentMethod($this->getReference('App.PaymentMethod.'.$paymentMethod));
        }

        return $channel;
    }
}
