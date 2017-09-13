config {
	no_cache = 1
	debug = 0
	xhtml_cleaning = 0
	admPanel = 0
	disableAllHeaderCode = 1
	sendCacheHeaders = 0
	sys_language_uid = 0
	sys_language_mode = ignore
	sys_language_overlay = 1
	absRefPrefix = /
	linkVars = L
	contentObjectExceptionHandler = 0

	intTarget = _blank
}

page = PAGE
page.config.no_cache = 0
page.config.contentObjectExceptionHandler = 0

page.10 = USER
page.10 {
	userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
	extensionName = FluidTest
	pluginName = Pi
	vendorName = Helhum
}
lib.viewConfig {
	templateRootPaths {
		1 = EXT:fluid_test/Resources/Private/Base/Templates/
	}
	partialRootPaths {
		1 = EXT:fluid_test/Resources/Private/Base/Partials/
	}
	layoutRootPaths {
		1 = EXT:fluid_test/Resources/Private/Base/Layouts/
	}
	widget.TYPO3\CMS\Fluid\ViewHelpers\Widget\PaginateViewHelper {
		templateRootPath = EXT:fluid_test/Resources/Private/Base/Templates/
		templateRootPaths {
			1 = EXT:fluid_test/Resources/Private/Base/Templates/
		}
	}
}

[globalVar = GP:TS = overrideAll]
lib.viewConfig {
	templateRootPaths {
		10 = EXT:fluid_test/Resources/Private/Override/Templates/
	}
	partialRootPaths {
		10 = EXT:fluid_test/Resources/Private/Override/Partials/
	}
	layoutRootPaths {
		10 = EXT:fluid_test/Resources/Private/Override/Layouts/
	}
}
[end]

[globalVar = GP:TS = templateOverride]
lib.viewConfig {
	templateRootPaths {
		15 = EXT:fluid_test/Resources/Private/TemplateOverride/Templates/
		10 = EXT:fluid_test/Resources/Private/Override/Templates/
	}
}
[end]

[globalVar = GP:TS = templateOverrideManual]
lib.viewConfig {
	templateRootPaths {
		10 = EXT:fluid_test/Resources/Private/Override/Templates/
		bla = EXT:fluid_test/Resources/Private/TemplateOverride/Templates/
	}
}
[end]

[globalVar = GP:TS = partialOverride]
lib.viewConfig {
	partialRootPaths {
		15 = EXT:fluid_test/Resources/Private/PartialOverride/Partials/
		10 = EXT:fluid_test/Resources/Private/Override/Partials/
	}
}
[end]

[globalVar = GP:TS = partialOverrideManual]
lib.viewConfig {
	partialRootPaths {
		10 = EXT:fluid_test/Resources/Private/Override/Partials/
		bla = EXT:fluid_test/Resources/Private/PartialOverride/Partials/
	}
}
[end]

[globalVar = GP:TS = layoutOverride]
lib.viewConfig {
	layoutRootPaths {
		15 = EXT:fluid_test/Resources/Private/LayoutOverride/Layouts/
		10 = EXT:fluid_test/Resources/Private/Override/Layouts/
	}
}
[end]

[globalVar = GP:TS = layoutOverrideManual]
lib.viewConfig {
	layoutRootPaths {
		10 = EXT:fluid_test/Resources/Private/Override/Layouts/
		bla = EXT:fluid_test/Resources/Private/LayoutOverride/Layouts/
	}
}
[end]

page.10.view < lib.viewConfig

[globalVar = GP:mode = plugin]
page.10.view >
plugin.tx_fluidtest.view < lib.viewConfig
[end]

[globalVar = GP:mode = TS]
page.10 >
page.10 < lib.viewConfig


page.10 = FLUIDTEMPLATE
page.10.templateName = BaseTemplate
[end]