<?php

namespace WechatMiniProgramLogBundle\Tests\Repository;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramLogBundle\Repository\FeedbackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramLogBundle\Entity\Feedback;

class FeedbackRepositoryTest extends TestCase
{
    private FeedbackRepository $repository;
    private EntityManagerInterface $entityManager;
    private ManagerRegistry $registry;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->registry = $this->createMock(ManagerRegistry::class);
        
        $classMetadata = $this->createMock(ClassMetadata::class);
        $classMetadata->name = Feedback::class;
        
        $this->entityManager->expects($this->any())
            ->method('getClassMetadata')
            ->with(Feedback::class)
            ->willReturn($classMetadata);
        
        $this->registry->expects($this->any())
            ->method('getManagerForClass')
            ->with(Feedback::class)
            ->willReturn($this->entityManager);
        
        $this->repository = new FeedbackRepository($this->registry);
    }

    public function testRepositoryCreation(): void
    {
        $this->assertInstanceOf(FeedbackRepository::class, $this->repository);
    }
}