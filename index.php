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
                    <article class="post-card mb-4 shadow-sm border rounded-3 p-4" 
                             itemscope itemtype="http://schema.org/BlogPosting">
                        <?php $thumbnailUrl = retypeGetThumbnailUrl($this); ?>
                        <?php if ($thumbnailUrl): ?>
                            <div class="post-thumbnail mb-3">
                                <a href="<?php $this->permalink(); ?>" class="d-block">
                                    <img src="<?php echo $thumbnailUrl; ?>" 
                                         alt="<?php $this->title(); ?>"
                                         class="img-fluid rounded w-100"
                                         loading="lazy">
                                </a>
                            </div>
                        <?php endif; ?>

                        <h2 class="fw-bold mb-3" itemprop="headline">
                            <a href="<?php $this->permalink(); ?>" 
                               class="text-decoration-none link-dark"
                               itemprop="url">
                                <?php $this->title(); ?>
                            </a>
                        </h2>

                        <?php retypeRenderPostMeta($this); ?>

                        <div class="post-content" itemprop="articleBody">
                            <?php $this->content(''); ?>
                        </div>

                        <div class="text-end mt-3">
                            <a href="<?php $this->permalink(); ?>" 
                               class="btn btn-outline-primary btn-sm">
                                <?php _e('阅读全文'); ?> <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </article>
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
