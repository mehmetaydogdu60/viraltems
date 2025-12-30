<?php
/**
 * Template Name: Lisans Al
 * Viralay Lisans Başvuru Sayfası
 */

if (!defined('ABSPATH')) exit;

get_header();
?>

<main class="min-h-screen bg-gradient-to-b from-primary/10 to-background py-16">
    <div class="container mx-auto px-4">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Lisans Al
            </h1>
            <p class="text-lg text-muted-foreground max-w-2xl mx-auto">
                Profesyonel video içeriklerimize erişim için lisans alın. Tüm videolara sınırsız indirme hakkı kazanın.
            </p>
        </div>

        <!-- Features -->
        <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto mb-12">
            <div class="bg-white/5 border border-white/10 rounded-xl p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-teal-500/20 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </div>
                <h3 class="text-white font-semibold mb-2">Sınırsız İndirme</h3>
                <p class="text-muted-foreground text-sm">Tüm videolara sınırsız indirme hakkı</p>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-xl p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-teal-500/20 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-white font-semibold mb-2">Ticari Kullanım</h3>
                <p class="text-muted-foreground text-sm">Tüm projelerinizde kullanım lisansı</p>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-xl p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-teal-500/20 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-white font-semibold mb-2">Yüksek Kalite</h3>
                <p class="text-muted-foreground text-sm">4K ve HD kalitesinde içerikler</p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="max-w-lg mx-auto">
            <?php
            // Viralay eklentisinden shortcode varsa kullan
            if (shortcode_exists('viralay_lisans_al')) {
                echo do_shortcode('[viralay_lisans_al]');
            } elseif (shortcode_exists('viralay_lisans_basvuru_formu')) {
                echo do_shortcode('[viralay_lisans_basvuru_formu]');
            } else {
                ?>
                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 text-center">
                    <div class="w-16 h-16 rounded-full bg-amber-500/20 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-white font-semibold mb-2">Viralay Eklentisi Gerekli</h3>
                    <p class="text-muted-foreground">Lisans başvuru formu için Viralay eklentisinin aktif olması gerekmektedir.</p>
                </div>
                <?php
            }
            ?>
        </div>

        <!-- Contact Info -->
        <div class="text-center mt-12">
            <p class="text-muted-foreground">
                Sorularınız için <a href="<?php echo esc_url(home_url('/iletisim')); ?>" class="text-teal-400 hover:text-teal-300 transition-colors">iletişime geçin</a>
            </p>
        </div>
    </div>
</main>

<?php get_footer(); ?>
