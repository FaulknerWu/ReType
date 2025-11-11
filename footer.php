<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

$options = retypeGetOptions(isset($this) ? $this : null);
?>

        </main>

        <footer id="footer" role="contentinfo" class="bg-white py-4 mt-5 shadow-sm">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                        <span class="text-muted">
                            &copy; <?php echo date('Y'); ?> 
                            <a href="<?php $options->siteUrl(); ?>" 
                               class="text-decoration-none">
                                <?php $options->title(); ?>
                            </a>
                        </span>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <?php $icpNumber = retypeGetThemeSetting('icpNumber'); ?>
                        <?php if ($icpNumber !== ''): ?>
                            <a href="https://beian.miit.gov.cn/" 
                               target="_blank" 
                               rel="nofollow noopener"
                               class="text-decoration-none text-muted">
                                <?php echo htmlspecialchars($icpNumber, ENT_QUOTES); ?>
                            </a>
                            <span class="text-muted mx-2">|</span>
                        <?php endif; ?>
                        <span class="text-muted">
                            <?php _e('由 <a href="https://typecho.org" rel="nofollow noopener" class="text-decoration-none">Typecho</a> 强力驱动'); ?>
                        </span>
                    </div>
                </div>
            </div>
        </footer>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Add line numbers to code blocks
                document.querySelectorAll('pre:not(.line-numbers):not(.no-line-numbers)').forEach(pre => {
                    if (!pre.closest('.no-line-numbers')) {
                        pre.classList.add('line-numbers');
                    }
                });

                // Initialize LaTeX rendering if enabled
                <?php if (retypeShouldLoadLatex($this)): ?>
                    if (typeof renderMathInElement !== 'undefined') {
                        renderMathInElement(document.body, {
                            delimiters: [
                                { left: "$$", right: "$$", display: true },
                                { left: "$", right: "$", display: false },
                                { left: "\\(", right: "\\)", display: false },
                                { left: "\\[", right: "\\]", display: true }
                            ],
                            throwOnError: false
                        });
                    }
                <?php endif; ?>
            });
        </script>

    </div>

<?php $this->footer(); ?>
</body>
</html>
