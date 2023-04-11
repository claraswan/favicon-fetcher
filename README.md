# Favicon Fetcher

Simple class that is able to grab a logo favicon from a given URL's domain.

"Tries to download the favicon for the (second level) domain name of a given URL.
If available, the favicon mentioned the HTML document of that domain is used.
Otherwise the script tries to guess the typical locations of the favicion 
document."

## Usage:

If successful, output is the path of the downloaded favicon.

If unsuccessful, output is an empty string. Thus, you can wrap it in a conditional that checks for an empty string, returning the path to your application's default favicon image in this case.

```
$faviconFetcher = new FaviconFetcher\FaviconFetcher();

$faviconPath = $faviconFetcher->getFavicon();

if (empty($faviconPath)) {
    $faviconPath .= '/path/to/default/favicon.ico';
}
```