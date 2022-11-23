# CHANGELOG

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
