# CmsContent
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Space48/cmscontent/badges/quality-score.png?b=master&s=7fd57a34a606d6f4f48fe992c1a223fd91180bb3)](https://scrutinizer-ci.com/g/Space48/cmscontent/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/Space48/cmscontent/badges/build.png?b=master&s=ea037c357a0630745b155e04ac62102d0ccabd20)](https://scrutinizer-ci.com/g/Space48/cmscontent/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/Space48/cmscontent/badges/coverage.png?b=master&s=c0181361e9ef048554f358b212b9f27937e1c6bd)](https://scrutinizer-ci.com/g/Space48/cmscontent/?branch=master)

This is a Magento2 module to import and export CMS content.

## Installation

**Manually** 

To install this module copy the code from this repo to `app/code/Space48/cmscontent` folder of your Magento 2 instance, but **DO NOT** run `php bin/magento setup:upgrade`

**Via composer**:

From the terminal execute the following:

`composer config repositories.space48-quick-view vcs git@github.com:Space48/cmscontent.git`

then

`composer require "space48/cmscontent:{release-version}"`

## How to use it
This is a CLI Tool so you can use as part of `bin/magento` or `magerun2` options.
This tool add the following Space48 commands:
- space48:cms-content:import
- space48:cms-content:export
- space48:cms-content:delete

## Commands
### space48:cms-content:import {argument}
This command will **import** CMS Pages and Blocks from csv files to the database.
The csv files need to be placed into `vendor/space48/cmscontent/fixtures`.

If the CMS Page or Block does not exists it will created and if it doesn't it will update it.

```bash
Usage:
 space48:cms-content:import cmsType

Arguments:
 cmsType               pages, blocks, all
    
```
### space48:cms-content:export {argument}
This command will **export** CMS Pages and Blocks from the database to a csv files.
The generated files can be found under `vendor/space48/cmscontent/fixtures`.

```bash
Usage:
 space48:cms-content:export cmsType

Arguments:
 cmsType               pages, blocks, all
    
```
### space48:cms-content:delete {argument}
This command can be use to **delete** CMS Pages and Blocks.

```bash
Usage:
 space48:cms-content:delete cmsType

Arguments:
 cmsType               pages, blocks, all
    
```
