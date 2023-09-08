<?php
namespace Hibrido\CustomColorChange\Console\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\State;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\Storage\WriterInterfaceFactory;

class ColorChangeCommandTest extends TestCase
{
    private $state;
    private $storeManager;
    private $configWriterFactory;
    private $command;

    protected function setUp(): void
    {
        $this->state = $this->createMock(State::class);
        $this->storeManager = $this->createMock(StoreManagerInterface::class);
        $this->configWriterFactory = $this->createMock(WriterInterfaceFactory::class);

        $this->command = new ColorChangeCommand(
            $this->state,
            $this->storeManager,
            $this->configWriterFactory
        );
    }

    public function testExecuteWithStoreId()
    {
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        $input->expects($this->once())
            ->method('getArgument')
            ->with('color')
            ->willReturn('#FF0000');

        $input->expects($this->once())
            ->method('getArgument')
            ->with('store_id')
            ->willReturn(1);

        $this->state->expects($this->once())
            ->method('setAreaCode')
            ->with('frontend');

        $this->storeManager->expects($this->once())
            ->method('getStore')
            ->with(1);

        $this->configWriterFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->createMock(WriterInterface::class));

        $output->expects($this->once())
            ->method('writeln')
            ->with('Button color changed successfully for Store ID 1 to #FF0000.');

        $this->assertSame(0, $this->command->execute($input, $output));
    }

    public function testExecuteWithoutStoreId()
    {
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        $input->expects($this->once())
            ->method('getArgument')
            ->with('color')
            ->willReturn('#FF0000');

        $input->expects($this->once())
            ->method('getArgument')
            ->with('store_id')
            ->willReturn(null);

        $this->state->expects($this->once())
            ->method('setAreaCode')
            ->with('frontend');

        $this->storeManager->expects($this->once())
            ->method('getStores')
            ->willReturn([$this->createMock(StoreInterface::class)]);

        $this->configWriterFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->createMock(WriterInterface::class));

        $output->expects($this->once())
            ->method('writeln')
            ->with('Button color changed successfully for Store ID 1 to #FF0000.');

        $this->assertSame(0, $this->command->execute($input, $output));
    }
}