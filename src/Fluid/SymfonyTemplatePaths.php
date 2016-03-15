<?php
namespace Mia3\FluidBundle\Fluid;

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */
use TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException;

/**
 * Template Paths Holder
 *
 * Class used to hold and resolve template files
 * and paths in multiple supported ways.
 *
 * The purpose of this class is to homogenise the
 * API that is used when working with template
 * paths coming from TypoScript, as well as serve
 * as a way to quickly generate default template-,
 * layout- and partial root paths by package.
 *
 * The constructor accepts two different types of
 * input - anything not of those types is silently
 * ignored:
 *
 * - a `string` input is assumed a package name
 *   and will call the `fillDefaultsByPackageName`
 *   value filling method.
 * - an `array` input is assumed a TypoScript-style
 *   array of root paths in one or more of the
 *   supported structures and will call the
 *   `fillFromTypoScriptArray` method.
 *
 * Either method can also be called after instance
 * is created, but both will overwrite any paths
 * you have previously configured.
 */
class SymfonyTemplatePaths extends \TYPO3Fluid\Fluid\View\TemplatePaths {

	const DEFAULT_TEMPLATES_DIRECTORY = '/Resources/Templates/';
	const DEFAULT_LAYOUTS_DIRECTORY = '/Resources/Layouts/';
	const DEFAULT_PARTIALS_DIRECTORY = '/Resources/Partials/';

	/**
	 * @param array $bundlePaths
	 * @return void
	 * @api
	 */
	public function fillDefaultsByBundlePaths($bundlePaths) {
		$templateRootPaths = array();
		$layoutRootPaths = array();
		$partialRootPaths = array();
		foreach ($bundlePaths as $path) {
			$templateRootPaths[] = $path . self::DEFAULT_TEMPLATES_DIRECTORY;
			$layoutRootPaths[] = $path . self::DEFAULT_LAYOUTS_DIRECTORY;
			$partialRootPaths[] = $path . self::DEFAULT_PARTIALS_DIRECTORY;
		}
		$this->setTemplateRootPaths($templateRootPaths);
		$this->setLayoutRootPaths($layoutRootPaths);
		$this->setPartialRootPaths($partialRootPaths);
	}

}
