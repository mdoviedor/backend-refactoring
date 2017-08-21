<?php

namespace models;

/**
 * Class Service
 * @package models
 */
class Service
{

    public const CONFIRMED = 2;
    public const UNCONFIRMED = 1;

    /**
     * @var int
     */
    protected $status_id;

    /**
     * @var Driver
     */
    protected $driver_id;

    protected $card_id;


    /**
     * @param string $card_id
     * @return Service
     */
    public function setCardId(string $card_id): Service
    {
        $this->card_id = $card_id;
        return $this;

    }

    /**
     * @param int $statusId
     * @return Service
     */
    public function setStatusId(int $statusId): Service
    {
        $this->status_id = $statusId;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatusId()
    {
        return $this->status_id;
    }

    /**
     * @return Driver|null
     */
    public function getDriver(): ?  Driver
    {
        return $this->driver_id;
    }

    /**
     * @param Driver $driver
     * @return Service
     */
    public function setDriver(Driver $driver): Service
    {
        $this->driver_id = $driver;

        return $this;
    }

}