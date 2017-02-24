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

use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Fluid\View\StandaloneView;

class EscapingExamplesTest extends FunctionalTestCase {

    protected $testExtensionsToLoad = ['typo3conf/ext/fluid_test'];
    protected $coreExtensionsToLoad = ['fluid'];
    protected $configurationToUseInTestInstance = [
        'SYS' => [
            'caching' => [
                'cacheConfigurations' => [
                    'fluid_template' => array(
                        'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class,
                    ),
                ]
            ]
        ]
    ];

    public function viewHelperTemplateSourcesDataProvider()
    {
        return [
            'Tag syntax argument is string' => [
                '<ft:varExport content="{content}"/>',
                ['content' => '<html>Hello</html>'],
                '<html>Hello</html>'
            ],
            'Tag syntax argument is array' => [
                '<ft:varExport content="{content}"/>',
                ['content' => ['<html>Hello</html>']],
                '<html>Hello</html>'
            ],
            'Tag syntax children is string' => [
                '<ft:varExport>{content}</ft:varExport>',
                ['content' => '<html>Hello</html>'],
                '&lt;html&gt;Hello&lt;/html&gt;'
            ],
            'Tag syntax children is array' => [
                '<ft:varExport>{content}</ft:varExport>',
                ['content' => ['<html>Hello</html>']],
                '<html>Hello</html>'
            ],
            'Tag syntax children is array with string' => [
                '<ft:varExport>{content} world</ft:varExport>',
                ['content' => ['<html>Hello</html>']],
                'Array world'
            ],
            'Inline syntax argument is string' => [
                '{ft:varExport(content: content)}',
                ['content' => '<html>Hello</html>'],
                '<html>Hello</html>'
            ],
            'Inline syntax argument is string in quotes' => [
                '{ft:varExport(content: \'{content}\')}',
                ['content' => '<html>Hello</html>'],
                '<html>Hello</html>'
            ],
            'Inline syntax argument is array' => [
                '{ft:varExport(content: content)}',
                ['content' => ['<html>Hello</html>']],
                '<html>Hello</html>'
            ],
            'Inline syntax argument is array in quotes' => [
                '{ft:varExport(content: \'{content}\')}',
                ['content' => ['<html>Hello</html>']],
                '<html>Hello</html>'
            ],
            'Inline syntax children is string' => [
                '{content -> ft:varExport()}',
                ['content' => '<html>Hello</html>'],
                '&lt;html&gt;Hello&lt;/html&gt;'
            ],
            'Inline syntax children is array' => [
                '{content -> ft:varExport()}',
                ['content' => ['<html>Hello</html>']],
                '<html>Hello</html>'
            ],
        ];
    }

    /**
     * @param string $viewHelperTemplate
     * @param array $vars
     * @param string $expectedOutput
     * @param string $format
     *
     * @test
     * @dataProvider viewHelperTemplateSourcesDataProvider
     */
    public function renderingTest($viewHelperTemplate, array $vars, $expectedOutput, $format = 'html')
    {
        if (!in_array($format, array('html', 'json'), true)) {
            $format = 'html';
        }
        $view = new StandaloneView();
        $view->assignMultiple($vars);
        $view->setFormat($format);
        $viewHelperTemplate = '{namespace ft=Helhum\FluidTest\ViewHelpers}' . $viewHelperTemplate;
        $view->setTemplateSource($viewHelperTemplate);
//        $this->assertSame($expectedOutput, $view->render());
        $this->assertContains($expectedOutput, $view->render());
    }
}
