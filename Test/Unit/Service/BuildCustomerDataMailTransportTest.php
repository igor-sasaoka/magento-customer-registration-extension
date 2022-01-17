<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2022 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace Sasa\CustomerRegistration\Test\Unit\Service;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Exception\InvalidArgumentException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Mail\TransportInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Sasa\CustomerRegistration\Service\BuildCustomerDataMailTransport;

/**
 * Test for helper service
 */
class BuildCustomerDataMailTransportTest extends TestCase
{
    /**
     * @var BuildCustomerDataMailTransport
     */
    private $object;

    /**
     * @var TransportBuilder|MockObject
     */
    private $transportBuilder;

    /**
     * @var ScopeConfigInterface|MockObject
     */
    private $scopeConfig;

    /**
     * @var MockObject|LoggerInterface
     */
    private $logger;

    /**
     * @var CustomerInterface|MockObject
     */
    private $customer;

    /**
     * @var TransportInterface|MockObject
     */
    private $transport;

    /**
     * TestExecute
     *
     * @return void
     */
    public function testExecute(): void
    {
        $expected = $this->transport;

        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->willReturn('some/config/path');
        $this->transportBuilder->expects($this->once())
            ->method('addTo')
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('setFromByScope')
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('setTemplateOptions')
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('setTemplateIdentifier')
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('setTemplateVars')
            ->willReturnSelf();

        $this->customer->expects($this->once())
            ->method('getFirstname')
            ->willReturn('firstname');
        $this->customer->expects($this->once())
            ->method('getLastname')
            ->willReturn('lastname');
        $this->customer->expects($this->once())
            ->method('getEmail')
            ->willReturn('customer@mail.com');

        $this->transportBuilder->expects($this->once())
            ->method('getTransport')
            ->willReturn($this->transport);

        $result = $this->object->execute($this->customer);

        $this->assertEquals($expected, $result);
    }

    /**
     * TestExecuteWillThrowException
     *
     * @return void
     */
    public function testExecuteWillThrowException(): void
    {
        $expected = null;

        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->willReturn('some/config/path');
        $this->transportBuilder->expects($this->once())
            ->method('addTo')
            ->willThrowException(new InvalidArgumentException());

        $result = $this->object->execute($this->customer);
        $this->assertSame($expected, $result);
    }

    /**
     * SetUp
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->setUpMockObjects();
        $this->object = new BuildCustomerDataMailTransport(
            $this->transportBuilder,
            $this->scopeConfig,
            $this->logger
        );
    }

    /**
     * SetUpMockObjects
     *
     * @return void
     */
    private function setUpMockObjects(): void
    {
        $this->transportBuilder = $this->createMock(TransportBuilder::class);
        $this->scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->customer = $this->createMock(CustomerInterface::class);
        $this->transport = $this->createMock(TransportInterface::class);
    }
}
