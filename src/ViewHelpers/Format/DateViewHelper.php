<?php
namespace Mia3\FluidBundle\ViewHelper\Format;

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Tag based view helper.
 * Should be used as the base class for all view helpers which output simple tags, as it provides some
 * convenience methods to register default attributes, ...
 *
 * @api
 */
class DateViewHelper extends AbstractViewHelper {

    /**
     * Constructor
     *
     * @api
     */
    public function initializeArguments()
    {
        $this->registerArgument('date', 'mixed', 'Date to be formatted');
        $this->registerArgument('format', 'string', 'Format to apply to the date');
    }

    /**
     * @return string
     */
    public function render() {
        var_dump($this->arguments);
    }

}
