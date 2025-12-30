<?php
if (!defined('ABSPATH')) {
    exit;
}

const VIRALAY_STATIC_CONTENT_VERSION = '2025-11-21';

function viralay_static_page_slugs(): array {
    return array(
        'gizlilik-politikasi',
        'cerez-politikasi',
        'kvkk-aydinlatma-metni',
        'dmca-politikasi',
        'hizmet-sartlari',
        'ai-veri-kullanimi',
        'lisans-al',
        'markalar-ajanslar',
        'tv-produksiyon',
        'yayincilar',
        'hakkimizda',
        'iletisim'
    );
}

function viralay_get_default_page_content(string $slug): string {
    static $cache = array();
    if (isset($cache[$slug])) {
        return $cache[$slug];
    }
    $path = get_template_directory() . '/content/pages/' . $slug . '.html';
    if (!file_exists($path)) {
        $cache[$slug] = '';
        return '';
    }
    $data = file_get_contents($path);
    if ($data === false) {
        $cache[$slug] = '';
        return '';
    }
    $cache[$slug] = $data;
    return $data;
}

function viralay_should_seed_page(WP_Post $page): bool {
    // Get content without HTML tags
    $content = trim(wp_strip_all_tags($page->post_content));
    
    // If completely empty, seed it
    if ($content === '') {
        return true;
    }
    
    // If very short (less than 100 chars), probably a placeholder
    $length = function_exists('mb_strlen') ? mb_strlen($content) : strlen($content);
    if ($length < 100) {
        return true;
    }
    
    // If it's just a shortcode placeholder, don't override
    if (strpos($page->post_content, '[viralay_') !== false) {
        return false;
    }
    
    return false;
}

function viralay_seed_static_pages(): void {
    foreach (viralay_static_page_slugs() as $slug) {
        $page = get_page_by_path($slug);
        if (!$page instanceof WP_Post) {
            continue;
        }
        if (!viralay_should_seed_page($page)) {
            continue;
        }
        $content = viralay_get_default_page_content($slug);
        if ($content === '') {
            continue;
        }
        wp_update_post(array(
            'ID' => $page->ID,
            'post_content' => $content
        ));
    }
}

function viralay_bootstrap_static_pages(): void {
    // Always run on theme switch to ensure content is loaded
    viralay_seed_static_pages();
    update_option('viralay_static_content_version', VIRALAY_STATIC_CONTENT_VERSION);
}
add_action('after_switch_theme', 'viralay_bootstrap_static_pages', 99);

function viralay_render_static_page(string $slug): void {
    if (!have_posts()) {
        $fallback = viralay_get_default_page_content($slug);
        if ($fallback !== '') {
            echo '<div class="viralay-static-page">' . apply_filters('the_content', $fallback) . '</div>';
        }
        return;
    }
    while (have_posts()) {
        the_post();
        $raw = get_the_content();
        if (!is_string($raw)) {
            $raw = '';
        }
        if (trim(wp_strip_all_tags($raw)) === '') {
            $raw = viralay_get_default_page_content($slug);
        }
        if ($raw === '') {
            continue;
        }
        echo '<div class="viralay-static-page">' . apply_filters('the_content', $raw) . '</div>';
    }
}
