# 微信小程序日志包

[![Latest Version](https://img.shields.io/packagist/v/tourze/wechat-mini-program-log-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-log-bundle)
[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen)](https://github.com/tourze/php-monorepo)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen)](https://github.com/tourze/php-monorepo)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/wechat-mini-program-log-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-log-bundle)

[English](README.md) | [中文](README.zh-CN.md)

一个用于管理微信小程序日志的 Symfony 扩展包，包括错误追踪、反馈收集和违规管理功能。

## 目录

- [功能特性](#功能特性)
- [安装](#安装)
- [快速开始](#快速开始)
- [配置](#配置)
- [依赖要求](#依赖要求)
- [命令](#命令)
- [实体](#实体)
- [高级用法](#高级用法)
- [安全性](#安全性)
- [自动调度](#自动调度)
- [许可证](#许可证)

## 功能特性

- **错误追踪**：监控和收集微信小程序的 JavaScript 错误
- **反馈管理**：收集和管理来自小程序的用户反馈
- **违规监控**：跟踪交易体验分违规记录
- **自动同步**：定时命令进行数据同步
- **实体管理**：使用 Doctrine 实体进行结构化数据存储

## 安装

```bash
composer require tourze/wechat-mini-program-log-bundle
```

## 快速开始

### 1. 注册扩展包

将扩展包添加到您的 `config/bundles.php`：

```php
return [
    // ...
    WechatMiniProgramLogBundle\WechatMiniProgramLogBundle::class => ['all' => true],
];
```

### 2. 配置服务

扩展包会自动注册其服务。确保您已安装所需的依赖项。

### 3. 数据库设置

为实体表创建并运行迁移：

```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

## 配置

扩展包使用来自 `tourze/wechat-mini-program-bundle` 的微信小程序 API 客户端。
确保您的微信账号已正确配置有效的 API 凭据。

在 `config/packages/wechat_mini_program.yaml` 中的配置示例：

```yaml
wechat_mini_program:
    accounts:
        default:
            app_id: 'your_app_id'
            app_secret: 'your_app_secret'
```

## 依赖要求

此扩展包需要以下依赖：

- PHP 8.1 或更高版本
- Symfony 6.4 或更高版本
- `tourze/wechat-mini-program-bundle ^0.1` - 微信小程序 API 客户端
- `tourze/doctrine-timestamp-bundle ^0.0` - 时间戳管理
- `tourze/doctrine-snowflake-bundle ^0.1` - 雪花 ID 生成
- `tourze/enum-extra ^0.1` - 增强的枚举功能
- `tourze/symfony-cron-job-bundle ^0.1` - 自动化任务调度

## 命令

扩展包提供四个控制台命令用于数据同步：

### 错误管理

```bash
# 从微信 API 同步错误列表
php bin/console wechat-mini-program:sync-get-error-list

# 从微信 API 同步错误详情
php bin/console wechat-mini-program:sync-get-error-detail
```

### 反馈收集

```bash
# 收集来自小程序的用户反馈
php bin/console wechat-mini-program:get-feedback
```

### 违规监控

```bash
# 监控交易体验分违规记录
php bin/console wechat-mini-program:get-penalty
```

## 实体

扩展包包含多个用于数据管理的实体：

- `ErrorDetail`：详细的 JavaScript 错误信息
- `ErrorListData`：聚合的错误统计数据
- `Feedback`：用户反馈数据
- `PenaltyList`：交易违规记录

## 高级用法

### 自定义仓库使用

```php
use WechatMiniProgramLogBundle\Repository\ErrorDetailRepository;

class MyService
{
    public function __construct(
        private ErrorDetailRepository $errorDetailRepository
    ) {}
    
    public function findRecentErrors(): array
    {
        return $this->errorDetailRepository->findBy(
            ['date' => new \DateTime('-7 days')],
            ['createTime' => 'DESC'],
            10
        );
    }
}
```

### 事件监听器

注册自定义事件监听器来处理错误数据：

```php
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ErrorEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'wechat.error.created' => 'onErrorCreated',
        ];
    }
    
    public function onErrorCreated(ErrorEvent $event): void
    {
        // 自定义错误处理逻辑
    }
}
```

## 安全性

### 数据验证

所有实体都包含全面的验证约束以确保数据完整性：

- 字符串长度限制防止数据库溢出
- 类型验证确保正确的数据类型
- 枚举验证将值限制为有效选择

### 访问控制

扩展包不包含内置的访问控制。在您的应用程序中实施适当的安全措施：

- 使用 Symfony Security 组件进行身份验证
- 实施适当的授权检查
- 安全地验证 API 凭据

### 敏感数据

请注意此扩展包处理以下数据：

- 用户反馈数据
- 可能包含敏感详细信息的错误信息
- OpenID 标识符

确保遵守相关的数据保护法规。

## 自动调度

所有命令都配置了 cron 表达式用于自动执行：

- 错误列表同步：每天凌晨 3:06 和上午 11:35
- 错误详情同步：每天凌晨 4:02 和上午 8:22
- 反馈收集：每小时第 15 分钟
- 违规监控：每天凌晨 1:40 和下午 1:45

## 贡献

我们欢迎贡献！请查看我们的[贡献指南](../../CONTRIBUTING.md)了解详情：

- 如何提交问题
- 如何提交拉取请求
- 代码风格要求
- 测试要求

## 许可证

此扩展包使用 MIT 许可证。查看完整许可证：

[LICENSE](LICENSE)