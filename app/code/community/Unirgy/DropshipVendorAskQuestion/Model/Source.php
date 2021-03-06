<?php

class Unirgy_DropshipVendorAskQuestion_Model_Source extends Unirgy_Dropship_Model_Source_Abstract
{
    const UDQA_STATUS_DECLINED = -1;
    const UDQA_STATUS_PENDING  = 0;
    const UDQA_STATUS_APPROVED = 1;

    const UDQA_VISIBILITY_PRIVATE = 0;
    const UDQA_VISIBILITY_PUBLIC  = 1;

    public function toOptionHash($selector=false)
    {
        $hlp = Mage::helper('udqa');

        $options = array();

        switch ($this->getPath()) {

            case 'udqa/general/default_question_status':
            case 'udqa/general/default_answer_status':
            case 'statuses':
                $options = array(
                    self::UDQA_STATUS_PENDING  => Mage::helper('udropship')->__('Pending'),
                    self::UDQA_STATUS_APPROVED => Mage::helper('udropship')->__('Approved'),
                    self::UDQA_STATUS_DECLINED => Mage::helper('udropship')->__('Declined'),
                );
                break;

            case 'visibility':
                $options = array(
                    self::UDQA_VISIBILITY_PRIVATE => Mage::helper('udropship')->__('Private'),
                    self::UDQA_VISIBILITY_PUBLIC  => Mage::helper('udropship')->__('Public'),
                );
                break;

            default:
                Mage::throwException(Mage::helper('udropship')->__('Invalid request for source options: '.$this->getPath()));
        }

        if ($selector) {
            $options = array(''=>Mage::helper('udropship')->__('* Please select')) + $options;
        }

        return $options;
    }
}