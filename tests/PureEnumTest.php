<?php

use Cerbero\Enum\CasesCollection;
use Cerbero\Enum\PureEnum;

it('determines whether the enum is pure')
    ->expect(PureEnum::isPure())
    ->toBeTrue();

it('determines whether the enum is backed')
    ->expect(PureEnum::isBacked())
    ->toBeFalse();

it('retrieves all the names of the cases')
    ->expect(PureEnum::names())
    ->toBe(['one', 'two', 'three']);

it('retrieves all the values of the backed cases')
    ->expect(PureEnum::values())
    ->toBeEmpty();

it('retrieves all the keys of the cases')
    ->expect(PureEnum::keys('color'))
    ->toBe(['red', 'green', 'blue']);

it('throws a value error when requesting an invalid key', fn () => PureEnum::keys('invalid'))
    ->throws(ValueError::class, '"invalid" is not a valid key for enum "Cerbero\Enum\PureEnum"');

it('retrieves a collection with all the cases')
    ->expect(PureEnum::collect())
    ->toBeInstanceOf(CasesCollection::class)
    ->cases()
    ->toBe([PureEnum::one, PureEnum::two, PureEnum::three]);

it('retrieves all cases keyed by name')
    ->expect(PureEnum::casesByName())
    ->toBe(['one' => PureEnum::one, 'two' => PureEnum::two, 'three' => PureEnum::three]);

it('retrieves all cases keyed by value')
    ->expect(PureEnum::casesByValue())
    ->toBeEmpty();

it('retrieves all cases keyed by a custom key')
    ->expect(PureEnum::casesBy('color'))
    ->toBe(['red' => PureEnum::one, 'green' => PureEnum::two, 'blue' => PureEnum::three]);

it('retrieves all cases keyed by the result of a closure')
    ->expect(PureEnum::casesBy(fn (PureEnum $case) => $case->shape()))
    ->toBe(['triangle' => PureEnum::one, 'square' => PureEnum::two, 'circle' => PureEnum::three]);

it('retrieves a collection with the filtered cases')
    ->expect(PureEnum::filter(fn (UnitEnum $case) => $case->name !== 'three'))
    ->toBeInstanceOf(CasesCollection::class)
    ->cases()
    ->toBe([PureEnum::one, PureEnum::two]);

it('retrieves a collection of cases having the given names')
    ->expect(PureEnum::only('two', 'three'))
    ->toBeInstanceOf(CasesCollection::class)
    ->cases()
    ->toBe([PureEnum::two, PureEnum::three]);

it('retrieves a collection of cases not having the given names')
    ->expect(PureEnum::except('one', 'three'))
    ->toBeInstanceOf(CasesCollection::class)
    ->cases()
    ->toBe([PureEnum::two]);

it('retrieves a collection of backed cases having the given values')
    ->expect(PureEnum::onlyValues(2, 3))
    ->toBeInstanceOf(CasesCollection::class)
    ->cases()
    ->toBeEmpty();

it('retrieves a collection of backed cases not having the given values')
    ->expect(PureEnum::exceptValues(1, 3))
    ->toBeInstanceOf(CasesCollection::class)
    ->cases()
    ->toBeEmpty();

it('retrieves an array of values')
    ->expect(PureEnum::pluck())
    ->toBe(['one', 'two', 'three']);

it('retrieves an array of custom values')
    ->expect(PureEnum::pluck('color'))
    ->toBe(['red', 'green', 'blue']);

it('retrieves an associative array with custom keys and values')
    ->expect(PureEnum::pluck('color', 'shape'))
    ->toBe(['triangle' => 'red', 'square' => 'green', 'circle' => 'blue']);

it('retrieves an associative array with keys and values resolved from closures')
    ->expect(PureEnum::pluck(fn (PureEnum $case) => $case->name, fn (PureEnum $case) => $case->color()))
    ->toBe(['red' => 'one', 'green' => 'two', 'blue' => 'three']);

it('determines whether an enum has a target')
    ->expect(fn (mixed $target, bool $result) => PureEnum::has($target) === $result)
    ->toBeTrue()
    ->with([
        [PureEnum::one, true],
        [new stdClass(), false],
        ['one', true],
        ['four', false],
        [1, false],
        [4, false],
        ['1', false],
        ['4', false],
    ]);

it('determines whether an enum does not have a target')
    ->expect(fn (mixed $target, bool $result) => PureEnum::doesntHave($target) === $result)
    ->toBeTrue()
    ->with([
        [PureEnum::one, false],
        [new stdClass(), true],
        ['one', false],
        ['four', true],
        [1, true],
        [4, true],
        ['1', true],
        ['4', true],
    ]);

