<?php
declare(strict_types=1);

namespace TypeWriter\Module;

/**
 * Class Module
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module
 * @since 1.0.0
 */
abstract class Module
{

	/**
	 * @var string
	 */
	private $className;

	/**
	 * @var string
	 */
	private $description;

	/**
	 * Module constructor.
	 *
	 * @param string $name
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	protected function __construct(string $name)
	{
		$this->className = get_called_class();
		$this->description = $name;
	}

	/**
	 * Invoked when TypeWriter is initializing.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 * @internal
	 */
	public function onInitialize(): void
	{
	}

	/**
	 * Invoked when TypeWriter is ran.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 * @internal
	 */
	public function onRun(): void
	{
	}

	/**
	 * Gets the module class name.
	 *
	 * @return string
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public final function getClassName(): string
	{
		return $this->className;
	}

	/**
	 * Gets the module description.
	 *
	 * @return string
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public final function getDescription(): string
	{
		return $this->description;
	}

}
