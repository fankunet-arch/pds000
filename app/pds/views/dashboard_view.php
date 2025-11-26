<?php
// dashboard_view.php

if (!defined('PDS_ENTRY')) {
    die('Access denied.');
}

render_header(['page_title' => t('dashboard')]);
?>

<div class="card">
    <p>欢迎使用 PDS - 研发与备料系统。</p>
    <p>请使用左侧的导航菜单开始操作。</p>
</div>

<?php
render_footer();
