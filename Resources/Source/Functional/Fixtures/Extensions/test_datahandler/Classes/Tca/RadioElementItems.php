<?php
declare(strict_types=1);
namespace TYPO3\TestDatahandler\Classes\Tca;

/**
 * Items processor for radio buttons for the functional tests of DataHandler::checkValue()
 */
class RadioElementItems
{
    /**
     * @param mixed $params
     * @return array
     */
    public function getItems($params)
    {
        $params['items'][] = ['processed label', 'processed value'];
    }
}
