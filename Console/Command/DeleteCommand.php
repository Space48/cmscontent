<?php

namespace Space48\CmsContent\Console\Command;

use Space48\CmsContent\Model\Block;
use Space48\CmsContent\Model\Page;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteCommand extends Command
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
     * DeleteCommand constructor.
     *
     * @param Page  $cmsPages
     * @param Block $cmsBlocks
     *
     * @internal param CmsInterface $cmsContent
     *
     * @throws \LogicException
     */
    public function __construct(Page $cmsPages, Block $cmsBlocks)
    {
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
        $this->setName('space48:cms-content:delete');
        $this->setDescription('Delete CMS Blocks and Pages present in the database');
        $this->addArgument('cmsType', InputArgument::REQUIRED, 'pages, blocks, all');
    }

    /**
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \InvalidArgumentException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        switch ($input->getArgument('cmsType')) {
            case 'blocks':
                $output->writeln('Deleting CMS Blocks...');
                $this->cmsBlocks->delete();
                break;
            case 'pages':
                $output->writeln('Deleting CMS Pages...');
                $this->cmsPages->delete();
                break;
            case 'all':
                $output->writeln('Deleting CMS Pages and Blocks...');
                $this->cmsBlocks->delete();
                $this->cmsPages->delete();
                break;
            default:
                $output->writeln('<error>Invalid argument provided</error>');
        }
    }
}

