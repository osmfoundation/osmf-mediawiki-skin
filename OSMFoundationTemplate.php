<?php
/**
 * Template file for skin OSMF.
 *
 * @file
 * @ingroup Skins
 */

/**
 * QuickTemplate class for OSMF skin
 * @ingroup Skins
 */
class OSMFoundationTemplate extends BaseTemplate {

    /**
     * @param string $name
     * @param array|string $content
     * @param null|string $msg
     * @param null|string|array $hook
     */
    protected function renderPortal( $name, $content, $msg = null, $hook = null ) {
        if ( $msg === null ) {
            $msg = $name;
        }
        $msgObj = $this->getMsg( $msg );
        $labelId = Sanitizer::escapeIdForAttribute( "p-$name-label" );
        ?>
        <div class="portal" role="navigation" id="<?php
        echo htmlspecialchars( Sanitizer::escapeIdForAttribute( "p-$name" ) )
        ?>"<?php
        echo Linker::tooltip( 'p-' . $name )
        ?> aria-labelledby="<?php echo htmlspecialchars( $labelId ) ?>">
            <h3<?php $this->html( 'userlangattributes' ) ?> id="<?php echo htmlspecialchars( $labelId )
                ?>"><?php
                echo htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $msg );
                ?></h3>
            <div class="body">
                <?php
                if ( is_array( $content ) ) {
                ?>
                <ul>
                    <?php
                    foreach ( $content as $key => $val ) {
                        echo $this->makeListItem( $key, $val );
                    }
                    if ( $hook !== null ) {
                        // Avoid PHP 7.1 warning
                        $skin = $this;
                        Hooks::run( $hook, [ &$skin, true ] );
                    }
                    ?>
                </ul>
                <?php
                } else {
                    // Allow raw HTML block to be defined by extensions
                    echo $content;
                }

