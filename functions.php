<?php
if (!defined('ABSPATH')) exit;

// Theme Setup
function viralay_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    
    register_nav_menus(array(
        'primary' => 'Ana Menü',
        'footer' => 'Footer Menü'
    ));
}
add_action('after_setup_theme', 'viralay_theme_setup');

// Include page content handler
require_once get_template_directory() . '/inc/page-content.php';

// Include SVG icons helper (replaces Font Awesome - saves 700KB!)
require_once get_template_directory() . '/inc/svg-icons.php';

// Enqueue Scripts & Styles - ULTRA OPTIMIZED FOR 100 PAGESPEED
function viralay_theme_scripts() {
    // Load optimized minified CSS (only used classes, ~15KB vs 3MB CDN!)
    wp_enqueue_style('viralay-main', get_template_directory_uri() . '/assets/css/main.min.css', array(), '4.2.1', 'all');
    
    // Original style.css for theme metadata
    wp_enqueue_style('style', get_stylesheet_uri(), array('viralay-main'), '4.2.1');
    
    // Inline critical autofill CSS (minimal, performance-optimized)
    wp_add_inline_style('viralay-main', viralay_get_inline_css());
}
add_action('wp_enqueue_scripts', 'viralay_theme_scripts');

// Autofill fix and performance optimizations
function viralay_get_inline_css() {
    return '
    /* Autofill Tamamen Devre Dışı - Chrome/Safari sarı arka plan sorunu */
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus, 
    input:-webkit-autofill:active,
    textarea:-webkit-autofill,
    select:-webkit-autofill {
        -webkit-box-shadow: 0 0 0 9999px #ffffff inset !important;
        -webkit-text-fill-color: #000000 !important;
        box-shadow: 0 0 0 9999px #ffffff inset !important;
        transition: background-color 5000s ease-in-out 0s;
        background-color: #ffffff !important;
    }
    
    [data-theme="dark"] input:-webkit-autofill,
    [data-theme="dark"] input:-webkit-autofill:hover,
    [data-theme="dark"] input:-webkit-autofill:focus,
    .dark input:-webkit-autofill,
    .dark input:-webkit-autofill:hover,
    .dark input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0 9999px #1e293b inset !important;
        -webkit-text-fill-color: #F8FAFC !important;
        box-shadow: 0 0 0 9999px #1e293b inset !important;
        background-color: #1e293b !important;
    }
    
    /* Form performance - reduce repaints */
    .viralay-form-container {
        contain: layout style;
        will-change: auto;
    }
    
    /* Smooth scrolling performance */
    html {
        scroll-behavior: smooth;
    }
    
    @media (prefers-reduced-motion: reduce) {
        html {
            scroll-behavior: auto;
        }
    }
    ';
}

// ===== ULTRA PERFORMANCE OPTIMIZATIONS FOR 100 PAGESPEED =====

// Remove unnecessary WordPress features
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');

// Remove WordPress version
remove_action('wp_head', 'wp_generator');

// Disable embeds
function viralay_disable_embeds() {
    wp_dequeue_script('wp-embed');
}
add_action('wp_footer', 'viralay_disable_embeds');

// Add lazy loading to images
function viralay_add_lazy_loading($content) {
    if (is_feed() || is_preview()) return $content;
    $content = preg_replace('/<img(.*?)src=/i', '<img$1loading="lazy" src=', $content);
    return $content;
}
add_filter('the_content', 'viralay_add_lazy_loading', 99);
add_filter('post_thumbnail_html', 'viralay_add_lazy_loading', 99);
add_filter('widget_text', 'viralay_add_lazy_loading', 99);

// Defer JavaScript loading
function viralay_defer_scripts($tag, $handle, $src) {
    // Don't defer jQuery or admin scripts
    $defer_scripts = array('jquery', 'jquery-core', 'jquery-migrate');
    if (in_array($handle, $defer_scripts)) {
        return $tag;
    }
    return str_replace(' src', ' defer src', $tag);
}
add_filter('script_loader_tag', 'viralay_defer_scripts', 10, 3);

// Remove query strings from static resources
function viralay_remove_query_strings($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'viralay_remove_query_strings', 10, 2);
add_filter('script_loader_src', 'viralay_remove_query_strings', 10, 2);

// Optimize database queries
function viralay_limit_post_revisions($num, $post) {
    return 3;
}
add_filter('wp_revisions_to_keep', 'viralay_limit_post_revisions', 10, 2);

// Preconnect to external domains
function viralay_add_resource_hints($hints, $relation_type) {
    if ('dns-prefetch' === $relation_type) {
        $hints[] = '//fonts.googleapis.com';
        $hints[] = '//fonts.gstatic.com';
    }
    return $hints;
}
add_filter('wp_resource_hints', 'viralay_add_resource_hints', 10, 2);

// Disable unnecessary REST API endpoints for better security & performance
function viralay_disable_rest_api($access) {
    if (!is_user_logged_in()) {
        return new WP_Error('rest_disabled', __('REST API disabled for non-authenticated users'), array('status' => 403));
    }
    return $access;
}
// add_filter('rest_authentication_errors', 'viralay_disable_rest_api'); // Uncomment if needed

// Remove unnecessary header links
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_shortlink_wp_head');

// Optimize WordPress Heartbeat API
function viralay_optimize_heartbeat($settings) {
    $settings['interval'] = 60; // 60 seconds
    return $settings;
}
add_filter('heartbeat_settings', 'viralay_optimize_heartbeat');

// ===== END PERFORMANCE OPTIMIZATIONS =====

// --- CPT: Viral Videos ---
function create_viral_videos_post_type() {
    register_post_type('viral_videos',
        array(
            'labels' => array(
                'name' => 'Viral Videolar',
                'singular_name' => 'Viral Video',
                'add_new' => 'Yeni Video Ekle',
                'add_new_item' => 'Yeni Viral Video Ekle',
                'edit_item' => 'Video Düzenle',
                'new_item' => 'Yeni Video',
                'view_item' => 'Videoyu Görüntüle',
                'search_items' => 'Video Ara',
                'not_found' => 'Video bulunamadı',
                'menu_name' => 'Viral Videolar'
            ),
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-video-alt3',
            'menu_position' => 5,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'video'),
            'taxonomies' => array('video_category', 'video_tag')
        )
    );
}
add_action('init', 'create_viral_videos_post_type');

// --- CPT: FAQ ---
function create_faq_post_type() {
    register_post_type('faq',
        array(
            'labels' => array(
                'name' => 'SSS',
                'singular_name' => 'Soru',
                'add_new' => 'Yeni Soru Ekle',
                'add_new_item' => 'Yeni Soru Ekle',
                'edit_item' => 'Soruyu Düzenle',
                'menu_name' => 'SSS (FAQ)'
            ),
            'public' => true,
            'has_archive' => false,
            'menu_icon' => 'dashicons-editor-help',
            'menu_position' => 6,
            'supports' => array('title', 'editor', 'page-attributes'),
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'sss-item'),
            'taxonomies' => array('faq_category')
        )
    );
}
add_action('init', 'create_faq_post_type');

// --- Taxonomies ---
function create_video_taxonomies() {
    register_taxonomy('video_category', 'viral_videos', array(
        'hierarchical' => true,
        'labels' => array('name' => 'Video Kategorileri', 'singular_name' => 'Kategori'),
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'video', 'hierarchical' => false),
        'show_in_rest' => true
    ));
    
    register_taxonomy('video_tag', 'viral_videos', array(
        'hierarchical' => false,
        'labels' => array('name' => 'Video Etiketleri', 'singular_name' => 'Etiket'),
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'etiket'),
        'show_in_rest' => true
    ));
    
    register_taxonomy('faq_category', 'faq', array(
        'hierarchical' => true,
        'labels' => array('name' => 'SSS Kategorileri', 'singular_name' => 'Kategori'),
        'show_ui' => true,
        'show_in_rest' => true
    ));
}
add_action('init', 'create_video_taxonomies');

// --- Video Meta Boxes ---
function viral_ay_add_video_meta_boxes() {
    add_meta_box('viral_video_details', 'Video Detayları', 'viral_ay_video_details_callback', 'viral_videos', 'normal', 'high');
    add_meta_box('faq_details', 'SSS Ayarları', 'viral_ay_faq_details_callback', 'faq', 'side');
}
add_action('add_meta_boxes', 'viral_ay_add_video_meta_boxes');

