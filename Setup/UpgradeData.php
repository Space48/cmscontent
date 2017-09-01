<?php
/**
 * UpgradeData
 *
 * @copyright Copyright © 2017 Space48. All rights reserved.
 * @author    raul@space48.com
 */

declare(strict_types=1);

namespace Space48\CmsContent\Setup;

use Magento\Cms\Model\BlockFactory;
use Magento\Cms\Model\PageFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{

    /**
     * @var PageFactory
     */
    private $pageFactory;
    /**
     * @var BlockFactory
     */
    private $blockFactory;

    /**
     * UpgradeData constructor.
     *
     * @param PageFactory  $pageFactory
     * @param BlockFactory $blockFactory
     */
    public function __construct(
        PageFactory $pageFactory,
        BlockFactory $blockFactory
    ) {
        $this->pageFactory = $pageFactory;
        $this->blockFactory = $blockFactory;
    }

    /**
     * Upgrades data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     *
     * @return void
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.0', '<')) {

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
    }
}
