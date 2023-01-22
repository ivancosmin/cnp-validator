<?php

namespace App\Tests;

use App\Entity\Counties;
use App\Service\CnpValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;

class CnpValidatorServiceTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private CnpValidatorService $cnpValidatorService;
    private ObjectRepository $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ObjectRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->cnpValidatorService = new CnpValidatorService($this->entityManager);
    }

    public function testValidCnp()
    {
        $validCNP = '1960304385562';

        $county = (new Counties())
            ->setCode('22')
            ->setName('test name')
            ->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->expects(self::once())
            ->method('getRepository')
            ->willReturn($this->repository);

        $this->repository->expects(self::once())
            ->method('findOneBy')
            ->with(['code' => '38'])
            ->willReturn($county);

        $validator = $this->cnpValidatorService->isCnpValid($validCNP);
        $this->assertTrue($validator);
    }

    public function testInvalidLength()
    {
        $validCNP = '123';

        $this->entityManager->expects(self::never())
            ->method('getRepository');

        $this->repository->expects(self::never())
            ->method('findOneBy');

        $validator = $this->cnpValidatorService->isCnpValid($validCNP);
        $this->assertFalse($validator);
    }

    public function testInvalidNumericInput()
    {
        $validCNP = '123asd';

        $this->entityManager->expects(self::never())
            ->method('getRepository');

        $this->repository->expects(self::never())
            ->method('findOneBy');

        $validator = $this->cnpValidatorService->isCnpValid($validCNP);
        $this->assertFalse($validator);
    }

    public function testInvalidDate()
    {
        $validCNP = '1961504385562';

        $this->entityManager->expects(self::never())
            ->method('getRepository');

        $this->repository->expects(self::never())
            ->method('findOneBy');

        $validator = $this->cnpValidatorService->isCnpValid($validCNP);
        $this->assertFalse($validator);
    }

    public function testInvalidCountyCode()
    {
        $validCNP = '1960304995562';

        $this->entityManager->expects(self::once())
            ->method('getRepository')
            ->willReturn($this->repository);

        $this->repository->expects(self::once())
            ->method('findOneBy')
            ->with(['code' => '99'])
            ->willReturn(null);

        $validator = $this->cnpValidatorService->isCnpValid($validCNP);
        $this->assertFalse($validator);
    }

    public function testInvalidUniqueCode()
    {
        $validCNP = '1960304380002';

        $county = (new Counties())
            ->setCode('22')
            ->setName('test name')
            ->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->expects(self::once())
            ->method('getRepository')
            ->willReturn($this->repository);

        $this->repository->expects(self::once())
            ->method('findOneBy')
            ->with(['code' => '38'])
            ->willReturn($county);

        $validator = $this->cnpValidatorService->isCnpValid($validCNP);
        $this->assertFalse($validator);
    }
}