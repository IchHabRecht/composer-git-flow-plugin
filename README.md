# composer-git-flow-plugin

This plugin allows to use repositories using the Git Flow workflow (or any other branching model) with Composer.

## Installation

First you have to require the plugin. 

```bash
$ composer require ichhabrecht/composer-git-flow-plugin
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
dependencies. The new composer command `git-flow-update` makes sure only repositories with *dev-master* dependency
constraints are updated.

```bash
$ STABILITY=develop composer git-flow-update
```

On Windows systems the command looks like that:

```bash
$ SET STABILITY=develop && composer git-flow-update
```

If you want to checkout your repositories with the latest release branch you can simply set the stability to `release`.
The plugin searches for any available branch with the stability prefix with a fallback to master branch if no other 
suitable branch was found.
