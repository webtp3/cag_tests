<?php

return [
    // Acceptance
    '\\TYPO3\\Components\\TestingFramework\\Core\\Acceptance\\Step\\Backend\\Admin' => \CAG\CagTests\Core\Acceptance\Step\Backend\Admin::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Acceptance\\Step\\Backend\\Editor' => \CAG\CagTests\Core\Acceptance\Step\Backend\Editor::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Acceptance\\Support\\Helper\\ModalDialog' => \CAG\CagTests\Core\Acceptance\Support\Helper\ModalDialog::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Acceptance\\Support\\Helper\\Topbar' => \CAG\CagTests\Core\Acceptance\Support\Helper\Topbar::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Acceptance\\Support\\Page\\PageTree' => \CAG\CagTests\Core\Acceptance\Support\Page\PageTree::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Acceptance\\AcceptanceCoreEnvironment' => \CAG\CagTests\Core\Acceptance\AcceptanceCoreEnvironment::class,

    // Functional
    '\\TYPO3\\Components\\TestingFramework\\Core\\Functional\\FunctionalTestCase' => \CAG\CagTests\Core\Functional\FunctionalTestCase::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Functional\\Framework\Frontend\\Hook\\BackendUserHandler' => \CAG\CagTests\Core\Functional\Framework\Frontend\Hook\BackendUserHandler::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Functional\\Framework\Frontend\\Collector' => \CAG\CagTests\Core\Functional\Framework\Frontend\Collector::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Functional\\Framework\Frontend\\Hook\\FrontendUserHandler' => \CAG\CagTests\Core\Functional\Framework\Frontend\Hook\FrontendUserHandler::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Functional\\Framework\Frontend\\Parser' => \CAG\CagTests\Core\Functional\Framework\Frontend\Parser::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Functional\\Framework\Frontend\\Renderer' => \CAG\CagTests\Core\Functional\Framework\Frontend\Renderer::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Functional\\Framework\Frontend\\RequestBootstrap' => \CAG\CagTests\Core\Functional\Framework\Frontend\RequestBootstrap::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Functional\\Framework\Frontend\\ResponseContent' => \CAG\CagTests\Core\Functional\Framework\Frontend\ResponseContent::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Functional\\Framework\Frontend\\ResponseSection' => \CAG\CagTests\Core\Functional\Framework\Frontend\ResponseSection::class,

    // Unit
    '\\TYPO3\\Components\\TestingFramework\\Core\\Unit\\UnitTestCase' => \CAG\CagTests\Core\Unit\UnitTestCase::class,

    // General
    '\\TYPO3\\Components\\TestingFramework\\Core\\AccessibleObjectInterface' => \CAG\CagTests\Core\AccessibleObjectInterface::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\BaseTestCase' => \CAG\CagTests\Core\BaseTestCase::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Exception' => \CAG\CagTests\Core\Exception::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\FileStreamWrapper' => \CAG\CagTests\Core\FileStreamWrapper::class,
    '\\TYPO3\\Components\\TestingFramework\\Core\\Testbase' => \CAG\CagTests\Core\Testbase::class,

    // Fluid
    '\\TYPO3\\Components\\TestingFramework\\Fluid\\Unit\\ViewHelpers\\ViewHelperBaseTestcase' => \CAG\CagTests\Fluid\Unit\ViewHelpers\ViewHelperBaseTestcase::class,
    //Migrations for ext. Tests
    'Tx_Phpunit_TestCase' => \TYPO3\CMS\Core\Tests\Unit\Resource\BaseTestCase::class,
    'PHPUnit_Framework_TestCase' => \TYPO3\TestingFramework\Core\Unit\UnitTestCase::class,
    'Tx_Phpunit_Database_TestCase' => \TYPO3\TestingFramework\Core\Unit\UnitTestCase::class
];
