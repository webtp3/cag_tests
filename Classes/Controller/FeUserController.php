<?php
declare(strict_types=1);
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
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;

/**
 * FeUserController
 */
class FeUserController extends FrontendUserAuthentication
{
    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $feUsers = $this->feUserRepository->findAll();
        $this->view->assign('feUsers', $feUsers);
    }

    /**
     * action show
     *
     * @param \CAG\CagTests\Domain\Model\FeUser $feUser
     * @return void
     */
    public function showAction(\CAG\CagTests\Domain\Model\FeUser $feUser)
    {
        $this->view->assign('feUser', $feUser);
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
     * @param \CAG\CagTests\Domain\Model\FeUser $newFeUser
     * @return void
     */
    public function createAction(\CAG\CagTests\Domain\Model\FeUser $newFeUser)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->feUserRepository->add($newFeUser);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \CAG\CagTests\Domain\Model\FeUser $feUser
     * @ignorevalidation $feUser
     * @return void
     */
    public function editAction(\CAG\CagTests\Domain\Model\FeUser $feUser)
    {
        $this->view->assign('feUser', $feUser);
    }

    /**
     * action update
     *
     * @param \CAG\CagTests\Domain\Model\FeUser $feUser
     * @return void
     */
    public function updateAction(\CAG\CagTests\Domain\Model\FeUser $feUser)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->feUserRepository->update($feUser);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \CAG\CagTests\Domain\Model\FeUser $feUser
     * @return void
     */
    public function deleteAction(\CAG\CagTests\Domain\Model\FeUser $feUser)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->feUserRepository->remove($feUser);
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
