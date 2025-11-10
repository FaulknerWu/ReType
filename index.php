<?php
/**
 * ReType - 一个简洁优雅的 Typecho 主题
 * 
 * @package ReType
 * @author Faulkner
 * @version 1.0.0
 * @link https://faulkner.fun/
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

$this->need('header.php');
?>

<div class="container py-4">
    <div class="row">
        <div class="col-12" id="main" role="main">
            <?php if ($this->have()): ?>
                <?php while ($this->next()): ?>
                    <?php retypeRenderPostCard($this, [
                        'titleClass'     => 'fw-bold mb-3',
                        'titleLinkClass' => 'text-decoration-none link-dark',
                        'readMore'       => ['enabled' => true],
                    ]); ?>
                <?php endwhile; ?>

                <?php $this->pageNav('&laquo; ' . _t('前一页'), _t('后一页') . ' &raquo;'); ?>

            <?php else: ?>
                <div class="alert alert-info shadow-sm p-4 text-center">
                    <i class="bi bi-info-circle fs-4 mb-2 d-block"></i>
                    <p class="mb-0"><?php _e('暂无文章'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $this->need('footer.php'); ?>
