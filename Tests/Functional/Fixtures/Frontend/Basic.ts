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
page.10 = FLUIDTEMPLATE
page.10 {
	templateName = BaseTemplate
	templateRootPaths {
		1 = EXT:fluid_test/Resources/Private/Base/Templates
	}
	partialRootPaths {
		1 = EXT:fluid_test/Resources/Private/Base/Partials
	}
	layoutRootPaths {
		1 = EXT:fluid_test/Resources/Private/Base/Layouts
	}
}

[globalVar = GP:TS = overrideAll]
page.10 {
	templateRootPaths {
		10 = EXT:fluid_test/Resources/Private/Override/Templates
	}
	partialRootPaths {
		10 = EXT:fluid_test/Resources/Private/Override/Partials
	}
	layoutRootPaths {
		10 = EXT:fluid_test/Resources/Private/Override/Layouts
	}
}
[end]

[globalVar = GP:TS = templateOverride]
page.10 {
	templateRootPaths {
		15 = EXT:fluid_test/Resources/Private/TemplateOverride/Templates
		10 = EXT:fluid_test/Resources/Private/Override/Templates
	}
}
[end]

[globalVar = GP:TS = partialOverride]
page.10 {
	partialRootPaths {
		15 = EXT:fluid_test/Resources/Private/PartialOverride/Partials
		10 = EXT:fluid_test/Resources/Private/Override/Partials
	}
}
[end]

[globalVar = GP:TS = layoutOverride]
page.10 {
	layoutRootPaths {
		15 = EXT:fluid_test/Resources/Private/LayoutOverride/Layouts
		10 = EXT:fluid_test/Resources/Private/Override/Layouts
	}
}
[end]

