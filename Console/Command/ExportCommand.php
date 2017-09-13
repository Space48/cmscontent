<?php

namespace Space48\CmsContent\Console\Command;

use Space48\CmsContent\Model\Block;
use Space48\CmsContent\Model\Page;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportCommand extends Command
{

    /**
     * @var Page
     */
    private $cmsPage;
    /**
     * @var Block
     */
    private $cmsBlock;

    /**
     * ExportCommand constructor.
     *
     * @param Page  $cmsPages
     * @param Block $cmsBlocks
     *
     * @internal param CmsInterface $cmsContent
     *
     * @throws \LogicException
     */
    public function __construct(
        Page $cmsPages,
        Block $cmsBlocks

    ) {
        $this->cmsPage = $cmsPages;
        $this->cmsBlock = $cmsBlocks;
        parent::__construct();
    }

    /**
     * Configures the current command.
     *
     * @throws \InvalidArgumentException
     */
    public function configure()
    {
        $this->setName('space48:cms-content:export');
        $this->setDescription('Export CMS Blocks and Pages in the database to html files');
        $this->addArgument('cmsType', InputArgument::REQUIRED, 'pages, blocks, all');

    }

    /**
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code
     * @throws \InvalidArgumentException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        switch ($input->getArgument('cmsType')) {
            case 'blocks':
                $output->writeln('Exporting CMS Blocks...');
                $this->cmsBlock->export();
                break;
            case 'pages':
                $output->writeln('Exporting CMS Pages...');
                $this->cmsPage->export();
                break;
            case 'all':
                $output->writeln('Exporting CMS Pages and Blocks...');
                $this->cmsPage->export();
                $this->cmsBlock->export();
                break;
            default:
                $output->writeln('<error>Invalid argument provided</error>');
        }
    }

}

