<?php

namespace repositories;

use models\Driver;

class DriverRepository
{

    /**
     * @param Driver $driver
     */
    public function setNotAvilable(Driver $driver)
    {
    }

    /**
     * @param int $driverId
     * @return Driver|null
     */
    public function findById(int $driverId): ? Driver
    {
        return new Driver();
    }
}
