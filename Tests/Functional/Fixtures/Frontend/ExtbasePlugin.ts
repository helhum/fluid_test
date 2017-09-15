page.10 = USER
page.10 {
	userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
	extensionName = FluidTest
	pluginName = Pi
	vendorName = Helhum
}
[globalVar = GP:pluginConfig = extensionKey]
plugin.tx_fluidtest.view < lib.viewConfig
[end]

[globalVar = GP:pluginConfig = pluginName]
plugin.tx_fluidtest_pi.view < lib.viewConfig
[end]
