# MediaWiki skin for OSMF

A MediaWiki skin for the OSMF website based on the standard Vector skin of
MediaWiki. The skin aims to be modern, clean, good-looking and fully
responsive. Care has been taken to preserve as much original functionality of
Vector as possible.

## Installation

Place the repository in the `skins` folder of your MediaWiki installation and
name the directory `OSMF`. Then add the following to your `LocalSettings.php`:

```
# Enabled skins.
# The following skins were automatically enabled:
...
wfLoadSkin( 'OSMF' );
```

Make sure that the Vector theme is present as well. You can also set this theme
as default by setting `$wgDefaultSkin = "OSMF";` in `LocalSettings.php`.

## License

Since this is a subskin of the [Vector](https://github.com/wikimedia/mediawiki-skins-Vector) skin of MediaWiki
and Vector is available under GPL-2.0+ this subskin has to be licensed
accordingly. The OSMF logo is CC-BY-SA 3.0 (by nebulon42) because the original
OSM logo (by Ken Vermette) is licensed that way. The font in the logo and on the
website is Palanquin by Pria Ravichandran and OSMFText respectively. See also
the README file in the `fonts` directory.