it('determines whether an enum case matches a target')
    ->expect(fn (mixed $target, bool $result) => PureEnum::one->is($target) === $result)
    ->toBeTrue()
    ->with([
        [PureEnum::one, true],
        [PureEnum::two, false],
        ['one', true],
        ['two', false],
        [1, false],
        [2, false],
        ['1', false],
        ['2', false],
    ]);

it('determines whether an enum case does not match a target')
    ->expect(fn (mixed $target, bool $result) => PureEnum::one->isNot($target) === $result)
    ->toBeTrue()
    ->with([
        [PureEnum::one, false],
        [PureEnum::two, true],
        ['one', false],
        ['two', true],
        [1, true],
        [2, true],
        ['1', true],
        ['2', true],
    ]);

it('determines whether an enum case matches some targets')
    ->expect(fn (mixed $targets, bool $result) => PureEnum::one->in($targets) === $result)
    ->toBeTrue()
    ->with([
        [[PureEnum::one, PureEnum::two], true],
        [[PureEnum::two, PureEnum::three], false],
        [['one', 'two'], true],
        [['two', 'three'], false],
        [[1, 2], false],
        [[2, 3], false],
        [['1', '2'], false],
        [['2', '3'], false],
    ]);

it('determines whether an enum case does not match any target')
    ->expect(fn (mixed $targets, bool $result) => PureEnum::one->notIn($targets) === $result)
    ->toBeTrue()
    ->with([
        [[PureEnum::one, PureEnum::two], false],
        [[PureEnum::two, PureEnum::three], true],
        [['one', 'two'], false],
        [['two', 'three'], true],
        [[1, 2], true],
        [[2, 3], true],
        [['1', '2'], true],
        [['2', '3'], true],
    ]);

it('retrieves a collection of cases sorted by name ascending')
    ->expect(PureEnum::sort())
    ->toBeInstanceOf(CasesCollection::class)
    ->cases()
    ->toBe([PureEnum::one, PureEnum::three, PureEnum::two]);

it('retrieves a collection of cases sorted by name descending')
    ->expect(PureEnum::sortDesc())
    ->toBeInstanceOf(CasesCollection::class)
    ->cases()
    ->toBe([PureEnum::two, PureEnum::three, PureEnum::one]);

it('retrieves a collection of cases sorted by value ascending')
    ->expect(PureEnum::sortByValue())
    ->toBeInstanceOf(CasesCollection::class)
    ->cases()
    ->toBeEmpty();

it('retrieves a collection of cases sorted by value descending')
    ->expect(PureEnum::sortDescByValue())
    ->toBeInstanceOf(CasesCollection::class)
    ->cases()
    ->toBeEmpty();

it('retrieves a collection of cases sorted by a custom value ascending')
    ->expect(PureEnum::sortBy('color'))
    ->toBeInstanceOf(CasesCollection::class)
    ->cases()
    ->toBe([PureEnum::three, PureEnum::two, PureEnum::one]);

it('retrieves a collection of cases sorted by a custom value descending')
    ->expect(PureEnum::sortDescBy('color'))
    ->toBeInstanceOf(CasesCollection::class)
    ->cases()
    ->toBe([PureEnum::one, PureEnum::two, PureEnum::three]);

it('retrieves a collection of cases sorted by the result of a closure ascending')
    ->expect(PureEnum::sortBy(fn (PureEnum $case) => $case->shape()))
    ->toBeInstanceOf(CasesCollection::class)
    ->cases()
    ->toBe([PureEnum::three, PureEnum::two, PureEnum::one]);

it('retrieves a collection of cases sorted by the result of a closure descending')
    ->expect(PureEnum::sortDescBy(fn (PureEnum $case) => $case->shape()))
    ->toBeInstanceOf(CasesCollection::class)
    ->cases()
    ->toBe([PureEnum::one, PureEnum::two, PureEnum::three]);

it('retrieves the count of cases')
    ->expect(PureEnum::count())
    ->toBe(3);

it('retrieves the case hydrated from a value')
    ->expect(fn (string $value, PureEnum $case) => PureEnum::from($value) === $case)
    ->toBeTrue()
    ->with([
        ['one', PureEnum::one],
        ['two', PureEnum::two],
        ['three', PureEnum::three],
    ]);

it('throws a value error when hydrating cases with an invalid value', fn () => PureEnum::from('1'))
    ->throws(ValueError::class, '"1" is not a valid name for enum "Cerbero\Enum\PureEnum"');

