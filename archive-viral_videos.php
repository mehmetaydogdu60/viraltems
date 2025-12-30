<?php get_header(); ?>

<?php
$is_lisans_alicisi = false;
$can_download = false;
if (is_user_logged_in() && class_exists('Viralay_Abone_Manager')) {
    $is_lisans_alicisi = Viralay_Abone_Manager::is_abone();
    $can_download = Viralay_Abone_Manager::can_download_video();
}
// Admin de indirebilir
if (current_user_can('manage_options')) {
    $can_download = true;
}
?>

<?php if ($is_lisans_alicisi || current_user_can('manage_options')) : ?>
<div class="bg-gradient-to-r from-teal-600 to-teal-500 py-4 border-b border-teal-400/30">
    <div class="container mx-auto px-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-white font-semibold">Hoş geldiniz, <?php echo esc_html(wp_get_current_user()->display_name); ?></p>
                    <p class="text-teal-100 text-sm"><?php echo current_user_can('manage_options') ? 'Admin hesabı' : 'Lisans hesabınız aktif'; ?></p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="px-4 py-2 bg-white/20 rounded-lg text-white text-sm font-medium">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Video İndirme Aktif
                </span>
                <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-white text-sm font-medium transition-colors">
                    Çıkış
                </a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="py-8 bg-gradient-to-b from-primary/10 to-background border-b border-white/5">
    <div class="container mx-auto px-6">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-4">
                <?php
                if (is_tax('video_category')) {
                    single_cat_title();
                } else {
                    echo 'Video Galeri';
                }
                ?>
            </h1>
            <p class="text-muted-foreground">Uzman seçimi kaliteli videolar, her tür projeniz için lisanslama imkanı</p>
        </div>
    </div>
</div>

