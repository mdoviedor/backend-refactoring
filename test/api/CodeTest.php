<?php
/**
 * Created by PhpStorm.
 * User: marlon
 * Date: 21/08/17
 * Time: 02:30 PM
 */

namespace test\api;

use api\Code;
use domain\PushMessage;
use models\Driver;
use models\Service;
use PHPUnit\Framework\TestCase;
use repositories\DriverRepository;
use repositories\ServiceRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CodeTest
 * @package test\api
 */
class CodeTest extends TestCase
{
    public function test_not_found_service()
    {
        $serviceRepository = $this->createMock(ServiceRepository::class);
        $driverRepository = $this->createMock(DriverRepository::class);
        $pushMessage = $this->createMock(PushMessage::class);

        $request = Request::create('', 'POST', [
            'service_id' => 12,
            'driver_id' => 10,
        ]);

        $serviceRepository->expects($this->once())
            ->method('findById')
            ->with(12)
            ->willReturn(null);

        $code = new Code($serviceRepository, $driverRepository, $pushMessage);

        $response = $code->confirm($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(6, json_decode($response->getContent())->error);
        $this->assertEquals(202, $response->getStatusCode());
    }

    public function test_status_id_equals_6()
    {
        $serviceRepository = $this->createMock(ServiceRepository::class);
        $driverRepository = $this->createMock(DriverRepository::class);
        $pushMessage = $this->createMock(PushMessage::class);

        $request = Request::create('', 'POST', [
            'service_id' => 12,
            'driver_id' => 10,
        ]);

        $service = new Service();
        $service->setStatusId(6);

        $serviceRepository->expects($this->once())
            ->method('findById')
            ->with(12)
            ->willReturn($service);


        $code = new Code($serviceRepository, $driverRepository, $pushMessage);

        $response = $code->confirm($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(2, json_decode($response->getContent())->error);
        $this->assertEquals(202, $response->getStatusCode());
    }

    public function test_service_already_confirmed()
    {
        $serviceRepository = $this->createMock(ServiceRepository::class);
        $driverRepository = $this->createMock(DriverRepository::class);
        $pushMessage = $this->createMock(PushMessage::class);

        $request = Request::create('', 'POST', [
            'service_id' => 12,
            'driver_id' => 10,
        ]);

        $service = new Service();
        $service->setStatusId(Service::CONFIRMED)
            ->setDriver(new Driver());

        $serviceRepository->expects($this->once())
            ->method('findById')
            ->with(12)
            ->willReturn($service);

        $code = new Code($serviceRepository, $driverRepository, $pushMessage);

        $response = $code->confirm($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(1, json_decode($response->getContent())->error);
        $this->assertEquals(202, $response->getStatusCode());
    }

    public function test_unconfirmed_service()
    {
        $serviceRepository = $this->createMock(ServiceRepository::class);
        $driverRepository = $this->createMock(DriverRepository::class);
        $pushMessage = $this->createMock(PushMessage::class);

        $request = Request::create('', 'POST', [
            'service_id' => 12,
            'driver_id' => 10,
        ]);

        $service = new Service();
        $service->setStatusId(Service::UNCONFIRMED);

        $serviceRepository->expects($this->once())
            ->method('findById')
            ->with(12)
            ->willReturn($service);

        $driver = new Driver();
        $driverRepository->expects($this->once())
            ->method('findById')
            ->with(10)
            ->willReturn($driver);

        $driverRepository->expects($this->once())
            ->method('setNotAvilable')
            ->with($driver);

        $serviceRepository->expects($this->once())
            ->method('driverConfirmedService')
            ->with($service, $driver);

        $pushMessage->expects($this->once())
            ->method('execute')
            ->with($service);

        $code = new Code($serviceRepository, $driverRepository, $pushMessage);

        $response = $code->confirm($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(0, json_decode($response->getContent())->error);
        $this->assertEquals(202, $response->getStatusCode());
    }
}
