<?php

namespace Cerbero\Enum\Concerns;

use Cerbero\Enum\CasesCollection;
use ValueError;

/**
 * The trait to hydrate an enum.
 *
 */
trait Hydrates
{
    /**
     * Retrieve the case hydrated from the given name (called by pure enums only)
     *
     * @param string $name
     * @return static
     * @throws ValueError
     */
    public static function from(string $name): static
    {
        return static::fromName($name);
    }

    /**
     * Retrieve the case hydrated from the given name or NULL (called by pure enums only)
     *
     * @param string $name
     * @return static|null
     */
    public static function tryFrom(string $name): ?static
    {
        return static::tryFromName($name);
    }

    /**
     * Retrieve the case hydrated from the given name
     *
     * @param string $name
     * @return static
     * @throws ValueError
     */
    public static function fromName(string $name): static
    {
        if ($case = static::tryFromName($name)) {
            return $case;
        }

        throw new ValueError(sprintf('"%s" is not a valid name for enum "%s"', $name, static::class));
    }

    /**
     * Retrieve the case hydrated from the given name or NULL
     *
     * @param string $name
     * @return static|null
     */
    public static function tryFromName(string $name): ?static
    {
        foreach (static::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        return null;
    }

    /**
     * Retrieve cases hydrated from the given key
     *
     * @param callable|string $key
     * @param mixed $value
     * @return CasesCollection|static
     * @throws ValueError
     */
    public static function fromKey(callable|string $key, mixed $value): CasesCollection|static
    {
        if ($result = static::tryFromKey($key, $value)) {
            return $result;
        }

        $target = is_callable($key) ? 'given callable key' : "key \"$key\"";

        throw new ValueError(sprintf('Invalid value for the %s for enum "%s"', $target, static::class));
    }

    /**
     * Retrieve cases hydrated from the given key or NULL
     *
     * @param callable|string $key
     * @param mixed $value
     * @return CasesCollection|static|null
     */
    public static function tryFromKey(callable|string $key, mixed $value): CasesCollection|static|null
    {
        $cases = [];

        foreach (static::cases() as $case) {
            if ($case->get($key) === $value) {
                $cases[] = $case;
            }
        }

        return match (count($cases)) {
            0 => null,
            1 => $cases[0],
            default => new CasesCollection($cases),
        };
    }

    /**
     * Retrieve cases hydrated from keys dynamically
     *
     * @param string $name
     * @param array $parameters
     * @return CasesCollection|static|null
     */
    public static function __callStatic(string $name, array $parameters): CasesCollection|static|null
    {
        return match (0) {
            strpos($name, 'from') => static::fromKey(lcfirst(substr($name, 4)), $parameters[0] ?? true),
            strpos($name, 'tryFrom') => static::tryFromKey(lcfirst(substr($name, 7)), $parameters[0] ?? true),
        };
    }
}
