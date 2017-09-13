<?php

namespace Space48\CmsContent\Console\Command;

use Space48\CmsContent\Model\Block;
use Space48\CmsContent\Model\Page;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command
{

    /**
     * @var Page
     */
    private $cmsPages;
    /**
     * @var Block
     */
    private $cmsBlocks;

    /**
     * ImportCommand constructor.
     *
     * @param Page  $cmsPages
     * @param Block $cmsBlocks
     *
     * @throws \LogicException
     */
    public function __construct(
        Page $cmsPages,
        Block $cmsBlocks
    ) {
        $this->cmsPages = $cmsPages;
        $this->cmsBlocks = $cmsBlocks;
        parent::__construct();
    }

    /**
     * Configures the current command.
     *
     * @throws \InvalidArgumentException
     */
    public function configure()
    {
        $this->setName('space48:cms-content:import');
        $this->setDescription('Import CMS Blocks and Pages in html files to the database');
        $this->addArgument('cmsType', InputArgument::REQUIRED, 'pages, blocks, all');

    }

    /**
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        switch ($input->getArgument('cmsType')) {
            case 'pages':
                $output->writeln('Importing CMS Pages...');
                $this->cmsPages->Import(['Space48_CmsContent::fixtures/pages/pages.csv']);
                break;
            case 'blocks':
                $output->writeln('Importing CMS Blocks...');
                $this->cmsBlocks->Import(['Space48_CmsContent::fixtures/blocks/blocks.csv']);
                break;
            case 'all':
                $output->writeln('Importing CMS Pages and Blocks...');
                $this->cmsBlocks->Import(['Space48_CmsContent::fixtures/blocks/blocks.csv']);
                $this->cmsPages->Import(['Space48_CmsContent::fixtures/pages/pages.csv']);
                break;
            default:
                $output->writeln('Param specified is not correct');

        }
    }
}

