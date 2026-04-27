<div align="center">
  <p>This plugin was made and is maintained by ALPIN11 New Media GmbH.</p>
  <img src="https://github.com/alpin11.png" width="100px" alt="Albuquerque, New Mexico" />&nbsp;&nbsp;
  <img src="https://github.com/vendure-ecommerce.png" width="100px" alt="Albuquerque, New Mexico" />
</div>

# Opendxp Vendure bridge bundle

This bridge provide basic data structure from pimcore based projects to vendure applications. It allows you to export data and synchronize
products, variants and collections.

## Installation

- add bundle repository to composer.json
```json
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:alpin11/pimcore-vendure-bridge-bundle.git"
    }
],
```
- install bundle `composer require alpin11/pimcore-vendure-bridge-bundle`
- register bundle in `bundles.php`

```php
\OpendxpVendureBridgeBundle\OpendxpVendureBridgeBundle::class => ['all' => true]
```
- add env variable `PIMCORE_VENDURE_SECURITY_API_KEY` for token auth - will be checked on each data access
- in config.yaml necessary to register config from bundle
```yaml
imports:
- { resource: '@OpendxpVendureBridgeBundle/Resources/config/config.yaml'}
```
- in routes.yaml necessary to register routes from bundle
```yaml
OpendxpVendureBridgeBundle:
    resource: '@OpendxpVendureBridgeBundle/Resources/config/routes.yaml'
```

## Webhook flow
Direct HTTP webhooks to Vendure are sent by `App\EventSubscriber\AbstractWebhookSubscriber` (in the host app, not this bundle). Vendure receives the webhook and pulls full data via the REST endpoints in this bundle (see `Controller/Rest/`). Data objects must implement `\OpendxpVendureBridgeBundle\Model\OpendxpVendureInterface`.

The previous AMQP/RabbitMQ pipeline was removed (April 2026) — there was no consumer.

## Custom serializer handlers
In bundle is prepared few custom handlers to process data from dataojects.

- localization
    - localizationNameType
    - localizationDescriptionType
    - localizationSlugType
- parent
    - parentSameInstanceType
    - variantParentType
- price
    - listPriceType

## Usage
Queue contain only ID of object that was updated/deleted. Delete is real delete of object so should be deleted in vendure too.
Update state contain only ID too but it is necessary then to call API endpoint to get dataobject data (serialized by serializer to specific structure)

Endpoit example: `http://skeleton-pimcorex-debug.local-127-0-0-1.nip.io/api/pimcore-vendure-bridge/?id=2`
will return
```json
"{"pimcore_id":"4","pimcore_parent_id":3,"name":{"en":["subc1"],"de":["subc1"]},"description":{"en":["cccc"],"de":["cccc"]},"slug":{"en":["dddd"],"de":["dddd"]},"customFields":[]}"
```
NOTICE: necessary to have in .env correct `PIMCORE_VENDURE_SECURITY_API_KEY` property + in request header that api key as `X-API-Key`

## Command
`php bin/console pimcore-vendure:data:generate -c 5` - will generate 5 dev products with prices and all necessary parts


## Contributors

This plugin was developed by the following contributors:

<table>
  <tbody>
    <tr>
      <td style="text-align: center;"><a href="https://github.com/grolmus"><img src="https://github.com/grolmus.png" width="100px;" alt=""/><br /><sub><b>Martin Grolmus</b></sub></a><br /></td>
      <td style="text-align: center;"><a href="https://github.com/elauser"><img src="https://github.com/elauser.png" width="100px;" alt=""/><br /><sub><b>Matthias Oberleitner</b></sub></a><br /></td>
    </tr>
  </tbody>
</table>
