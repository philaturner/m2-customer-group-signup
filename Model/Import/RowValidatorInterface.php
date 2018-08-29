<?php
namespace PhilTurner\GroupPricesInvite\Model\Import;

interface RowValidatorInterface extends \Magento\Framework\Validator\ValidatorInterface
{
    const ERROR_NAME_IS_EMPTY = 'EmptyNameValue';
    const ERROR_CODE_IS_EMPTY = 'EmptyInvCode';

    /**
     * Initialize validator
     *
     * @param $context
     * @return $this
     */
    public function init($context);
}