<?php
/**
 * Block
 *
 * @copyright Copyright © 2017 Space48. All rights reserved.
 * @author    raul@space48.com
 */

declare(strict_types=1);

namespace Space48\CmsContent\Model;

use Magento\Cms\Api\Data\BlockInterfaceFactory;
use Magento\Cms\Model\BlockFactory;
use Magento\Framework\File\Csv;
use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\Setup\SampleData\Context;
use Magento\Store\Model\Store;

class Block implements CmsInterface
{

    const CSV_FILE = 'blocks.csv';
    const DELIMITER = ',';
    const ENCLOSURE = '"';
    const FILE_PATH = '/fixtures/blocks/';

    /**
     * @var Context
     */
    private $fixtureManager;

    /**
     * @var BlockInterfaceFactory
     */
    private $blockInterfaceFactory;

    /**
     * @var Csv
     */
    private $csvReader;
    /**
     * @var Csv
     */
    private $csvProcessor;

    /**
     * @var Reader
     */
    private $moduleDirReader;
    /**
     * @var BlockFactory
     */
    private $blockFactory;

    /**
     * Block constructor.
     *
     * @param Context               $sampleDataContext
     * @param BlockInterfaceFactory $blockInterfaceFactory
     * @param BlockFactory          $blockFactory
     * @param Csv                   $csvProcessor
     * @param Reader                $moduleDirReader
     */
    public function __construct(
        Context $sampleDataContext,
        BlockInterfaceFactory $blockInterfaceFactory,
        BlockFactory $blockFactory,
        Csv $csvProcessor,
        Reader $moduleDirReader

    ) {
        $this->fixtureManager = $sampleDataContext->getFixtureManager();
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->csvProcessor = $csvProcessor;
        $this->blockInterfaceFactory = $blockInterfaceFactory;
        $this->moduleDirReader = $moduleDirReader;
        $this->blockFactory = $blockFactory;
    }

    /**
     * Import CMS Blocks
     *
     * @param array $fixtures
     *
     * @throws \Magento\Framework\Exception\LocalizedException | \Exception
     */
    public function Import(array $fixtures)
    {
        foreach ($fixtures as $fileName) {
            $fileName = $this->fixtureManager->getFixture($fileName);
            if (!file_exists($fileName)) {
                continue;
            }
            $rows = $this->csvReader->getData($fileName);
            $header = array_shift($rows);
            foreach ($rows as $row) {
                $data = [];
                foreach ($row as $key => $value) {
                    $data[$header[$key]] = $value;
                }
                $row = $data;
                $this->blockInterfaceFactory->create()
                    ->load($row['identifier'], 'identifier')
                    ->addData($row)
                    ->setStores([Store::DEFAULT_STORE_ID])
                    ->save();
            }
        }
    }

    /**
     * Export CMS Blocks
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function export()
    {
        $this->csvProcessor->setDelimiter(self::DELIMITER);
        $this->csvProcessor->setEnclosure(self::ENCLOSURE);

        $blocks = $this->blockFactory->create();

        $cmsBlocks[] = ['title', 'identifier', 'content', 'is_active'];
        foreach ($blocks->getCollection() as $block) {
            $cmsBlocks[] = [
                'title'      => $block->getData('title'),
                'identifier' => $block->getData('identifier'),
                'content'    => $block->getData('content'),
                'is_active'  => $block->getData('active')
            ];
        }

        $this->csvProcessor->saveData($this->getFilePath(), $cmsBlocks);
    }

    /**
     * @return string
     */
    private function getFilePath()
    {
        return $this->moduleDirReader->getModuleDir('', 'Space48_CmsContent') .
            self::FILE_PATH .
            self::CSV_FILE;
    }

    /**
     * Delete CMS Content
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete()
    {
        $blocks = $this->blockFactory->create();
        foreach ($blocks->getCollection() as $block) {
            $block->delete();
        }
    }
}
