# CHANGELOG

## [8.0.0] - 2023-11-14

### Added

* Added existing string methods to interface `RepresentsStringType`:
  * `trim`
  * `replace`
  * `substring`
  * `toLowerCase`
  * `toUpperCase`
  * `capitalizeFirst`
  * `deCapitalizeFirst`
  * `toKebabCase`
  * `toSnakeCase`
  * `toUpperCamelCase`
  * `toLowerCamelCase`
  * `split`
  * `splitRaw`
  * `matchRegularExpression`
  * `getLength`
  * `isEmpty`
  * `contains`
* Added new methods to interface `RepresentsStringType` and trait `RepresentingStringType`
  * `containsOneOf`
  * `isOneOf`

---

[8.0.0]: https://github.com/fortuneglobe/types/compare/7.1.0...8.0.0

## [7.1.0] - 2023-10-25

### Changed

* Add overrideable method `transform` to 
  * `AbstractArrayType`
  * `AbstractFloatType`
  * `AbstractFloatTypeArray`
  * `AbstractIntType`
  * `AbstractIntTypeArray`
  * `AbstractStringType`
  * `AbstractStringTypeArray`

---

[7.1.0]: https://github.com/fortuneglobe/types/compare/7.0.2...7.1.0

## [7.0.2] - 2023-09-18

### Fixed

* Add missing type hint for `fromArrayType`

---

[7.0.2]: https://github.com/fortuneglobe/types/compare/7.0.1...7.0.2

## [7.0.1] - 2023-07-08

### Fixed

* Comparison methods of date types

---

[7.0.1]: https://github.com/fortuneglobe/types/compare/7.0.0...7.0.1

## [7.0.0] - 2023-07-07

### Added

* New methods to `RepresentsDateType` and it's implementations
  * `sub`
  * `add`
  * `isLessThan`
  * `isGreaterThan`
  * `isGreaterThanOrEqual`
  * `isLessThanOrEqual`
  * `hasExpired`

### Changed

* json_serialize will now format date time to more common format `Y-m-d H:i:s` (without microseconds) instead of `Y-m-d H:i:s.u` (with microseconds)

---

[7.0.0]: https://github.com/fortuneglobe/types/compare/6.0.1...7.0.0

## [6.0.1] - 2023-15-03

### Fixed

* Change return type of method `generate` of `Uuid4` to static

---

[6.0.1]: https://github.com/fortuneglobe/types/compare/6.0.0...6.0.1

## [6.0.0] - 2023-24-01

### Added

* `\JsonSerializable` to all interfaces 
* Implementations for `\JsonSerializable`

### Changed

* Uuid4 now extends `AbstractStringType` 

### Deleted

* `RepresentingUuid4` is obsolete now. Instead, extend from `Uuid4` now.

---

[6.0.0]: https://github.com/fortuneglobe/types/compare/5.4.0...6.0.0

## [5.4.0] - 2023-24-01

### Added

* Helper class `TypesToArrayHelper`: Converts array of types to array of primitive types 

### Changed

* Remove method `__toString()` from `RepresentsStringType` and extend `\Stringable` instead (no BC)
* Add `\Stringable` as valid argument type to methods `equalsValue` and `replace` of `AbstractStringType`:

---

[5.4.0]: https://github.com/fortuneglobe/types/compare/5.3.0...5.4.0

## [5.3.0] - 2023-20-01

### Added

* Add new methods to RepresentingStringType and so also to AbstractStringType:
  * `getLength`
  * `contains`
  * `split`
  * `splitRaw`
  * `matchRegularExpression`
* Add more new methods to AbstractStringType:
  * `trim`
  * `replace`
  * `substring`
  * `toLowerCase`
  * `toUpperCase`
  * `capitalizeFirst`
  * `deCapitalizeFirst`
  * `toKebabCase`
  * `toSnakeCase`
  * `toUpperCamelCase`
  * `toLowerCamelCase`

