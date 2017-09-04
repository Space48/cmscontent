# CmsContent
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Space48/cmscontent/badges/quality-score.png?b=master&s=7fd57a34a606d6f4f48fe992c1a223fd91180bb3)](https://scrutinizer-ci.com/g/Space48/cmscontent/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/Space48/cmscontent/badges/build.png?b=master&s=ea037c357a0630745b155e04ac62102d0ccabd20)](https://scrutinizer-ci.com/g/Space48/cmscontent/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/Space48/cmscontent/badges/coverage.png?b=master&s=c0181361e9ef048554f358b212b9f27937e1c6bd)](https://scrutinizer-ci.com/g/Space48/cmscontent/?branch=master)

This is a Magento2 module to programmatically create and update CMS content.

## Installation

**Manually** 

To install this module copy the code from this repo to `app/code/Space48/cmscontent` folder of your Magento 2 instance, but **DO NOT** run `php bin/magento setup:upgrade`

**Via composer**:

From the terminal execute the following:

`composer config repositories.space48-quick-view vcs git@github.com:Space48/cmscontent.git`

then

`composer require "space48/cmscontent:{release-version}"`

## How to use it
Take a look at `/Setup/UpgradeData.php`, there is a sample code that creates a dummy CMS Block and a dummy CMS Page.

```php
        if (version_compare($context->getVersion(), '1.1.0', '<')) {

            $newPage = [
                'title'           => 'Test page title',
                'identifier'      => 'test-page',
                'stores'          => [0],
                'is_active'       => 1,
                'content_heading' => 'Test page heading',
                'content'         => 'Test page content',
                'page_layout'     => '1column'
            ];
            $this->pageFactory->create()->setData($newPage)->save();

            $newBlock = [
                'title'      => 'Test block title',
                'identifier' => 'test-block',
                'stores'     => [0],
                'is_active'  => 1,
            ];
            $this->blockFactory->create()->setData($newBlock)->save();

        }
```
Modify this sample to meet your need and when you finished, upgrade the module version in `/etc/module.xml` as shown bellow:

```xml
<module name="Space48_CmsContent" setup_version="1.1.0">
```

Finally run `php bin/magento setup:upgrade` to install the module and add to the database the CMS Blocks/Pages you have previously defined.

## Need to create multiple Pages and Blocks?

```php
        if (version_compare($context->getVersion(), '1.1.0', '=')) {
            // CMS Pages
            $newPage = [
                'title'           => 'Test page title1',
                'identifier'      => 'test-page1',
                'stores'          => [0],
                'is_active'       => 1,
                'content_heading' => 'Test page heading1',
                'content'         => 'Test page content1',
                'page_layout'     => '1column'
            ];
            $this->pageFactory->create()->setData($newPage)->save();

            $newPage = [
                'title'           => 'Test page title2',
                'identifier'      => 'test-page2',
                'stores'          => [0],
                'is_active'       => 1,
                'content_heading' => 'Test page heading2',
                'content'         => 'Test page content2',
                'page_layout'     => '1column'
            ];
            $this->pageFactory->create()->setData($newPage)->save();
            
            // CMS Blocks
            $newBlock = [
                'title'      => 'Test block title1',
                'identifier' => 'test-block1',
                'stores'     => [0],
                'is_active'  => 1,
                'content'    => 'Sample content'
            ];
            $this->blockFactory->create()->setData($newBlock)->save();
            $newBlock = [
                'title'      => 'Test block title2',
                'identifier' => 'test-block2',
                'stores'     => [0],
                'is_active'  => 1,
                'content'    => 'Sample content'
            ];
            $this->blockFactory->create()->setData($newBlock)->save();
        }
```
## Need to update multiple times?
```php
if (version_compare($context->getVersion(), '1.2.0', '=')) {

                ...

        }
if (version_compare($context->getVersion(), '1.3.0', '=')) {

                ...

        }
```
Make sure you update the version in `/etc/module.xml` accordingly.

### Example:

From:

`<module name="Space48_CmsContent" setup_version="1.2.0">`

to:

`<module name="Space48_CmsContent" setup_version="1.3.0">`
