<?php

declare(strict_types=1);

namespace WechatMiniProgramLogBundle\Service;

use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use WechatMiniProgramLogBundle\Entity\ErrorDetail;
use WechatMiniProgramLogBundle\Entity\ErrorListData;
use WechatMiniProgramLogBundle\Entity\Feedback;
use WechatMiniProgramLogBundle\Entity\PenaltyList;

#[Autoconfigure(public: true)]
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(private LinkGeneratorInterface $linkGenerator)
    {
    }

    public function __invoke(ItemInterface $item): void
    {
        if (null === $item->getChild('小程序管理')) {
            $item->addChild('小程序管理');
        }

        $parent = $item->getChild('小程序管理');
        assert($parent instanceof ItemInterface);

        if (null === $parent->getChild('日志管理')) {
            $parent->addChild('日志管理');
        }

        $logMenu = $parent->getChild('日志管理');
        assert($logMenu instanceof ItemInterface);

        $logMenu
            ->addChild('错误详情')
            ->setUri($this->linkGenerator->getCurdListPage(ErrorDetail::class))
            ->setAttribute('icon', 'fas fa-bug')
        ;

        $logMenu
            ->addChild('错误列表数据')
            ->setUri($this->linkGenerator->getCurdListPage(ErrorListData::class))
            ->setAttribute('icon', 'fas fa-list-alt')
        ;

        $logMenu
            ->addChild('反馈记录')
            ->setUri($this->linkGenerator->getCurdListPage(Feedback::class))
            ->setAttribute('icon', 'fas fa-comment-dots')
        ;

        $logMenu
            ->addChild('违规记录')
            ->setUri($this->linkGenerator->getCurdListPage(PenaltyList::class))
            ->setAttribute('icon', 'fas fa-exclamation-triangle')
        ;
    }
}
