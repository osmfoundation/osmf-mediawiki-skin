<?php
/**
* Skin file for skin OSMF.
*
* @file
* @ingroup Skins
*/
wfLoadSkin('Vector');

/**
 * SkinTemplate class for OSMF skin
 * @ingroup Skins
 */
class SkinOSMFoundation extends SkinVector {

    var $skinname = 'osmfoundation', $stylename = 'OSMFoundation', $template = 'OSMFoundationTemplate';

    /**
     * This function adds JavaScript via ResourceLoader and
     * a meta tag.
     *
     * @param OutputPage $out
     */

    public function initPage( OutputPage $out ) {
        parent::initPage( $out );
        $out->addMeta( 'viewport', 'width=device-width, initial-scale=1.0' );
        $out->addModules( 'skins.osmfoundation.js' );
        $out->addModuleStyles( "skins.osmfoundation" );
    }
}
