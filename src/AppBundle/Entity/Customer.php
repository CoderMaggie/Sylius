<?php

namespace AppBundle\Entity;

use Sylius\Component\Core\Model\Customer as BaseCustomer;

/**
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class Customer extends BaseCustomer
{
    /**
     * @var string
     */
    private $secondPhoneNumber;

    /**
     * @var bool
     */
    private $receiveNewsletter = false;

    /**
     * @var string
     */
    private $nickname;

    /**
     * @var string
     */
    private $favouriteGames;

    /**
     * @var string
     */
    private $futurePurchases;

    /**
     * @return string
     */
    public function getSecondPhoneNumber()
    {
        return $this->secondPhoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setSecondPhoneNumber($phoneNumber)
    {
        $this->secondPhoneNumber = $phoneNumber;
    }

    /**
     * @return bool
     */
    public function isReceiveNewsletter()
    {
        return $this->receiveNewsletter;
    }

    /**
     * @param bool $receiveNewsletter
     */
    public function setReceiveNewsletter($receiveNewsletter)
    {
        $this->receiveNewsletter = $receiveNewsletter;
    }

    /**
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    /**
     * @return string
     */
    public function getFuturePurchases()
    {
        return $this->futurePurchases;
    }

    /**
     * @param string $futurePurchases
     */
    public function setFuturePurchases($futurePurchases)
    {
        $this->futurePurchases = $futurePurchases;
    }

    /**
     * @return string
     */
    public function getFavouriteGames()
    {
        return $this->favouriteGames;
    }

    /**
     * @param string $favouriteGames
     */
    public function setFavouriteGames($favouriteGames)
    {
        $this->favouriteGames = $favouriteGames;
    }
}
