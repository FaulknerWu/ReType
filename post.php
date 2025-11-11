<?php if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
} ?>
<?php $this->need('header.php'); ?>

<div class="container py-4">
    <article class="post-card shadow-sm border rounded-3 p-4 mb-4" 
             itemscope itemtype="http://schema.org/BlogPosting">
        <?php $thumbnailUrl = retypeGetThumbnailUrl($this); ?>
        <?php if ($thumbnailUrl): ?>
            <div class="post-thumbnail mb-3">
                <img src="<?php echo $thumbnailUrl; ?>" 
                     alt="<?php $this->title(); ?>" 
                     class="img-fluid rounded w-100"
                     itemprop="image"
                     loading="lazy">
            </div>
        <?php endif; ?>
        
        <h1 class="post-title fw-bold fs-1" itemprop="headline">
            <?php $this->title(); ?>
        </h1>
        
        <?php retypeRenderPostMeta($this, [
            'containerClass' => 'post-meta text-muted mb-3'
        ]); ?>

        <div class="post-content mb-4 fs-5" itemprop="articleBody">
            <?php $this->content(); ?>
        </div>

        <?php retypeRenderTagBadges($this, [
            'wrapperClass' => 'tags mb-4',
            'iconClass'    => 'bi bi-tags',
            'badgeClass'   => 'badge bg-light text-dark me-1',
        ]); ?>
    </article>

    <?php $this->need('comments.php'); ?>
</div>

<?php $this->need('footer.php'); ?>
