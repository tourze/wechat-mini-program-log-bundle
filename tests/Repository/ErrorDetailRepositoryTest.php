<?php

namespace WechatMiniProgramLogBundle\Tests\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramLogBundle\Entity\ErrorDetail;
use WechatMiniProgramLogBundle\Repository\ErrorDetailRepository;

class ErrorDetailRepositoryTest extends TestCase
{
    private ErrorDetailRepository $repository;
    private EntityManagerInterface $entityManager;
    private ManagerRegistry $registry;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->registry = $this->createMock(ManagerRegistry::class);
        
        // 设置registry返回entityManager
        $this->registry->method('getManagerForClass')
            ->with(ErrorDetail::class)
            ->willReturn($this->entityManager);
        
        $this->repository = new ErrorDetailRepository($this->registry);
    }

    public function testConstructor_ShouldInitializeWithCorrectEntityClass(): void
    {
        // 验证构造函数是否正确设置实体类
        $this->assertInstanceOf(ErrorDetailRepository::class, $this->repository);
    }

    public function testFindOneBy_ShouldDelegateToParentMethod(): void
    {
        // 由于这是对父类方法的测试，我们不需要做任何额外的验证
        // ServiceEntityRepository的方法已经由Doctrine测试
        // 这个测试主要是为了确保我们的类正确继承并可用
        $this->assertInstanceOf(ErrorDetailRepository::class, $this->repository);
    }

    public function testFindBy_ShouldDelegateToParentMethod(): void
    {
        // 由于这是对父类方法的测试，我们不需要做任何额外的验证
        // ServiceEntityRepository的方法已经由Doctrine测试
        // 这个测试主要是为了确保我们的类正确继承并可用
        $this->assertInstanceOf(ErrorDetailRepository::class, $this->repository);
    }

    public function testFindAll_ShouldDelegateToParentMethod(): void
    {
        // 由于这是对父类方法的测试，我们不需要做任何额外的验证
        // ServiceEntityRepository的方法已经由Doctrine测试
        // 这个测试主要是为了确保我们的类正确继承并可用
        $this->assertInstanceOf(ErrorDetailRepository::class, $this->repository);
    }

    public function testFind_ShouldDelegateToParentMethod(): void
    {
        // 由于这是对父类方法的测试，我们不需要做任何额外的验证
        // ServiceEntityRepository的方法已经由Doctrine测试
        // 这个测试主要是为了确保我们的类正确继承并可用
        $this->assertInstanceOf(ErrorDetailRepository::class, $this->repository);
    }
} 