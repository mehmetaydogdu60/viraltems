<?php
/*
Template Name: TV & Prodüksiyon
*/
get_header();
?>

<section class="py-10 bg-gradient-to-b from-purple-500/10 to-background border-b border-white/5">
    <div class="container mx-auto px-6 text-center">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Yayın Standartlarında (Broadcast-Ready) Ham Görüntüler</h1>
            <p class="text-lg text-muted-foreground">Ana haber bültenleri, belgeseller ve TV şovları için yüksek bit-rate değerine sahip, logosuz ve ham master dosyalar.</p>
        </div>
    </div>
</section>

<section class="py-12 px-6">
    <div class="container mx-auto max-w-6xl">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 rounded-xl bg-white/5 border border-white/10 hover:border-purple-500/50 transition-all">
                <div class="w-14 h-14 bg-purple-500 rounded-lg flex items-center justify-center text-white mb-4 shadow-lg">
                    <i class="fas fa-video text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Broadcast Kalite Standartları</h3>
                <p class="text-muted-foreground leading-relaxed">Full HD, 4K ve talep üzerine RAW formatlar. Kurgu masanızla tam uyumlu, sıkıştırma kaybı olmayan ProRes/Master dosya teslimatı.</p>
            </div>
            
            <div class="p-6 rounded-xl bg-white/5 border border-white/10 hover:border-purple-500/50 transition-all">
                <div class="w-14 h-14 bg-purple-500 rounded-lg flex items-center justify-center text-white mb-4 shadow-lg">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">7/24 Arşiv ve Haber Erişimi</h3>
                <p class="text-muted-foreground leading-relaxed">Dünyanın dört bir yanından akan sıcak görüntüler anlık olarak sisteme düşer. Haber bülteninizi zenginleştirecek içeriklere saniyeler içinde ulaşın.</p>
            </div>
            
            <div class="p-6 rounded-xl bg-white/5 border border-white/10 hover:border-purple-500/50 transition-all">
                <div class="w-14 h-14 bg-purple-500 rounded-lg flex items-center justify-center text-white mb-4 shadow-lg">
                    <i class="fas fa-plug text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Teknik Entegrasyon</h3>
                <p class="text-muted-foreground leading-relaxed">Kurumsal müşteriler için FTP, API veya CMS entegrasyonu desteği. XML/JSON metadata akışı ile arşivinizi otomatik besleyin.</p>
            </div>
        </div>
        
        <div class="text-center mt-8">
            <a href="<?php echo esc_url(home_url('/lisans-al')); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-primary hover:bg-primary/90 text-white rounded-lg font-semibold transition-colors">
                <i class="fas fa-paper-plane"></i> TV Lisanslama Talebi Oluştur
            </a>
        </div>
    </div>
</section>

<?php get_footer(); ?>