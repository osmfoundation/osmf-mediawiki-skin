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
     * Render a series of portals
     *
     * @param array $portals
     */
    protected function renderPortals( array $portals ) {
    }

    /**
     * Render one or more navigations elements by name, automatically reversed by css
     * when UI is in RTL mode
     *
     * @param array $elements
     */
    protected function renderNavigation( array $elements ) {
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
        }
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
                <div id="p-logo" role="banner"><a class="logo" href="<?php
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
                    <li id="footer-<?php echo $category ?>-<?php echo $link ?>"><?php $this->html( $link ) ?></li>
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
