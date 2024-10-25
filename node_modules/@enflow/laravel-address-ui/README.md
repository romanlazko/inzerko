# Laravel Address UI

[![Software License](https://img.shields.io/badge/license-ISC-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Latest Version on NPM](https://img.shields.io/npm/v/enflow/laravel-address-ui.svg?style=flat-square)](https://npmjs.com/package/@enflow/laravel-address-ui)
[![npm](https://img.shields.io/npm/dt/enflow/laravel-address-ui.svg?style=flat-square)](https://www.npmjs.com/package/@enflow/laravel-address-ui)

The frontend UI kit for usage with [enflow/laravel-address](https://github.com/enflow/laravel-address).

## Usage
You can install the package with yarn or npm:

```bash
yarn add @enflow/laravel-address-ui
// or
npm install @enflow/laravel-address-ui --save
```

You may instance the `Address` component. For instance:

```javascript
var Address = require('@enflow/laravel-address-ui');

window.onload = () => {
    document.querySelectorAll('[role=address]').forEach((field) => {
        new Address(field);
    });
};
```

You must include the CSS provides by this package in your build process as well. The easiest would be by using `postcss-import` and importing it in your core stylesheet. This can be modified based on your build process.

```css
@import "@enflow/laravel-address-ui";
```

Then, add the field to your template:

#### .blade.php
```blade
<div role="address">
    <input type="text" role="address-label" value="{{ old('address_label', optional($user->address)->label) }}" id="address_label" name="address_label" class="form-input">
    <input type="hidden" role="address-value" value="{{ old('address', $user->address ? json_encode($user->address->value()) : null) }}" id="address" name="address">
</div>
```

#### .twig
```twig
<div class="mt-1 rounded-md shadow-sm" role="address">
    <input type="text" role="address-label" value="{{ old('address_label', user.address ? user.address.label : null) }}" id="address_label" name="address_label" class="form-input">
    <input type="hidden" role="address-value" value="{{ old('address', user.address ? user.address.value()|json_encode : null) }}" id="address" name="address">
</div>
```

## Testing
``` bash
$ npm run start
```

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security
If you discover any security related issues, please email michel@enflow.nl instead of using the issue tracker.

## Credits
This package is forked from [mapbox/mapbox-gl-geocoder](https://github.com/mapbox/mapbox-gl-geocoder) and modified to work out of the box with `enflow/laravel-address`. They did an amazing job with the intital pacakge, and this work wouldn't be here without them. 

- [Michel Bardelmeijer](https://github.com/mbardelmeijer)
- [Mapbox & Contributers](https://github.com/mapbox/mapbox-gl-geocoder)
- [All Contributors](../../contributors)

## About Enflow
Enflow is a digital creative agency based in Alphen aan den Rijn, Netherlands. We specialize in developing web applications, mobile applications and websites. You can find more info [on our website](https://enflow.nl/en).

## License
The ISC License (ISC). Please see [License File](LICENSE.md) for more information.
