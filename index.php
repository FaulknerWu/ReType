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
                        <?php if (isset($this->fields->thumbnail) && $this->fields->thumbnail): ?>
                            <div class="post-thumbnail mb-3">
                                <a href="<?php $this->permalink(); ?>" class="d-block">
                                    <img src="<?php echo $this->fields->thumbnail; ?>" 
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

                        <div class="post-meta d-flex flex-wrap mb-3 text-muted small">
                            <span class="me-3">
                                <i class="bi bi-person-circle"></i> 
                                <span itemprop="author"><?php $this->author(); ?></span>
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
                                <a href="<?php $this->permalink(); ?>#comments">
                                    <?php $this->commentsNum('评论', '1 条评论', '%d 条评论'); ?>
                                </a>
                            </span>
                        </div>

                        <div class="post-content" itemprop="articleBody">
                            <?php $this->content(''); ?>
                        </div>

                        <div class="text-end mt-3">
                            <a href="<?php $this->permalink(); ?>" 
                               class="btn btn-outline-primary btn-sm">
                                阅读全文 <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>

                <?php $this->pageNav('&laquo; 前一页', '后一页 &raquo;'); ?>

            <?php else: ?>
                <div class="alert alert-info shadow-sm p-4 text-center">
                    <i class="bi bi-info-circle fs-4 mb-2 d-block"></i>
                    <p class="mb-0">暂无文章</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $this->need('footer.php'); ?>