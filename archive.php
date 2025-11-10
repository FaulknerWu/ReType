<?php if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
} ?>
<?php $this->need('header.php'); ?>

<div class="container py-4" id="main" role="main">
    <!-- 归档标题部分 -->
    <h1 class="archive-title mb-4 fs-3 text-dark">
        <?php $this->archiveTitle([
            'category' => _t('分类 %s 下的文章'),
            'search'   => _t('包含关键字 %s 的文章'),
            'tag'      => _t('标签 %s 下的文章'),
            'author'   => _t('%s 发布的文章')
        ], '', ''); ?>
    </h1>

    <!-- 文章列表部分 -->
    <?php if ($this->have()): ?>
        <?php while ($this->next()): ?>
            <?php retypeRenderPostCard($this, [
                'contentMode'      => 'excerpt',
                'metaOptions'      => ['containerClass' => 'post-meta text-muted mb-3'],
                'showTags'         => true,
                'tagsWrapperClass' => 'tags',
                'tagsIconClass'    => 'bi bi-tags',
                'tagBadgeClass'    => 'badge bg-light text-dark me-1',
                'emptyTagText'     => '',
            ]); ?>
        <?php endwhile; ?>

        <!-- 分页导航 -->
        <nav aria-label="文章分页导航" class="my-4">
            <?php $this->pageNav('&laquo; ' . _t('前一页'), _t('后一页') . ' &raquo;'); ?>
        </nav>

    <?php else: ?>
        <div class="alert alert-info shadow-sm p-4 text-center">
            <i class="bi bi-info-circle fs-4 mb-2 d-block"></i>
            <h2 class="fs-5 mb-0"><?php _e('没有找到内容'); ?></h2>
        </div>
    <?php endif; ?>
</div>

<?php $this->need('footer.php'); ?>