function viral_ay_video_details_callback($post) {
    wp_nonce_field('viral_ay_save_video_details', 'viral_ay_video_details_nonce');
    
    $submitter_name = get_post_meta($post->ID, '_submitter_name', true);
    $video_url = get_post_meta($post->ID, '_video_url', true);
    $video_embed = get_post_meta($post->ID, '_video_embed', true);
    $video_duration = get_post_meta($post->ID, '_video_duration', true);
    $video_price = get_post_meta($post->ID, '_video_price', true);
    $video_license_type = get_post_meta($post->ID, '_video_license_type', true);
    $video_location = get_post_meta($post->ID, '_video_location', true);
    $video_shot_date = get_post_meta($post->ID, '_video_shot_date', true);
    $video_source_credit = get_post_meta($post->ID, '_video_source_credit', true);
    $video_asset_id = get_post_meta($post->ID, '_video_asset_id', true);
    $video_rating = get_post_meta($post->ID, '_video_rating', true);
    $video_duration_text = get_post_meta($post->ID, '_video_duration_text', true);
    $video_keywords = get_post_meta($post->ID, '_video_keywords', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="submitter_name">Gönderen Adı:</label></th>
            <td><input type="text" id="submitter_name" name="submitter_name" value="<?php echo esc_attr($submitter_name); ?>" style="width: 100%;"/></td>
        </tr>
        <tr>
            <th><label for="video_url">Video URL (YouTube/Vimeo):</label></th>
            <td><input type="url" id="video_url" name="video_url" value="<?php echo esc_attr($video_url); ?>" style="width: 100%;"/></td>
        </tr>
        <tr>
            <th><label for="video_embed">Video Embed Kodu:</label></th>
            <td><textarea id="video_embed" name="video_embed" rows="5" style="width: 100%;"><?php echo esc_textarea($video_embed); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="video_duration">Video Süresi (dk):</label></th>
            <td><input type="number" id="video_duration" name="video_duration" value="<?php echo esc_attr($video_duration); ?>"/></td>
        </tr>
        <tr>
            <th><label for="video_duration_text">Süre (mm:ss):</label></th>
            <td><input type="text" id="video_duration_text" name="video_duration_text" value="<?php echo esc_attr($video_duration_text); ?>" placeholder="00:13"/></td>
        </tr>
        <tr>
            <th><label for="video_price">Lisans Fiyatı ($):</label></th>
            <td><input type="number" id="video_price" name="video_price" value="<?php echo esc_attr($video_price); ?>" step="0.01" min="0"/></td>
        </tr>
        <tr>
            <th><label for="video_license_type">Lisans Türü:</label></th>
            <td>
                <select id="video_license_type" name="video_license_type" style="width: 100%;">
                    <option value="standard" <?php selected($video_license_type, 'standard'); ?>>Standart Lisans</option>
                    <option value="extended" <?php selected($video_license_type, 'extended'); ?>>Genişletilmiş Lisans</option>
                    <option value="exclusive" <?php selected($video_license_type, 'exclusive'); ?>>Münhasır Lisans</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="video_location">Lokasyon</label></th>
            <td><input type="text" id="video_location" name="video_location" value="<?php echo esc_attr($video_location); ?>" placeholder="Cebu, Philippines" style="width:100%"></td>
        </tr>
        <tr>
            <th><label for="video_shot_date">Çekim Tarihi</label></th>
            <td><input type="text" id="video_shot_date" name="video_shot_date" value="<?php echo esc_attr($video_shot_date); ?>" placeholder="03/08/2025" style="width:100%"></td>
        </tr>
        <tr>
            <th><label for="video_source_credit">Kaynak</label></th>
            <td><input type="text" id="video_source_credit" name="video_source_credit" value="<?php echo esc_attr($video_source_credit); ?>" placeholder="Ajans/İsim" style="width:100%"></td>
        </tr>
        <tr>
            <th><label for="video_asset_id">Varlık ID</label></th>
            <td><input type="text" id="video_asset_id" name="video_asset_id" value="<?php echo esc_attr($video_asset_id); ?>" placeholder="ID" style="width:100%"></td>
        </tr>
        <tr>
            <th><label for="video_rating">Derecelendirme</label></th>
            <td>
                <select id="video_rating" name="video_rating">
                    <option value="social_safe" <?php selected($video_rating, 'social_safe'); ?>>Sosyal için güvenli</option>
                    <option value="brand_safe" <?php selected($video_rating, 'brand_safe'); ?>>Marka için güvenli</option>
                    <option value="editorial" <?php selected($video_rating, 'editorial'); ?>>Sadece editoryal</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="video_keywords">Anahtar Kelimeler</label></th>
            <td><textarea id="video_keywords" name="video_keywords" rows="3" class="large-text" placeholder="whale shark, snorkeller, ocean, marine life"><?php echo esc_textarea($video_keywords); ?></textarea><p class="description">Virgülle ayırın.</p></td>
        </tr>
        <tr>
            <th><label for="r2_object_key">R2 İndirme Dosyası</label></th>
            <td>
                <?php 
                $r2_object_key = get_post_meta($post->ID, '_r2_object_key', true);
                $r2_configured = class_exists('Viralay_R2_Storage') && Viralay_R2_Storage::get_instance()->is_configured();
                ?>
                <input type="text" id="r2_object_key" name="r2_object_key" value="<?php echo esc_attr($r2_object_key); ?>" style="width: 70%;" placeholder="videos/2025/12/uuid.mp4"/>
                <?php if ($r2_configured) : ?>
                <button type="button" id="r2_upload_btn" class="button">Dosya Yükle</button>
                <button type="button" id="r2_test_btn" class="button">Test Et</button>
                <?php endif; ?>
                <p class="description">R2 bucket'taki dosya yolu. Örn: <code>videos/2025/12/abc123.mp4</code></p>
                <?php if ($r2_object_key) : ?>
                <p style="color: #0a7; margin-top: 5px;"><span class="dashicons dashicons-yes-alt"></span> R2 dosyası tanımlı - Aboneler indirebilir</p>
                <?php endif; ?>
                <div id="r2_upload_progress" style="display:none; margin-top:10px;">
                    <progress id="r2_upload_bar" value="0" max="100" style="width:100%;"></progress>
                    <span id="r2_upload_status"></span>
                </div>
            </td>
        </tr>
    </table>
    
    <?php if ($r2_configured) : ?>
    <script>
    jQuery(document).ready(function($){
        var ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
        var nonce = '<?php echo wp_create_nonce('viralay_nonce'); ?>';
        
        $('#r2_test_btn').on('click', function(){
            var key = $('#r2_object_key').val();
            if (!key) {
                alert('Önce R2 dosya yolu girin.');
                return;
            }
            $(this).text('Kontrol ediliyor...');
            $.post(ajaxUrl, {
                action: 'viralay_check_r2_file',
                nonce: nonce,
                object_key: key
            }, function(response){
                if (response.success) {
                    alert('✓ Dosya R2\'da mevcut!');
                } else {
                    alert('✗ Dosya bulunamadı: ' + (response.data || 'Bilinmeyen hata'));
                }
                $('#r2_test_btn').text('Test Et');
            }).fail(function(){
                alert('AJAX hatası');
                $('#r2_test_btn').text('Test Et');
            });
        });
        
        $('#r2_upload_btn').on('click', function(){
            var input = $('<input type="file" accept="video/*">');
            input.on('change', function(e){
                var file = e.target.files[0];
                if (!file) return;
                
                var maxSize = 500 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('Dosya çok büyük. Maksimum 500MB.');
                    return;
                }
                
                $('#r2_upload_progress').show();
                $('#r2_upload_status').text('URL alınıyor...');
                
                $.post(ajaxUrl, {
                    action: 'viralay_get_r2_upload_url',
                    nonce: nonce,
                    filename: file.name,
                    content_type: file.type,
                    file_size: file.size
                }, function(response){
                    if (!response.success) {
                        $('#r2_upload_status').text('Hata: ' + response.data);
                        return;
                    }
                    
                    $('#r2_upload_status').text('Yükleniyor...');
                    
                    var xhr = new XMLHttpRequest();
                    xhr.open('PUT', response.data.upload_url, true);
                    xhr.setRequestHeader('Content-Type', file.type);
                    
                    xhr.upload.onprogress = function(e){
                        if (e.lengthComputable) {
                            var percent = Math.round((e.loaded / e.total) * 100);
                            $('#r2_upload_bar').val(percent);
                            $('#r2_upload_status').text('Yükleniyor: %' + percent);
                        }
                    };
                    
                    xhr.onload = function(){
                        if (xhr.status >= 200 && xhr.status < 300) {
                            $('#r2_object_key').val(response.data.object_key);
                            $('#r2_upload_status').text('✓ Yüklendi!');
                            setTimeout(function(){ $('#r2_upload_progress').fadeOut(); }, 2000);
                        } else {
                            $('#r2_upload_status').text('Yükleme hatası: ' + xhr.status);
                        }
                    };
                    
                    xhr.onerror = function(){
                        $('#r2_upload_status').text('Ağ hatası');
                    };
                    
                    xhr.send(file);
                }).fail(function(){
                    $('#r2_upload_status').text('AJAX hatası');
                });
            });
            input.click();
        });
    });
    </script>
    <?php endif; ?>
    <?php
}

function viral_ay_faq_details_callback($post) {
    $show_on_home = get_post_meta($post->ID, '_faq_show_on_home', true);
    $home_column = get_post_meta($post->ID, '_faq_home_column', true);
    $home_rank = get_post_meta($post->ID, '_faq_home_rank', true);
    
    echo '<p><label><input type="checkbox" name="_faq_show_on_home" value="1" ' . checked($show_on_home, '1', false) . '> Ana Sayfada Göster</label></p>';
    echo '<p><label>Ana Sayfa Sütunu:<br><select name="_faq_home_column" style="width:100%"><option value="">Seçin</option><option value="seller"' . selected($home_column, 'seller', false) . '>Satıcı</option><option value="buyer"' . selected($home_column, 'buyer', false) . '>Alıcı</option></select></label></p>';
    echo '<p><label>Sıra:<br><input type="number" name="_faq_home_rank" value="' . esc_attr($home_rank) . '" style="width:100%"></label></p>';
}

function viral_ay_save_video_details($post_id) {
    if (!isset($_POST['viral_ay_video_details_nonce'])) return;
    if (!wp_verify_nonce($_POST['viral_ay_video_details_nonce'], 'viral_ay_save_video_details')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    $meta_fields = [
        'submitter_name', 'video_url', 'video_embed', 'video_duration', 'video_duration_text', 
        'video_price', 'video_license_type', 'video_location', 'video_shot_date', 
        'video_source_credit', 'video_asset_id', 'video_rating', 'video_keywords',
        '_faq_show_on_home', '_faq_home_column', '_faq_home_rank'
    ];
    
    foreach ($meta_fields as $field) {
        $key = (strpos($field, '_') === 0) ? $field : '_' . $field;
        if (isset($_POST[$field])) {
            if ($field === 'video_url') {
                update_post_meta($post_id, $key, esc_url_raw($_POST[$field]));
            } elseif ($field === 'video_embed') {
                update_post_meta($post_id, $key, wp_kses_post($_POST[$field]));
            } else {
                update_post_meta($post_id, $key, sanitize_text_field($_POST[$field]));
            }
        }
    }
    
    if (isset($_POST['r2_object_key'])) {
        update_post_meta($post_id, '_r2_object_key', sanitize_text_field($_POST['r2_object_key']));
    }
}
add_action('save_post', 'viral_ay_save_video_details');

// --- Helper Functions ---

function get_youtube_id($url) {
    $video_id = '';
    if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches)) {
        $video_id = $matches[1];
    } elseif (preg_match('/youtube\.com\/shorts\/([^?&]+)/', $url, $matches)) {
        // YouTube Shorts URL'leri için destek
        $video_id = $matches[1];
    } elseif (preg_match('/youtu\.be\/([^?]+)/', $url, $matches)) {
        $video_id = $matches[1];
    } elseif (preg_match('/youtube\.com\/embed\/([^?&]+)/', $url, $matches)) {
        $video_id = $matches[1];
    }
    return $video_id;
}

