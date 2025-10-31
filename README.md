# Wechat Mini Program Log Bundle

[![Latest Version](https://img.shields.io/packagist/v/tourze/wechat-mini-program-log-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-log-bundle)
[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen)](https://github.com/tourze/php-monorepo)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen)](https://github.com/tourze/php-monorepo)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/wechat-mini-program-log-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-log-bundle)

[English](README.md) | [中文](README.zh-CN.md)

A Symfony bundle for managing WeChat Mini Program logs, including error tracking, 
feedback collection, and penalty management.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Configuration](#configuration)
- [Dependencies](#dependencies)
- [Commands](#commands)
- [Entities](#entities)
- [Advanced Usage](#advanced-usage)
- [Security](#security)
- [Automated Scheduling](#automated-scheduling)
- [License](#license)

## Features

- **Error Tracking**: Monitor and collect JavaScript errors from WeChat Mini Programs
- **Feedback Management**: Collect and manage user feedback from mini programs
- **Penalty Monitoring**: Track transaction experience score violations
- **Automated Synchronization**: Scheduled commands for data synchronization
- **Entity Management**: Doctrine entities for structured data storage

## Installation

```bash
composer require tourze/wechat-mini-program-log-bundle
```

## Quick Start

### 1. Register the Bundle

Add the bundle to your `config/bundles.php`:

```php
return [
    // ...
    WechatMiniProgramLogBundle\WechatMiniProgramLogBundle::class => ['all' => true],
];
```

### 2. Configure Services

The bundle automatically registers its services. Make sure you have the required 
dependencies installed.

### 3. Database Setup

Create and run migrations for the entity tables:

```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

## Configuration

The bundle uses the WeChat Mini Program API client from 
`tourze/wechat-mini-program-bundle`. Ensure your WeChat accounts are properly 
configured with valid API credentials.

Example configuration in `config/packages/wechat_mini_program.yaml`:

```yaml
wechat_mini_program:
    accounts:
        default:
            app_id: 'your_app_id'
            app_secret: 'your_app_secret'
```

## Dependencies

This bundle requires the following packages:

- PHP 8.1 or higher
- Symfony 6.4 or higher
- `tourze/wechat-mini-program-bundle ^0.1` - WeChat Mini Program API client
- `tourze/doctrine-timestamp-bundle ^0.0` - Timestamp management
- `tourze/doctrine-snowflake-bundle ^0.1` - Snowflake ID generation
- `tourze/enum-extra ^0.1` - Enhanced enum functionality
- `tourze/symfony-cron-job-bundle ^0.1` - Automated task scheduling

## Commands

The bundle provides four console commands for data synchronization:

### Error Management

```bash
# Synchronize error list from WeChat API
php bin/console wechat-mini-program:sync-get-error-list

# Synchronize error details from WeChat API
php bin/console wechat-mini-program:sync-get-error-detail
```

### Feedback Collection

```bash
# Collect user feedback from mini programs
php bin/console wechat-mini-program:get-feedback
```

### Penalty Monitoring

```bash
# Monitor transaction experience score violations
php bin/console wechat-mini-program:get-penalty
```

## Entities

The bundle includes several entities for data management:

- `ErrorDetail`: Detailed JavaScript error information
- `ErrorListData`: Aggregated error statistics
- `Feedback`: User feedback data
- `PenaltyList`: Transaction violation records

## Advanced Usage

### Custom Repository Usage

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

### Event Listeners

Register custom event listeners to handle error data:

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
        // Custom error handling logic
    }
}
```

## Security

### Data Validation

All entities include comprehensive validation constraints to ensure data integrity:

- String length limits prevent database overflow
- Type validation ensures proper data types
- Enum validation restricts values to valid choices

### Access Control

The bundle does not include built-in access control. Implement appropriate 
security measures in your application:

- Use Symfony Security component for authentication
- Implement proper authorization checks
- Validate API credentials securely

### Sensitive Data

Be aware that this bundle processes:

- User feedback data
- Error information that may contain sensitive details
- OpenID identifiers

Ensure compliance with relevant data protection regulations.

## Automated Scheduling

All commands are configured with cron expressions for automated execution:

- Error list sync: Daily at 3:06 AM and 11:35 AM
- Error detail sync: Daily at 4:02 AM and 8:22 AM  
- Feedback collection: Hourly at 15 minutes past
- Penalty monitoring: Daily at 1:40 AM and 1:45 PM

## Contributing

We welcome contributions! Please see our [contributing guidelines](../../CONTRIBUTING.md) for details on:

- How to submit issues
- How to submit pull requests  
- Code style requirements
- Testing requirements

## License

This bundle is under the MIT license. See the complete license in the bundle:

[LICENSE](LICENSE)