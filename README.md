<a href="https://bas.dev" target="_blank" rel="noopener">
	<img src="https://bas.dev/module/@bas/website/resource/image/logo.svg" alt="Logo" height="60" width="60" />
</a>

---

# TypeWriter
An advanced WordPress framework created by [Bas Milius](https://bas.dev).

### 👋🏽 About
TypeWriter is a custom-made WordPress framework that sits before WordPress. It has support
for all WordPress features and improves them when needed. The framework uses Columba for
adding advanced features, such as routing and a database orm, to your project.

### 🚀 Get started
TypeWriter is used to write custom themes for WordPress and should not be used with themes
that are not build with the framework. To develop a custom theme, follow the steps below.
- Clone the project. `git clone https://github.com/basmilius/typewriter`
- Install composer dependencies. `composer install`
- Install node.js dependencies. `yarn`
- Build a production bundle of our core files. `yarn build`
- Start developing. `yarn serve`

### 🔧 Config
You should create a `config.json` file in the root of your project. TypeWriter will load
that file and injects the properties in a generated `wp-config.php` file. There is also
a `config.sample.json` file that you can use to create your own. To generate keys and salts
you can use [this website by Roots.io](https://roots.io/salts.html) and use them instead
of the default ones provided by TypeWriter.
