<?php

namespace api;


use api\presentators\JsonView;
use domain\PushMessage;
use models\Service;
use repositories\DriverRepository;
use repositories\ServiceRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class Code
{
    protected const NO_SERVICE = 6;

    /**
     * @var ServiceRepository
     */
    private $serviceRepository;

    /**
     * @var DriverRepository
     */
    private $driverRepository;
    /**
     * @var PushMessage
     */
    private $pushMessage;

    /**
     * Code constructor.
     * @param ServiceRepository $serviceRepository
     * @param DriverRepository $driverRepository
     * @param PushMessage $pushMessage
     */
    public function __construct(ServiceRepository $serviceRepository, DriverRepository $driverRepository, PushMessage $pushMessage)
    {

        $this->serviceRepository = $serviceRepository;
        $this->driverRepository = $driverRepository;
        $this->pushMessage = $pushMessage;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function confirm(Request $request)
    {
        $serviceId = (int)$request->request->get('service_id');
        $driverId = (int)$request->request->get('driver_id');

        $service = $this->serviceRepository->findById($serviceId);

        if (!isset($service)) {
            return new JsonResponse([
                'error' => self::NO_SERVICE
            ], 202);
        }

        if ($service->getStatusId() === 6) {
            return new JsonResponse([
                'error' => 2
            ], 202);
        }

        if ($this->isUnconfirmedService($service)) {
            $driver = $this->driverRepository->findById($driverId);

            $this->driverRepository->setNotAvilable($driver);
            $this->serviceRepository->driverConfirmedService($service, $driver);

            $this->pushMessage->execute($service);

            return new JsonResponse([
                'error' => 0
            ], 202);
        }
        return new JsonResponse([
            'error' => 1
        ], 202);
    }

    /**
     * @param Service $service
     * @return bool
     */
    protected function isUnconfirmedService(Service $service): bool
    {
        if (!$service->getDriver() && $service->getStatusId() === Service::UNCONFIRMED) {
            return true;
        }
        return false;
    }

}