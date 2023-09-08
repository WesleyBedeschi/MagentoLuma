<?php
namespace Hibrido\CustomColorChange\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Magento\Framework\App\State;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\Storage\WriterInterfaceFactory;

class ColorChangeCommand extends Command
{
    const COMMAND_NAME = 'color:change';

    private $state;
    private $storeManager;
    private $configWriterFactory;

    public function __construct(
        State $state,
        StoreManagerInterface $storeManager,
        WriterInterfaceFactory $configWriterFactory
    ) {
        parent::__construct();
        $this->state = $state;
        $this->storeManager = $storeManager;
        $this->configWriterFactory = $configWriterFactory;
    }

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription('Change button color for a store view or all buttons on the site.')
            ->setDefinition([
                new InputArgument('color', InputArgument::REQUIRED, 'Hex color code'),
                new InputArgument('store_id', InputArgument::OPTIONAL, 'Store view ID (optional)'),
            ]);
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $color = $input->getArgument('color');
        $storeViewId = $input->getArgument('store_id');

        $this->state->setAreaCode('frontend');
        $storeManager = $this->storeManager;
        $configWriter = $this->configWriterFactory->create();
        $configWriter->save('button_color/general/color', $color, 'default', 0);

        if ($storeViewId) {
            $store = $storeManager->getStore($storeViewId);
            $output->writeln("Button color changed successfully for Store ID $storeViewId to #$color.");
        } else {
            $stores = $storeManager->getStores();
            foreach ($stores as $store) {
                $output->writeln("Button color changed successfully for Store ID {$store->getId()} to #$color.");
            }
        }
        return 0;
    }
}