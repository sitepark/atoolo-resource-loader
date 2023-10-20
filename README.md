# Atoolo resource loader

The primary purpose of the resource loader is to load aggregated data from IES (Sitepark's content management system) and make it available in PHP.

By using different `ResourceLoaderProviders` different data sources and data formats can be supported.

Resources can be protected. Therefore also a `SecurityFilterChain` is provided, with which the access rights to the resource can be checked.

The resource would be specified via a location identifier the form

`scheme:path` or `scheme:id`.