/**
 * Video thumbnail URL'i al - Geliştirilmiş versiyon
 * Öncelik sırası:
 * 1. WordPress Featured Image (post thumbnail) - HER ZAMAN ÖNCELİKLİ
 * 2. Özel thumbnail meta alanı (_video_thumbnail_url)
 * 3. YouTube video thumbnail
 * 4. Vimeo video thumbnail
 * 5. Placeholder resim
 */
function get_video_thumbnail($post_id) {
    // 1. WordPress Featured Image - EN ÖNCELİKLİ
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail_url($post_id, 'large');
    }

    // 2. Özel thumbnail URL (admin'den ayarlanabilir)
    $custom_thumbnail = get_post_meta($post_id, '_video_thumbnail_url', true);
    if (!empty($custom_thumbnail)) {
        return esc_url($custom_thumbnail);
    }

    // 3. Video URL'den thumbnail çıkar
    $video_url = get_post_meta($post_id, '_video_url', true);

    if ($video_url) {
        // YouTube
        if (strpos($video_url, 'youtube') !== false || strpos($video_url, 'youtu.be') !== false) {
            $youtube_id = get_youtube_id($video_url);
            if ($youtube_id) {
                // maxresdefault yoksa hqdefault dene
                return "https://img.youtube.com/vi/{$youtube_id}/hqdefault.jpg";
            }
        }

        // Vimeo
        if (strpos($video_url, 'vimeo.com') !== false) {
            preg_match('/vimeo\.com\/(\d+)/', $video_url, $matches);
            if (!empty($matches[1])) {
                $vimeo_id = $matches[1];
                // Vimeo API'den thumbnail al (cache'lenmiş)
                $cached = get_transient('viralay_vimeo_thumb_' . $vimeo_id);
                if ($cached) {
                    return $cached;
                }
                $vimeo_data = @file_get_contents("https://vimeo.com/api/v2/video/{$vimeo_id}.json");
                if ($vimeo_data) {
                    $vimeo_json = json_decode($vimeo_data, true);
                    if (!empty($vimeo_json[0]['thumbnail_large'])) {
                        $thumb = $vimeo_json[0]['thumbnail_large'];
                        set_transient('viralay_vimeo_thumb_' . $vimeo_id, $thumb, DAY_IN_SECONDS);
                        return $thumb;
                    }
                }
            }
        }
    }

    // 4. R2'dan video varsa placeholder göster
    $r2_key = get_post_meta($post_id, '_r2_object_key', true);
    if (!empty($r2_key)) {
        // R2 videolu içerik için özel placeholder
        return get_template_directory_uri() . '/assets/images/video-placeholder.png';
    }

    // 5. Genel placeholder
    return 'https://placehold.co/600x400/1e293b/14B8A6?text=Video+%F0%9F%8E%AC&font=roboto';
}

// Alias for compatibility
function get_viral_thumbnail($post_id) {
    return get_video_thumbnail($post_id);
}

// --- Page Link Helper ---
function viral_ay_get_link($link_type) {
    switch($link_type) {
        case 'video_gallery':
            return get_option('viral_ay_video_gallery_link', home_url('/video'));
        case 'video_submit':
            return get_option('viral_ay_video_submit_link', home_url('/video-gonder'));
        case 'user_panel':
            return get_option('viral_ay_user_panel_link', home_url('/panel'));
        case 'login':
            return get_option('viral_ay_login_link', home_url('/giris-yap'));
        case 'lifestyle':
            return get_option('viral_ay_lifestyle_link', home_url('/category/yasam-tarzi'));
        case 'contact':
            return get_option('viral_ay_contact_link', home_url('/iletisim'));
        case 'license':
            return get_option('viral_ay_license_cta_link', home_url('/lisans-al'));
        default:
            return home_url('/');
    }
}

// --- Video Gallery Filtering ---
add_action('pre_get_posts', 'viralay_video_filter');
function viralay_video_filter($query) {
    if (!is_admin() && $query->is_main_query() && (is_post_type_archive('viral_videos') || is_tax('video_category'))) {
        
        // Duration filter
        if (isset($_GET['duration']) && !empty($_GET['duration'])) {
            $duration = intval($_GET['duration']);
            $query->set('meta_query', array(
                array(
                    'key' => '_video_duration',
                    'value' => $duration,
                    'compare' => '<=',
                    'type' => 'NUMERIC'
                )
            ));
        }
        
        // Orderby
        if (isset($_GET['orderby'])) {
            switch ($_GET['orderby']) {
                case 'date_asc':
                    $query->set('orderby', 'date');
                    $query->set('order', 'ASC');
                    break;
                case 'title':
                    $query->set('orderby', 'title');
                    $query->set('order', 'ASC');
                    break;
                default:
                    $query->set('orderby', 'date');
                    $query->set('order', 'DESC');
            }
        }
    }
}

// --- Enhanced Theme Admin Panel ---
add_action('admin_menu', 'viralay_theme_options_page');
function viralay_theme_options_page() {
    add_menu_page(
        'Viralay Ayarları',
        'Viralay Ayarları',
        'manage_options',
        'viralay-theme-options',
        'viralay_theme_options_page_html',
        'dashicons-admin-customizer',
        3
    );
}

