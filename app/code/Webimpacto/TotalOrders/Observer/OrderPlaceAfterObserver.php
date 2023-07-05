<?php
namespace Webimpacto\TotalOrders\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;

class OrderPlaceAfterObserver implements ObserverInterface
{
    protected $customerRepository;
    protected $scopeConfig;
    protected $transportBuilder;
    protected $storeManager;
    protected $orderCollectionFactory;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        ScopeConfigInterface $scopeConfig,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        CollectionFactory $orderCollectionFactory
    ) {
        $this->customerRepository = $customerRepository;
        $this->scopeConfig = $scopeConfig;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->orderCollectionFactory = $orderCollectionFactory;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $customerId = $order->getCustomerId();

        if ($customerId) {
            $customer = $this->customerRepository->getById($customerId);
            $total=0;
            $orders = $this->orderCollectionFactory->create()
            ->addAttributeToFilter('customer_email', $order->getCustomerEmail());
            foreach ($orders as $order) {
                $total=$total+$order->getGrandTotal();
            }
            $totalOrders = $total;
            $this->sendEmail($customer, $totalOrders);
        }
    }

    protected function sendEmail($customer, $totalOrders)
    {
        $emailTemplate = 'total_orders_email_template';

        $transport = $this->transportBuilder->setTemplateIdentifier($emailTemplate)
            ->setTemplateOptions(['area' => 'frontend', 'store' => $this->storeManager->getStore()->getId()])
            ->setTemplateVars(['name' => $customer->getFirstname(), 'totalorders' => $totalOrders])
            ->setFrom(['email' => 'email@webimpacto.com', 'name' => 'Email Test'])
            ->addTo($customer->getEmail(), $customer->getFirstname())
            ->getTransport();

        $transport->sendMessage();
    }
}
