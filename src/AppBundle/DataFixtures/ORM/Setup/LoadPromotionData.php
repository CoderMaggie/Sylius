<?php

namespace AppBundle\DataFixtures\Setup;

use AppBundle\Checker\ShippingMethodPromotionRuleChecker;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;
use Sylius\Component\Core\Model\PromotionInterface;
use Sylius\Component\Core\Promotion\Action\UnitFixedDiscountAction;
use Sylius\Component\Promotion\Model\ActionInterface;
use Sylius\Component\Promotion\Model\RuleInterface;
use Sylius\Component\Shipping\Model\ShippingMethodInterface;

/**
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class LoadPromotionData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $channel = 'WEB-UK';

        /** @var ShippingMethodInterface $localCollection */
        $localCollection = $this->getReference('App.ShippingMethod.local_collection');

        $localCollectionPromotion = $this->createPromotion(
            'Local Collection Promotion',
            'One pound off of every item on local collection.',
            0,
            $channel,
            [$this->createRule(ShippingMethodPromotionRuleChecker::TYPE, ['shipping_method' => $localCollection])],
            [$this->createAction(UnitFixedDiscountAction::TYPE, ['amount' => 100])]
        );

        $manager->persist($localCollectionPromotion);
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 60;
    }

    /**
     * @param string $name
     * @param string $description
     * @param int $priority
     * @param string $channelCode
     * @param RuleInterface[] $rules
     * @param ActionInterface[] $actions
     *
     * @return PromotionInterface
     */
    private function createPromotion($name, $description, $priority, $channelCode, array $rules, array $actions)
    {
        $code = $this->getCodeFromName($name);

        /** @var PromotionInterface $promotion */
        $promotion = $this->getPromotionFactory()->createNew();
        $promotion->setCode($code);
        $promotion->setName($name);
        $promotion->setDescription($description);
        $promotion->setPriority($priority);

        $promotion->addChannel($this->getReference('App.Channel.'.$channelCode));

        foreach ($rules as $rule) {
            $promotion->addRule($rule);
        }
        foreach ($actions as $action) {
            $promotion->addAction($action);
        }

        $this->setReference('App.Promotion.'.$code, $promotion);

        return $promotion;
    }

    /**
     * @param string $type
     * @param array $configuration
     *
     * @return RuleInterface
     */
    private function createRule($type, array $configuration)
    {
        /** @var RuleInterface $rule */
        $rule = $this->getPromotionRuleFactory()->createNew();
        $rule->setType($type);
        $rule->setConfiguration($configuration);

        return $rule;
    }

    private function createAction($type, array $configuration)
    {
        /** @var ActionInterface $action */
        $action = $this->getPromotionActionFactory()->createNew();
        $action->setType($type);
        $action->setConfiguration($configuration);

        return $action;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getCodeFromName($name)
    {
        return strtolower(str_replace(' ', '_', $name));
    }
}
