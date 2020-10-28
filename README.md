tap-xero config generator
=========================

This is a small repo designed to help you create a development configuration for tap-xero. tap-xero is a command-line
tool but OAuth2 requires a running webserver to post requests back to.

Please limit your use of this to gaining development credentials for tap-xero or other commandline xero scripts, and do
**not** host this on a public URL.

Usage
-----

### Xero Developer Portal

 * Log into the Xero developer portal
 * Create a new OAuth2 app.
 * When creating the app, use https://tap-xero-config.lndo.site/login.php as the redirect URL
 * Create a new client secret
 * Copy `.env.sample` to `.env` and load the client ID and client secret in

### Lando

 * This project comes with a lando config to set up the site. Installer Docker and Lando, if you haven't already.

### Execute

 * Visit https://tap-xero-config.lndo.site/login.php
 * Log into Xero
 * Approve access to your Xero data
 * You will be redirected to a screen that dumps possible configuration, one for each organisation

### What if I don't want to install Lando?

If you already have your own PHP webserver running, it's fine to use that. Just be aware of the following:

 * Your webserver will need HTTPS.
 * You will need to specify a new redirect URL when creating the Xero Developer app.
 * The redirect-back URL is hardcoded in login.php, you will need to change that.
