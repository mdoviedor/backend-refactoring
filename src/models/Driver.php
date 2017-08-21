<?php
/**
 * Created by PhpStorm.
 * User: marlon
 * Date: 21/08/17
 * Time: 01:28 PM
 */

namespace models;

class Driver
{
    public const _AVAILABLE = 1;
    public const UNAVAILABLE = 0;

    protected $available;

    protected $card_id;


    /**
     * @return Driver
     */
    public function setAvailable(): Driver
    {
        $this->available = self::_AVAILABLE;
        return $this;
    }

    /**
     * @return Driver
     */
    public function setUnavailable(): Driver
    {
        $this->available = self::NO_AVAILABLE;
        return $this;
    }

    /**
     * @param string $card_id
     * @return Driver
     */
    public function setCardId(string $card_id): Driver
    {
        $this->card_id = $card_id;
        return $this;
    }

    public function getCardId()
    {
        return $this->card_id;
    }
}
