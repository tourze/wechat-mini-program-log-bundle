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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use WechatMiniProgramLogBundle\Entity\ErrorDetail;

#[AdminCrud(routePath: '/wechat-mini-program-log/error-detail', routeName: 'wechat_mini_program_log_error_detail')]
final class ErrorDetailCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ErrorDetail::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('错误详情')
            ->setEntityLabelInPlural('错误详情')
            ->setSearchFields(['openId', 'errorMsgCode', 'errorMsg', 'nickname', 'appVersion', 'deviceModel'])
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
            ->add('appVersion')
            ->add('deviceModel')
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

        yield TextField::new('errorStackCode', '错误堆栈代码')
            ->hideOnIndex()
            ->setColumns(6)
        ;

        yield TextareaField::new('errorStack', '错误堆栈')
            ->hideOnIndex()
            ->setColumns(12)
        ;

        yield TextField::new('count', '计数')
            ->setColumns(3)
        ;

        yield TextField::new('appVersion', '应用版本')
            ->setColumns(3)
        ;

        yield TextField::new('deviceModel', '设备型号')
            ->setColumns(6)
        ;

        yield TextField::new('nickname', '昵称')
            ->setColumns(6)
        ;

        yield TextField::new('sdkVersion', 'SDK版本')
            ->hideOnIndex()
            ->setColumns(4)
        ;

        yield TextField::new('clientVersion', '客户端版本')
            ->hideOnIndex()
            ->setColumns(4)
        ;

        yield TextField::new('osName', '操作系统')
            ->hideOnIndex()
            ->setColumns(4)
        ;

        yield DateTimeField::new('timeStamp', '时间戳')
            ->hideOnIndex()
            ->setColumns(6)
        ;

        yield TextField::new('ds', 'DS')
            ->hideOnIndex()
            ->setColumns(3)
        ;

        yield TextField::new('pluginVersion', '插件版本')
            ->hideOnIndex()
            ->setColumns(3)
        ;

        yield TextField::new('appId', '应用ID')
            ->hideOnIndex()
            ->setColumns(6)
        ;

        yield TextField::new('source', '来源')
            ->hideOnIndex()
            ->setColumns(4)
        ;

        yield TextField::new('route', '路由')
            ->hideOnIndex()
            ->setColumns(8)
        ;

        yield TextField::new('uin', 'UIN')
            ->hideOnIndex()
            ->setColumns(6)
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
