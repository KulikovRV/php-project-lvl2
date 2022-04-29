# Files diff generator

- [![Maintainability](https://api.codeclimate.com/v1/badges/b9402a6639aeed5d824e/maintainability)](https://codeclimate.com/github/KulikovRV/php-project-lvl2/maintainability)
- [![Test Coverage](https://api.codeclimate.com/v1/badges/b9402a6639aeed5d824e/test_coverage)](https://codeclimate.com/github/KulikovRV/php-project-lvl2/test_coverage)
- [![Actions Status](https://github.com/KulikovRV/php-project-lvl2/workflows/hexlet-check/badge.svg)](https://github.com/KulikovRV/php-project-lvl2/actions)
- [![PHP CI](https://github.com/KulikovRV/php-project-lvl2/actions/workflows/workflow.yml/badge.svg)](https://github.com/KulikovRV/php-project-lvl2/actions/workflows/workflow.yml)

Diff generator is the second project of the "Php developer" Hexlet learning platform.
Is a program that determines the difference between two data structures

Utility features:
- Support for different input formats: yaml and json
- Report generation in the form of plain text, stylish and json

## Install
``` bash
$ composer require kulikov-rv/gendiff
```

## Demonstration reports
### Stylish(default)
``` bash
$ bin/gendiff pathToFile1 pathToFile2 
```
[![asciicast](https://asciinema.org/a/7IlAIF0MQZ72lVuBgAILIodpW.svg)](https://asciinema.org/a/7IlAIF0MQZ72lVuBgAILIodpW)
### Plain
``` bash
$ bin/gendiff --format plain pathToFile1 pathToFile2 
```
[![asciicast](https://asciinema.org/a/zQhubJe1owIcEgcGHZgUwi9Gj.svg)](https://asciinema.org/a/zQhubJe1owIcEgcGHZgUwi9Gj)

### Json
``` bash
$ bin/gendiff --format json pathToFile1 pathToFile2 
```
[![asciicast](https://asciinema.org/a/0BqxEqdOMTCNU4N4BMnCiT9sp.svg)](https://asciinema.org/a/0BqxEqdOMTCNU4N4BMnCiT9sp)

