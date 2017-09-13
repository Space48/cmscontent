<?php
/**
 * Page
 *
 * @copyright Copyright © 2017 Space48. All rights reserved.
 * @author    raul@space48.com
 */

declare(strict_types=1);

namespace Space48\CmsContent\Model;

use Magento\Cms\Api\Data\PageInterfaceFactory;
use Magento\Cms\Model\PageFactory;
use Magento\Framework\File\Csv;
use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\Setup\SampleData\Context;
use Magento\Store\Model\Store;

class Page implements CmsInterface
{

    const PAGES_CSV_FILE = 'pages.csv';
    const DELIMITER = ',';
    const ENCLOSURE = '"';
    const PAGES_PATH = '/fixtures/pages/';

    /**
     * @var Context
     */
    private $fixtureManager;

    /**
     * @var PageInterfaceFactory
     */
    private $pageInterfaceFactory;

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
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * Page constructor.
     *
     * @param Context              $sampleDataContext
     * @param PageInterfaceFactory $pageInterfaceFactory
     * @param Csv                  $csvProcessor
     * @param Reader               $moduleDirReader
     * @param PageFactory          $pageFactory
     */
    public function __construct(
        Context $sampleDataContext,
        Csv $csvProcessor,
        Reader $moduleDirReader,
        PageInterfaceFactory $pageInterfaceFactory,
        PageFactory $pageFactory
    ) {
        $this->fixtureManager = $sampleDataContext->getFixtureManager();
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->csvProcessor = $csvProcessor;
        $this->moduleDirReader = $moduleDirReader;
        $this->pageFactory = $pageFactory;
        $this->pageInterfaceFactory = $pageInterfaceFactory;
    }

    /**
     * Import CMS Pages
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
                $this->pageInterfaceFactory->create()
                    ->load($row['identifier'], 'identifier')
                    ->addData($row)
                    ->setStores([Store::DEFAULT_STORE_ID])
                    ->save();
            }
        }
    }

    /**
     * Export CMS Pages
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function export()
    {
        $this->csvProcessor->setDelimiter(self::DELIMITER);
        $this->csvProcessor->setEnclosure(self::ENCLOSURE);

        $pages = $this->pageFactory->create();

        $cmsPages[] = [
            'title',
            'page_layout',
            'meta_keywords',
            'meta_description',
            'identifier',
            'content_heading',
            'content',
            'is_active',
            'sort_order',
            'layout_update_xml',
            'custom_theme',
            'custom_root_template',
            'custom_layout_update_xml',
            'custom_theme_from',
            'custom_theme_to'
        ];

        foreach ($pages->getCollection() as $page) {
            $cmsPages[] = [
                'title'                    => $page->getData('title'),
                'page_layout'              => $page->getData('page_layout'),
                'meta_keywords'            => $page->getData('meta_keywords'),
                'meta_description'         => $page->getData('meta_description'),
                'identifier'               => $page->getData('identifier'),
                'content_heading'          => $page->getData('content_heading'),
                'content'                  => $page->getData('content'),
                'is_active'                => $page->getData('is_active'),
                'sort_order'               => $page->getData('sort_order'),
                'layout_update_xml'        => $page->getData('layout_update_xml'),
                'custom_theme'             => $page->getData('custom_theme'),
                'custom_root_template'     => $page->getData('custom_root_template'),
                'custom_layout_update_xml' => $page->getData('custom_layout_update_xml'),
                'custom_theme_from'        => $page->getData('custom_theme_from'),
                'custom_theme_to'          => $page->getData('custom_theme_to')
            ];
        }
        $this->csvProcessor->saveData($this->getFilePath(), $cmsPages);

    }

    /**
     * Get File Path
     *
     * @return string
     */
    private function getFilePath()
    {
        return $this->moduleDirReader->getModuleDir('', 'Space48_CmsContent') .
            self::PAGES_PATH .
            self::PAGES_CSV_FILE;
    }

    /**
     * Delete CMS Pages
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete()
    {
        $pages = $this->pageFactory->create();
        foreach ($pages->getCollection() as $page) {
            $page->delete();
        }
    }
}
