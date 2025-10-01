<?php if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
} ?>

<footer id="footer" role="contentinfo" class="bg-white py-4 mt-5 shadow-sm">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                <span class="text-muted">
                    &copy; <?php echo date('Y'); ?> 
                    <a href="<?php $this->options->siteUrl(); ?>" 
                       class="text-decoration-none">
                        <?php $this->options->title(); ?>
                    </a>
                </span>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="https://beian.miit.gov.cn/" 
                   target="_blank" 
                   rel="nofollow noopener"
                   class="text-decoration-none text-muted">
                    湘ICP备2023031253号
                </a>
                <span class="text-muted mx-2">|</span>
                <span class="text-muted">
                    <?php _e('由 <a href="https://typecho.org" rel="nofollow noopener" class="text-decoration-none">Typecho</a> 强力驱动'); ?>
                </span>
            </div>
        </div>
    </div>
</footer>

<?php $this->footer(); ?>
</body>
</html>