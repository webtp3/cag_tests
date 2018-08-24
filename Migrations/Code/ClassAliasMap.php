<?php

return [
    // Acceptance
    '\\TYPO3\\Components\\TestingFramework\\Core\\Acceptance\\Step\\Backend\\Admin' => \Cag\CagTests\Core\Acceptance\Step\Backend\Admin::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Acceptance\\Step\\Backend\\Editor' => \Cag\CagTests\Core\Acceptance\Step\Backend\Editor::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Acceptance\\Support\\Helper\\ModalDialog' => \Cag\CagTests\Core\Acceptance\Support\Helper\ModalDialog::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Acceptance\\Support\\Helper\\Topbar' => \Cag\CagTests\Core\Acceptance\Support\Helper\Topbar::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Acceptance\\Support\\Page\\PageTree' => \Cag\CagTests\Core\Acceptance\Support\Page\PageTree::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Acceptance\\AcceptanceCoreEnvironment' => \Cag\CagTests\Core\Acceptance\AcceptanceCoreEnvironment::class,

    // Functional
    '\\TYPO3\\Components\\TestingFramework\\Core\\Functional\\FunctionalTestCase' => \Cag\CagTests\Core\Functional\FunctionalTestCase::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Functional\\Framework\Frontend\\Hook\\BackendUserHandler' => \Cag\CagTests\Core\Functional\Framework\Frontend\Hook\BackendUserHandler::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Functional\\Framework\Frontend\\Collector' => \Cag\CagTests\Core\Functional\Framework\Frontend\Collector::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Functional\\Framework\Frontend\\Hook\\FrontendUserHandler' => \Cag\CagTests\Core\Functional\Framework\Frontend\Hook\FrontendUserHandler::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Functional\\Framework\Frontend\\Parser' => \Cag\CagTests\Core\Functional\Framework\Frontend\Parser::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Functional\\Framework\Frontend\\Renderer' => \Cag\CagTests\Core\Functional\Framework\Frontend\Renderer::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Functional\\Framework\Frontend\\RequestBootstrap' => \Cag\CagTests\Core\Functional\Framework\Frontend\RequestBootstrap::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Functional\\Framework\Frontend\\ResponseContent' => \Cag\CagTests\Core\Functional\Framework\Frontend\ResponseContent::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Functional\\Framework\Frontend\\ResponseSection' => \Cag\CagTests\Core\Functional\Framework\Frontend\ResponseSection::class,

    // Unit
    '\\TYPO3\\Components\\TestingFramework\\Core\\Unit\\UnitTestCase' => \Cag\CagTests\Core\Unit\UnitTestCase::class,

    // General
    '\\TYPO3\\Components\\TestingFramework\\Core\\AccessibleObjectInterface' => \Cag\CagTests\Core\AccessibleObjectInterface::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\BaseTestCase' => \Cag\CagTests\Core\BaseTestCase::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Exception' => \Cag\CagTests\Core\Exception::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\FileStreamWrapper' => \Cag\CagTests\Core\FileStreamWrapper::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Testbase' => \Cag\CagTests\Core\Testbase::class,

    // Fluid
    '\\TYPO3\\Components\\TestingFramework\\Fluid\\Unit\\ViewHelpers\\ViewHelperBaseTestcase' => \Cag\CagTests\Fluid\Unit\ViewHelpers\ViewHelperBaseTestcase::class

];
