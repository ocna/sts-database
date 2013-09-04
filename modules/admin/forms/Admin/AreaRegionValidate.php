<?php
class Admin_AreaRegionValidate extends Zend_Validate_Abstract
{
    const MISSING = 'regionMissing';
    const PICKONE = 'regionPickOne';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::MISSING      => 'Please select an existing region or create a new one.',
        self::PICKONE      => 'Cannot pick an existing region AND create a new region. Pick just one.',
    );

    /**
     * isValid
     *
     * Ensures that only region or region_new have a value.
     *
     * @param mixed $value
     * @param null $context
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        if (empty($context['region']) && empty($context['region_new'])) {
            $this->_error(self::MISSING);
            return false;
        }

        if (!empty($context['region']) && !empty($context['region_new'])) {
            $this->_error(self::PICKONE);
            return false;
        }

        return true;
    }
}