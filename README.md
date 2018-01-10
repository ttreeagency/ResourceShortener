# Resource Link Shortner

This package allow to create "short link" for package static resources.

## Install

	composer require ttree/resourceshortener

## Configure

    Neos:
      Flow:
        http:
          chain:
            process:
              chain:
                'Ttree.ResourceShortener:Shortener':
                  componentOptions:
                    sites:
                      'website':
                        'manifest.webmanifest':
                          resource: 'resource://Ttree.Website/Public/Manifest.webmanifest'
                          headers:
                            'Content-Type': application/manifest+json
                            'Cache-Control': max-age=2592000
                        'sw.js':
                          resource: 'resource://Ttree.Website/Public/Scripts/ServiceWorker.js'
                          headers:
                            'Content-Type': text/javascript

In the configuration `website` is the root node name for the current site. With this configuration, `Manifest.webmanifest`
is available at `https://domain.com/manifest.webmanifest` and `ServiceWorker.js` at `https://domain.com/manifest.webmanifest`.

You can use the `headers` section to send custom HTTP headers.

**Warning**: For performance reason it's better to configure the rewritte in your HTTP Server (Apache, Nginx, Caddy, ...). But sometimes 
you don't have access to those configuration so this package can be useful. 
            
## Acknowledgments

Development sponsored by [ttree ltd - neos solution provider](http://ttree.ch).

We try our best to craft this package with a lots of love, we are open to sponsoring, support request, ... just contact us.

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