---

[5.3.0]: https://github.com/fortuneglobe/types/compare/5.2.0...5.3.0

## [5.2.0] - 2022-11-25

### Changed

* Add interface `RepresentsStringType` to `Uuid4` and implement methods

---

[5.2.0]: https://github.com/fortuneglobe/types/compare/5.1.0...5.2.0

## [5.1.0] - 2022-11-23

### Changed

* `RepresentingArrayType` returns now null if offset is missing instead of throwing an exception
* Now you can use also `RepresentsStringType` for `fromString` method of `RepresentingUuid4`

---

[5.1.0]: https://github.com/fortuneglobe/types/compare/5.0.0...5.1.0

## [5.0.0] - 2022-11-01

### Added

* Methods to `RepresentsFloatType`
    * `toString`,
    * `add`,
    * `subtract`,
    * `multiply`,
    * `divide`,
    * `isZero`,
    * `isPositive`,
    * `isNegative`,
    * `isPositiveOrZero`,
    * `isNegativeOrZero`

* Methods to `RepresentsIntType`
    * `toFloat`,
    * `toBool`,
    * `isZero`,
    * `isPositive`,
    * `isNegative`,
    * `isPositiveOrZero`,
    * `isNegativeOrZero`

* Methods to `RepresentsStringType`
    * `toFloat`,
    * `toInt`,

### Changed

* Package for PHP >= 8.1
* Now you can use also corresponding primitive data types for calculation and checking equality of values

---

[5.0.0]: https://github.com/fortuneglobe/types/compare/4.0.0...5.0.0

## [4.0.0] - 2022-09-30

### Changed

* Package for PHP >= 8.0

---

[4.0.0]: https://github.com/fortuneglobe/types/compare/3.1.0...4.0.0

## [3.1.0] - 2022-09-07

### Added

* Abstract classes for lists of types
    * `AbstractFloatTypeArray`
    * `AbstractIntTypeArray`
    * `AbstractStringTypeArray`
* Traits for lists of types
    * `RepresentingFloatTypeArray`
    * `RepresentingIntTypeArray`
    * `RepresentingStringTypeArray`
* Ready-To-Use implementations for lists of types
    * `FloatTypeArray`
    * `IntTypeArray`
    * `StringTypeArray`

### Removed

* Static function `fromJson` from interface `RepresentsArrayType` because it didn't make sense

---

[3.1.0]: https://github.com/fortuneglobe/types/compare/3.0.0...3.1.0

## [3.0.0] - 2021-12-22

### Changed

* `isValid` is static now

---

[3.0.0]: https://github.com/fortuneglobe/types/compare/2.1.0...3.0.0

## [2.1.0] - 2021-12-07

### Added

* New instantiation method `fromTimestamp` to `AbstractDateType`

---

[2.1.0]: https://github.com/fortuneglobe/types/compare/2.0.0...2.1.0

## [2.0.0] - 2021-08-05

It's a rework. All previous classes were removed or replaced.

---

[2.0.0]: https://github.com/fortuneglobe/types/compare/1.0.0...2.0.0

## [1.0.0] - 2017-11-13

### Changed

* All base types prefixed with `Abstract`
* Renamed `UuidType` to `AbstractUuid4Type` to clarify UUID version
* All base types define an abstract `guardValueIsValid()` method. (Except `AbstractUuid4Type`)

## [0.9.1] - 2017-10-30

### Fixed

* Composer package name and description - [#2]

## [0.9.0] - 2017-10-26

* Initial release

[1.0.0]: https://github.com/fortuneglobe/types/compare/v0.9.1...v1.0.0

[0.9.1]: https://github.com/fortuneglobe/types/compare/v0.9.0...v0.9.1

[0.9.0]: https://github.com/fortuneglobe/types/tags/v0.9.0

[#2]: https://github.com/fortuneglobe/types/issues/2