<div class="py-8">
    <div class="container mx-auto px-6">
        
        <!-- Video Filter Form -->
        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 mb-8">
            <form method="get" action="<?php echo esc_url(home_url('/video')); ?>" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Search Input -->
                <div>
                    <label for="video-search" class="block text-sm font-medium text-white mb-2">
                        <i class="fas fa-search text-primary"></i> Video Ara
                    </label>
                    <input 
                        type="text" 
                        id="video-search" 
                        name="s" 
                        placeholder="Video başlığı veya açıklama..."
                        value="<?php echo esc_attr(get_query_var('s')); ?>"
                        class="w-full px-4 py-2 rounded-lg bg-white/5 border border-white/10 text-white placeholder-muted-foreground focus:outline-none focus:border-primary transition-colors"
                    >
                </div>
                
                <!-- Category Filter -->
                <div>
                    <label for="video-category" class="block text-sm font-medium text-white mb-2">
                        <i class="fas fa-folder text-primary"></i> Kategori
                    </label>
                    <select 
                        id="video-category" 
                        name="video_category"
                        class="w-full px-4 py-2 rounded-lg bg-white/5 border border-white/10 text-white focus:outline-none focus:border-primary transition-colors"
                    >
                        <option value="">Tüm Kategoriler</option>
                        <?php
                        $categories = get_terms(array(
                            'taxonomy' => 'video_category',
                            'hide_empty' => false
                        ));
                        
                        if (!is_wp_error($categories)) {
                            $current_category = get_query_var('video_category');
                            foreach ($categories as $category) {
                                $selected = ($current_category === $category->slug) ? 'selected' : '';
                                echo '<option value="' . esc_attr($category->slug) . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                
                <!-- Duration Filter -->
                <div>
                    <label for="video-duration" class="block text-sm font-medium text-white mb-2">
                        <i class="fas fa-clock text-primary"></i> Süre
                    </label>
                    <select 
                        id="video-duration" 
                        name="duration"
                        class="w-full px-4 py-2 rounded-lg bg-white/5 border border-white/10 text-white focus:outline-none focus:border-primary transition-colors"
                    >
                        <option value="">Tüm Süreler</option>
                        <option value="5" <?php selected(isset($_GET['duration']) ? $_GET['duration'] : '', '5'); ?>>5 dk altı</option>
                        <option value="10" <?php selected(isset($_GET['duration']) ? $_GET['duration'] : '', '10'); ?>>10 dk altı</option>
                        <option value="20" <?php selected(isset($_GET['duration']) ? $_GET['duration'] : '', '20'); ?>>20 dk altı</option>
                        <option value="30" <?php selected(isset($_GET['duration']) ? $_GET['duration'] : '', '30'); ?>>30 dk altı</option>
                        <option value="60" <?php selected(isset($_GET['duration']) ? $_GET['duration'] : '', '60'); ?>>1 saat altı</option>
                    </select>
                </div>
                
                <!-- Sort Filter -->
                <div>
                    <label for="video-sort" class="block text-sm font-medium text-white mb-2">
                        <i class="fas fa-sort text-primary"></i> Sıralama
                    </label>
                    <select 
                        id="video-sort" 
                        name="orderby"
                        class="w-full px-4 py-2 rounded-lg bg-white/5 border border-white/10 text-white focus:outline-none focus:border-primary transition-colors"
                    >
                        <option value="date" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'date'); ?>>En Yeni</option>
                        <option value="date_asc" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'date_asc'); ?>>En Eski</option>
                        <option value="title" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'title'); ?>>İsme Göre (A-Z)</option>
                    </select>
                </div>
                
                <!-- Filter Buttons -->
                <div class="md:col-span-2 lg:col-span-4 flex gap-3">
                    <button type="submit" class="px-6 py-2 rounded-lg bg-primary hover:bg-primary/90 text-white font-semibold transition-colors">
                        <i class="fas fa-filter"></i> Filtrele
                    </button>
                    <a href="<?php echo esc_url(home_url('/video')); ?>" class="px-6 py-2 rounded-lg border border-white/10 hover:bg-white/5 text-white font-semibold transition-colors">
                        <i class="fas fa-times"></i> Temizle
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Results Info -->
        <div class="flex justify-between items-center mb-6 text-sm">
            <p class="text-muted-foreground">
                <?php
                global $wp_query;
                $total = $wp_query->found_posts;
                echo '<strong class="text-white">' . number_format($total) . '</strong> video bulundu';
                ?>
            </p>
        </div>
        
        <!-- Videos Grid -->
        <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php
                    $submitter_name = get_post_meta(get_the_ID(), '_submitter_name', true);
                    $video_duration = get_post_meta(get_the_ID(), '_video_duration', true);
                    $thumbnail_url = get_video_thumbnail(get_the_ID());
                    $duration_text = get_post_meta(get_the_ID(), '_video_duration_text', true);
                    $license_type = get_post_meta(get_the_ID(), '_video_license_type', true);
                    $categories = get_the_terms(get_the_ID(), 'video_category');
                    ?>
                    <div class="group block bg-white/5 rounded-xl overflow-hidden border border-white/10 hover:border-primary/50 transition-all">
                        <a href="<?php the_permalink(); ?>" class="block">
                            <div class="relative aspect-video bg-black/50">
                                <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/40">
                                    <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center shadow-lg transform scale-0 group-hover:scale-100 transition-transform">
                                        <i class="fas fa-play"></i>
                                    </div>
                                </div>
                                <?php if ($duration_text || $video_duration) : ?>
                                    <span class="absolute bottom-2 right-2 bg-black/80 text-white text-xs px-2 py-1 rounded font-mono">
                                        <?php echo esc_html($duration_text ?: $video_duration . ' dk'); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($license_type) : ?>
                                    <span class="absolute top-2 left-2 bg-primary/90 text-white text-xs px-2 py-1 rounded font-semibold">
                                        <?php
                                        switch ($license_type) {
                                            case 'extended': echo 'Genişletilmiş'; break;
                                            case 'exclusive': echo 'Münhasır'; break;
                                            default: echo 'Standart';
                                        }
                                        ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </a>
                        <div class="p-4">
                            <a href="<?php the_permalink(); ?>">
                                <h3 class="font-semibold text-white line-clamp-2 group-hover:text-primary transition-colors mb-2"><?php the_title(); ?></h3>
                            </a>
                            
                            <?php if ($categories && !is_wp_error($categories)) : ?>
                                <div class="flex flex-wrap gap-1 mb-2">
                                    <?php foreach (array_slice($categories, 0, 2) as $category) : ?>
                                        <span class="inline-flex items-center text-xs px-2 py-0.5 rounded bg-primary/20 text-primary">
                                            <?php echo esc_html($category->name); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex items-center justify-between text-xs text-muted-foreground">
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-user"></i>
                                    <?php echo esc_html($submitter_name ?: 'Anonim'); ?>
                                </span>
                                <span><?php echo get_the_date('d.m.Y'); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="col-span-full text-center py-20">
                    <i class="fas fa-video-slash text-6xl text-white/20 mb-4"></i>
                    <h3 class="text-xl font-bold text-white mb-2">Video bulunamadı</h3>
                    <p class="text-muted-foreground mb-6">Arama kriterlerinizi değiştirmeyi deneyin</p>
                    <a href="<?php echo esc_url(home_url('/video')); ?>" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary hover:bg-primary/90 text-white font-semibold transition-colors">
                        <i class="fas fa-refresh"></i>
                        Tüm Videolar
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <?php if (have_posts() && $wp_query->max_num_pages > 1) : ?>
            <div class="mt-12 flex justify-center">
                <div class="flex items-center gap-2">
                    <?php
                    echo paginate_links(array(
                        'prev_text' => '<i class="fas fa-chevron-left"></i>',
                        'next_text' => '<i class="fas fa-chevron-right"></i>',
                        'type' => 'array',
                        'before_page_number' => '<span class="sr-only">Sayfa </span>'
                    ));
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.page-numbers {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0 12px;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(255, 255, 255, 0.05);
    color: white;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.page-numbers:hover {
    background: hsl(217 91% 60%);
    border-color: hsl(217 91% 60%);
    color: white;
}

.page-numbers.current {
    background: hsl(217 91% 60%);
    border-color: hsl(217 91% 60%);
    color: white;
}

.page-numbers.dots {
    border: none;
    background: transparent;
}

/* Select dropdown fix - dark text on white background */
select {
    color: white !important;
    background-color: rgba(255, 255, 255, 0.05) !important;
}

select option {
    background-color: #1e293b !important;
    color: white !important;
}

select:focus {
    outline: 2px solid hsl(217 91% 60%);
    outline-offset: 2px;
}
</style>

<?php get_footer(); ?>
