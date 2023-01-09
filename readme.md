<p align="center">
    <img src="https://raw.githubusercontent.com/molbal/ai-phpdoc/main/art/code-example.png" width="800" alt="Code example">
    <p align="center">
        <a href="https://packagist.org/packages/molbal/ai-phpdoc"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/molbal/ai-phpdoc"></a>
        <a href="https://packagist.org/packages/molbal/ai-phpdoc"><img alt="Latest Version" src="https://img.shields.io/packagist/v/molbal/ai-phpdoc"></a>
        <a href="https://packagist.org/packages/molbal/ai-phpdoc"><img alt="License" src="https://img.shields.io/github/license/molbal/ai-phpdoc"></a>
    </p>
</p>

------

# <img src="https://raw.githubusercontent.com/molbal/ai-phpdoc/main/art/icon.png" width="40" alt="Cute icon. You like it!"> AI PHPDocs


AI PHPDocs is a tool that uses GPT-3 to automatically add missing PHPDoc comments to your PHP code.

Demo video: https://youtu.be/bu-fkRyLQaI

## Prerequisites

Before using AI PHPDocs, you will need to have an OpenAI API key set as an environment variable. 

```shell
export OPENAI_KEY=...
```

## Installation

To install AI PHPDocs, run the following command:


```shell
composer global require molbal/ai-phpdocs
```

## Usage

To add missing PHPDoc comments to a single file, use the following command:

```shell
aiphpdocs file  /path/to/file.php
```

To add missing PHPDoc comments to a directory of files, use the following command. By default it iterates through the current directory for all files, but does not go into subdirectories:

```shell
aiphpdocs dir
```


You may set the `--recursive` flag, or `-r` for short for it to go into subdirectories.

If you pass another variable (regardless of the recursive flag) it will treat it as another directory to sweep through instead of the working directory.

```shell
aiphpdocs directory -r /somewhere/else
```

## License

AI PHPDocs is licensed under the AGPL-3.0 license. See LICENSE for more information.
