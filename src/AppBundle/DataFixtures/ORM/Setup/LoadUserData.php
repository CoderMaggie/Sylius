<?php

namespace AppBundle\DataFixtures\Setup;

use AppBundle\Entity\Customer;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;
use Sylius\Component\Core\Model\UserInterface;

class LoadUserData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = $this->createAdminUser('Michał', 'Marcinkowski', 'michal.marcinkowski@lakion.com');
        $manager->persist($user);

        $user = $this->createAdminUser('Christian', 'Morgan', 'christian@caponica.com');
        $manager->persist($user);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 20;
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     * @param bool   $enabled
     * @param array  $roles
     * @param string $currency
     *
     * @return UserInterface
     */
    protected function createUser($firstName, $lastName, $email, $password, $enabled = true, array $roles = array('ROLE_USER'), $currency = 'GBP')
    {
        $canonicalizer = $this->get('sylius.user.canonicalizer');

        /* @var $user UserInterface */
        $user = $this->getUserFactory()->createNew();
        /** @var Customer $customer */
        $customer = $this->getCustomerFactory()->createNew();
        $customer->setFirstname($firstName);
        $customer->setLastname($lastName);
        $customer->setCurrency($currency);
        $user->setCustomer($customer);
        $user->setUsername($email);
        $user->setEmail($email);
        $user->setUsernameCanonical($canonicalizer->canonicalize($user->getUsername()));
        $user->setEmailCanonical($canonicalizer->canonicalize($user->getEmail()));
        $user->setPlainPassword($password);
        $user->setRoles($roles);
        $user->setEnabled($enabled);

        $this->get('sylius.user.password_updater')->updatePassword($user);

        return $user;
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     *
     * @return UserInterface
     */
    private function createAdminUser($firstName, $lastName, $email, $password = 'password123')
    {
        $user = $this->createUser(
            $firstName, $lastName,
            $email,
            $password,
            true,
            array('ROLE_USER', 'ROLE_SYLIUS_ADMIN', 'ROLE_ADMINISTRATION_ACCESS')
        );
        $user->addAuthorizationRole($this->get('sylius.repository.role')->findOneBy(array('code' => 'administrator')));

        return $user;
    }
}
