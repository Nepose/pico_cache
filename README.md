# Pico Cache

Plugin adds simple function of server-side caching your website to plain HTML / XHTML (.html with correct header) type.

## Installation

To install the Pico Cache plugin, simply download the `PicoZCache.php` and put it in the plugins directory
`{picoInstallation}/plugins/`.

## Configuration
 
In config.yml you can change default caching settings:
```yaml
PicoZCache:
  enabled: true          # True/False if cache is enabled
  dir: cache/html/    # Directory where cache should be saved
  time: 604800           # Interval between caching (period from one to second cache) in seconds, here is 7 days = 60 * 60 * 24 * 7.
  xhtml_output: false    # If true, XHTML Content-Type header will be sent when loading cache page
```

## Cache clearing

To *clear the cache*, remove the files from the cache folder, or delete the whole cache folder.

## Common Pitfalls

+ Make sure the directory in which the cache directory shall be created automatically has the appropriate permissions.
+ Make sure the cache directory, if created manually, has the right permission for the server to read and write files to.
+ If your site uses multiple protocols, set the *base_url* parameter in your *config.yml* to be protocol independent, like `base_url: '//example.com';`

## Difference between this plugin and Twig caching function

Pico CMS offers for you function of Twig caching, which caches Twig templates as plain PHP files. Of course this omits stage of Twig parsing, but such cache still requires Pico to enable all other dependencies, libraries, plugins and do Markdown parsing.

On the other hand comes my plugin, which does caching entire page to HTML files. This omits both parsing by Twig and Parsedown and additionally lets Pico stop doing its job at half of work, due to loading HTML file immediately when URL address is known. The plugin just saves HTML source of every visited page at first time, so when next user will visit such page, its HTML source will be shown instead running parsers return.

## Credits

The plugin was mostly done by [glumb](https://github.com/glumb/pico_cache), but for Pico 0.8. I've modified it to be compatible with Pico 2.x logic.
