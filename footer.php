    </main>

    <!-- Footer -->
    <footer class="w-full py-8 px-6 border-t border-white/10 bg-background mt-auto">
        <div class="max-w-6xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 mb-8">
            <div>
                <h3 class="font-bold text-xl site-title-custom mb-4"><?php echo esc_html(get_option('viralay_site_title_text', 'VIRALAY')); ?></h3>
                <p class="text-muted-foreground text-sm">Viral içeriklerinizi küresel pazara taşıyan lisanslama platformu.</p>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-4">Platform</h4>
                <ul class="space-y-2 text-sm text-muted-foreground">
                    <li><a href="<?php echo home_url('/yayincilar'); ?>" class="hover:text-primary">Yayıncılar</a></li>
                    <li><a href="<?php echo home_url('/markalar-ajanslar'); ?>" class="hover:text-primary">Markalar</a></li>
                    <li><a href="<?php echo home_url('/tv-produksiyon'); ?>" class="hover:text-primary">TV & Prodüksiyon</a></li>
                    <li><a href="<?php echo home_url('/ai-veri-kullanimi'); ?>" class="hover:text-primary">AI Veri Kullanımı</a></li>
                    <li><a href="<?php echo home_url('/hakkimizda'); ?>" class="hover:text-primary">Hakkımızda</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-4">Destek</h4>
                <ul class="space-y-2 text-sm text-muted-foreground">
                    <li><a href="<?php echo home_url('/sss'); ?>" class="hover:text-primary">Sıkça Sorulan Sorular</a></li>
                    <li><a href="<?php echo home_url('/iletisim'); ?>" class="hover:text-primary">İletişim</a></li>
                    <li><a href="<?php echo home_url('/lisans-al'); ?>" class="hover:text-primary">Lisans Al</a></li>
                </ul>
            </div>
             <div>
                <h4 class="font-semibold text-white mb-4">Yasal</h4>
                <ul class="space-y-2 text-sm text-muted-foreground">
                    <li><a href="<?php echo home_url('/hizmet-sartlari'); ?>" class="hover:text-primary">Hizmet Şartları</a></li>
                    <li><a href="<?php echo home_url('/gizlilik-politikasi'); ?>" class="hover:text-primary">Gizlilik Politikası</a></li>
                    <li><a href="<?php echo home_url('/kvkk-aydinlatma-metni'); ?>" class="hover:text-primary">KVKK Aydınlatma</a></li>
                    <li><a href="<?php echo home_url('/cerez-politikasi'); ?>" class="hover:text-primary">Çerez Politikası</a></li>
                    <li><a href="<?php echo home_url('/telif-hakki-dmca'); ?>" class="hover:text-primary">DMCA</a></li>
                </ul>
            </div>
        </div>

        <!-- Ticker -->
        <div class="w-full py-6 overflow-hidden border-t border-white/5 bg-white/[0.02]">
            <div class="flex items-center gap-8 animate-scroll whitespace-nowrap px-6">
                <span class="text-lg font-medium text-muted-foreground">#Hayvanlar</span>
                <span class="text-lg font-medium text-muted-foreground">#İnsanlar</span>
                <span class="text-lg font-medium text-muted-foreground">#Kazalar</span>
                <span class="text-lg font-medium text-muted-foreground">#Viral</span>
                <span class="text-lg font-medium text-muted-foreground">#YaşamTarzı</span>
                <span class="text-lg font-medium text-muted-foreground">#Teknoloji</span>
                <span class="text-lg font-medium text-muted-foreground">#Hayvanlar</span>
                <span class="text-lg font-medium text-muted-foreground">#İnsanlar</span>
                <span class="text-lg font-medium text-muted-foreground">#Kazalar</span>
                <span class="text-lg font-medium text-muted-foreground">#Viral</span>
                <span class="text-lg font-medium text-muted-foreground">#YaşamTarzı</span>
                <span class="text-lg font-medium text-muted-foreground">#Teknoloji</span>
            </div>
        </div>
        
        <div class="text-center text-xs text-muted-foreground mt-8">
            &copy; <?php echo date('Y'); ?> Viralay. Tüm hakları saklıdır.
        </div>
    </footer>
    <?php wp_footer(); ?>
</body>
</html>
