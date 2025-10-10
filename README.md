# Data privacy bundle for OpenDXP
Add data privacy features (GDPR / DSGVO) to OpenDXP

Version 1.x can be used for OpenDXP 1.

## Features
* Does support multiple domains (using OpenDXP sites)
* Easy installation in OpenDXP projects (drop-in-solution)
* Admin interface to configure trackers and tools
* Frontend with cookie and tracking consent features

![Backend Interface](docs/img/localized_admin_interface.png)

## Dependencies
This bundle does include the [tarteaucitron.js](https://github.com/AmauriC/tarteaucitron.js) script in the `pringuin/DataprivacyBundle/Resources/public/js/tarteaucitron` folder.

## Installation

### Composer Installation
1. Add code below to your `composer.json` or install it via command line

```json
"require": {
"pringuin/opendxp-dataprivacybundle" : "^1.0"
}
```

### Add the bundle
Add the bundle to your Kernel in src/Kernel.php like this:
```php
public function registerBundlesToCollection(BundleCollection $collection): void
{
$collection->addBundle(new \pringuin\DataprivacyBundle\pringuinDataprivacyBundle(), 70);
}
```
You might also need to add it to your config/bundles.php:
```php
    \pringuin\DataprivacyBundle\pringuinDataprivacyBundle::class => ['all' => true],
```

### Install the assets
After you have installed the Dataprivacy Bundle via composer:
- Execute: `$ bin/console assets:install`

### Configure trackers, tools and services
The backend configuration should now be available. below the search in your OpenDXP admin backend.
You can set your tracking IDs (for example your UA-XXXXXXXX-X) there.
This will generate files in the folder `OPENDXP_INSTALLATION/config/pringuin_dataprivacy`.
The file for the default site will be `siteconfig_default.yml`
The next sites will follow the OpenDXP IDs and look like `siteconfig_1.yml`, `siteconfig_2.yml`.
You can also change these files directly using your favorite IDE. They can be archived using git.

### Installation into your template
To include the cookie consent into your frontend layout, you can use the following twig code. Simply insert it into your template (e.g. layout.html.twig) before the closing body Tag:
```twig
{{ render(controller('pringuin\\DataprivacyBundle\\Controller\\DefaultController::defaultAction', [])) }}
```
Hint: You can always override this template by implementing your own template in the folder `OPENDXP_INSTALLATION/app/Resources/pringuinDataprivacyBundle/views/default/default.html.twig`

![Default Frontend Interface](docs/img/frontend_locale_cookie_consent.png)

### Adaption of button colors
You can always use your own css to override the default css of tarteaucitron. If you'd like to keep the default css but have all buttons in the same color (as required by TTDSG for example), you can use the following CSS:
```css
html body #tarteaucitronRoot #tarteaucitron .tarteaucitronAllow, html body #tarteaucitronRoot #tarteaucitron .tarteaucitronDeny,
html body #tarteaucitronRoot #tarteaucitronAlertBig .tarteaucitronAllow, html body #tarteaucitronRoot #tarteaucitronAlertBig .tarteaucitronDeny, html body #tarteaucitronRoot #tarteaucitronAlertBig #tarteaucitronCloseAlert,
#tarteaucitronAlertBig #tarteaucitronCloseAlert, #tarteaucitronAlertBig #tarteaucitronPersonalize, .tarteaucitronCTAButton, #tarteaucitron #tarteaucitronPrivacyUrl, #tarteaucitron #tarteaucitronPrivacyUrlDialog, #tarteaucitronRoot .tarteaucitronDeny {
    background: black !important;
    color: white !important;
}
```

## Supported Tackers and Tools
This package does currently ship with the following trackers/tools integrations:
- Crazy Egg
- eKomi
- eTracker
- Facebook Pixel
- Google Analytics (supporting different implementations)
- Google Adwords Remarketing
- Google Fonts
- Google Tag Manager
- Hubspot
- Matomo (formerly Piwik)
- Mautic
- Zopim
- YouTube
- Vimeo
- Dailymotion

Note that you'll take care of the implementation of your video brick to make YouTube, Vimeo and Daily Motion work.

Sample code by [breakone](https://github.com/breakone):
```twig
{% set autoplay = autoplay.isChecked() ? true : false %}
{% set loop = loop.isChecked() ? true : false %}
{% set mute = muted.isChecked ? true: false %}

{% set video = opendxp_video('video') %}

{% if video.getVideoType == 'youtube' %}
  <div class="opendxp_tag_video opendxp_editable_video ">
    <div class="youtube_player" videoID="{{ video.id }}" width="{{ width }}" height="auto" theme="dark" rel="0" controls="1" showinfo="0" autoplay="{{ autoplay }}" mute="{{ mute }}" srcdoc="srcdoc" loop="{{ loop }}" loading="0"></div>
  </div>
{% elseif video.getVideoType == 'vimeo' %}
  <div class="opendxp_tag_video opendxp_editable_video ">
    <div class="vimeo_player" videoID="{{ video.id }}" width="{{ width }}" height="auto" autoplay="{{ autoplay }}" mute="{{ mute }}" loop="{{ loop }}"></div>
  </div>
{% elseif video.getVideoType == 'dailymotion' %}
  <div class="opendxp_tag_video opendxp_editable_video ">
    <div class="dailymotion_player" videoID="{{ video.id }}" width="{{ width }}" height="auto" showinfo="0" autoplay="{{ autoplay }}" embedType="video"></div>
  </div>
{% else %}
  {{ video|raw }}
{% endif %}
```


### Add your own tracker or tool consent
If a tracker or tool is missing, please check if the tool is integrated into [tarteaucitron.js](https://github.com/AmauriC/tarteaucitron.js) first.
You can do this using the source code or the official [tarteaucitron.js website](https://opt-out.ferank.eu/en/install/) using the installation guide.

If the tracker or tool **is not** included into the tarteaucitron.js package, please integrate it there first (they accept pull requests).
Alternatively you can directly include your custom service using this example code from tarteaucitron.js:
```js
tarteaucitron.services.mycustomservice = {
    "key": "mycustomservice",
    "type": "social|analytic|ads|video|support",
    "name": "MyCustomService",
    "needConsent": true,
    "cookies": ['cookie', 'cookie2'],
    "readmoreLink": "/custom_read_more", // If you want to change readmore link
    "js": function () {
        "use strict";
        // When user allow cookie
    },
    "fallback": function () {
        "use strict";
        // when use deny cookie
    }
};
(tarteaucitron.job = tarteaucitron.job || []).push('mycustomservice');
```

To get a tracker or tool which **is** included in the tarteaucitron.js package into this bundle, you'll have to do the following steps:
#### Add it to the default configuration
The default configuration is the base for all site specific configurations. Changes to the default configuration will also change your configuration files for the sites.
The default configuration is located at Resources/var/defaultconfiguration.yml

#### Add the view output
The necessary code for the templates can be found in the [tarteaucitron.js installation instructions](https://opt-out.ferank.eu/en/install/) easily.
Simply select Free manual installation and scroll down to the next step. Search for your service to get the installation code, typically looking like this:
```html
<script type="text/javascript">
    tarteaucitron.user.etracker = 'data-secure-code';
    (tarteaucitron.job = tarteaucitron.job || []).push('etracker');
</script>
```
Add it to the other codes in the `Resources/views/default/default.html.twig` file and replace dynamic parameters like the data-secude-code above with twig values as shown in the currently implemented trackers and tools.


#### Add a translation
Finally add a backend translation entry in the file `Resources/translations/admin.en.yml` and/or `admin.de.yml`
The key will be the same you used in the `defaultconfiguration.yml`.
You can also append a `_helptext` to the key to insert a custom helptext for editors.

#### Final step
Test your implementation and please create a pull request in this repository so everybody can now use your newly implemented service, tracker or tool.
Thanks!

## Updating

### Migrating from Pimcore
If you migrate from Pimcore to OpenDXP you should be able to simply switch from [dataprivacybundle for pimcore](https://github.com/pringuin/dataprivacybundle) 2.X to the OpenDXP dataprivacybundle 1.X version. Simply replace it in your composer.json

## Contributing
While we consider this bundle stable and ready for productive use, we'd be very happy if you'd support us and the whole OpenDXP community by improving this bundle with pull requests. This is our first public OpenDXP bundle so constructive feedback would be very welcome too!

## Copyright and license
Copyright: [PRinguin GbR](https://pringuin.de)  
For licensing details please visit [LICENSE.md](LICENSE.md)  
