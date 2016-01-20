<?php
namespace Helhum\FluidTest\Tests\Functional;

use TYPO3\CMS\Core\Tests\FunctionalTestCase;
use TYPO3\CMS\Fluid\View\StandaloneView;

class DirectRenderingTest extends FunctionalTestCase
{
    public function viewHelperTemplateSourcesDataProvider()
    {
        return [
            'EscapingInterceptorEnabled: Tag syntax with children, properly encodes variable value' =>
            [
                '<ft:escapingInterceptorEnabled>{settings.test}</ft:escapingInterceptorEnabled>',
                '&lt;strong&gt;Bla&lt;/strong&gt;'
            ],
            'EscapingInterceptorEnabled: Inline syntax with children, properly encodes variable value' =>
            [
                '{settings.test -> ft:escapingInterceptorEnabled()}',
                '&lt;strong&gt;Bla&lt;/strong&gt;'
            ],
            'EscapingInterceptorEnabled: Tag syntax with argument, does not encode variable value' =>
            [
                '<ft:escapingInterceptorEnabled content="{settings.test}" />',
                '<strong>Bla</strong>'
            ],
            'EscapingInterceptorEnabled: Inline syntax with argument, does not encode variable value' =>
            [
                '{ft:escapingInterceptorEnabled(content: settings.test)}',
                '<strong>Bla</strong>'
            ],
            'EscapingInterceptorEnabled: Inline syntax with string, does not encode string value' =>
            [
                '{ft:escapingInterceptorEnabled(content: \'<strong>Bla</strong>\')}',
                '<strong>Bla</strong>'
            ],
            'EscapingInterceptorEnabled: Inline syntax with argument in quotes, does encode variable value (encoded before passed to VH)' =>
            [
                '{ft:escapingInterceptorEnabled(content: \'{settings.test}\')}',
                '&lt;strong&gt;Bla&lt;/strong&gt;'
            ],
            'EscapingInterceptorEnabled: Tag syntax with nested inline syntax and children rendering, does not encode variable value' =>
            [
                '<ft:escapingInterceptorEnabled content="{settings.test -> ft:escapingInterceptorEnabled()}" />',
                '<strong>Bla</strong>'
            ],
            'EscapingInterceptorEnabled: Tag syntax with nested inline syntax and argument in inline, does not encode variable value' =>
            [
                '<ft:escapingInterceptorEnabled content="{ft:escapingInterceptorEnabled(content: settings.test)}" />',
                '<strong>Bla</strong>'
            ],


            'EscapingInterceptorDisabled: Tag syntax with children, properly encodes variable value' =>
            [
                '<ft:escapingInterceptorDisabled>{settings.test}</ft:escapingInterceptorDisabled>',
                '<strong>Bla</strong>'
            ],
            'EscapingInterceptorDisabled: Inline syntax with children, properly encodes variable value' =>
            [
                '{settings.test -> ft:escapingInterceptorDisabled()}',
                '<strong>Bla</strong>'
            ],
            'EscapingInterceptorDisabled: Tag syntax with argument, does not encode variable value' =>
            [
                '<ft:escapingInterceptorDisabled content="{settings.test}" />',
                '<strong>Bla</strong>'
            ],
            'EscapingInterceptorDisabled: Inline syntax with argument, does not encode variable value' =>
            [
                '{ft:escapingInterceptorDisabled(content: settings.test)}',
                '<strong>Bla</strong>'
            ],
            'EscapingInterceptorDisabled: Inline syntax with string, does not encode string value' =>
            [
                '{ft:escapingInterceptorDisabled(content: \'<strong>Bla</strong>\')}',
                '<strong>Bla</strong>'
            ],
            'EscapingInterceptorDisabled: Inline syntax with argument in quotes, does encode variable value (encoded before passed to VH)' =>
            [
                '{ft:escapingInterceptorDisabled(content: \'{settings.test}\')}',
                '&lt;strong&gt;Bla&lt;/strong&gt;'
            ],
            'EscapingInterceptorDisabled: Tag syntax with nested inline syntax and children rendering, does not encode variable value' =>
            [
                '<ft:escapingInterceptorDisabled content="{settings.test -> ft:escapingInterceptorDisabled()}" />',
                '<strong>Bla</strong>'
            ],
            'EscapingInterceptorDisabled: Tag syntax with nested inline syntax and argument in inline, does not encode variable value' =>
            [
                '<ft:escapingInterceptorDisabled content="{ft:escapingInterceptorDisabled(content: settings.test)}" />',
                '<strong>Bla</strong>'
            ],
        ];
    }

    /**
     * @param string $viewHelperTemplate
     * @param string $expectedOutput
     *
     * @test
     * @dataProvider viewHelperTemplateSourcesDataProvider
     */
    public function renderingTest($viewHelperTemplate, $expectedOutput)
    {
        $view = new StandaloneView();
        $view->assign('settings', ['test' => '<strong>Bla</strong>']);
        $templateString = "{namespace ft=Helhum\\FluidTest\\ViewHelpers}";
        $templateString .= $viewHelperTemplate;
        $view->setTemplateSource($templateString);

        $this->assertSame($expectedOutput, $view->render());
    }
}