function viralay_theme_options_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Get active tab
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
    
    // Save settings
    if (isset($_POST['viralay_settings_submit'])) {
        check_admin_referer('viralay_theme_options');
        
        // General settings
        if (isset($_POST['hero_title'])) {
            update_option('viralay_home_hero_title', sanitize_text_field($_POST['hero_title']));
        }
        if (isset($_POST['hero_subtitle'])) {
            update_option('viralay_home_hero_subtitle', sanitize_textarea_field($_POST['hero_subtitle']));
        }
        if (isset($_POST['how_it_works_title'])) {
            update_option('viralay_how_it_works_title', sanitize_text_field($_POST['how_it_works_title']));
        }
        if (isset($_POST['how_it_works_subtitle'])) {
            update_option('viralay_how_it_works_subtitle', sanitize_text_field($_POST['how_it_works_subtitle']));
        }
        if (isset($_POST['about_home_title'])) {
            update_option('viralay_about_home_title', sanitize_text_field($_POST['about_home_title']));
        }
        if (isset($_POST['about_home_subtitle'])) {
            update_option('viralay_about_home_subtitle', sanitize_text_field($_POST['about_home_subtitle']));
        }
        if (isset($_POST['about_home_text'])) {
            update_option('viralay_about_home_text', sanitize_textarea_field($_POST['about_home_text']));
        }
        if (isset($_POST['faq_home_limit'])) {
            update_option('viralay_faq_home_limit', intval($_POST['faq_home_limit']));
        }
        if (isset($_POST['faq_page_title'])) {
            update_option('viralay_faq_page_title', sanitize_text_field($_POST['faq_page_title']));
        }
        if (isset($_POST['faq_page_excerpt'])) {
            update_option('viralay_faq_page_excerpt', sanitize_text_field($_POST['faq_page_excerpt']));
        }
        
        // Color settings
        if (isset($_POST['primary_color'])) {
            update_option('viralay_primary_color', sanitize_hex_color($_POST['primary_color']));
        }
        if (isset($_POST['icon_style'])) {
            update_option('viralay_icon_style', sanitize_text_field($_POST['icon_style']));
        }
        
        // Font settings
        if (isset($_POST['heading_font'])) {
            update_option('viralay_heading_font', sanitize_text_field($_POST['heading_font']));
        }
        if (isset($_POST['body_font'])) {
            update_option('viralay_body_font', sanitize_text_field($_POST['body_font']));
        }
        
        // Site branding settings
        if (isset($_POST['site_title_text'])) {
            update_option('viralay_site_title_text', sanitize_text_field($_POST['site_title_text']));
        }
        if (isset($_POST['site_title_color_dark'])) {
            update_option('viralay_site_title_color_dark', sanitize_hex_color($_POST['site_title_color_dark']));
        }
        if (isset($_POST['site_title_color_light'])) {
            update_option('viralay_site_title_color_light', sanitize_hex_color($_POST['site_title_color_light']));
        }
        if (isset($_POST['site_title_font'])) {
            update_option('viralay_site_title_font', sanitize_text_field($_POST['site_title_font']));
        }
        if (isset($_POST['site_title_case'])) {
            update_option('viralay_site_title_case', sanitize_text_field($_POST['site_title_case']));
        }
        
        echo '<div class="notice notice-success is-dismissible"><p><strong>Ayarlar başarıyla kaydedildi!</strong></p></div>';
    }
    
    // Get current values
    $hero_title = get_option('viralay_home_hero_title', 'Videonuzdaki Gelir Potansiyelini Açığa Çıkarın.');
    $hero_subtitle = get_option('viralay_home_hero_subtitle', 'Telefonunuzdaki o eşsiz anı, dünya medyasının aradığı lisanslı bir içeriğe dönüştürüyoruz. Videonuzu koruma altına alın, global ağımıza katılın ve izlenmeleri gerçek bir kazanca çevirin.');
    $faq_limit = get_option('viralay_faq_home_limit', 4);
    $primary_color = get_option('viralay_primary_color', '#14B8A6');
    $icon_style = get_option('viralay_icon_style', 'solid');
    $heading_font = get_option('viralay_heading_font', 'Inter');
    $body_font = get_option('viralay_body_font', 'Inter');
    $site_title_text = get_option('viralay_site_title_text', 'VIRALAY');
    $site_title_color_dark = get_option('viralay_site_title_color_dark', '#FFFFFF');
    $site_title_color_light = get_option('viralay_site_title_color_light', '#0F172A');
    $site_title_font = get_option('viralay_site_title_font', 'Inter');
    $site_title_case = get_option('viralay_site_title_case', 'uppercase');
    
    ?>
    <div class="wrap">
        <h1>🎨 Viralay Tema Ayarları</h1>
        <p class="description">Temayı özelleştirin ve ayarlarınızı yönetin.</p>
        
        <h2 class="nav-tab-wrapper">
            <a href="?page=viralay-theme-options&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">🏠 Genel</a>
            <a href="?page=viralay-theme-options&tab=branding" class="nav-tab <?php echo $active_tab == 'branding' ? 'nav-tab-active' : ''; ?>">✨ Site Başlığı</a>
            <a href="?page=viralay-theme-options&tab=colors" class="nav-tab <?php echo $active_tab == 'colors' ? 'nav-tab-active' : ''; ?>">🎨 Renkler & Simgeler</a>
            <a href="?page=viralay-theme-options&tab=fonts" class="nav-tab <?php echo $active_tab == 'fonts' ? 'nav-tab-active' : ''; ?>">🔤 Yazı Tipleri</a>
            <a href="?page=viralay-theme-options&tab=pages" class="nav-tab <?php echo $active_tab == 'pages' ? 'nav-tab-active' : ''; ?>">📝 Sayfalar</a>
        </h2>
        
        <form method="post" action="">
            <?php wp_nonce_field('viralay_theme_options'); ?>
            
            <?php if ($active_tab == 'general'): ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="hero_title">Ana Sayfa Hero Başlık</label></th>
                        <td>
                            <input type="text" id="hero_title" name="hero_title" value="<?php echo esc_attr($hero_title); ?>" class="large-text">
                            <p class="description">Ana sayfadaki büyük başlık metni</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="hero_subtitle">Ana Sayfa Hero Alt Başlık</label></th>
                        <td>
                            <textarea id="hero_subtitle" name="hero_subtitle" rows="3" class="large-text"><?php echo esc_textarea($hero_subtitle); ?></textarea>
                            <p class="description">Ana sayfadaki açıklama metni</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="how_it_works_title">Nasıl Çalışır Başlık</label></th>
                        <td>
                            <input type="text" id="how_it_works_title" name="how_it_works_title" value="<?php echo esc_attr(get_option('viralay_how_it_works_title', 'Kazanç Sürecini Başlatın')); ?>" class="large-text">
                            <p class="description">Nasıl Çalışır bölümü başlığı</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="how_it_works_subtitle">Nasıl Çalışır Alt Başlık</label></th>
                        <td>
                            <input type="text" id="how_it_works_subtitle" name="how_it_works_subtitle" value="<?php echo esc_attr(get_option('viralay_how_it_works_subtitle', 'Videonuzu profesyonel bir dijital varlığa dönüştürme yolculuğu.')); ?>" class="large-text">
                            <p class="description">Nasıl Çalışır bölümü alt başlığı</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="about_home_title">Hakkımızda (Ana Sayfa) Başlık</label></th>
                        <td>
                            <input type="text" id="about_home_title" name="about_home_title" value="<?php echo esc_attr(get_option('viralay_about_home_title', 'VİRALAY HAKKINDA')); ?>" class="large-text">
                            <p class="description">Ana sayfadaki hakkımızda bölümü başlığı</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="about_home_subtitle">Hakkımızda (Ana Sayfa) Alt Başlık</label></th>
                        <td>
                            <input type="text" id="about_home_subtitle" name="about_home_subtitle" value="<?php echo esc_attr(get_option('viralay_about_home_subtitle', 'Sıradan Anları Küresel Hikayelere Dönüştürüyoruz.')); ?>" class="large-text">
                            <p class="description">Ana sayfadaki hakkımızda alt başlığı</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="about_home_text">Hakkımızda (Ana Sayfa) Metin</label></th>
                        <td>
                            <textarea id="about_home_text" name="about_home_text" rows="5" class="large-text"><?php echo esc_textarea(get_option('viralay_about_home_text', 'Viralay, kullanıcı kaynaklı içerikleri (UGC) profesyonel medya varlıklarına dönüştüren Türkiye\'nin yeni nesil lisanslama platformudur. Amacımız; içerik üreticilerinin haklarını koruyarak onlara sürdürülebilir bir gelir kapısı açmak, medya kuruluşlarına ise güvenilir ve lisanslı içerik sağlamaktır. Galerinizde bekleyen videolar, sandığınızdan çok daha değerli olabilir.')); ?></textarea>
                            <p class="description">Ana sayfadaki hakkımızda metni</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="faq_home_limit">Ana Sayfada Gösterilecek SSS Sayısı</label></th>
                        <td>
                            <input type="number" id="faq_home_limit" name="faq_home_limit" value="<?php echo esc_attr($faq_limit); ?>" min="1" max="10" class="small-text">
                            <p class="description">Her sütunda (Satıcı/Alıcı) kaç SSS gösterilecek (varsayılan: 4)</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="faq_page_title">SSS Sayfa Başlığı</label></th>
                        <td>
                            <input type="text" id="faq_page_title" name="faq_page_title" value="<?php echo esc_attr(get_option('viralay_faq_page_title', 'Sıkça Sorulan Sorular (SSS)')); ?>" class="large-text">
                            <p class="description">SSS sayfasının ana başlığı</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="faq_page_excerpt">SSS Sayfa Açıklaması</label></th>
                        <td>
                            <input type="text" id="faq_page_excerpt" name="faq_page_excerpt" value="<?php echo esc_attr(get_option('viralay_faq_page_excerpt', 'Aklınızdaki tüm soruların net, şeffaf ve hukuki yanıtları.')); ?>" class="large-text">
                            <p class="description">SSS sayfasının alt açıklama metni</p>
                        </td>
                    </tr>
                </table>
            <?php endif; ?>
            
            <?php if ($active_tab == 'branding'): ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="site_title_text">Site Başlığı Metni</label></th>
                        <td>
                            <input type="text" id="site_title_text" name="site_title_text" value="<?php echo esc_attr($site_title_text); ?>" class="regular-text">
                            <p class="description">Header ve Footer'da görünen site başlığı (varsayılan: VIRALAY)</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="site_title_color_dark">Başlık Rengi (Karanlık Mod)</label></th>
                        <td>
                            <input type="color" id="site_title_color_dark" name="site_title_color_dark" value="<?php echo esc_attr($site_title_color_dark); ?>">
                            <p class="description">Karanlık modda site başlığının rengi (varsayılan: Beyaz #FFFFFF)</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="site_title_color_light">Başlık Rengi (Aydınlık Mod)</label></th>
                        <td>
                            <input type="color" id="site_title_color_light" name="site_title_color_light" value="<?php echo esc_attr($site_title_color_light); ?>">
                            <p class="description">Aydınlık modda site başlığının rengi (varsayılan: Siyah #0F172A)</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="site_title_font">Başlık Yazı Tipi</label></th>
                        <td>
                            <select id="site_title_font" name="site_title_font">
                                <option value="Inter" <?php selected($site_title_font, 'Inter'); ?>>Inter</option>
                                <option value="Poppins" <?php selected($site_title_font, 'Poppins'); ?>>Poppins</option>
                                <option value="Roboto" <?php selected($site_title_font, 'Roboto'); ?>>Roboto</option>
                                <option value="Montserrat" <?php selected($site_title_font, 'Montserrat'); ?>>Montserrat</option>
                                <option value="Open Sans" <?php selected($site_title_font, 'Open Sans'); ?>>Open Sans</option>
                                <option value="Playfair Display" <?php selected($site_title_font, 'Playfair Display'); ?>>Playfair Display</option>
                                <option value="Oswald" <?php selected($site_title_font, 'Oswald'); ?>>Oswald</option>
                            </select>
                            <p class="description">Site başlığında kullanılacak font</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="site_title_case">Harf Büyüklüğü</label></th>
                        <td>
                            <select id="site_title_case" name="site_title_case">
                                <option value="uppercase" <?php selected($site_title_case, 'uppercase'); ?>>BÜYÜK HARF (UPPERCASE)</option>
                                <option value="lowercase" <?php selected($site_title_case, 'lowercase'); ?>>küçük harf (lowercase)</option>
                                <option value="capitalize" <?php selected($site_title_case, 'capitalize'); ?>>Kelime Başı Büyük (Capitalize)</option>
                                <option value="normal" <?php selected($site_title_case, 'normal'); ?>>Normal (Yazdığınız Gibi)</option>
                            </select>
                            <p class="description">Site başlığının harf büyüklüğü stili</p>
                        </td>
                    </tr>
                </table>
            <?php endif; ?>
            
            <?php if ($active_tab == 'colors'): ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="primary_color">Ana Renk (Primary)</label></th>
                        <td>
                            <input type="color" id="primary_color" name="primary_color" value="<?php echo esc_attr($primary_color); ?>">
                            <p class="description">Temadaki ana vurgu rengi (varsayılan: #14B8A6 - Turkuaz)</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="icon_style">Simge Stili</label></th>
                        <td>
                            <select id="icon_style" name="icon_style">
                                <option value="solid" <?php selected($icon_style, 'solid'); ?>>Solid (Düz Renk)</option>
                                <option value="gradient" <?php selected($icon_style, 'gradient'); ?>>Gradient (Degrade)</option>
                                <option value="outlined" <?php selected($icon_style, 'outlined'); ?>>Outlined (Çerçeveli)</option>
                            </select>
                            <p class="description">"Nasıl Çalışır" bölümündeki simgelerin görünümü</p>
                        </td>
                    </tr>
                </table>
            <?php endif; ?>
            
            <?php if ($active_tab == 'fonts'): ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="heading_font">Başlık Yazı Tipi</label></th>
                        <td>
                            <select id="heading_font" name="heading_font">
                                <option value="Inter" <?php selected($heading_font, 'Inter'); ?>>Inter</option>
                                <option value="Poppins" <?php selected($heading_font, 'Poppins'); ?>>Poppins</option>
                                <option value="Roboto" <?php selected($heading_font, 'Roboto'); ?>>Roboto</option>
                                <option value="Montserrat" <?php selected($heading_font, 'Montserrat'); ?>>Montserrat</option>
                                <option value="Open Sans" <?php selected($heading_font, 'Open Sans'); ?>>Open Sans</option>
                            </select>
                            <p class="description">Başlıklarda kullanılacak font</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="body_font">Gövde Yazı Tipi</label></th>
                        <td>
                            <select id="body_font" name="body_font">
                                <option value="Inter" <?php selected($body_font, 'Inter'); ?>>Inter</option>
                                <option value="Poppins" <?php selected($body_font, 'Poppins'); ?>>Poppins</option>
                                <option value="Roboto" <?php selected($body_font, 'Roboto'); ?>>Roboto</option>
                                <option value="Montserrat" <?php selected($body_font, 'Montserrat'); ?>>Montserrat</option>
                                <option value="Open Sans" <?php selected($body_font, 'Open Sans'); ?>>Open Sans</option>
                            </select>
                            <p class="description">Normal metinlerde kullanılacak font</p>
                        </td>
                    </tr>
                </table>
            <?php endif; ?>
            
            <?php if ($active_tab == 'pages'): ?>
                <h3>Otomatik Oluşturulan Sayfalar</h3>
                <p>Aşağıdaki sayfalar tema aktive edildiğinde otomatik olarak oluşturulur ve içerikleri yüklenir:</p>
                <ul style="list-style: disc; margin-left: 30px; line-height: 2;">
                    <li><strong>Gizlilik Politikası</strong> - <code>/gizlilik-politikasi</code></li>
                    <li><strong>Hizmet Şartları</strong> - <code>/hizmet-sartlari</code></li>
                    <li><strong>KVKK Aydınlatma Metni</strong> - <code>/kvkk-aydinlatma-metni</code></li>
                    <li><strong>Çerez Politikası</strong> - <code>/cerez-politikasi</code></li>
                    <li><strong>DMCA Politikası</strong> - <code>/dmca-politikasi</code></li>
                    <li><strong>Yayıncılar</strong> - <code>/yayincilar</code></li>
                    <li><strong>Markalar & Ajanslar</strong> - <code>/markalar-ajanslar</code></li>
                    <li><strong>TV & Prodüksiyon</strong> - <code>/tv-produksiyon</code></li>
                    <li><strong>AI Veri Kullanımı</strong> - <code>/ai-veri-kullanimi</code></li>
                    <li><strong>Hakkımızda</strong> - <code>/hakkimizda</code></li>
                    <li><strong>Lisans Al</strong> - <code>/lisans-al</code></li>
                </ul>
                <hr>
                <h3>SSS (FAQ) Ayarları</h3>
                <p>Örnek SSS'ler tema aktive edildiğinde otomatik oluşturulur. <a href="<?php echo admin_url('edit.php?post_type=faq'); ?>">SSS'leri düzenlemek için tıklayın</a>.</p>
                <p>Kategoriler: <strong>Genel</strong>, <strong>Başlangıç</strong>, <strong>Satıcı</strong>, <strong>Alıcı</strong>, <strong>Ödemeler</strong></p>
            <?php endif; ?>
            
            <?php submit_button('Ayarları Kaydet', 'primary large', 'viralay_settings_submit'); ?>
        </form>
    </div>
    <style>
        .nav-tab-wrapper { margin: 20px 0; }
        .form-table th { width: 250px; }
        input[type="color"] { width: 100px; height: 40px; border: none; cursor: pointer; }
    </style>
    <?php
}

// --- Auto-create Static Pages with Content ---
add_action('after_switch_theme', 'viralay_create_default_pages');
function viralay_create_default_pages() {
    $pages = array(
        'giris-yap' => 'Giriş Yap',
        'video-gonder' => 'Video Gönder',
        'panel' => 'Kullanıcı Paneli',
        'sss' => 'Sıkça Sorulan Sorular',
        'gizlilik-politikasi' => 'Gizlilik Politikası',
        'hizmet-sartlari' => 'Hizmet Şartları',
        'kvkk-aydinlatma-metni' => 'KVKK Aydınlatma Metni',
        'cerez-politikasi' => 'Çerez Politikası',
        'dmca-politikasi' => 'DMCA Politikası',
        'telif-hakki-dmca' => 'Telif Hakkı ve DMCA',
        'yayincilar' => 'Yayıncılar',
        'markalar-ajanslar' => 'Markalar & Ajanslar',
        'tv-produksiyon' => 'TV & Prodüksiyon',
        'ai-veri-kullanimi' => 'AI Veri Kullanımı',
        'lisans-al' => 'Lisans Al',
        'hakkimizda' => 'Hakkımızda',
        'iletisim' => 'İletişim',
    );
    
    foreach ($pages as $slug => $title) {
        $page_check = get_page_by_path($slug);
        
        // Get content from HTML files
        $content_file = get_template_directory() . '/content/pages/' . $slug . '.html';
        $content = '';
        if (file_exists($content_file)) {
            $content = file_get_contents($content_file);
        }
        
        // Add shortcodes for specific pages
        if ($slug == 'video-gonder') {
            $content = '[viralay_video_form]';
        } elseif ($slug == 'lisans-al') {
            $content = '[viralay_license_form]';
        }
        
        if (!$page_check) {
            // Create new page
            $page_id = wp_insert_post(array(
                'post_title' => $title,
                'post_name' => $slug,
                'post_content' => $content,
                'post_status' => 'publish',
                'post_type' => 'page',
                'comment_status' => 'closed',
                'ping_status' => 'closed',
            ));
        } else {
            // Update existing page if content is empty
            $existing_content = get_post_field('post_content', $page_check->ID);
            if (empty(trim(strip_tags($existing_content))) && !empty($content)) {
                wp_update_post(array(
                    'ID' => $page_check->ID,
                    'post_content' => $content,
                    'post_status' => 'publish'
                ));
            }
        }
    }
    
    // Create sample FAQs
    viralay_create_sample_faqs();
}

// --- Create Sample FAQs on Theme Activation ---
function viralay_create_sample_faqs() {
    // Check if already created
    if (get_option('viralay_sample_faqs_created')) {
        return;
    }
    
    // Delete ALL existing FAQs and categories first (clean slate)
    $existing_faqs = get_posts(array(
        'post_type' => 'faq',
        'posts_per_page' => -1,
        'post_status' => 'any'
    ));
    
    foreach ($existing_faqs as $faq_post) {
        wp_delete_post($faq_post->ID, true);
    }
    
    // Delete all FAQ categories
    $existing_cats = get_terms(array(
        'taxonomy' => 'faq_category',
        'hide_empty' => false
    ));
    
    foreach ($existing_cats as $cat) {
        wp_delete_term($cat->term_id, 'faq_category');
    }
    
    // Create FAQ categories
    $categories = array(
        'genel' => 'Genel',
        'baslangic' => 'Başlangıç',
        'satici' => 'Satıcı (İçerik Sahibi)',
        'alici' => 'Alıcı',
        'odemeler' => 'Ödemeler'
    );
    
    $cat_ids = array();
    foreach ($categories as $slug => $name) {
        $term = term_exists($slug, 'faq_category');
        if (!$term) {
            $term = wp_insert_term($name, 'faq_category', array('slug' => $slug));
        }
        if (!is_wp_error($term)) {
            $cat_ids[$slug] = is_array($term) ? $term['term_id'] : $term;
        }
    }
    
    // Sample FAQs - Professional 30 Questions
    $sample_faqs = array(
        // BÖLÜM 1: GENEL VE BAŞLANGIÇ
        array(
            'title' => 'Viralay.com tam olarak ne yapar?',
            'content' => 'Viralay, kullanıcılar tarafından çekilen ilginç, komik veya haber değeri taşıyan videoları (UGC) doğrular, telif haklarını koruma altına alır ve global medya kuruluşlarına (TV kanalları, Haber siteleri, Reklam ajansları) lisanslar. Kısaca; videonuzun profesyonel menajerliğini yaparız.',
            'category' => 'genel',
            'show_home' => false,
            'home_column' => '',
            'rank' => 1
        ),
        array(
            'title' => 'Video göndermek veya sisteme katılmak ücretli mi?',
            'content' => 'Hayır. Viralay "Gelir Ortaklığı" modeliyle çalışır. Video göndermek, değerlendirme süreci veya portföye girmek için sizden hiçbir ücret talep edilmez. Biz, sadece videonuz satıldığında elde edilen gelir üzerinden komisyon alırız.',
            'category' => 'genel',
            'show_home' => false,
            'home_column' => '',
            'rank' => 2
        ),
        array(
            'title' => 'Hangi tür videoları kabul ediyorsunuz?',
            'content' => 'Sadece kendi çektiğiniz (orijinal) videoları kabul ediyoruz. Haber değeri taşıyan anlar, komik evcil hayvanlar, yetenek gösterileri, doğa olayları, kazalar veya "viral olma" potansiyeli taşıyan her türlü özgün içerik değerlendirilir.',
            'category' => 'genel',
            'show_home' => false,
            'home_column' => '',
            'rank' => 3
        ),
        array(
            'title' => 'İnternette bulduğum veya arkadaşımın gönderdiği videoyu yükleyebilir miyim?',
            'content' => 'Hayır, kesinlikle. Sadece kameranın arkasındaki kişi sizseniz veya videoyu çeken kişinin yasal temsilcisiyseniz video gönderebilirsiniz. İnternetten alınan videolar sistemimiz tarafından reddedilir ve hukuki sorumluluk yükleyene aittir.',
            'category' => 'genel',
            'show_home' => false,
            'home_column' => '',
            'rank' => 4
        ),
        array(
            'title' => 'Yaş sınırı var mı?',
            'content' => 'Yasal olarak sözleşme imzalayabilmek için 18 yaşından büyük olmanız gerekir. Eğer 18 yaşından küçükseniz, videoyu ebeveyninizin veya yasal vasinizin onayı ve bilgileriyle göndermelisiniz.',
            'category' => 'genel',
            'show_home' => false,
            'home_column' => '',
            'rank' => 5
        ),
        
        // BÖLÜM 2: VİDEO GÖNDERİMİ VE SÜREÇ
        array(
            'title' => 'Videomu gönderdikten sonra ne olur?',
            'content' => 'Video ekibimiz içeriği inceler (Vetting). Orijinalliği doğrulanırsa ve potansiyel görülürse, size e-posta yoluyla bir "Temsilcilik Sözleşmesi" göndeririz. Siz bu sözleşmeyi dijital olarak onayladıktan sonra videonuz portföye eklenir ve pazarlama süreci başlar.',
            'category' => 'satici',
            'show_home' => true,
            'home_column' => 'seller',
            'rank' => 6
        ),
        array(
            'title' => 'Videom ne kadar sürede onaylanır?',
            'content' => 'İnceleme süreci genellikle 24-48 saat sürer. Hafta sonları veya yoğun dönemlerde bu süre biraz uzayabilir. Eğer videonuz kriterlerimize uygun değilse tarafınıza dönüş yapılmayabilir.',
            'category' => 'satici',
            'show_home' => true,
            'home_column' => 'seller',
            'rank' => 7
        ),
        array(
            'title' => 'Videomu düzenleyip (edit yapıp) mi göndermeliyim?',
            'content' => 'Hayır. Mümkünse videonun en ham (raw), kesilmemiş ve düzenlenmemiş halini tercih ederiz. Üzerine yazı, müzik, emoji veya filtre eklenmemiş videoların medya kuruluşlarına satılma şansı çok daha yüksektir.',
            'category' => 'satici',
            'show_home' => true,
            'home_column' => 'seller',
            'rank' => 8
        ),
        array(
            'title' => 'Telefonumdaki orijinal videoyu sildim, ne yapmalıyım?',
            'content' => 'Orijinal dosya, videonun "tapusu" gibidir. Eğer orijinali sildiyseniz sahipliğinizi kanıtlamamız zorlaşır. Ancak videonun yüksek kaliteli bir kopyası elinizde varsa yine de şansımızı deneyebiliriz.',
            'category' => 'satici',
            'show_home' => false,
            'home_column' => '',
            'rank' => 9
        ),
        array(
            'title' => 'Aynı videoyu başka yerlere de gönderdim, sorun olur mu?',
            'content' => 'Eğer Viralay ile "Münhasır (Exclusive)" anlaşma imzalarsanız, videonun yönetimini tek elden bize bırakmanız gerekir. Başka ajanslarla da çalışıyorsanız, hak çatışması olmaması için bunu bize baştan bildirmeniz şarttır.',
            'category' => 'satici',
            'show_home' => false,
            'home_column' => '',
            'rank' => 10
        ),
        
        // BÖLÜM 3: HAKLAR, KORUMA VE HUKUK
        array(
            'title' => 'Videomu gönderince telif hakkını kaybediyor muyum?',
            'content' => 'Hayır. Videonun eser sahibi (müellifi) her zaman SİZ KALIRSINIZ. Bize sadece videonun pazarlanması, satışı ve temsili için "Lisanslama Yetkisi" verirsiniz. Videonun sahibi siz, yöneticisi biziz.',
            'category' => 'satici',
            'show_home' => true,
            'home_column' => 'seller',
            'rank' => 11
        ),
        array(
            'title' => 'Videomu kendi sosyal medya hesaplarımdan silmem gerekir mi?',
            'content' => 'Hayır, asla silmeyin! Videonuzun kişisel hesaplarınızda (Instagram, TikTok, YouTube vb.) kalmasında hiçbir sakınca yoktur. Aksine, orada viral olması satış şansını artırır. Sadece açıklama kısmına "Licensing: info@viralay.com" yazmanızı rica ederiz.',
            'category' => 'satici',
            'show_home' => false,
            'home_column' => '',
            'rank' => 12
        ),
        array(
            'title' => '"Münhasır Temsilcilik" (Exclusive Rights) ne demek?',
            'content' => 'Bu, videonun ticari haklarının yönetimini belirli bir süre için sadece Viralay\'a vermeniz demektir. Bu sayede biz; videonuzu izinsiz kullananları uyarabilir, telif ihtarları gönderebilir ve videonuzu TV kanallarına daha yüksek fiyatlardan satabiliriz.',
            'category' => 'satici',
            'show_home' => false,
            'home_column' => '',
            'rank' => 13
        ),
        array(
            'title' => 'Videom izinsiz olarak başka sayfalarda paylaşılmış, ne yapacaksınız?',
            'content' => 'Anlaşmamız başladığı andan itibaren, ekibimiz ve yazılımlarımız videonuzun kopyalarını tarar. İzinsiz kullanımları tespit ederiz ve (platform kurallarına göre) ya videoyu kaldırırız ya da o videolardan elde edilen geliri sizin adınıza talep ederiz (Claim).',
            'category' => 'satici',
            'show_home' => false,
            'home_column' => '',
            'rank' => 14
        ),
        array(
            'title' => 'Videomun nerelerde satıldığını görebilecek miyim?',
            'content' => 'Evet. Şeffaflık ilkemiz gereği, videonuz her lisanslandığında (bir TV kanalı veya site tarafından satın alındığında) size bilgi verilir ve kullanıcı panelinize/raporunuza yansır.',
            'category' => 'satici',
            'show_home' => false,
            'home_column' => '',
            'rank' => 15
        ),
        
        // BÖLÜM 4: KAZANÇ VE ÖDEMELER
        array(
            'title' => 'Videomdan ne kadar para kazanırım?',
            'content' => 'Bu konuda "garanti" vermek yasal ve etik değildir. Kazanç; videonun içeriğine, o günkü gündeme ve alıcının bütçesine göre değişir. Bir video 500 TL de kazandırabilir, globalde viral olup 5.000 Dolar da. Bizim işimiz, videoyu mümkün olan en yüksek fiyattan satmaktır.',
            'category' => 'odemeler',
            'show_home' => false,
            'home_column' => '',
            'rank' => 16
        ),
        array(
            'title' => 'Gelir paylaşımı oranı nedir?',
            'content' => 'Standart modelimizde, videonun lisanslanmasından elde edilen NET GELİRİN %50\'si size ödenir. Net gelir; brüt satış tutarından yasal vergiler, banka masrafları ve zorunlu kesintiler düşüldükten sonra kalan tutardır.',
            'category' => 'odemeler',
            'show_home' => false,
            'home_column' => '',
            'rank' => 17
        ),
        array(
            'title' => 'Ödememi ne zaman alırım?',
            'content' => 'Süreç şöyle işler: Videoyu müşteriye satarız. Müşteri ödemeyi bize yapar (Genelde 30-60 gün vade ile). Para hesabımıza girdiği andan itibaren, takip eden ödeme döneminde (Ayın 1\'i ile 15\'i arası) payınız size gönderilir. Müşteriden tahsilat yapılmadan ödeme yapılmaz.',
            'category' => 'odemeler',
            'show_home' => false,
            'home_column' => '',
            'rank' => 18
        ),
        array(
            'title' => 'Ödemeyi nasıl gönderiyorsunuz?',
            'content' => 'Ödemeler sadece Banka Havalesi / EFT yoluyla, adınıza kayıtlı bir banka hesabına (IBAN) yapılır. Güvenlik gereği, sözleşmedeki isim ile banka hesap sahibi aynı kişi olmalıdır.',
            'category' => 'odemeler',
            'show_home' => false,
            'home_column' => '',
            'rank' => 19
        ),
        array(
            'title' => 'Vergi ödemem gerekiyor mu?',
            'content' => 'Viralay, Türkiye Cumhuriyeti vergi kanunlarına tam uyumludur. Eğer şirketiniz yoksa, size yapılacak ödemeden doğan "Stopaj (Gelir Vergisi Kesintisi)" tarafımızca hesaplanıp sizin adınıza devlete ödenir. Kalan net tutar hesabınıza yatar. Böylece vergi dairesiyle uğraşmazsınız.',
            'category' => 'odemeler',
            'show_home' => false,
            'home_column' => '',
            'rank' => 20
        ),
        
        // BÖLÜM 5: MEDYA KURULUŞLARI VE ALICILAR İÇİN (B2B)
        array(
            'title' => 'Videoların telif hakları temiz mi (Rights Cleared)?',
            'content' => 'Evet. Viralay kataloğundaki her videonun sahibiyle imzalı bir "Temsilcilik Sözleşmesi" mevcuttur. Zincirleme hak sahipliği (Chain of Title) doğrulanmıştır. Güvenle yayınlayabilirsiniz.',
            'category' => 'alici',
            'show_home' => true,
            'home_column' => 'buyer',
            'rank' => 21
        ),
        array(
            'title' => 'Kurumsal fatura kesiyor musunuz?',
            'content' => 'Kesinlikle. Viralay Medya, yasal bir ticari kuruluştur. Satın alacağınız tüm içerikler için kurumunuza "Telif ve Lisans Bedeli" açıklamalı resmi e-Arşiv/e-Fatura düzenlenir. Giderleştirebilirsiniz.',
            'category' => 'alici',
            'show_home' => true,
            'home_column' => 'buyer',
            'rank' => 22
        ),
        array(
            'title' => 'Satın aldığım videoyu nerelerde kullanabilirim?',
            'content' => 'Kullanım hakları satın aldığınız lisans paketine göre değişir. Standart Lisans: Tek mecra (Örn: Sadece Web Sitesi veya Sadece YouTube). Yayın Lisansı: TV Haber bülteni veya programı. Kapsamlı Lisans: Tüm dijital ve konvansiyonel mecralar. Detaylar teklif aşamasında belirlenir.',
            'category' => 'alici',
            'show_home' => true,
            'home_column' => 'buyer',
            'rank' => 23
        ),
        array(
            'title' => 'Videoları filigransız (Clean Feed) veriyor musunuz?',
            'content' => 'Evet. Lisanslama işlemi tamamlandığında, videonun logosuz, yazısız ve en yüksek çözünürlüklü ham (Raw) halini size dijital transfer yoluyla iletiyoruz.',
            'category' => 'alici',
            'show_home' => true,
            'home_column' => 'buyer',
            'rank' => 24
        ),
        array(
            'title' => 'Haber merkezimiz için abonelik sistemi var mı?',
            'content' => 'Evet. Haber ajansları ve büyük yayıncılar için "Yıllık Abonelik" veya "Kredili Paket" sistemlerimiz mevcuttur. Düzenli içerik akışı için satış ekibimizle iletişime geçebilirsiniz.',
            'category' => 'alici',
            'show_home' => false,
            'home_column' => '',
            'rank' => 25
        ),
        array(
            'title' => 'Arşivinizde olmayan bir videoyu bulabilir misiniz?',
            'content' => 'Ekibimiz "İçerik Avcılığı" (Content Scouting) konusunda uzmandır. Aradığınız spesifik bir video veya konu varsa, bize bildirin; sizin için hak sahibini bulup lisanslama sürecini yönetelim.',
            'category' => 'alici',
            'show_home' => false,
            'home_column' => '',
            'rank' => 26
        ),
        
        // BÖLÜM 6: DİĞER
        array(
            'title' => 'Sitenizdeki bir videonun kaldırılmasını isteyebilir miyim?',
            'content' => 'Eğer bir videoda kişisel haklarınızın ihlal edildiğini düşünüyorsanız veya videonun gerçek sahibi sizseniz ancak başkası yüklediyse, "Telif Hakkı Bildirimi" sayfamızdan bize ulaşın. Hukuk ekibimiz 24 saat içinde inceleme başlatacaktır.',
            'category' => 'genel',
            'show_home' => false,
            'home_column' => '',
            'rank' => 27
        ),
        array(
            'title' => 'Yurt dışında yaşıyorum, yine de video gönderebilir miyim?',
            'content' => 'Evet. Dünyanın her yerinden video kabul ediyoruz. Ödemelerinizi uluslararası banka transferi (SWIFT) ile alabilirsiniz.',
            'category' => 'genel',
            'show_home' => false,
            'home_column' => '',
            'rank' => 28
        ),
        array(
            'title' => 'Videom TV\'de yayınlandı ama bana para ödenmedi, neden?',
            'content' => 'Bazen videolar "Adil Kullanım (Fair Use)" kapsamında kısa süreli haber olarak kullanılabilir veya izinsiz kullanılmış olabilir. Eğer izinsiz bir kullanım varsa, hukuk departmanımız devreye girer ve geriye dönük telif takibi yapar. Bu süreç zaman alabilir.',
            'category' => 'genel',
            'show_home' => false,
            'home_column' => '',
            'rank' => 29
        ),
        array(
            'title' => 'Viralay ile nasıl iletişime geçebilirim?',
            'content' => 'Her türlü soru, görüş ve lisans talebi için info@viralay.com adresinden bize ulaşabilirsiniz. Destek ekibimiz hafta içi 09:00 - 18:00 saatleri arasında hizmet vermektedir.',
            'category' => 'genel',
            'show_home' => false,
            'home_column' => '',
            'rank' => 30
        )
    );
    
    foreach ($sample_faqs as $faq) {
        $faq_id = wp_insert_post(array(
            'post_title' => $faq['title'],
            'post_content' => $faq['content'],
            'post_status' => 'publish',
            'post_type' => 'faq',
            'comment_status' => 'closed',
            'menu_order' => isset($faq['rank']) ? $faq['rank'] : 0
        ));
        
        if ($faq_id && isset($cat_ids[$faq['category']])) {
            wp_set_object_terms($faq_id, (int)$cat_ids[$faq['category']], 'faq_category');
            
            // Add home page display meta
            if (isset($faq['show_home']) && $faq['show_home']) {
                update_post_meta($faq_id, '_faq_show_on_home', '1');
                update_post_meta($faq_id, '_faq_home_column', $faq['home_column']);
            }
            
            // Add rank meta
            if (isset($faq['rank'])) {
                update_post_meta($faq_id, '_faq_rank', $faq['rank']);
            }
        }
    }
    
    update_option('viralay_sample_faqs_created', true);
}

// Enhanced category icon function with term meta support
function get_category_icon($term_id) {
    $icon = get_term_meta($term_id, 'category_icon', true);
    if ($icon) {
        return 'fas fa-' . $icon;
    }
    return 'fas fa-video';
}

function get_category_color($term_id) {
    $color = get_term_meta($term_id, 'category_color', true);
    return $color ?: 'primary';
}

// Auto-create default categories
add_action('after_switch_theme', 'viralay_create_default_categories');
function viralay_create_default_categories() {
    $categories = array(
        array(
            'name' => 'Doğa',
            'slug' => 'doga',
            'description' => 'Doğa manzaraları ve olayları',
            'icon' => 'tree',
            'color' => 'emerald'
        ),
        array(
            'name' => 'Hayvan',
            'slug' => 'hayvan',
            'description' => 'Hayvanlar ve vahşi yaşam',
            'icon' => 'paw',
            'color' => 'teal'
        ),
        array(
            'name' => 'İnsanlar',
            'slug' => 'insanlar',
            'description' => 'İnsan hikayeleri ve etkinlikler',
            'icon' => 'user-group',
            'color' => 'cyan'
        ),
        array(
            'name' => 'Viral',
            'slug' => 'viral',
            'description' => 'Viral içerikler ve trendler',
            'icon' => 'fire',
            'color' => 'orange'
        ),
    );
    
    foreach ($categories as $cat) {
        $term = term_exists($cat['slug'], 'video_category');
        if (!$term) {
            $term = wp_insert_term(
                $cat['name'],
                'video_category',
                array(
                    'slug' => $cat['slug'],
                    'description' => $cat['description']
                )
            );
            
            if (!is_wp_error($term)) {
                update_term_meta($term['term_id'], 'category_icon', $cat['icon']);
                update_term_meta($term['term_id'], 'category_color', $cat['color']);
            }
        }
    }
}

// VIRALAY EKLENTI ENTEGRASYONU
// Tema kurulduğunda otomatik sayfalar oluştur
function viral_ay_create_plugin_pages() {
    // Debug log
    error_log('VIRALAY DEBUG: viral_ay_create_plugin_pages() çalıştırıldı');
    
    $definitions = array(
        'video-gonder' => array(
            'title' => 'Video Gönder',
            'content' => '[viralay_video_form]',
            'shortcode' => 'viralay_video_form',
            'template' => 'page-video-gonder.php'
        ),
        'panel' => array(
            'title' => 'Panel',
            'content' => '[viralay_user_dashboard]',
            'shortcode' => 'viralay_user_dashboard',
            'template' => 'page-panel.php'
        ),
        'giris-yap' => array(
            'title' => 'Giriş Yap',
            'content' => '[viralay_login]',
            'shortcode' => 'viralay_login',
            'template' => 'page-giris-yap.php',
            'legacy' => array('giris')
        ),
        'parola-belirle' => array(
            'title' => 'Parola Belirle',
            'content' => '[viralay_set_password]',
            'shortcode' => 'viralay_set_password',
            'template' => 'page-parola-belirle.php'
        ),
        'lisans-al' => array(
            'title' => 'Lisans Al',
            'content' => '[viralay_license_form]',
            'shortcode' => 'viralay_license_form',
            'template' => 'page-lisans-al.php'
        )
    );

    $all_exist = true;
    foreach ($definitions as $slug => $page) {
        if (!get_page_by_path($slug)) {
            $all_exist = false;
            break;
        }
    }
    
    // UYARI: Option kontrolünü kaldırdık çünkü sayfalar varsa bile içeriklerini güncellememiz gerekiyor!
    // Eski kod: if ($all_exist && get_option('viral_ay_plugin_pages_created') === 'yes') { return; }
    // YENİ: Her zaman devam et, içerikleri kontrol et ve gerekirse güncelle
    error_log('VIRALAY DEBUG: Sayfa kontrolü başlıyor. Tüm sayfalar var mı? ' . ($all_exist ? 'EVET' : 'HAYIR'));

    $permalinks = array();
    foreach ($definitions as $slug => $page) {
        $current = get_page_by_path($slug);
        if (!$current && !empty($page['legacy'])) {
            foreach ($page['legacy'] as $legacy_slug) {
                $legacy = get_page_by_path($legacy_slug);
                if ($legacy instanceof WP_Post) {
                    wp_update_post(array(
                        'ID' => $legacy->ID,
                        'post_name' => $slug,
                        'post_title' => $page['title'],
                        'post_status' => 'publish'
                    ));
                    $current = get_post($legacy->ID);
                    break;
                }
            }
        }
        if (!$current) {
            $post_id = wp_insert_post(array(
                'post_title' => $page['title'],
                'post_name' => $slug,
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_content' => $page['content']
            ));
            if ($post_id && !is_wp_error($post_id)) {
                $current = get_post($post_id);
                error_log('VIRALAY DEBUG: Sayfa oluşturuldu - ' . $slug . ' (ID: ' . $post_id . ')');
            } else {
                error_log('VIRALAY DEBUG: Sayfa oluşturulamadı - ' . $slug);
            }
        } else {
            error_log('VIRALAY DEBUG: Sayfa zaten var, kontrol ediliyor - ' . $slug . ' (ID: ' . $current->ID . ') Mevcut içerik: ' . substr($current->post_content, 0, 100));
            if ($current->post_name !== $slug) {
                wp_update_post(array('ID' => $current->ID, 'post_name' => $slug));
                $current = get_post($current->ID);
            }
            if (!empty($page['shortcode'])) {
                $desired = trim($page['content']);
                $existing = (string) $current->post_content;
                $shortcode = $page['shortcode'];
                $pattern = '/\[' . preg_quote($shortcode, '/') . '(\s[^\]]*)?\]/i';
                $occurrences = preg_match_all($pattern, $existing, $matches);
                $needs_reset = false;
                if ($occurrences !== 1) {
                    $needs_reset = true;
                } else {
                    $stripped = trim(strip_shortcodes($existing));
                    if ($stripped !== '') {
                        $needs_reset = true;
                    }
                }
                if (!has_shortcode($existing, $shortcode)) {
                    $needs_reset = true;
                }
                if ($needs_reset) {
                    wp_update_post(array('ID' => $current->ID, 'post_content' => $desired));
                    $current = get_post($current->ID);
                    error_log('VIRALAY DEBUG: Sayfa içeriği güncellendi - ' . $slug . ' (ID: ' . $current->ID . ') Content: ' . $desired);
                }
            }
        }
        if ($current) {
            if (!empty($page['template'])) {
                update_post_meta($current->ID, '_wp_page_template', $page['template']);
                error_log('VIRALAY DEBUG: Template atandı - ' . $slug . ' -> ' . $page['template']);
            }
            $permalinks[$slug] = get_permalink($current);
        } else {
            error_log('VIRALAY DEBUG: HATA - Sayfa bulunamadı: ' . $slug);
        }
    }
    if (!empty($permalinks['video-gonder'])) {
        update_option('viral_ay_video_submit_link', $permalinks['video-gonder']);
    }
    if (!empty($permalinks['panel'])) {
        update_option('viral_ay_user_panel_link', $permalinks['panel']);
    }
    if (!empty($permalinks['giris-yap'])) {
        update_option('viral_ay_login_link', $permalinks['giris-yap']);
    }
    if (!empty($permalinks['lisans-al'])) {
        update_option('viral_ay_license_cta_link', $permalinks['lisans-al']);
    }
    update_option('viral_ay_plugin_pages_created', 'yes');
    error_log('VIRALAY DEBUG: Tüm sayfalar işlendi. viral_ay_plugin_pages_created = yes');
}
add_action('after_setup_theme', 'viral_ay_create_plugin_pages');

// Admin Debug Sayfası
add_action('admin_menu', 'viralay_add_debug_page');
function viralay_add_debug_page() {
    add_submenu_page(
        'tools.php',
        'Viralay Debug',
        'Viralay Debug',
        'manage_options',
        'viralay-debug',
        'viralay_debug_page_content'
    );
}

function viralay_debug_page_content() {
    echo '<div class="wrap">';
    echo '<h1>Viralay Tema Debug</h1>';
    
    // Reset butonu
    if (isset($_POST['reset_pages'])) {
        delete_option('viral_ay_plugin_pages_created');
        error_log('VIRALAY DEBUG: Admin panelden RESET butonuna basıldı. Option silindi, fonksiyon tekrar çalışacak.');
        // Sayfayı yenile ki fonksiyon tekrar çalışsın
        echo '<div class="notice notice-success"><p><strong>Sayfa oluşturma sıfırlandı!</strong> Sayfayı yenileyin veya herhangi bir sayfayı ziyaret edin. Fonksiyon otomatik çalışacak ve sayfaları güncelleyecek.</p></div>';
        echo '<script>setTimeout(function(){ location.reload(); }, 2000);</script>';
    }
    
    // Manuel güncelleme butonu
    if (isset($_POST['manual_update'])) {
        error_log('VIRALAY DEBUG: Manuel güncelleme butonuna basıldı. Fonksiyon zorla çalıştırılıyor...');
        delete_option('viral_ay_plugin_pages_created'); // Option'ı sil ki fonksiyon çalışsın
        viral_ay_create_plugin_pages(); // Fonksiyonu zorla çalıştır
        echo '<div class="notice notice-success"><p><strong>Sayfalar manuel olarak güncellendi!</strong> Debug log dosyasını kontrol edin.</p></div>';
        echo '<script>setTimeout(function(){ location.reload(); }, 2000);</script>';
    }
    
    echo '<h2>Hızlı İşlemler</h2>';
    echo '<form method="post" style="display: inline-block; margin-right: 10px;">';
    echo '<input type="submit" name="manual_update" class="button button-primary" value="🔄 Sayfaları ŞİMDİ Güncelle">';
    echo '</form>';
    echo '<form method="post" style="display: inline-block;">';
    echo '<input type="submit" name="reset_pages" class="button button-secondary" value="🗑️ Sıfırla (Sonra sayfa yenile)">';
    echo '</form>';
    echo '<p class="description">🔄 Butonu: Sayfaları hemen günceller ve debug log yazar.<br>🗑️ Butonu: Sayfa oluşturma durumunu sıfırlar, sonra herhangi bir sayfayı ziyaret edin.</p>';
    echo '<hr>';
    
    // Sayfa durumları
    $pages = array(
        'giris-yap' => 'Giriş Yap',
        'panel' => 'Panel',
        'video-gonder' => 'Video Gönder',
        'parola-belirle' => 'Parola Belirle',
        'lisans-al' => 'Lisans Al'
    );
    
    echo '<h2>Sayfa Durumları</h2>';
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>Sayfa</th><th>Durum</th><th>İçerik</th><th>Template</th><th>URL</th></tr></thead>';
    echo '<tbody>';
    
    foreach ($pages as $slug => $title) {
        $page = get_page_by_path($slug);
        echo '<tr>';
        echo '<td><strong>' . esc_html($title) . '</strong><br><small>Slug: ' . esc_html($slug) . '</small></td>';
        
        if ($page) {
            echo '<td style="color: green;">✓ Var (ID: ' . $page->ID . ')</td>';
            
            // İçerik kontrolü - shortcode var mı?
            $content = $page->post_content;
            $has_shortcode = false;
            $expected_shortcodes = array(
                'giris-yap' => 'viralay_login',
                'panel' => 'viralay_user_dashboard',
                'video-gonder' => 'viralay_video_form',
                'parola-belirle' => 'viralay_set_password',
                'lisans-al' => 'viralay_license_form'
            );
            
            if (isset($expected_shortcodes[$slug]) && has_shortcode($content, $expected_shortcodes[$slug])) {
                $has_shortcode = true;
            }
            
            if ($has_shortcode) {
                echo '<td style="color: green; font-weight: bold;">✓ <code>' . esc_html(substr($content, 0, 50)) . '...</code></td>';
            } else {
                echo '<td style="color: red; font-weight: bold;">✗ BOŞ veya YANLIŞ! <br><small>Mevcut: <code>' . esc_html(substr($content, 0, 100)) . '</code></small></td>';
            }
            
            $template = get_post_meta($page->ID, '_wp_page_template', true);
            echo '<td>' . esc_html($template ?: 'Default') . '</td>';
            echo '<td><a href="' . get_permalink($page) . '" target="_blank" class="button">Görüntüle</a></td>';
        } else {
            echo '<td style="color: red;">✗ Yok</td>';
            echo '<td colspan="3">-</td>';
        }
        
        echo '</tr>';
    }
    
    echo '</tbody></table>';
    
    // Eklenti durumu
    echo '<h2>Eklenti Durumu</h2>';
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>Shortcode</th><th>Durum</th></tr></thead>';
    echo '<tbody>';
    
    $shortcodes = array(
        'viralay_login' => 'Giriş Formu',
        'viralay_user_dashboard' => 'Kullanıcı Paneli',
        'viralay_video_form' => 'Video Gönder Formu',
        'viralay_set_password' => 'Parola Belirle',
        'viralay_license_form' => 'Lisans Al Formu'
    );
    
    foreach ($shortcodes as $shortcode => $title) {
        echo '<tr>';
        echo '<td><strong>' . esc_html($title) . '</strong> (<code>[' . $shortcode . ']</code>)</td>';
        if (shortcode_exists($shortcode)) {
            echo '<td style="color: green;">✓ Kayıtlı</td>';
        } else {
            echo '<td style="color: red;">✗ Kayıtsız (Eklenti aktif değil veya yüklenmedi)</td>';
        }
        echo '</tr>';
    }
    
    echo '</tbody></table>';
    
    // Option'lar
    echo '<h2>WordPress Options</h2>';
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>Option</th><th>Değer</th></tr></thead>';
    echo '<tbody>';
    
    $options = array(
        'viral_ay_plugin_pages_created',
        'viral_ay_permalinks_flushed',
        'viral_ay_login_link',
        'viral_ay_user_panel_link',
        'viral_ay_video_submit_link',
        'viral_ay_license_cta_link'
    );
    
    foreach ($options as $option) {
        echo '<tr>';
        echo '<td><code>' . esc_html($option) . '</code></td>';
        $value = get_option($option, '(boş)');
        echo '<td>' . esc_html($value) . '</td>';
        echo '</tr>';
    }
    
    echo '</tbody></table>';
    
    echo '</div>';
}


// Template Redirect ve Routing Düzeltmeleri
function viral_ay_template_redirect_fixes() {
    // Permalink yapısını flush et (sadece tema aktivasyonunda)
    if (get_option('viral_ay_permalinks_flushed') != 'yes') {
        flush_rewrite_rules();
        update_option('viral_ay_permalinks_flushed', 'yes');
    }
}
add_action('template_redirect', 'viral_ay_template_redirect_fixes');

// Tema deaktive edildiğinde permalink flush seçeneğini sıfırla
function viral_ay_deactivation_cleanup() {
    delete_option('viral_ay_permalinks_flushed');
}
add_action('switch_theme', 'viral_ay_deactivation_cleanup');

function viralay_theme_activation_setup() {
    $pages_to_create = array(
        'video-gonder' => array(
            'title' => 'Video Gönder',
            'content' => '[viralay_video_form]',
            'template' => 'page-video-gonder.php'
        ),
        'giris-yap' => array(
            'title' => 'Giriş Yap',
            'content' => '[viralay_login]',
            'template' => 'page-giris-yap.php'
        ),
        'panel' => array(
            'title' => 'Kullanıcı Paneli',
            'content' => '[viralay_user_dashboard]',
            'template' => 'page-panel.php'
        ),
        'parola-belirle' => array(
            'title' => 'Parola Belirle',
            'content' => '[viralay_set_password]',
            'template' => 'page-parola-belirle.php'
        ),
        'lisans-paneli' => array(
            'title' => 'Lisans Paneli',
            'content' => '[viralay_lisans_paneli]',
            'template' => 'page-lisans-paneli.php'
        ),
        'video-galeri' => array(
            'title' => 'Video Galeri',
            'content' => '',
            'template' => ''
        ),
        'lisans-al' => array(
            'title' => 'Lisans Al',
            'content' => '[viralay_license_form popup="0"]',
            'template' => 'page-lisans-al.php'
        ),
        'sss' => array(
            'title' => 'Sıkça Sorulan Sorular',
            'content' => '',
            'template' => 'page-sss.php'
        ),
        'hakkimizda' => array(
            'title' => 'Hakkımızda',
            'content' => '',
            'template' => 'page-hakkimizda.php'
        ),
        'iletisim' => array(
            'title' => 'İletişim',
            'content' => '',
            'template' => 'page-iletisim.php'
        ),
        'gizlilik-politikasi' => array(
            'title' => 'Gizlilik Politikası',
            'content' => '',
            'template' => 'page-gizlilik-politikasi.php'
        ),
        'hizmet-sartlari' => array(
            'title' => 'Hizmet Şartları',
            'content' => '',
            'template' => 'page-hizmet-sartlari.php'
        ),
        'kvkk-aydinlatma-metni' => array(
            'title' => 'KVKK Aydınlatma Metni',
            'content' => '',
            'template' => 'page-kvkk-aydinlatma-metni.php'
        )
    );
    
    foreach ($pages_to_create as $slug => $page_data) {
        $existing = get_page_by_path($slug);
        
        if (!$existing) {
            $page_id = wp_insert_post(array(
                'post_title' => $page_data['title'],
                'post_name' => $slug,
                'post_content' => $page_data['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1
            ));
            
            if ($page_id && !is_wp_error($page_id) && !empty($page_data['template'])) {
                update_post_meta($page_id, '_wp_page_template', $page_data['template']);
            }
        }
    }
    
    flush_rewrite_rules();
    update_option('viralay_theme_pages_created', 'yes');
}
add_action('after_switch_theme', 'viralay_theme_activation_setup');


