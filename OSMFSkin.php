<?php
/**
* Skin file for skin OSMF.
*
* @file
* @ingroup Skins
*/

require_once( dirname( __FILE__ ) . '/../Vector/Vector.php' );

/**
 * SkinTemplate class for OSMF skin
 * @ingroup Skins
 */
class SkinOSMF extends SkinVector {

    var $skinname = 'osmf', $stylename = 'OSMF', $template = 'OSMFTemplate';

    /**
     * @param $out OutputPage object
     */
    function setupSkinUserCss( OutputPage $out ) {
        parent::setupSkinUserCss( $out );
        $out->addModuleStyles( "skins.osmf" );
    }

    /**
     * This function adds JavaScript via ResourceLoader and
     * a meta tag.
     *
     * @param OutputPage $out
     */

    public function initPage( OutputPage $out ) {
        parent::initPage( $out );
        $out->addMeta( 'viewport', 'width=device-width, initial-scale=1.0' );
        $out->addModules( 'skins.osmf.js' );
    }
}
