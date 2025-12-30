<?php
/**
 * Template Name: Lisans Paneli
 * Viralay Lisans Alıcısı Panel Sayfası
 */

if (!defined('ABSPATH')) exit;

// Giriş kontrolü
if (!is_user_logged_in()) {
    wp_redirect(home_url('/giris-yap'));
    exit;
}

$user = wp_get_current_user();

// Rol kontrolü - lisans_alicisi veya administrator
$allowed_roles = array('lisans_alicisi', 'administrator');
$has_access = false;
foreach ($allowed_roles as $role) {
    if (in_array($role, (array) $user->roles)) {
        $has_access = true;
        break;
    }
}

if (!$has_access) {
    // Erişim yok - ana sayfaya yönlendir veya uyarı göster
    wp_redirect(home_url('/lisans-al'));
    exit;
}

get_header();
?>

<main class="min-h-screen bg-gradient-to-b from-muted to-background py-12">
    <div class="container mx-auto px-4">
        <?php
        // Viralay eklentisinden shortcode varsa kullan
        if (shortcode_exists('viralay_lisans_paneli')) {
            echo do_shortcode('[viralay_lisans_paneli]');
        } else {
            ?>
            <div class="max-w-4xl mx-auto">
                <div class="bg-white/5 border border-white/10 rounded-2xl p-8">
                    <!-- Profile Header -->
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-6 mb-8 pb-8 border-b border-white/10">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                            <?php echo strtoupper(substr($user->display_name, 0, 1)); ?>
                        </div>
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-white mb-1"><?php echo esc_html($user->display_name); ?></h1>
                            <p class="text-teal-400"><?php echo esc_html($user->user_email); ?></p>
                            <p class="text-muted-foreground text-sm mt-1">Lisans hesabınız aktif</p>
                        </div>
                        <div class="flex gap-3">
                            <a href="<?php echo esc_url(home_url('/video')); ?>" class="px-4 py-2 rounded-lg bg-teal-500 hover:bg-teal-600 text-white font-medium transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                Video Galeri
                            </a>
                            <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="px-4 py-2 rounded-lg border border-white/20 hover:bg-white/5 text-white font-medium transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Çıkış
                            </a>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="grid md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white/5 rounded-xl p-6 text-center">
                            <div class="w-12 h-12 rounded-full bg-teal-500/20 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </div>
                            <p class="text-2xl font-bold text-white">0</p>
                            <p class="text-muted-foreground text-sm">Toplam İndirme</p>
                        </div>
                        <div class="bg-white/5 rounded-xl p-6 text-center">
                            <div class="w-12 h-12 rounded-full bg-emerald-500/20 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="text-2xl font-bold text-white">Aktif</p>
                            <p class="text-muted-foreground text-sm">Lisans Durumu</p>
                        </div>
                        <div class="bg-white/5 rounded-xl p-6 text-center">
                            <div class="w-12 h-12 rounded-full bg-cyan-500/20 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <p class="text-2xl font-bold text-white"><?php echo date('d.m.Y', strtotime($user->user_registered)); ?></p>
                            <p class="text-muted-foreground text-sm">Üyelik Tarihi</p>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div class="text-center py-8">
                        <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-white font-semibold mb-2">Videoları Keşfedin</h3>
                        <p class="text-muted-foreground mb-6">Video galerimizden dilediğiniz içerikleri indirin</p>
                        <a href="<?php echo esc_url(home_url('/video')); ?>" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-teal-500 hover:bg-teal-600 text-white font-semibold transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Video Galeriye Git
                        </a>
                    </div>

                    <p class="text-center text-sm text-muted-foreground mt-4">
                        Detaylı indirme geçmişi için Viralay eklentisinin aktif olması gerekmektedir.
                    </p>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</main>

<?php get_footer(); ?>
