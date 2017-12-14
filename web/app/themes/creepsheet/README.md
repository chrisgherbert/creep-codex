# Timber Theme

To get started with Timber Theme, follow the steps below:

### Installation

You need Gulp and Bower installed globally:

```sh
$ npm i -g gulp
$ npm i -g bower
```
From within the theme, grab the Gulp plugins (already included in a package.json):
```sh
$ npm i
```
Next, install the Bower dependencies via the command below. By default, the `bower_components` folder is located in `assets`.
```sh
$ bower install
```
Look for the `devvars.example.js` file to create your own `devvars.js` file. This allows you to use LiveReload during development.

To create a LiveReload server that watches you scripts and styles, use this command:
```sh
gulp serve --devvars ./devvars.js
```
If you would rather just pass in arguments for the LiveReload server, you can also do that:
```sh
gulp serve --port 3001 --proxy mysite.local
```
You *must* use one or the other for serve tasks to function properly.

Remember to add any new javascript paths or SASS paths in the `gulpvars.js` file that you would like included in the build process.

### Version
0.1

License
----

MIT


