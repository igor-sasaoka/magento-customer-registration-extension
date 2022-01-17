<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2022 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace Sasa\CustomerRegistration\Service;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Mail\TransportInterface;
use Magento\Store\Model\Store;
use Psr\Log\LoggerInterface;

/**
 * Service responsible to build a mail transport for
 * Customer Data email after register
 */
class BuildCustomerDataMailTransport
{
    const CUSTOMER_SUPPORT_EMAIL_CONFIG_PATH = 'trans_email/ident_support/email';
    const CUSTOMER_SUPPORT_NAME_CONFIG_PATH = 'trans_email/ident_support/name';
    const OWNER_EMAIL_CONFIG_PATH = 'trans_email/ident_general/email';
    const OWNER_NAME_CONFIG_PATH = 'trans_email/ident_general/name';
    const CUSTOMER_DATA_EMAIL_TEMPLATE = 'email_after_customer_registration';

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param TransportBuilder $transportBuilder
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * Build a mail transport for customer data email
     *
     * @param CustomerInterface $customer
     * @return TransportInterface|null
     */
    public function execute(CustomerInterface $customer): ?TransportInterface
    {
        try {
            $this->transportBuilder->addTo(
                $this->scopeConfig->getValue(self::CUSTOMER_SUPPORT_EMAIL_CONFIG_PATH),
                $this->scopeConfig->getValue(self::CUSTOMER_SUPPORT_NAME_CONFIG_PATH)
            );
            $this->transportBuilder->setFromByScope([
                'name' => $this->scopeConfig->getValue(self::OWNER_NAME_CONFIG_PATH),
                'email' => $this->scopeConfig->getValue(self::OWNER_EMAIL_CONFIG_PATH)
            ]);
            $this->transportBuilder->setTemplateOptions([
                'area' => Area::AREA_FRONTEND,
                'store' => Store::DEFAULT_STORE_ID,
            ]);
            $this->transportBuilder->setTemplateIdentifier(self::CUSTOMER_DATA_EMAIL_TEMPLATE);
            $this->transportBuilder->setTemplateVars([
                'customerFirstName' => $customer->getFirstname(),
                'customerLastName' => $customer->getLastname(),
                'customerEmail' => $customer->getEmail()
            ]);

            return $this->transportBuilder->getTransport();
        } catch (\Exception $exception) {
            $this->logger->error(
                $exception->getMessage()
            );
        }

        return null;
    }
}
