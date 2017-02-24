<?php
namespace Helhum\FluidTest\ViewHelpers;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

class VarExportViewHelper extends AbstractViewHelper
{
    protected $escapeChildren = true;
    protected $escapeOutput = false;

    /**
     * @param string $content
     * @return string
     */
    public function render($content = null)
    {
        if ($content === null) {
            $content = $this->renderChildren();
        }
        return var_export($content, true);
    }
}
