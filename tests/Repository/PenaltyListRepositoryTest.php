<?php

namespace WechatMiniProgramLogBundle\Tests\Repository;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramLogBundle\Repository\PenaltyListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramLogBundle\Entity\PenaltyList;

class PenaltyListRepositoryTest extends TestCase
{
    private PenaltyListRepository $repository;
    private EntityManagerInterface $entityManager;
    private ManagerRegistry $registry;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->registry = $this->createMock(ManagerRegistry::class);
        
        $classMetadata = $this->createMock(ClassMetadata::class);
        $classMetadata->name = PenaltyList::class;
        
        $this->entityManager->expects($this->any())
            ->method('getClassMetadata')
            ->with(PenaltyList::class)
            ->willReturn($classMetadata);
        
        $this->registry->expects($this->any())
            ->method('getManagerForClass')
            ->with(PenaltyList::class)
            ->willReturn($this->entityManager);
        
        $this->repository = new PenaltyListRepository($this->registry);
    }

    public function testRepositoryCreation(): void
    {
        $this->assertInstanceOf(PenaltyListRepository::class, $this->repository);
    }
}