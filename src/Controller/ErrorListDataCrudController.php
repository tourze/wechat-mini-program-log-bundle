<?php

declare(strict_types=1);

namespace WechatMiniProgramLogBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use WechatMiniProgramLogBundle\Entity\ErrorListData;

#[AdminCrud(routePath: '/wechat-mini-program-log/error-list-data', routeName: 'wechat_mini_program_log_error_list_data')]
final class ErrorListDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ErrorListData::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('错误列表数据')
            ->setEntityLabelInPlural('错误列表数据')
            ->setSearchFields(['openId', 'errorMsgCode', 'errorMsg'])
            ->setDefaultSort(['createTime' => 'DESC'])
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
            ->add('account')
            ->add('date')
            ->add('openId')
            ->add('errorMsgCode')
            ->add('uv')
            ->add('pv')
            ->add('createTime')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')->onlyOnDetail();

        yield AssociationField::new('account', '账户')
            ->setColumns(6)
        ;

        yield DateField::new('date', '日期')
            ->setColumns(6)
        ;

        yield TextField::new('openId', 'OpenID')
            ->setColumns(6)
        ;

        yield TextField::new('errorMsgCode', '错误消息代码')
            ->setColumns(6)
        ;

        yield TextareaField::new('errorMsg', '错误消息')
            ->setMaxLength(100)
            ->onlyOnIndex()
            ->setColumns(12)
        ;

        yield TextareaField::new('errorMsg', '错误消息')
            ->hideOnIndex()
            ->setColumns(12)
        ;

        yield IntegerField::new('uv', 'UV访问量')
            ->setColumns(3)
        ;

        yield IntegerField::new('pv', 'PV访问量')
            ->setColumns(3)
        ;

        yield TextField::new('uvPercent', 'UV百分比')
            ->setColumns(3)
        ;

        yield TextField::new('pvPercent', 'PV百分比')
            ->setColumns(3)
        ;

        yield TextField::new('errorStackCode', '错误堆栈代码')
            ->hideOnIndex()
            ->setColumns(6)
        ;

        yield TextareaField::new('errorStack', '错误堆栈')
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
