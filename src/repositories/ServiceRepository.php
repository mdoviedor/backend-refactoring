<?php

namespace repositories;

use models\Driver;
use models\Service;

class ServiceRepository
{

    /**
     * @param int $serviceId
     * @return Service|null
     */
    public function findById(int $serviceId): ? Service
    {
        return new Service();
    }

    public function driverConfirmedService(Service $service, Driver $driver)
    {
        $service->setDriver($driver)
            ->setStatusId(Service::CONFIRMED)
            ->setCardId($driver->getCardId());
    }

    public function doPushMessage(Service $service)
    {
        \error_log('Enviando mensaje');
    }
}
