<?php if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
} ?>
<?php $this->need('header.php'); ?>

<div class="container py-4">
    <div class="row">
        <div class="col-12" id="main" role="main">
            <article class="p-4 mb-4 shadow-sm border rounded-3 bg-white" 
                     itemscope itemtype="http://schema.org/Article">
                <h1 class="post-title fw-bold fs-2 mb-4" itemprop="headline">
                    <?php $this->title(); ?>
                </h1>
                <div class="post-content fs-5" itemprop="articleBody">
                    <?php $this->content(); ?>
                </div>
            </article>

            <?php $this->need('comments.php'); ?>
        </div>
    </div>
</div>

<?php $this->need('footer.php'); ?>