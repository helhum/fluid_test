<?php
namespace Helhum\FluidTest\Tests\Functional;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Helmut Hummel <helmut.hummel@typo3.org>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the text file GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Nimut\TestingFramework\Http\Response;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class RenderingTest
 */
class RenderingTest extends FunctionalTestCase {

    protected $configurationToUseInTestInstance = [
        'EXTCONF' => [
            'extbase' => [
                'extensions' => [
                    'FluidTest' => [
                        'plugins' => [
                            'Pi' => [
                                'controllers' => [
                                    'Template' => [
                                        'actions' => [
                                            'baseTemplate'
                                        ],
                                        'nonCacheableActions' => [
                                            'baseTemplate'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ];

    /**
     * @var array
     */
    protected $testExtensionsToLoad = array('typo3conf/ext/fluid_test');

    /**
     * @var array
     */
    protected $coreExtensionsToLoad = array('fluid');

    public function setUp()
    {
        parent::setUp();
        $this->importDataSet(__DIR__ . '/Fixtures/Database/pages.xml');
        $this->setUpFrontendRootPage(1, array('EXT:fluid_test/Tests/Functional/Fixtures/Frontend/Basic.ts'));
    }

    public function differentOverrideScenariosDataProvider()
    {
        return [
            'base' => [
                'base',
                'Base Template',
                'Base Partial',
                'Base Layout',
            ],
            'overrideAll' => [
                'overrideAll',
                'Override Template',
                'Override Partial',
                'Override Layout',
            ],
            'templateOverride' => [
                'templateOverride',
                'TemplateOverride',
                'Base Partial',
                'Base Layout',
            ],
            'templateOverrideManual' => [
                'templateOverrideManual',
                'TemplateOverride',
                'Base Partial',
                'Base Layout',
            ],
            'partialOverride' => [
                'partialOverride',
                'Base Template',
                'PartialOverride',
                'Base Layout',
            ],
            'partialOverrideManual' => [
                'partialOverrideManual',
                'Base Template',
                'PartialOverride',
                'Base Layout',
            ],
            'layoutOverride' => [
                'layoutOverride',
                'Base Template',
                'Base Partial',
                'LayoutOverride',
            ],
            'layoutOverrideManual' => [
                'layoutOverrideManual',
                'Base Template',
                'Base Partial',
                'LayoutOverride',
            ],
        ];
    }

    /**
     * @test
     * @param string $overrideType
     * @param string $expectedTemplate
     * @param string $expectedPartial
     * @param string $expectedLayout
     * @dataProvider differentOverrideScenariosDataProvider
     */
    public function baseRenderingWorksForCObject($overrideType, $expectedTemplate, $expectedPartial, $expectedLayout)
    {
        $requestArguments = array('id' => '1', 'override' => $overrideType, 'mode' => 'fluidTemplate');
        $content = $this->fetchFrontendResponse($requestArguments)->getContent();
        $this->assertContains($expectedTemplate,
            $content
        );
        $this->assertContains($expectedPartial,
            $content
        );
        $this->assertContains($expectedLayout,
            $content
        );
    }

    /**
     * @test
     * @param string $overrideType
     * @param string $expectedTemplate
     * @param string $expectedPartial
     * @param string $expectedLayout
     * @param string $expectedWidget
     * @dataProvider differentOverrideScenariosDataProvider
     * @group exec
     */
    public function baseRenderingWorksForControllerAsGlobalUsage($overrideType, $expectedTemplate, $expectedPartial, $expectedLayout, $expectedWidget = '')
    {
        $requestArguments = array('id' => '1', 'override' => $overrideType, 'mode' => 'controller');
        $content = $this->fetchFrontendResponse($requestArguments)->getContent();
        $this->assertContains($expectedTemplate,
            $content
        );
        $this->assertContains($expectedPartial,
            $content
        );
        $this->assertContains($expectedLayout,
            $content
        );
        if ($expectedWidget) {
            $this->assertContains($expectedWidget,
                $content
            );
        }
    }

    /**
     * @test
     */
    public function widgetTemplateCanBeOverridden()
    {
        $requestArguments = array('id' => '1', 'override' => 'base', 'mode' => 'controller', 'widgetConfig' => 'new');
        $content = $this->fetchFrontendResponse($requestArguments)->getContent();
        $this->assertContains('PAGINATE WIDGET', $content);
    }

    /**
     * @test
     */
    public function widgetTemplateCanBeOverriddenWithLegacyConfig()
    {
        $requestArguments = array('id' => '1', 'override' => 'base', 'mode' => 'controller', 'widgetConfig' => 'old');
        $content = $this->fetchFrontendResponse($requestArguments)->getContent();
        $this->assertContains('PAGINATE WIDGET', $content);
    }

    /**
     * @test
     * @param string $overrideType
     * @param string $expectedTemplate
     * @param string $expectedPartial
     * @param string $expectedLayout
     * @param string $expectedWidget
     * @dataProvider differentOverrideScenariosDataProvider
     */
    public function baseRenderingWorksForControllerAsPluginUsage($overrideType, $expectedTemplate, $expectedPartial, $expectedLayout, $expectedWidget = '')
    {
        $requestArguments = array('id' => '1', 'override' => $overrideType, 'mode' => 'plugin', 'pluginConfig' => 'extensionKey');
        $content = $this->fetchFrontendResponse($requestArguments)->getContent();
        $this->assertContains($expectedTemplate,
            $content
        );
        $this->assertContains($expectedPartial,
            $content
        );
        $this->assertContains($expectedLayout,
            $content
        );
    }

    /**
     * @test
     */
    public function baseRenderingWorksForControllerAsPluginUsageWithIncompleteConfig()
    {
        $requestArguments = array('id' => '1', 'override' => 'base', 'mode' => 'plugin', 'pluginConfig' => 'incomplete');
        $content = $this->fetchFrontendResponse($requestArguments)->getContent();
        $this->assertContains('Base Template',
            $content
        );
        $this->assertContains('Default Layout',
            $content
        );
        $this->assertContains('Default Partial',
            $content
        );
    }

    /**
     * @test
     * @param string $overrideType
     * @param string $expectedTemplate
     * @param string $expectedPartial
     * @param string $expectedLayout
     * @param string $expectedWidget
     * @dataProvider differentOverrideScenariosDataProvider
     */
    public function baseRenderingWorksForControllerAsPluginUsageWithPluginConfig($overrideType, $expectedTemplate, $expectedPartial, $expectedLayout, $expectedWidget = '')
    {
        $requestArguments = array('id' => '1', 'override' => $overrideType, 'mode' => 'plugin', 'pluginConfig' => 'pluginName');
        $content = $this->fetchFrontendResponse($requestArguments)->getContent();
        $this->assertContains($expectedTemplate,
            $content
        );
        $this->assertContains($expectedPartial,
            $content
        );
        $this->assertContains($expectedLayout,
            $content
        );
        if ($expectedWidget) {
            $this->assertContains($expectedWidget,
                $content
            );
        }
    }

    /**
     * @test
     */
    public function baseRenderingWorksForControllerWithTwoPlugins()
    {
        $requestArguments = array('id' => '1', 'mode' => '2plugins');
        $content = $this->fetchFrontendResponse($requestArguments)->getContent();
        $this->assertContains('Base Template',
            $content
        );
        $this->assertContains('Override Template',
            $content
        );
    }



    /* ***************
     * Utility methods
     * ***************/


    /**
     * @param array $requestArguments
     * @param bool $failOnFailure
     * @return Response
     */
    protected function fetchFrontendResponse(array $requestArguments, $failOnFailure = true) 
    {
        if (!empty($requestArguments['url'])) {
            $requestUrl = '/' . ltrim($requestArguments['url'], '/');
        } else {
            $requestUrl = '/?' . GeneralUtility::implodeArrayForUrl('', $requestArguments);
        }
        if (property_exists($this, 'instancePath')) {
            $instancePath = $this->instancePath;
        } else {
            $instancePath = ORIGINAL_ROOT . 'typo3temp/var/tests/functional-' . substr(sha1(get_class($this)), 0, 7);
        }
        $arguments = array(
            'documentRoot' => $instancePath,
            'requestUrl' => 'http://localhost' . $requestUrl,
        );

        $template = new \Text_Template(__DIR__ . '/Fixtures/Frontend/request.tpl');
        $template->setVar(
            array(
                'arguments' => var_export($arguments, true),
                'originalRoot' => ORIGINAL_ROOT,
            )
        );

        $php = \PHPUnit_Util_PHP::factory();
        $response = $php->runJob($template->render());

        $result = json_decode($response['stdout'], true);

        if ($result === null) {
            $this->fail('Frontend Response has errors. Message: ' . $response['stdout'] . chr(10) . $response['stderr']);
        }

        if ($failOnFailure && $result['status'] === Response::STATUS_Failure) {
            $this->fail('Frontend Response has failure:' . LF . $result['error']);
        }

        $response = new Response($result['status'], $result['content'], $result['error']);
        return $response;
    }
}