                echo $this->getSkin()->getAfterPortlet( $name );
                ?>
            </div>
        </div>
    <?php
    }

    /**
     * Render a series of portals
     *
     * @param array $portals
     */
    protected function renderPortals( array $portals ) {
        // Force the rendering of the following portals
        if ( !isset( $portals['TOOLBOX'] ) ) {
            $portals['TOOLBOX'] = true;
        }
        if ( !isset( $portals['LANGUAGES'] ) ) {
            $portals['LANGUAGES'] = true;
        }
        // Render portals
        foreach ( $portals as $name => $content ) {
            if ( $content === false ) {
                continue;
            }

            // Numeric strings gets an integer when set as key, cast back - T73639
            $name = (string)$name;

            switch ( $name ) {
                case 'SEARCH':
                    break;
                case 'TOOLBOX':
                    $toolbox = $this->data['sidebar']['TOOLBOX'];
                    $this->renderPortal( 'tb', $toolbox, 'toolbox', 'SkinTemplateToolboxEnd' );
                    break;
                case 'LANGUAGES':
                    if ( $this->data['language_urls'] !== false ) {
                        $this->renderPortal( 'lang', $this->data['language_urls'], 'otherlanguages' );
                    }
                    break;
                default:
                    $this->renderPortal( $name, $content );
                    break;
            }
        }
    }

    /**
     * Render one or more navigations elements by name, automatically reversed by css
     * when UI is in RTL mode
     *
     * @param array $elements
     */
    protected function renderNavigation( array $elements ) {
        // Render elements
        foreach ( $elements as $name => $element ) {
            switch ( $element ) {
                case 'NAMESPACES':
                    ?>
                    <div id="p-namespaces" role="navigation" class="vectorTabs<?php
                    if ( count( $this->data['namespace_urls'] ) == 0 ) {
                        echo ' emptyPortlet';
                    }
                    ?>" aria-labelledby="p-namespaces-label">
                        <h3 id="p-namespaces-label"><?php $this->msg( 'namespaces' ) ?></h3>
                        <ul<?php $this->html( 'userlangattributes' ) ?>>
                            <?php
                            foreach ( $this->data['namespace_urls'] as $key => $item ) {
                                echo $this->makeListItem( $key, $item, [
                                    'vector-wrap' => true,
                                ] );
                            }
                            ?>
                        </ul>
                    </div>
                    <?php
                    break;
                case 'VARIANTS':
                    ?>
                    <div id="p-variants" role="navigation" class="vectorMenu<?php
                    if ( count( $this->data['variant_urls'] ) == 0 ) {
                        echo ' emptyPortlet';
                    }
                    ?>" aria-labelledby="p-variants-label">
                        <?php
                        // Replace the label with the name of currently chosen variant, if any
                        $variantLabel = $this->getMsg( 'variants' )->text();
                        foreach ( $this->data['variant_urls'] as $item ) {
                            if ( isset( $item['class'] ) && stripos( $item['class'], 'selected' ) !== false ) {
                                $variantLabel = $item['text'];
                                break;
                            }
                        }
                        ?>
                        <input type="checkbox" class="vectorMenuCheckbox" aria-labelledby="p-variants-label" />
                        <h3 id="p-variants-label">
                            <span><?php echo htmlspecialchars( $variantLabel ) ?></span>
                        </h3>
                        <ul class="menu">
                            <?php
                            foreach ( $this->data['variant_urls'] as $key => $item ) {
                                echo $this->makeListItem( $key, $item );
                            }
                            ?>
                        </ul>
                    </div>
                    <?php
                    break;
                case 'VIEWS':
                    ?>
                    <div id="p-views" role="navigation" class="vectorTabs<?php
                    if ( count( $this->data['view_urls'] ) == 0 ) {
                        echo ' emptyPortlet';
                    }
                    ?>" aria-labelledby="p-views-label">
                        <h3 id="p-views-label"><?php $this->msg( 'views' ) ?></h3>
                        <ul<?php $this->html( 'userlangattributes' ) ?>>
                            <?php
                            foreach ( $this->data['view_urls'] as $key => $item ) {
                                echo $this->makeListItem( $key, $item, [
                                    'vector-wrap' => true,
                                    'vector-collapsible' => true,
                                ] );
                            }
                            ?>
                        </ul>
                    </div>
                    <?php
                    break;
                case 'ACTIONS':
                    ?>
                    <div id="p-cactions" role="navigation" class="vectorMenu<?php
                    if ( count( $this->data['action_urls'] ) == 0 ) {
                        echo ' emptyPortlet';
                    }
                    ?>" aria-labelledby="p-cactions-label">
                        <input type="checkbox" class="vectorMenuCheckbox" aria-labelledby="p-cactions-label" />
                        <h3 id="p-cactions-label"><span><?php
                            $this->msg( 'vector-more-actions' )
                        ?></span></h3>
                        <ul class="menu"<?php $this->html( 'userlangattributes' ) ?>>
                            <?php
                            foreach ( $this->data['action_urls'] as $key => $item ) {
                                echo $this->makeListItem( $key, $item );
                            }
                            ?>
                        </ul>
                    </div>
                    <?php
                    break;
                case 'PERSONAL':
                    ?>
                    <div id="p-personal" role="navigation"<?php
                    if ( count( $this->data['personal_urls'] ) == 0 ) {
                        echo ' class="emptyPortlet"';
                    }
                    ?> aria-labelledby="p-personal-label">
                        <h3 id="p-personal-label"><?php $this->msg( 'personaltools' ) ?></h3>
                        <ul<?php $this->html( 'userlangattributes' ) ?>>
                            <?php
                            $notLoggedIn = '';

                            if ( !$this->getSkin()->getUser()->isRegistered() &&
                                User::groupHasPermission( '*', 'edit' )
                            ) {
                                $notLoggedIn =
                                    Html::element( 'li',
                                        [ 'id' => 'pt-anonuserpage' ],
                                        $this->getMsg( 'notloggedin' )->text()
                                    );
                            }

                            $personalTools = $this->getPersonalTools();

                            $langSelector = '';
                            if ( array_key_exists( 'uls', $personalTools ) ) {
                                $langSelector = $this->makeListItem( 'uls', $personalTools[ 'uls' ] );
                                unset( $personalTools[ 'uls' ] );
                            }

                            echo $langSelector;
                            echo $notLoggedIn;
                            foreach ( $personalTools as $key => $item ) {
                                echo $this->makeListItem( $key, $item ) . '&nbsp;';
                            }
                            ?>
                        </ul>
                    </div>
                    <?php
                    break;
                case 'SEARCH':
                    ?>
                    <div id="p-search" role="search">
                        <h3<?php $this->html( 'userlangattributes' ) ?>>
                            <label for="searchInput"><?php $this->msg( 'search' ) ?></label>
                        </h3>
                        <form action="<?php $this->text( 'wgScript' ) ?>" id="searchform">
                            <div id="simpleSearch">
                                <?php
                                echo $this->makeSearchInput( [ 'id' => 'searchInput' ] );
                                echo Html::hidden( 'title', $this->get( 'searchtitle' ) );
                                /* We construct two buttons (for 'go' and 'fulltext' search modes),
                                 * but only one will be visible and actionable at a time (they are
                                 * overlaid on top of each other in CSS).
                                 * * Browsers will use the 'fulltext' one by default (as it's the
                                 *   first in tree-order), which is desirable when they are unable
                                 *   to show search suggestions (either due to being broken or
                                 *   having JavaScript turned off).
                                 * * The mediawiki.searchSuggest module, after doing tests for the
                                 *   broken browsers, removes the 'fulltext' button and handles
                                 *   'fulltext' search itself; this will reveal the 'go' button and
                                 *   cause it to be used.
                                 */
                                echo $this->makeSearchButton(
                                    'fulltext',
                                    [ 'id' => 'mw-searchButton', 'class' => 'searchButton mw-fallbackSearchButton' ]
                                );
                                echo $this->makeSearchButton(
                                    'go',
                                    [ 'id' => 'searchButton', 'class' => 'searchButton' ]
                                );
                                ?>
                            </div>
                        </form>
                    </div>
                    <?php

                    break;
            }
        }
    }

    /**
     * Outputs the entire contents of the (X)HTML page
     */
    public function execute() {
        // Build additional attributes for navigation urls
        $nav = $this->data['content_navigation'];

        $user = $this->getSkin()->getUser();
        $relevantTitle = $this->getSkin()->getRelevantTitle();
        $isWatched = false;
        // Use method_exists to maintain compatibility with MediaWiki 1.30
        if ( method_exists( $user, 'isWatched' ) ) {
            $isWatched = $user->isWatched( $relevantTitle );
        } else {
            $instance = MediaWiki\MediaWikiServices::getInstance();
            $isWatched = $instance->getWatchedItemStore()->isWatched(
                $user,
                $relevantTitle
            );
        $mode = $isWatched ? 'unwatch' : 'watch';

        if ( isset( $nav['actions'][$mode] ) ) {
            $nav['views'][$mode] = $nav['actions'][$mode];
            $nav['views'][$mode]['class'] = $nav['views'][$mode]['class'];
            $nav['views'][$mode]['primary'] = true;
            unset( $nav['actions'][$mode] );
        }

        $xmlID = '';
        foreach ( $nav as $section => $links ) {
          foreach ( $links as $key => $link ) {

            $xmlID = isset( $link['id'] ) ? $link['id'] : 'ca-' . $xmlID;
            $nav[$section][$key]['attributes'] =
              ' id="' . Sanitizer::escapeIdForAttribute( $xmlID ) . '"';
            if ( $link['class'] ) {
              $nav[$section][$key]['attributes'] .=
                ' class="' . htmlspecialchars( $link['class'] ) . '"';
              unset( $nav[$section][$key]['class'] );
            }
            if ( isset( $link['tooltiponly'] ) && $link['tooltiponly'] ) {
              $nav[$section][$key]['key'] =
                Linker::tooltip( $xmlID );
            } else {
              $nav[$section][$key]['key'] =
                Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( $xmlID ) );
            }
          }
        }
        $this->data['namespace_urls'] = $nav['namespaces'];
        $this->data['view_urls'] = $nav['views'];
        $this->data['action_urls'] = $nav['actions'];
        $this->data['variant_urls'] = $nav['variants'];

        // Reverse horizontally rendered navigation elements
        if ( $this->data['rtl'] ) {
          $this->data['view_urls'] =
            array_reverse( $this->data['view_urls'] );
          $this->data['namespace_urls'] =
            array_reverse( $this->data['namespace_urls'] );
          $this->data['personal_urls'] =
            array_reverse( $this->data['personal_urls'] );
        }

        $this->data['pageLanguage'] =
          $this->getSkin()->getTitle()->getPageViewLanguage()->getHtmlCode();

        // Output HTML Page
        $this->html( 'headelement' );
        ?>
        <div id="jump-to-nav" class="mw-jump">
            <?php $this->msg( 'jumpto' ) ?>
            <a href="#firstHeading"><?php
                $this->msg( 'jumptocontent' )
            ?></a><?php $this->msg( 'comma-separator' ) ?>
            <a href="#mw-navigation"><?php
                $this->msg( 'jumptonavigation' )
            ?></a><?php $this->msg( 'comma-separator' ) ?>
            <a href="#p-search"><?php $this->msg( 'jumptosearch' ) ?></a>
        </div>
        <header id="mw-navigation">
            <h2><?php $this->msg( 'navigation-heading' ) ?></h2>
            <div id="mw-panel">
                <button type="button" aria-expanded="false" aria-label="<?php $this->msg( 'navigation-toggle' ) ?>"
                    class="nav-toggle" aria-controls="mainnav">
                    <div class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <div class="cross">
                        <span></span>
                        <span></span>
                    </div>
                </button>
                <div id="p-logo" role="banner"><a class="logo mw-wiki-logo" href="<?php
                    echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] )
                    ?>" <?php
                    echo Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( 'p-logo' ) )
                    ?>></a></div>
                <?php $this->renderNav( $this->data['sidebar']['MENUBAR'] ?? $this->data['sidebar']['navigation'] ?? [] ); ?>
            </div>
        </header>
        <div class="main-wrapper">
            <main id="content" class="mw-body" role="main">
                <a id="top"></a>
                <?php
                if ( $this->data['sitenotice'] ) {
                    echo Html::rawElement( 'div',
                        [
                            'id' => 'siteNotice',
                            'class' => 'mw-body-content',
                        ],
                        // Raw HTML
                        $this->get( 'sitenotice' )
                    );
                }
                if ( is_callable( [ $this, 'getIndicators' ] ) ) {
                    echo $this->getIndicators();
                }
                // Loose comparison with '!=' is intentional, to catch null and false too, but not '0'
                if ( $this->data['title'] != '' ) {
                    echo Html::rawElement( 'h1',
                        [
                            'id' => 'firstHeading',
                            'class' => 'firstHeading',
                            'lang' => $this->get( 'pageLanguage' ),
                        ],
                        // Raw HTML
                        $this->get( 'title' )
                    );
                }

                $this->html( 'prebodyhtml' );
                ?>
                <div id="bodyContent" class="mw-body-content">
                    <?php
                    if ( $this->data['isarticle'] ) {
                        echo Html::element( 'div',
                            [
                                'id' => 'siteSub',
                                'class' => 'noprint',
                            ],
                            $this->getMsg( 'tagline' )->text()
                        );
                    }
                    ?>
                    <div id="contentSub"<?php $this->html( 'userlangattributes' ) ?>><?php
                        $this->html( 'subtitle' )
                    ?></div>
                    <?php
                    if ( $this->data['undelete'] ) {
                        echo Html::rawElement( 'div',
                            [ 'id' => 'contentSub2' ],
                            // Raw HTML
                            $this->get( 'undelete' )
                        );
                    }
                    if ( $this->data['newtalk'] ) {
                        echo Html::rawElement( 'div',
                            [ 'class' => 'usermessage' ],
                            // Raw HTML
                            $this->get( 'newtalk' )
                        );
                    }
                    ?>
                    <?php
                    $this->html( 'bodycontent' );

                    if ( $this->data['printfooter'] ) {
                        ?>
                        <div class="printfooter">
                            <?php $this->html( 'printfooter' ); ?>
                        </div>
                    <?php
                    }

                    if ( $this->data['catlinks'] ) {
                        $this->html( 'catlinks' );
                    }

                    if ( $this->data['dataAfterContent'] ) {
                        $this->html( 'dataAfterContent' );
                    }
                    ?>
                    <div class="visualClear"></div>
                    <?php $this->html( 'debughtml' ); ?>
                </div>
            </main>
        </div>
        <?php Hooks::run( 'VectorBeforeFooter' ); ?>
        <footer id="footer" role="contentinfo"<?php $this->html( 'userlangattributes' ) ?>>
            <div class="first-row">
                <div id="mw-personal">
                    <?php $this->renderNavigation( [ 'PERSONAL' ] ); ?>
                </div>

                <?php
                    $info = $this->getFooterLinks()['info'];
                    if ($info) {
                ?>
                <ul id="footer-info">
                    <?php
                    foreach ( $info as $link ) {
                    ?>
                    <li id="footer--<?php echo $link ?>"><?php $this->html( $link ) ?></li>
                    <?php
                    }
                    ?>
                </ul>
                <?php
                    }
                    else { ?>
                <div id="footer-info"></div>
                <?php
                    } ?>
            </div>

            <div class="second-row">
                <div class="page-nav">
                    <div id="left-navigation">
                        <?php $this->renderNavigation( [ 'NAMESPACES', 'VARIANTS' ] ); ?>
                    </div>
                    <div id="right-navigation">
                        <?php $this->renderNavigation( [ 'VIEWS', 'ACTIONS' ] ); ?>
                    </div>
                </div>

                <div id="search">
                    <?php $this->renderNavigation( [ 'SEARCH' ] ); ?>
                </div>
            </div>

            <?php
            foreach ( $this->getFooterLinks() as $category => $links ) {
                if ($category === 'info' || $category === 'places') {
                    continue;
                }
            ?>
            <ul id="footer-<?php echo $category ?>">
                <?php
                foreach ( $links as $link ) {
                ?>
                <li id="footer-<?php echo $category ?>-<?php echo $link ?>"><?php $this->html( $link ) ?></li>
                <?php
                }
                ?>
            </ul>
            <?php
            }
            ?>
            <div class="third-row">
            <?php
                $sidebarData = $this->data['sidebar'];
                unset( $sidebarData['MENUBAR'] );
                $this->renderPortals( $sidebarData );
            ?>
            </div>
            <div class=powered-by><?php echo wfMessage( 'powered-by' )->plain() ?></div>
        </footer>
        <?php $this->printTrail(); ?>

    </body>
</html>
<?php
    }

    /**
     * Render the main navigation
     *
     * @param array $portals
     */
    protected function renderNav( $nav ) {
        if ( count($nav) > 0 ) {
            ?>
            <ul class="mainnav">
                <?php
                foreach ( $nav as $key => $val ) {
                    echo $this->makeListItem(
                        $key, $val, array(
                            'text-wrapper' => array( 'tag' => 'span' )
                        )
                    );
                }
                if ( isset($hook) && $hook !== null ) {
                    // Avoid PHP 7.1 warning
                    $skin = $this;
                    Hooks::run( $hook, [ &$skin, true ] );
                }
                ?>
            </ul>
            <?php
        } else {
            ?>
            <div class="mainnav">
                Site misconfigured: Please add "MENUBAR" section to [[MediaWiki:Sidebar]].
            </div>
            <?php
        }
    }

}
