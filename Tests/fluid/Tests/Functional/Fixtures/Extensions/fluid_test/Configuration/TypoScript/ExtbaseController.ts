page.10 = USER
page.10 {
    userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
    extensionName = FluidTest
    pluginName = Pi
    vendorName = TYPO3Fluid
    view < lib.viewConfig
}

[globalVar = GP:widgetConfig = new]
    page.10.view.widget.TYPO3\CMS\Fluid\ViewHelpers\Widget\PaginateViewHelper.templateRootPath >
[end]

[globalVar = GP:widgetConfig = old]
    page.10.view.widget.TYPO3\CMS\Fluid\ViewHelpers\Widget\PaginateViewHelper.templateRootPaths >
[end]
