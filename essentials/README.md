### Plugin Installation Via CLI


#### Step  1: Run plugin installation command
```bash
php artisan plugin:install GodSpeed.Essentials
```

#### Step 2: Install plugin dependencies
```bash
cd plugins/godspeed/essentials
```

```bash
# grant execute permission for installation bash scripts
chmod u+x install_plugin.sh
```

#### Step 3: Run composer command in OctoberCMS project directory
```bash
user@server october: composer  update --lock
```

#### Step 4: Once plugin get installed  "GodSpeed Essentials" menu item should presentin the backend admin navigation bar


### Configure YouTube Data API Key

Since our plugin video manager rely on the video metadata given by the YouTube data API,
an API Key is required to allow metadata can be fetched from YouTube DataAPI.

To obtain the API Key please navigate to [Google Developer Console](https://console.developers.google.com/) create your project
enable YouTube Data API in the [API Library](https://console.developers.google.com/apis/library), once enable create an API credentials
in [API and Services Credentials](https://console.developers.google.com/apis/credentials) and place the credentials in the GodSpeed Essentials
Settings Page.

For production environment, to ensure the API Key security please enable the application restrictions and only bind your OctoberCMS project domain.


### Lazyload Image Twig Filter

Our plugin do provide lazyload twig filter, which generate a small size images either from a file or media library by
using image intervention library which can  increase the webpage loading performance on frontend.

```twig
{# For Media #}

{{ my_image  | media | lazyload(480, null)}}
```

```twig
{# For Files #}

{{ my_image.getPath() | lazyload(480, null)}}
```

### Default component markup

By default, all plugin components markup design is based on
GodSpeed Flametree Theme.
