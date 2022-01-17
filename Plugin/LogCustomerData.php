<?php
declare(strict_types=1);

namespace Sasa\CustomerRegistration\Plugin;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\AccountManagement;
use Psr\Log\LoggerInterface;

/**
 * Responsible for logging Customer data after registration
 */
class LogCustomerData
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Log Customer data
     *
     * @param AccountManagement $subject
     * @param CustomerInterface $result
     * @return CustomerInterface
     */
    public function afterCreateAccount(AccountManagement $subject, CustomerInterface $result): CustomerInterface
    {
        $this->logger->info(
            'Registered Customer data: ' . json_encode([
                CustomerInterface::FIRSTNAME => $result->getFirstname(),
                CustomerInterface::LASTNAME => $result->getLastname(),
                CustomerInterface::EMAIL => $result->getEmail(),
                CustomerInterface::CREATED_AT => $result->getCreatedAt()
            ])
        );

        return $result;
    }
}
