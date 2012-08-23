<?php

class Zend_View_Helper_FlashMessages extends \Zend_View_Helper_Abstract
{
    private $_flashMessenger = null;

    public function flashMessages()
    {
        $flashMessenger = $this->_getFlashMessenger();
        $flashMessenger->setNamespace('flashMessagesPulseNamespace');
        //get messages from previous requests
        $messages = $flashMessenger->getMessages();
        //add any messages from this request
        if ($flashMessenger->hasCurrentMessages()) {
            $messages = array_merge($messages, $flashMessenger->getCurrentMessages());
            //we don't need to display them twice.
            $flashMessenger->clearCurrentMessages();
        }
        $output = '';
        if (! empty($messages)) {
            $output .= '';
            $messages = array_unique($messages);
            foreach ($messages as $message) {
                $output .= $message;
            }
        }
        return $output;
    }

    /**
     * Lazily fetches FlashMessenger Instance.
     *
     * @return Zend_Controller_Action_Helper_FlashMessenger
     */
    public function _getFlashMessenger()
    {
        if (null === $this->_flashMessenger) {
            $this->_flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        }
        return $this->_flashMessenger;
    }
}