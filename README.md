# jquery-wp-content

This repository provisions the `wp-content` directory of a WordPress installation, with the themes, plugins, and site options for jQuery Project websites.

## Getting started

### Production

To learn how the sites are managed in production, refer to
<https://github.com/jquery/infrastructure-puppet>.

### Staging

Changes pushed to the `main` branch of this repository are automatically
pulled onto the staging servers, which are publicly reachable via the
`stage.*` subdomains of the live sites. For example, the staging site
for <https://api.jquery.com> is served at <https://stage.api.jquery.com>

## Local

To help preview or debug changes locally during development,
you can use <https://github.com/jquery/jquery-wp-docker>.
