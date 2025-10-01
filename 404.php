<?php if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
} ?>
<?php $this->need('header.php'); ?>

<div class="main-container">
    <div class="container py-5" style="min-height: 71.7vh;">
    <div class="row justify-content-center my-5">
        <div class="col-12 col-md-8">
            <div class="text-center mb-5 mt-4">
                <i class="bi bi-exclamation-circle text-danger" style="font-size: 6rem;" aria-hidden="true"></i>
            </div>
            
            <h1 class="post-title fw-bold fs-1 text-center mb-5">
                404 - <?php _e('页面没找到'); ?>
            </h1>
            
            <div class="post-content mb-5 fs-5 text-center text-muted">
                <p><?php _e('你想查看的页面已被转移或删除了，要不要搜索看看：'); ?></p>
            </div>

            <form method="post" 
                  action="<?php $this->options->siteUrl(); ?>" 
                  class="mt-5 mb-5"
                  role="search">
                <div class="input-group mx-auto" style="max-width: 400px;">
                    <input type="text" 
                           name="s" 
                           class="form-control form-control-lg" 
                           placeholder="<?php _e('输入关键词'); ?>"
                           aria-label="<?php _e('搜索关键词'); ?>"
                           autofocus>
                    <button type="submit" 
                            class="btn btn-dark"
                            aria-label="<?php _e('搜索'); ?>">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            <div class="text-center">
                <a href="<?php $this->options->siteUrl(); ?>" 
                   class="btn btn-outline-primary">
                    <i class="bi bi-house"></i> <?php _e('返回首页'); ?>
                </a>
            </div>
        </div>
    </div>
    </div>
</div>

<?php $this->need('footer.php'); ?>