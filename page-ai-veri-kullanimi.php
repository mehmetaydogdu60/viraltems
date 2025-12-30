<?php
/*
Template Name: AI Veri Kullanımı
*/
get_header();
?>

<section class="py-8 bg-gradient-to-b from-primary/10 to-background border-b border-white/5">
    <div class="container mx-auto px-6 text-center">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Yapay Zeka Eğitimi İçin Etik ve Lisanslı Veri Setleri</h1>
            <p class="text-lg text-muted-foreground">Makine öğrenimi (ML) ve Bilgisayarlı Görü (Computer Vision) modelleriniz için, hak sahiplerinin izniyle oluşturulmuş, yasal zemini sağlam video veri setleri.</p>
        </div>
    </div>
</section>

<section class="py-8 px-6">
    <div class="container mx-auto max-w-6xl">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 rounded-xl bg-white/5 border border-white/10 hover:border-primary/50 transition-all">
                <div class="w-14 h-14 bg-primary rounded-lg flex items-center justify-center text-white mb-4 shadow-lg">
                    <i class="fas fa-gavel text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Hukuki Uyum ve Etik Veri</h3>
                <p class="text-muted-foreground leading-relaxed">Telif davaları riskinden uzak durun. Tüm veri setlerimiz, AI eğitimi için özel izinleri alınmış "Beyaz Şapka" (White-Hat) veri politikasını kapsar.</p>
            </div>
            
            <div class="p-6 rounded-xl bg-white/5 border border-white/10 hover:border-primary/50 transition-all">
                <div class="w-14 h-14 bg-primary rounded-lg flex items-center justify-center text-white mb-4 shadow-lg">
                    <i class="fas fa-tags text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Zengin Metadata ve Etiketleme</h3>
                <p class="text-muted-foreground leading-relaxed">Nesne tanıma, duygu analizi ve hareket takibi için detaylı etiketlenmiş (Annotated), kategorize edilmiş binlerce saatlik video havuzu.</p>
            </div>
            
            <div class="p-6 rounded-xl bg-white/5 border border-white/10 hover:border-primary/50 transition-all">
                <div class="w-14 h-14 bg-primary rounded-lg flex items-center justify-center text-white mb-4 shadow-lg">
                    <i class="fas fa-code text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">API ve Toplu (Bulk) Erişim</h3>
                <p class="text-muted-foreground leading-relaxed">REST API üzerinden anlık veri çekimi veya proje bazlı toplu veri indirme (Bulk Download) seçenekleri. JSON şemaları ve SDK desteği.</p>
            </div>
        </div>
        
        <div class="text-center mt-8">
            <a href="<?php echo esc_url(home_url('/lisans-al')); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-primary hover:bg-primary/90 text-white rounded-lg font-semibold transition-colors">
                <i class="fas fa-download"></i> Veri Seti Örneği Talep Et
            </a>
        </div>
    </div>
</section>

<?php get_footer(); ?>