it('retrieves the case hydrated from a value or returns null')
    ->expect(fn (string $value, ?PureEnum $case) => PureEnum::tryFrom($value) === $case)
    ->toBeTrue()
    ->not->toThrow(ValueError::class)
    ->with([
        ['one', PureEnum::one],
        ['two', PureEnum::two],
        ['three', PureEnum::three],
        ['four', null],
    ]);

it('retrieves the case hydrated from a name')
    ->expect(fn (string $name, PureEnum $case) => PureEnum::fromName($name) === $case)
    ->toBeTrue()
    ->with([
        ['one', PureEnum::one],
        ['two', PureEnum::two],
        ['three', PureEnum::three],
    ]);

it('throws a value error when hydrating cases with an invalid name', fn () => PureEnum::fromName('1'))
    ->throws(ValueError::class, '"1" is not a valid name for enum "Cerbero\Enum\PureEnum"');

it('retrieves the case hydrated from a name or returns null')
    ->expect(fn (string $name, ?PureEnum $case) => PureEnum::tryFromName($name) === $case)
    ->toBeTrue()
    ->not->toThrow(ValueError::class)
    ->with([
        ['one', PureEnum::one],
        ['two', PureEnum::two],
        ['three', PureEnum::three],
        ['four', null],
    ]);

it('retrieves the case hydrated from a key')
    ->expect(fn (callable|string $key, mixed $value, PureEnum $case) => PureEnum::fromKey($key, $value) === $case)
    ->toBeTrue()
    ->with([
        ['color', 'red', PureEnum::one],
        ['name', 'three', PureEnum::three],
    ]);

it('retrieves all cases hydrated from a key')
    ->expect(PureEnum::fromKey('odd', true))
    ->toBeInstanceOf(CasesCollection::class)
    ->cases()
    ->toBe([PureEnum::one, PureEnum::three]);

it('retrieves the case hydrated from a key using a closure')
    ->expect(PureEnum::fromKey(fn (PureEnum $case) => $case->shape(), 'square'))
    ->toBe(PureEnum::two);

it('throws a value error when hydrating cases with an invalid key', fn () => PureEnum::fromKey('color', 'orange'))
    ->throws(ValueError::class, 'Invalid value for the key "color" for enum "Cerbero\Enum\PureEnum"');

it('retrieves the case hydrated from a key or returns null')
    ->expect(fn (callable|string $key, mixed $value, ?PureEnum $case) => PureEnum::tryFromKey($key, $value) === $case)
    ->toBeTrue()
    ->not->toThrow(ValueError::class)
    ->with([
        ['color', 'red', PureEnum::one],
        ['name', 'three', PureEnum::three],
        ['shape', 'rectangle', null],
    ]);

it('attempts to retrieve the case hydrated from a key using a closure')
    ->expect(PureEnum::tryFromKey(fn (PureEnum $case) => $case->shape(), 'square'))
    ->toBe(PureEnum::two);

it('retrieves the key of a case')
    ->expect(fn (string $key, mixed $value) => PureEnum::one->get($key) === $value)
    ->toBeTrue()
    ->with([
        ['name', 'one'],
        ['color', 'red'],
        ['shape', 'triangle'],
    ]);

it('retrieves the key of a case using a closure')
    ->expect(PureEnum::one->get(fn (PureEnum $case) => $case->color()))
    ->toBe('red');

it('throws a value error when attempting to retrieve an invalid key', fn () => PureEnum::one->get('invalid'))
    ->throws(ValueError::class, '"invalid" is not a valid key for enum "Cerbero\Enum\PureEnum"');

it('retrieves the case hydrated from a key dynamically')
    ->expect(PureEnum::fromColor('red'))
    ->toBe(PureEnum::one);

it('retrieves all cases hydrated from a key dynamically')
    ->expect(PureEnum::fromOdd())
    ->toBeInstanceOf(CasesCollection::class)
    ->cases()
    ->toBe([PureEnum::one, PureEnum::three]);

it('throws a value error when hydrating cases with an invalid key dynamically', fn () => PureEnum::fromOdd(123))
    ->throws(ValueError::class, 'Invalid value for the key "odd" for enum "Cerbero\Enum\PureEnum"');

it('attempts to retrieve the case hydrated from a key dynamically')
    ->expect(PureEnum::tryFromColor('red'))
    ->toBe(PureEnum::one)
    ->and(PureEnum::tryFromColor('violet'))
    ->toBeNull();

it('attempts to retrieve all cases hydrated from a key dynamically')
    ->expect(PureEnum::tryFromOdd())
    ->toBeInstanceOf(CasesCollection::class)
    ->cases()
    ->toBe([PureEnum::one, PureEnum::three]);
