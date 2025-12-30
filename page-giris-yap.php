<?php
/*
Template Name: Giriş Yap
*/
get_header();

// URL'den giriş tipi al
$login_type = isset($_GET['type']) ? sanitize_key($_GET['type']) : '';

// Giriş yapmış kullanıcıyı yönlendir
if (is_user_logged_in()) {
    $current_user = wp_get_current_user();
    $is_lisans_alicisi = in_array('lisans_alicisi', (array) $current_user->roles, true);

    if ($is_lisans_alicisi) {
        wp_redirect(home_url('/lisans-paneli'));
        exit;
    } else {
        wp_redirect(home_url('/panel'));
        exit;
    }
}
?>

<section class="py-12 sm:py-20 px-4 min-h-[70vh] flex items-center justify-center">
    <div class="w-full max-w-xl mx-auto">

        <?php if (empty($login_type)) : ?>
        <!-- İki Seçenekli Giriş Sayfası -->
        <div class="text-center mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-foreground mb-3">Giriş Yap</h1>
            <p class="text-muted-foreground text-lg">Hesap türünüzü seçerek devam edin</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Lisans Alıcı Girişi -->
            <a href="<?php echo esc_url(add_query_arg('type', 'lisans', get_permalink())); ?>" class="group relative bg-gradient-to-br from-teal-500/20 to-teal-600/10 border-2 border-teal-500/30 hover:border-teal-400 rounded-2xl p-8 transition-all duration-300 hover:shadow-xl hover:shadow-teal-500/10 hover:-translate-y-1">
                <div class="text-center">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center mx-auto mb-5 shadow-lg shadow-teal-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-foreground mb-2">Lisans Alıcı Girişi</h3>
                    <p class="text-muted-foreground text-sm">Lisans alarak video içeriklerini yüksek kalitede indirin</p>
                </div>
            </a>

            <!-- Video Gönderen Girişi -->
            <a href="<?php echo esc_url(add_query_arg('type', 'client', get_permalink())); ?>" class="group relative bg-gradient-to-br from-cyan-500/20 to-cyan-600/10 border-2 border-cyan-500/30 hover:border-cyan-400 rounded-2xl p-8 transition-all duration-300 hover:shadow-xl hover:shadow-cyan-500/10 hover:-translate-y-1">
                <div class="text-center">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center mx-auto mb-5 shadow-lg shadow-cyan-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-foreground mb-2">Video Gönderen Girişi</h3>
                    <p class="text-muted-foreground text-sm">Video gönderenler için panel erişimi ve kazanç takibi</p>
                </div>
            </a>
        </div>

        <div class="text-center mt-8 pt-6 border-t border-white/10">
            <p class="text-muted-foreground text-sm">
                Henüz hesabınız yok mu?
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center mt-3">
                <a href="<?php echo esc_url(home_url('/lisans-al')); ?>" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full bg-teal-500 hover:bg-teal-600 text-white font-semibold transition-colors text-sm">
                    Lisans Başvurusu
                </a>
                <a href="<?php echo esc_url(home_url('/video-gonder')); ?>" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full border-2 border-cyan-500/50 hover:bg-cyan-500/10 text-cyan-400 font-semibold transition-colors text-sm">
                    Video Gönder
                </a>
            </div>
        </div>

        <?php else : ?>
        <!-- Giriş Formu -->
        <div class="text-center mb-6">
            <a href="<?php echo esc_url(get_permalink()); ?>" class="inline-flex items-center gap-2 text-muted-foreground hover:text-primary transition-colors mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Geri
            </a>

            <?php if ($login_type === 'lisans') : ?>
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-teal-500/30">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-foreground mb-2">Lisans Alıcı Girişi</h1>
                <p class="text-muted-foreground text-sm">Lisans hesabınızla giriş yapın</p>
            <?php else : ?>
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-cyan-500/30">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-foreground mb-2">Video Gönderen Girişi</h1>
                <p class="text-muted-foreground text-sm">Video gönderenler paneline erişin</p>
            <?php endif; ?>
        </div>

        <!-- Giriş Formu (Shortcode'dan) -->
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                the_content();
            endwhile;
        endif;
        ?>

        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>
