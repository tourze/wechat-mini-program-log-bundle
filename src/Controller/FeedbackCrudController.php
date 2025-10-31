<?php

declare(strict_types=1);

namespace WechatMiniProgramLogBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Tourze\EasyAdminEnumFieldBundle\Field\EnumField;
use WechatMiniProgramLogBundle\Entity\Feedback;
use WechatMiniProgramLogBundle\Enum\FeedbackType;

#[AdminCrud(routePath: '/wechat-mini-program-log/feedback', routeName: 'wechat_mini_program_log_feedback')]
final class FeedbackCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Feedback::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('反馈记录')
            ->setEntityLabelInPlural('反馈记录')
            ->setSearchFields(['wxRecordId', 'content', 'phone', 'openid', 'nickname'])
            ->setDefaultSort(['wxCreateTime' => 'DESC'])
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
            ->add('wxRecordId')
            ->add('wxCreateTime')
            ->add('feedbackType')
            ->add('phone')
            ->add('openid')
            ->add('nickname')
            ->add('createTime')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')->onlyOnDetail();

        yield AssociationField::new('account', '账户')
            ->setColumns(6)
        ;

        yield TextField::new('wxRecordId', '微信记录ID')
            ->setColumns(6)
        ;

        yield DateTimeField::new('wxCreateTime', '微信创建时间')
            ->setColumns(6)
        ;

        $field = EnumField::new('feedbackType', '反馈类型');
        $field->setEnumCases(FeedbackType::cases());
        yield $field->setColumns(6);

        yield TextField::new('nickname', '昵称')
            ->setColumns(6)
        ;

        yield TextField::new('phone', '手机号')
            ->setColumns(6)
        ;

        yield TextareaField::new('content', '反馈内容')
            ->setMaxLength(100)
            ->onlyOnIndex()
            ->setColumns(12)
        ;

        yield TextareaField::new('content', '反馈内容')
            ->hideOnIndex()
            ->setColumns(12)
        ;

        yield TextField::new('openid', 'OpenID')
            ->hideOnIndex()
            ->setColumns(6)
        ;

        yield UrlField::new('headUrl', '头像URL')
            ->hideOnIndex()
            ->setColumns(6)
        ;

        yield ArrayField::new('mediaIds', '图片')
            ->hideOnIndex()
            ->setColumns(12)
        ;

        yield TextareaField::new('systemInfo', '系统信息')
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
