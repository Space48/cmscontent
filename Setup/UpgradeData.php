<?php
/**
 * UpgradeData
 *
 * @copyright Copyright © 2017 Space48. All rights reserved.
 * @author    raul@space48.com
 */

declare(strict_types=1);

namespace Space48\CmsContent\Setup;

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Cms\Api\Data\BlockInterfaceFactory;
use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\Data\PageInterfaceFactory;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{

    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;
    /**
     * @var BlockInterfaceFactory
     */
    private $blockInterfaceFactory;
    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;
    /**
     * @var PageInterfaceFactory
     */
    private $pageInterfaceFactory;

    /**
     * UpgradeData constructor.
     *
     * @param BlockRepositoryInterface $blockRepository
     * @param BlockInterfaceFactory    $blockInterfaceFactory
     * @param PageRepositoryInterface  $pageRepository
     * @param PageInterfaceFactory     $pageInterfaceFactory
     */
    public function __construct(

        BlockRepositoryInterface $blockRepository,
        BlockInterfaceFactory $blockInterfaceFactory,
        PageRepositoryInterface $pageRepository,
        PageInterfaceFactory $pageInterfaceFactory
    ) {
        $this->blockRepository = $blockRepository;
        $this->blockInterfaceFactory = $blockInterfaceFactory;
        $this->pageRepository = $pageRepository;
        $this->pageInterfaceFactory = $pageInterfaceFactory;
    }

    /**
     * Upgrades data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     *
     * @throws LocalizedException
     * @return void
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.1.0', '<')) {
            if ($setup->tableExists('cms_block')) {
                $this->createCmsBlock();
            }
            if ($setup->tableExists('cms_page')) {
                $this->createCmsPage();
            }
        }
    }

    /**
     * @throws LocalizedException
     * @return void
     */
    protected function createCmsBlock()
    {
        /** @var BlockInterface $cmsBlock */
        $cmsBlock = $this->blockInterfaceFactory->create();
        $cmsBlock
            ->setIdentifier('block-identifier')
            ->setTitle('Block Title')
            ->setContent('Block Content')
            ->setData('stores', [0]);

        $this->blockRepository->save($cmsBlock);
    }

    /**
     * @return void
     * @throws LocalizedException
     */
    protected function createCmsPage()
    {
        /** @var PageInterface $cmsPage */
        $cmsPage = $this->pageInterfaceFactory->create();
        $cmsPage
            ->setIdentifier('page-identifier')
            ->setTitle('Page Title')
            ->setContentHeading('Content Heading')
            ->setContent('Page content')
            ->setPageLayout('1column')
            ->setData('stores', [0]);

        $this->pageRepository->save($cmsPage);
    }
}
