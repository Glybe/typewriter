<?php
declare(strict_types=1);

namespace TypeWriter\Module\Core;

use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;
use WP_Post_Type;
use WP_Sitemaps_Provider;
use WP_Sitemaps_Stylesheet;
use function array_key_exists;
use function in_array;
use function TypeWriter\tw;

/**
 * Class SitemapsModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class SitemapsModule extends Module
{

    /**
     * SitemapsModule constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('Adjustments for sitemaps.');
    }

    /**
     * {@inheritdoc}
     *
     * @hook tw.sitemaps.enabled (bool $isEnabled): bool
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function onInitialize(): void
    {
        if (tw()->isInstalling() || !Hooks::applyFilters('tw.sitemaps.enabled', true)) {
            return;
        }

        Hooks::filter('wp_sitemaps_add_provider', [$this, 'onSitemapsAddProvider']);
        Hooks::filter('wp_sitemaps_post_types', [$this, 'onSitemapsPostTypes']);
        Hooks::filter('wp_sitemaps_stylesheet_content', [$this, 'onSitemapsStylesheetContent']);
        Hooks::filter('wp_sitemaps_stylesheet_index_content', [$this, 'onSitemapsStylesheetIndexContent']);
    }

    /**
     * Invoked on wp_sitemap_add_provider filter hook.
     * Filters which providers are added to the sitemap. This removes users
     * by default.
     *
     * @param WP_Sitemaps_Provider $provider
     * @param string $name
     *
     * @hook tw.sitemaps.disallow.providers (string[] $providers): string[]
     *
     * @return WP_Sitemaps_Provider|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onSitemapsAddProvider(WP_Sitemaps_Provider $provider, string $name): ?WP_Sitemaps_Provider
    {
        $disallow = Hooks::applyFilters('tw.sitemaps.disallow.providers', ['users']);

        if (in_array($name, $disallow)) {
            return null;
        }

        return $provider;
    }

    /**
     * Invoked on wp_sitemaps_post_types filter hook.
     * Removes post types from the sitemap.
     *
     * @param WP_Post_Type[] $postTypes
     *
     * @hook tw.sitemaps.disallow.types (string[] $postTypes): string[]
     *
     * @return WP_Post_Type[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onSitemapsPostTypes(array $postTypes): array
    {
        $disallow = Hooks::applyFilters('tw.sitemaps.disallow.types', []);

        foreach ($disallow as $type) {
            if (array_key_exists($type, $postTypes)) {
                unset($postTypes[$type]);
            }
        }

        return $postTypes;
    }

    /**
     * Invoked on wp_sitemaps_stylesheet_content filter hook.
     * Creates our custom sitemap template and removes translations, as it is not needed.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onSitemapsStylesheetContent(): string
    {
        $stylesheet = new WP_Sitemaps_Stylesheet();

        $css = $stylesheet->get_stylesheet_css();
        $language = get_bloginfo('language');
        $title = get_bloginfo('name');

        return <<<XSL
<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9" exclude-result-prefixes="sitemap">
    <xsl:output method="html" encoding="UTF-8" indent="yes" />
    <xsl:variable name="has-lastmod" select="count( /sitemap:urlset/sitemap:url/sitemap:lastmod )" />
	<xsl:variable name="has-changefreq" select="count( /sitemap:urlset/sitemap:url/sitemap:changefreq )" />
	<xsl:variable name="has-priority" select="count( /sitemap:urlset/sitemap:url/sitemap:priority )" />

    <xsl:template match="/">
        <html lang="{$language}">
        <head>
            <title>Sitemap {$title}</title>
            <style>{$css}</style>
        </head>
        <body>
            <div id="sitemap">
                <div id="sitemap__header">
                    <h1>{$title}</h1>
                    <p>This is the generated sitemap for {$title}. This sitemap contains <xsl:value-of select="count( sitemap:urlset/sitemap:url )" /> url(s).</p>
                </div>
                <div id="sitemap__content">
                    <table id="sitemap__table">
                        <thead>
                        <tr>
                            <th class="loc">URL</th>
                            <xsl:if test="\$has-lastmod">
                                <th class="lastmod">Last Modified</th>
                            </xsl:if>
                            <xsl:if test="\$has-changefreq">
                                <th class="changefreq">Change Frequency</th>
                            </xsl:if>
                            <xsl:if test="\$has-priority">
                                <th class="priority">Priority</th>
                            </xsl:if>
                        </tr>
                        </thead>
                        <tbody>
                        <xsl:for-each select="sitemap:urlset/sitemap:url">
                            <tr>
                                <td class="loc"><a href="{sitemap:loc}"><xsl:value-of select="sitemap:loc" /></a></td>
                                <xsl:if test="\$has-lastmod">
                                    <td class="lastmod"><xsl:value-of select="sitemap:lastmod" /></td>
                                </xsl:if>
                                <xsl:if test="\$has-changefreq">
                                    <td class="changefreq"><xsl:value-of select="sitemap:changefreq" /></td>
                                </xsl:if>
                                <xsl:if test="\$has-priority">
                                    <td class="priority"><xsl:value-of select="sitemap:priority" /></td>
                                </xsl:if>
                            </tr>
                        </xsl:for-each>
                        </tbody>
                    </table>
                </div>
            </div>
        </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
XSL;
    }

    /**
     * Invoked on wp_sitemaps_stylesheet_index_content filter hook.
     * Creates our custom sitemap template and removes translations, as it is not needed.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onSitemapsStylesheetIndexContent(): string
    {
        $stylesheet = new WP_Sitemaps_Stylesheet();

        $css = $stylesheet->get_stylesheet_css();
        $language = get_bloginfo('language');
        $title = get_bloginfo('name');

        return <<<XSL
<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9" exclude-result-prefixes="sitemap">
    <xsl:output method="html" encoding="UTF-8" indent="yes" />
    <xsl:variable name="has-lastmod" select="count( /sitemap:sitemapindex/sitemap:sitemap/sitemap:lastmod )" />

    <xsl:template match="/">
        <html lang="{$language}">
        <head>
            <title>Sitemap {$title}</title>
            <style>{$css}</style>
        </head>
        <body>
            <div id="sitemap">
                <div id="sitemap__header">
                    <h1>{$title}</h1>
                    <p>This is the generated sitemap for {$title}. This sitemap contains <xsl:value-of select="count( sitemap:sitemapindex/sitemap:sitemap )" /> url(s).</p>
                </div>
                <div id="sitemap__content">
                    <table id="sitemap__table">
                        <thead>
                        <tr>
                            <th class="loc">URL</th>
                            <xsl:if test="\$has-lastmod">
                                <th class="lastmod">Last Modified</th>
                            </xsl:if>
                        </tr>
                        </thead>
                        <tbody>
                        <xsl:for-each select="sitemap:sitemapindex/sitemap:sitemap">
                            <tr>
                                <td class="loc"><a href="{sitemap:loc}"><xsl:value-of select="sitemap:loc" /></a></td>
                                <xsl:if test="\$has-lastmod">
                                    <td class="lastmod"><xsl:value-of select="sitemap:lastmod" /></td>
                                </xsl:if>
                            </tr>
                        </xsl:for-each>
                        </tbody>
                    </table>
                </div>
            </div>
        </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
XSL;
    }

}
