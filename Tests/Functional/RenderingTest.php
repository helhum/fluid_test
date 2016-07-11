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

use TYPO3\CMS\Core\Tests\Functional\Framework\Frontend\Response;
use TYPO3\CMS\Core\Tests\FunctionalTestCase;
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
                'Base Layout'
            ],
            'overrideAll' => [
                'overrideAll',
                'Override Template',
                'Override Partial',
                'Override Layout'
            ],
            'templateOverride' => [
                'templateOverride',
                'TemplateOverride',
                'Base Partial',
                'Base Layout'
            ],
            'templateOverrideManual' => [
                'templateOverrideManual',
                'TemplateOverride',
                'Base Partial',
                'Base Layout'
            ],
            'partialOverride' => [
                'partialOverride',
                'Base Template',
                'PartialOverride',
                'Base Layout'
            ],
            'partialOverrideManual' => [
                'partialOverrideManual',
                'Base Template',
                'PartialOverride',
                'Base Layout'
            ],
            'layoutOverride' => [
                'layoutOverride',
                'Base Template',
                'Base Partial',
                'LayoutOverride'
            ],
            'layoutOverrideManual' => [
                'layoutOverrideManual',
                'Base Template',
                'Base Partial',
                'LayoutOverride'
            ],
        ];
    }

    /**
     * @test
     * @param string $tsIdentifier
     * @param string $expectedTemplate
     * @param string $expectedPartial
     * @param string $expectedLayout
     * @dataProvider differentOverrideScenariosDataProvider
     */
    public function baseRenderingWorksForCObject($tsIdentifier, $expectedTemplate, $expectedPartial, $expectedLayout)
    {
        $requestArguments = array('id' => '1', 'TS' => $tsIdentifier, 'mode' => 'TS');
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
     * @param string $tsIdentifier
     * @param string $expectedTemplate
     * @param string $expectedPartial
     * @param string $expectedLayout
     * @dataProvider differentOverrideScenariosDataProvider
     */
    public function baseRenderingWorksForController($tsIdentifier, $expectedTemplate, $expectedPartial, $expectedLayout)
    {
        $requestArguments = array('id' => '1', 'TS' => $tsIdentifier);
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
            $instancePath = ORIGINAL_ROOT . 'typo3temp/functional-' . substr(sha1(get_class($this)), 0, 7);
        }
        $arguments = array(
            'documentRoot' => $instancePath,
            'requestUrl' => 'http://localhost' . $requestUrl,
        );

        $template = new \Text_Template(ORIGINAL_ROOT . 'typo3/sysext/core/Tests/Functional/Fixtures/Frontend/request.tpl');
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
            $this->fail('Frontend Response is empty');
        }

        if ($failOnFailure && $result['status'] === Response::STATUS_Failure) {
            $this->fail('Frontend Response has failure:' . LF . $result['error']);
        }

        $response = new Response($result['status'], $result['content'], $result['error']);
        return $response;
    }
}
