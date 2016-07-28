# composer-git-flow-plugin

This plugin allows to use repositories using the Git Flow workflow (or any other branching model) with Composer.

## Installation

First you have to require the plugin. Make sure to run **either** `composer require` **or** `composer install` before you update to the 
needed branch.

```bash
$ composer require ichhabrecht/composer-git-flow-plugin
```

```bash
$ composer install
```

## Usage

To define the repositories for which the plugin should change the used branch, it is necessary to require those with
`dev-master` constraint.

```JSON
{
  "repositories": [
    {
      "type": "vcs",
      "url": "[path-to-your-repository]"
    }
  ],
  "require": {
    "vendor/package": "dev-master"
  }
}
```

To select the branch your repositories should use, you have to set an environment variable `STABILITY` and update your 
dependencies.

```bash
$ STABILITY=develop composer update
```

On Windows systems the command looks like that:

```bash
$ SET STABILITY=develop && composer update
```

If you want to checkout your repositories with the latest release branch you can simply set the stability to `release`.
The plugin searches for any available branch with the stability prefix with a fallback to master branch if no other 
suitable branch was found.
