<?php
declare(strict_types=1);

namespace TypeWriter\Cappuccino\Node;

use Cappuccino\Compiler;
use Cappuccino\Node\Node;

/**
 * Class ControllerNode
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Cappuccino\Node
 * @since 1.0.0
 */
final class ControllerNode extends Node
{

	/**
	 * ControllerNode constructor.
	 *
	 * @param string $controller
	 * @param int    $lineno
	 *
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public function __construct(string $controller, int $lineno = 0)
	{
		parent::__construct([], [], $lineno, 'controller');

		$this->setAttribute('controller', $controller);
	}

	/**
	 * {@inheritdoc}
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function compile(Compiler $compiler): void
	{
		$compiler->addDebugInfo($this);

		$compiler->write('$controller = new ' . $this->getAttribute('controller') . '();' . PHP_EOL);
		$compiler->write('$context = array_merge($context, $controller->getContext());');
	}

}