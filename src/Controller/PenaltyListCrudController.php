<?php

declare(strict_types=1);

namespace WechatMiniProgramLogBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Tourze\EasyAdminEnumFieldBundle\Field\EnumField;
use WechatMiniProgramLogBundle\Entity\PenaltyList;
use WechatMiniProgramLogBundle\Enum\PenaltyStatus;

#[AdminCrud(routePath: '/wechat-mini-program-log/penalty-list', routeName: 'wechat_mini_program_log_penalty_list')]
final class PenaltyListCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PenaltyList::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('违规记录')
            ->setEntityLabelInPlural('违规记录')
            ->setSearchFields(['illegalOrderId', 'complaintOrderId', 'orderId', 'illegalWording'])
            ->setDefaultSort(['illegalTime' => 'DESC'])
            ->setPaginatorPageSize(20)
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW, Action::EDIT, Action::DELETE)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('illegalOrderId')
            ->add('complaintOrderId')
            ->add('illegalTime')
            ->add('penaltyStatus')
            ->add('minusScore')
            ->add('currentScore')
            ->add('createTime')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')->onlyOnDetail();

        yield TextField::new('illegalOrderId', '违规订单ID')
            ->setColumns(6)
        ;

        yield TextField::new('complaintOrderId', '投诉订单ID')
            ->setColumns(6)
        ;

        yield DateTimeField::new('illegalTime', '违规时间')
            ->setColumns(6)
        ;

        $field = EnumField::new('penaltyStatus', '处罚状态');
        $field->setEnumCases(PenaltyStatus::cases());
        yield $field->setColumns(6);

        yield IntegerField::new('minusScore', '扣分')
            ->setColumns(3)
        ;

        yield IntegerField::new('currentScore', '当前分数')
            ->setColumns(3)
        ;

        yield TextField::new('orderId', '订单ID')
            ->setColumns(6)
        ;

        yield TextareaField::new('illegalWording', '违规措辞')
            ->setMaxLength(100)
            ->onlyOnIndex()
            ->setColumns(12)
        ;

        yield TextareaField::new('illegalWording', '违规措辞')
            ->hideOnIndex()
            ->setColumns(12)
        ;

        yield TextareaField::new('rawData', '原始数据')
            ->hideOnIndex()
            ->setColumns(12)
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->hideOnForm()
            ->setColumns(6)
        ;

        yield DateTimeField::new('updateTime', '更新时间')
            ->hideOnForm()
            ->hideOnIndex()
            ->setColumns(6)
        ;
    }
}
