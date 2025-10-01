<?php if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
} ?>
<?php $this->need('header.php'); ?>

<div class="container py-4">
    <article class="post-card shadow-sm border rounded-3 p-4 mb-4" 
             itemscope itemtype="http://schema.org/BlogPosting">
        <?php if (isset($this->fields->thumbnail) && $this->fields->thumbnail): ?>
            <div class="post-thumbnail mb-3">
                <img src="<?php echo $this->fields->thumbnail; ?>" 
                     alt="<?php $this->title(); ?>" 
                     class="img-fluid rounded w-100"
                     itemprop="image"
                     loading="lazy">
            </div>
        <?php endif; ?>
        
        <h1 class="post-title fw-bold fs-1" itemprop="headline">
            <?php $this->title(); ?>
        </h1>
        
        <div class="post-meta text-muted mb-3">
            <span class="me-3">
                <i class="bi bi-person-circle"></i> 
                <a href="<?php $this->author->permalink(); ?>" itemprop="author">
                    <?php $this->author(); ?>
                </a>
            </span>
            <span class="me-3">
                <i class="bi bi-calendar3"></i> 
                <time datetime="<?php $this->date('c'); ?>" itemprop="datePublished">
                    <?php $this->date(); ?>
                </time>
            </span>
            <span class="me-3">
                <i class="bi bi-folder2"></i> 
                <?php $this->category(','); ?>
            </span>
            <span>
                <i class="bi bi-chat-left"></i> 
                <a href="#comments">
                    <?php $this->commentsNum('评论', '1 条评论', '%d 条评论'); ?>
                </a>
            </span>
        </div>

        <div class="post-content mb-4 fs-5" itemprop="articleBody">
            <?php $this->content(); ?>
        </div>

        <?php if ($this->tags): ?>
            <div class="tags mb-4">
                <i class="bi bi-tags"></i>
                <?php $this->tags('<span class="badge bg-light text-dark me-1">', '</span>', '暂无标签'); ?>
            </div>
        <?php endif; ?>
    </article>

    <?php $this->need('comments.php'); ?>
</div>

<?php $this->need('footer.php'); ?>