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

class EscapingSyntaxParsingTest extends FunctionalTestCase {

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

    public function viewHelperSyntaxDataProvider()
    {
        return [
            'Tag syntax argument is string' => [
                '<f:if condition="true" then="{content}"/>',
                ['content' => '<html>Hello</html>'],
                '<html>Hello</html>'
            ],
            'Tag syntax argument is string with string' => [
                '<f:if condition="true" then="{content} world"/>',
                ['content' => '<html>Hello</html>'],
                '<html>Hello</html>'
            ],
            'Tag syntax argument is array' => [
                '<f:if condition="true" then="{content}"/>',
                ['content' => ['<html>Hello</html>']],
                '<html>Hello</html>'
            ],
            'Tag syntax argument is array with string' => [
                '<f:if condition="true" then="{content} world"/>',
                ['content' => ['<html>Hello</html>']],
                'Array world'
            ],
            'Tag syntax children is string' => [
                '<f:if condition="true">{content}</f:if>',
                ['content' => '<html>Hello</html>'],
                '&lt;html&gt;Hello&lt;/html&gt;'
            ],
            'Tag syntax children is string with string' => [
                '<f:if condition="true">{content} world</f:if>',
                ['content' => '<html>Hello</html>'],
                '&lt;html&gt;Hello&lt;/html&gt; world'
            ],
            'Tag syntax children is array' => [
                '<f:if condition="true">{content}</f:if>',
                ['content' => ['<html>Hello</html>']],
                '<html>Hello</html>'
            ],
            'Tag syntax children is array with string' => [
                '<f:if condition="true">{content} world</f:if>',
                ['content' => ['<html>Hello</html>']],
                'Array world'
            ],
            'Inline syntax argument is string' => [
                '{f:if(condition: \'true\', then: content)}',
                ['content' => '<html>Hello</html>'],
                '<html>Hello</html>'
            ],
//            'Inline syntax argument is string in quotes' => [
//                '{f:if(condition: \'true\', then: \'{content}\')}',
//                ['content' => '<html>Hello</html>'],
//                '<html>Hello</html>'
//            ],
            'Inline syntax argument is array' => [
                '{f:if(condition: \'true\', then: content)}',
                ['content' => ['<html>Hello</html>']],
                '<html>Hello</html>'
            ],
            'Inline syntax argument is array in quotes' => [
                '{f:if(condition: \'true\', then: \'{content}\')}',
                ['content' => ['<html>Hello</html>']],
                '<html>Hello</html>'
            ],
            'Inline syntax children is string' => [
                '{content -> f:if(condition: \'true\')}',
                ['content' => '<html>Hello</html>'],
                '&lt;html&gt;Hello&lt;/html&gt;'
            ],
            'Inline syntax children is array' => [
                '{content -> f:if(condition: \'true\')}',
                ['content' => ['<html>Hello</html>']],
                '<html>Hello</html>'
            ],

            'Nested tag syntax children is string' => [
                '<f:if condition="true"><f:if condition="true">{content}</f:if></f:if>',
                ['content' => '<html>Hello</html>'],
                '&lt;html&gt;Hello&lt;/html&gt;'
            ],
            'Nested tag syntax children is array' => [
                '<f:if condition="true"><f:if condition="true">{content}</f:if></f:if>',
                ['content' => ['<html>Hello</html>']],
                '<html>Hello</html>'
            ],
            'Nested tag with inline syntax children is string' => [
                '<f:if condition="true">{content -> f:if(condition: \'true\')}</f:if>',
                ['content' => '<html>Hello</html>'],
                '&lt;html&gt;Hello&lt;/html&gt;'
            ],
            'Nested tag with inline syntax children is array' => [
                '<f:if condition="true">{content -> f:if(condition: \'true\')}</f:if>',
                ['content' => ['<html>Hello</html>']],
                '<html>Hello</html>'
            ],
            'Nested tag syntax children is array with string' => [
                '<f:if condition="true"><f:if condition="true">{content} world</f:if></f:if>',
                ['content' => ['<html>Hello</html>']],
                'Array world'
            ],
            'Nested inline syntax children is string' => [
                '{content -> f:if(condition: \'true\') -> f:if(condition: \'true\')}',
                ['content' => '<html>Hello</html>'],
                '&lt;html&gt;Hello&lt;/html&gt;'
            ],
            'Nested inline syntax children is array' => [
                '{content -> f:if(condition: \'true\') -> f:if(condition: \'true\')}',
                ['content' => ['<html>Hello</html>']],
                '<html>Hello</html>'
            ],

            'Tag syntax nested inline with argument is string' => [
                '<f:if condition="true" then="{content -> f:if(condition: \'true\')}"/>',
                ['content' => '<html>Hello</html>'],
                '<html>Hello</html>'
            ],
            'Tag syntax nested inline with argument is string with string' => [
                '<f:if condition="true" then="{content -> f:if(condition: \'true\')} world"/>',
                ['content' => '<html>Hello</html>'],
                '<html>Hello</html>'
            ],
            'Tag syntax nested inline with argument is array' => [
                '<f:if condition="true" then="{content -> f:if(condition: \'true\')}"/>',
                ['content' => ['<html>Hello</html>']],
                '<html>Hello</html>'
            ],
            'Tag syntax nested inline with argument is array in string' => [
                '<f:if condition="true" then="{content -> f:if(condition: \'true\')} world"/>',
                ['content' => ['<html>Hello</html>']],
                'Array world'
            ],

//            'inline syntax nested inline with argument is string' => [
//                '{f:if(condition: \'true\' then: \'{content -> f:if(condition: \\\'true\\\')}\')}',
//                ['content' => '<html>Hello</html>'],
//                '<html>Hello</html>'
//            ],
            'inline syntax nested inline with argument is string with string' => [
                '<f:if condition="true" then="{content -> f:if(condition: \'true\')} world"/>',
                ['content' => '<html>Hello</html>'],
                '<html>Hello</html>'
            ],
            'inline syntax nested inline with argument is array' => [
                '{f:if(condition: \'true\' then: \'{content -> f:if(condition: \\\'true\\\')}\')}',
                ['content' => ['<html>Hello</html>']],
                '<html>Hello</html>'
            ],
            'inline syntax nested inline with argument is array with string' => [
                '{f:if(condition: \'true\' then: \'{content -> f:if(condition: \\\'true\\\')} world\')}',
                ['content' => ['<html>Hello</html>']],
                'Array world'
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
     * @dataProvider viewHelperSyntaxDataProvider
     */
    public function differentSyntaxTest($viewHelperTemplate, array $vars, $expectedOutput, $format = 'html')
    {
        if (!in_array($format, array('html', 'json'), true)) {
            $format = 'html';
        }
        $view = new StandaloneView();
        $view->assignMultiple($vars);
        $view->setFormat($format);
        $viewHelperTemplate = '{namespace ft=Helhum\FluidTest\ViewHelpers}' . $viewHelperTemplate;
        $view->setTemplateSource($viewHelperTemplate);
        $this->assertContains($expectedOutput, $view->render());
    }
}
