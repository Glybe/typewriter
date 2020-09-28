<?php
declare(strict_types=1);

namespace TypeWriter\Facade;

use Closure;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionNamedType;
use TypeWriter\Error\ViolationException;
use function call_user_func_array;
use function floatval;
use function gettype;
use function intval;
use function is_array;
use function is_numeric;
use function sprintf;
use function str_replace;

/**
 * Class AjaxHandler
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Facade
 * @since 1.0.0
 */
final class AjaxHandler
{

    private string $action;
    private Closure $callback;
    private int $priority;

    /**
     * AjaxHandler constructor.
     *
     * @param string $action
     * @param callable $callback
     * @param int $priority
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    protected function __construct(string $action, callable $callback, int $priority = 10)
    {
        $this->action = $action;
        $this->callback = Closure::fromCallable($callback);
        $this->priority = $priority;
    }

    /**
     * Invoked when the ajax action is called.
     *
     * @throws ReflectionException
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onInvoked(): void
    {
        $args = [];
        $arguments = $_REQUEST;

        $method = is_array($this->callback) ? new ReflectionMethod($this->callback[0], $this->callback[1]) : new ReflectionFunction($this->callback);
        $parameters = $method->getParameters();

        foreach ($parameters as $parameter) {
            $value = $arguments[$parameter->getName()] ?? null;

            if ($value === 'NULL')
                $value = null;

            if ($value === 'true')
                $value = true;

            if ($value === 'false')
                $value = false;

            if (is_numeric($value)) {
                $value = floatval($value);

                if (intval($value) == $value)
                    $value = intval($value);
            }

            if ($value === null && $parameter->isDefaultValueAvailable())
                $value = $parameter->getDefaultValue();

            if (!$parameter->allowsNull() && $value === null)
                throw new ViolationException(sprintf('[ajax: %s] Parameter "%s" does not accept NULL.', $this->action, $parameter->getName()), ViolationException::ERR_INVALID_PARAMETER);

            /** @var ReflectionNamedType $type */
            $type = $parameter->getType();

            $valueType = $this->normalizeType(gettype($value));
            $parameterType = $this->normalizeType($type->getName());

            if ($valueType !== 'NULL' && $valueType !== $parameterType)
                throw new ViolationException(sprintf('[ajax: %s] Parameter "%s" has to be an instance of %s, %s given.', $this->action, $parameter->getName(), $parameterType, $valueType), ViolationException::ERR_INVALID_PARAMETER);

            $args[] = $value;
        }

        call_user_func_array($this->callback, $args);
    }

    /**
     * Registers the ajax handler.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function register(): void
    {
        Hooks::action('wp_ajax_' . $this->action, [$this, 'onInvoked'], $this->priority);
    }

    /**
     * Normalizes the given type.
     *
     * @param string $type
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    private function normalizeType(string $type): string
    {
        $type = str_replace('boolean', 'bool', $type);
        $type = str_replace('integer', 'int', $type);

        return $type;
    }

    /**
     * Listens for an ajax action.
     *
     * @param string $action
     * @param callable $callback
     * @param int $priority
     *
     * @return static
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function listen(string $action, callable $callback, int $priority = 10): self
    {
        $handler = new self($action, $callback, $priority);
        $handler->register();

        return $handler;
    }

}
