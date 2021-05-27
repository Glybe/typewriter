<?php
declare(strict_types=1);

namespace TypeWriter\Module\Core;

use Raxos\Foundation\Util\StringUtil;
use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;
use function array_filter;
use function base_convert;
use function esc_attr;
use function esc_url;
use function filemtime;
use function get_oembed_endpoint_url;
use function get_page_template_slug;
use function get_permalink;
use function get_queried_object_id;
use function in_array;
use function is_array;
use function is_file;
use function is_numeric;
use function is_page_template;
use function is_scalar;
use function is_singular;
use function is_string;
use function parse_url;
use function remove_query_arg;
use function sprintf;
use function str_ends_with;
use function str_starts_with;
use function strpos;
use function strstr;
use function strtolower;
use function substr;
use function trim;
use function urldecode;
use function wp_dependencies_unique_hosts;
use function wp_get_canonical_url;
use function wp_get_shortlink;
use function wp_parse_url;
use const PHP_EOL;
use const PHP_URL_PATH;
use const TypeWriter\ROOT;
use const WP_DEBUG;

/**
 * Class ImproveOutputModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class ImproveOutputModule extends Module
{

    /**
     * ImproveOutputModule constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('Improves output html.');
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onInitialize(): void
    {
        Hooks::action('customize_controls_print_styles', [$this, 'addResourceHints']);
        Hooks::action('embed_head', [$this, 'addCanonical']);
        Hooks::action('login_head', [$this, 'addResourceHints']);
        Hooks::action('wp_head', [$this, 'addCanonical']);
        Hooks::action('wp_head', [$this, 'addEmbedDiscoveryLinks']);
        Hooks::action('wp_head', [$this, 'addResourceHints']);
        Hooks::action('wp_head', [$this, 'addShortlink']);

        Hooks::filter('body_class', [$this, 'onBodyClass']);
        Hooks::filter('script_loader_src', [$this, 'onResourceSrc']);
        Hooks::filter('style_loader_src', [$this, 'onResourceSrc']);
        Hooks::filter('script_loader_tag', [$this, 'onScriptLoaderTag']);
        Hooks::filter('style_loader_tag', [$this, 'onStyleLoaderTag']);

        Hooks::removeAction('embed_head', 'rel_canonical');
        Hooks::removeAction('wp_head', 'rel_canonical');
        Hooks::removeAction('wp_head', 'wp_oembed_add_discovery_links');
        Hooks::removeAction('wp_head', 'wp_shortlink_wp_head');

        Hooks::removeAction('customize_controls_print_styles', 'wp_resource_hints', 1);
        Hooks::removeAction('login_head', 'wp_resource_hints', 8);
        Hooks::removeFilter('wp_head', 'wp_resource_hints', 2);
    }

    /**
     * Invoked on embed_head and wp_head filter hooks.
     * Readds the canonical link tag with proper formatting.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function addCanonical(): void
    {
        if (!is_singular()) {
            return;
        }

        $id = get_queried_object_id();

        if ($id === 0) {
            return;
        }

        $url = wp_get_canonical_url($id);

        if (!empty($url)) {
            echo sprintf('	<link rel="canonical" href="%s"/>', esc_url($url)) . PHP_EOL;
        }
    }

    /**
     * Invoked on wp_head filter hook.
     * Readds oembed discovery links.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function addEmbedDiscoveryLinks(): void
    {
        $output = '';

        if (is_singular()) {
            $output .= sprintf('	<link rel="alternate" type="application/json+oembed" href="%s"/>', esc_url(get_oembed_endpoint_url(get_permalink()))) . PHP_EOL;
            $output .= sprintf('	<link rel="alternate" type="text/xml+oembed" href="%s"/>', esc_url(get_oembed_endpoint_url(get_permalink(), 'xml'))) . PHP_EOL;
        }

        echo Hooks::applyFilters('oembed_discovery_links', $output);
    }

    /**
     * Invoked on customize_controls_print_styles, login_head and wp_head action hooks.
     * Readds the resource hints with proper formatting.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function addResourceHints(): void
    {
        $hints = [
            'dns-prefetch' => wp_dependencies_unique_hosts(),
            'preconnect' => [],
            'prefetch' => [],
            'prerender' => [],
        ];

        foreach ($hints as $relationType => $urls) {
            $uniqueUrls = [];
            $urls = Hooks::applyFilters('wp_resource_hints', $urls, $relationType);

            foreach ($urls as $url) {
                $atts = [];

                if (is_array($url)) {
                    if (!isset($url['href'])) {
                        continue;
                    }

                    $atts = $url;
                    $url = $url['href'];
                }

                $url = esc_url($url, ['http', 'https']);

                if (!$url || isset($uniqueUrls[$url])) {
                    continue;
                }

                if (in_array($relationType, ['preconnect', 'dns-prefetch'])) {
                    $parsed = wp_parse_url($url);

                    if (empty($parsed['host'])) {
                        continue;
                    }

                    if ($relationType === 'preconnect' && !empty($parsed['scheme'])) {
                        $url = $parsed['scheme'] . '://' . $parsed['host'];
                    } else {
                        $url = '//' . $parsed['host'];
                    }
                }

                $atts['rel'] = $relationType;
                $atts['href'] = $url;

                $uniqueUrls[$url] = $atts;
            }

            foreach ($uniqueUrls as $atts) {
                $allowedAttrs = ['as', 'crossorigin', 'href', 'pr', 'rel', 'type'];
                $html = '';

                foreach ($atts as $attr => $value) {
                    if (!is_scalar($value) || (!in_array($attr, $allowedAttrs, true) && !is_numeric($attr))) {
                        continue;
                    }

                    $value = $attr === 'href' ? esc_url($value) : esc_attr($value);

                    if (!is_string($attr)) {
                        $html .= ' ' . $value;
                    } else {
                        $html .= ' ' . $attr . '="' . $value . '"';
                    }
                }

                $html = trim($html);

                echo "\t<link $html/>\n";
            }
        }
    }

    /**
     * Invoked on wp_head filter hook.
     * Readds the shortlink link tag with proper formatting.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function addShortlink(): void
    {
        $shortlink = wp_get_shortlink(0, 'query');

        if (empty($shortlink)) {
            return;
        }

        echo sprintf('	<link rel="shortlink" href="%s"/>', esc_url($shortlink)) . PHP_EOL;
    }

    /**
     * Invoked on body_class filter hook.
     * Filters obsolete classes and adds a few.
     *
     * @param array $classes
     *
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onBodyClass(array $classes): array
    {
        $blacklist = ['logged-in'];

        if (is_singular() && is_page_template()) {
            $template = get_page_template_slug(get_queried_object_id());

            if (!empty($template)) {
                $template = substr($template, 0, -4);
                $template = StringUtil::slugify($template);

                $classes[] = $template;
            }
        }

        return array_filter($classes, function (string $class) use ($blacklist): bool {
            if (str_ends_with($class, '-php'))
                return false;

            if (str_ends_with($class, '-c'))
                return false;

            if (str_starts_with($class, 'error'))
                return false;

            if (strpos($class, '-id-'))
                return false;

            if (strpos($class, '-template'))
                return false;

            if (in_array($class, $blacklist))
                return false;

            return true;
        });
    }

    /**
     * Invoked on script_loader_src and style_loader_src filter hooks.
     * Removes the version query param from the resource url. If we want versions, we'll use our own.
     *
     * @param string $src
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onResourceSrc(string $src): string
    {
        if (strpos($src, 'ver=')) {
            $src = remove_query_arg('ver', $src);
        }

        $src = urldecode($src);

        $file = ROOT . '/public' . parse_url($src . '?ver=13', PHP_URL_PATH);
        $join = strstr($src, '?') !== false ? '&' : '?';

        if (!is_file($file)) {
            return $src;
        }

        return $src . $join . 'b=' . strtolower(base_convert((string)(filemtime($file) ?: 0), 10, 16));
    }

    /**
     * Invoked on script_loader_tag filter hook.
     * Replaces that ugly single quoted script tag with a proper one.
     *
     * @param string $tag
     * @param string $handle
     * @param string $src
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onScriptLoaderTag(string $tag, string $handle, string $src): string
    {
        if (empty($tag)) {
            return $tag;
        }

        if (WP_DEBUG) {
            return sprintf('<script defer data-id="%s" type="text/javascript" src="%s"></script>', $handle, $src);
        }

        return sprintf('<script defer type="text/javascript" src="%s"></script>', $src);
    }

    /**
     * Invoked on style_loader_tag filter hook.
     * Replaces that ugly single quoted link tag with a proper one.
     *
     * @param string $tag
     * @param string $handle
     * @param string $href
     * @param string $media
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onStyleLoaderTag(string $tag, string $handle, string $href, string $media): string
    {
        if (empty($tag)) {
            return $tag;
        }

        if (WP_DEBUG) {
            return sprintf('<link rel="stylesheet" href="%s" data-id="%s" media="%s" type="text/css"/>', $href, $handle, $media);
        }

        return sprintf('<link rel="stylesheet" href="%s" media="%s" type="text/css"/>', $href, $media);
    }

}
