<?php
namespace Mia3\FluidBundle;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Mia3\FluidBundle\Fluid\SymfonyTemplatePaths;
use AppBundle\AppBundle;

class FluidEngine implements EngineInterface {
	/**
	 * @var \TYPO3\Fluid\View\TemplateView
	 */
	protected $fluid;

	public function __construct(\TYPO3Fluid\Fluid\View\TemplateView $fluid, FileLocatorInterface $locator, ContainerInterface $container)
	{
		$this->fluid = $fluid;
		$this->locator = $locator;
		$this->container = $container;
		$fluid->setRenderingContext($container->get('fluid.renderingContext'));
		$this->fluid->getRenderingContext()->setTemplatePaths(new SymfonyTemplatePaths);
		$this->fluid->getRenderingContext()->setContainer($container);
	}

	/**
	 * @return \TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface
	 */
	public function getRenderingContext()
	{
		return $this->fluid->getRenderingContext();
	}

	/**
	 * Renders a view and returns a Response.
	 *
	 * @param string $view The view name
	 * @param array $parameters An array of parameters to pass to the view
	 * @param Response $response A Response instance
	 *
	 * @return Response A Response instance
	 *
	 * @throws \RuntimeException if the template cannot be rendered
	 */
	public function renderResponse($view, array $parameters = array(), Response $response = null) {
		if (null === $response) {
			$response = new Response();
		}
		$response->setContent($this->render($view, $parameters));
		return $response;
	}

	/**
	 * Renders a template.
	 *
	 * @param string|TemplateReferenceInterface $name A template name or a TemplateReferenceInterface instance
	 * @param array $parameters An array of parameters to pass to the template
	 *
	 * @return string The evaluated template as a string
	 *
	 * @throws \RuntimeException if the template cannot be rendered
	 *
	 * @api
	 */
	public function render($name, array $parameters = array()) {
		$this->fluid->getTemplatePaths()->setTemplatePathAndFilename($this->load($name));
		$this->fluid->assignMultiple($parameters);
		return $this->fluid->render();
	}

	/**
	 * Returns true if the template exists.
	 *
	 * @param string|TemplateReferenceInterface $name A template name or a TemplateReferenceInterface instance
	 *
	 * @return bool true if the template exists, false otherwise
	 *
	 * @throws \RuntimeException if the engine cannot handle the template name
	 *
	 * @api
	 */
	public function exists($name) {
		$this->load($name);
		return true;
	}

	/**
	 * Returns true if this class is able to render the given template.
	 *
	 * @param string|TemplateReferenceInterface $name A template name or a TemplateReferenceInterface instance
	 *
	 * @return bool true if this class supports the given template, false otherwise
	 *
	 * @api
	 */
	public function supports($name) {
		return $this->load($name) !== NULL;
	}

	protected function load($name)
	{
		$name = str_replace(':/', ':', preg_replace('#/{2,}#', '/', str_replace('\\', '/', $name)));

		if (false !== strpos($name, '..')) {
			throw new \RuntimeException(sprintf('Template name "%s" contains invalid characters.', $name));
		}

		if (!preg_match('/^([^:]*):([^:]*):(.+)\.([^\.]+)\.*([^\.]*)$/', $name, $matches)) {
			return false;
		}

		$bundle = $this->container->get('kernel')->getBundle($matches[1]);
		$bundlePaths = array($bundle->getPath());
		if ($bundle instanceof AppBundle) {
			$bundlePaths[] = $bundle->getPath();
		}
		$this->fluid->getRenderingContext()->getTemplatePaths()->fillDefaultsByBundlePaths($bundlePaths);
		return $this->fluid->getRenderingContext()->getTemplatePaths()->resolveTemplateFileForControllerAndActionAndFormat($matches[2], $matches[3]);
	}
}
