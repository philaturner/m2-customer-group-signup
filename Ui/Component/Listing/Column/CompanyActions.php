<?php
namespace PhilTurner\GroupPricesInvite\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

/**
 * Class DepartmentActions
 */
class CompanyActions extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')]['edit'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'grouppricesinvite/company/edit',
                        ['id' => $item['id']]
                    ),
                    'label' => __('Edit'),
                    'hidden' => false,
                ];
                $item[$this->getData('name')]['delete'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'grouppricesinvite/company/delete',
                        ['id' => $item['id']]
                    ),
                    'label' => __('Delete'),
                    'hidden' => false,
                ];
                $item[$this->getData('name')]['magic'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'grouppricesinvite/company/easteregg',
                        ['id' => $item['id']]
                    ),
                    'label' => __('Easter Egg'),
                    'hidden' => true,
                ];
            }
        }

        return $dataSource;
    }
}