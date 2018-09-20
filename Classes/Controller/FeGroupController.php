<?php
namespace CAG\CagTests\Controller;

/***
 *
 * This file is part of the "Basic functional Tests" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Thomas Ruta <email@thomasruta.de>, tp3
 *           Jochen Rieger <j.rieger@connecta.ag>, connecta ag
 *
 ***/

/**
 * FeGroupController
 */
class FeGroupController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * action list
     * 
     * @return void
     */
    public function listAction()
    {
        $feGroups = $this->feGroupRepository->findAll();
        $this->view->assign('feGroups', $feGroups);
    }

    /**
     * action show
     * 
     * @param \CAG\CagTests\Domain\Model\FeGroup $feGroup
     * @return void
     */
    public function showAction(\CAG\CagTests\Domain\Model\FeGroup $feGroup)
    {
        $this->view->assign('feGroup', $feGroup);
    }

    /**
     * action new
     * 
     * @return void
     */
    public function newAction()
    {

    }

    /**
     * action create
     * 
     * @param \CAG\CagTests\Domain\Model\FeGroup $newFeGroup
     * @return void
     */
    public function createAction(\CAG\CagTests\Domain\Model\FeGroup $newFeGroup)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->feGroupRepository->add($newFeGroup);
        $this->redirect('list');
    }

    /**
     * action edit
     * 
     * @param \CAG\CagTests\Domain\Model\FeGroup $feGroup
     * @ignorevalidation $feGroup
     * @return void
     */
    public function editAction(\CAG\CagTests\Domain\Model\FeGroup $feGroup)
    {
        $this->view->assign('feGroup', $feGroup);
    }

    /**
     * action update
     * 
     * @param \CAG\CagTests\Domain\Model\FeGroup $feGroup
     * @return void
     */
    public function updateAction(\CAG\CagTests\Domain\Model\FeGroup $feGroup)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->feGroupRepository->update($feGroup);
        $this->redirect('list');
    }

    /**
     * action delete
     * 
     * @param \CAG\CagTests\Domain\Model\FeGroup $feGroup
     * @return void
     */
    public function deleteAction(\CAG\CagTests\Domain\Model\FeGroup $feGroup)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->feGroupRepository->remove($feGroup);
        $this->redirect('list');
    }

    /**
     * action login
     * 
     * @return void
     */
    public function loginAction()
    {

    }
}
