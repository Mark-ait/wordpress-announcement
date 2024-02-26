<?php
/* Template Name: 公告页 */
get_header();
function enqueue_bootstrap() {
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.5.1.slim.min.js', array(), null, true);
    wp_enqueue_script('popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js', array('jquery'), null, true);
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_bootstrap');
?>
<style>
    body {
        background-color: #f8f9fa;
    }

    .announcement-container {
        text-align: center;
        margin: 50px auto;
        max-width: 50%;
        padding: 40px;
        background-color: #ffffff;
        box-shadow: 0px 1px 20px 0 rgb(217 217 217 / 20%), 0px 1px 20px 0 #f9f9f9;
        border-radius: 10px;
    }

    .announcement-content {
        color: #333;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 100%;
        display: inline-block;
    }

    .announcement-content p {
        color: #333;
    }

    .announcement-content a {
        color: #016fff;
        text-decoration: none;
    }

    .announcement-content a:hover,
    .announcement-content p:hover {
        color: #3a65f4 !important;
    }

    .announcement-time {
        margin-top: 10px;
        color: #6c757d;
        font-size: 13px;
    }

    .announcement-time span {
        color: #6c757d;
        display: block;
    }

    .pagination {
        margin-top: 20px;
        text-align: center;
    }

    .pagination .page-numbers {
        display: inline-block;
        padding: 5px 20px;
        margin-right: 5px;
        background-color: #f2f2f2;
        border-radius: 5px;
        color: #333;
        text-decoration: none;
    }

    .pagination .current {
        font-weight: bold;
        background-color: #333;
        color: #fff;
    }

    table {
        width: 50%;
        margin: 0 auto;
        text-align: center;
    }
.imgnotice {
    display: block;
    margin: 20px auto; /* 上下间距为 20px，左右居中 */
}
/* 在手机端设置表格宽度为100% */
@media (max-width: 767px) {
    table {
        width: 100%;
    }
}
</style>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <img class="imgnotice" src="https://img.8i5.net/blog/ooize_notice.png">
            <?php
            // 获取所有已发布的公告
            $all_announcements = get_option('all_GongGao', array());
            // 逆序处理数组
            $all_announcements = array_reverse($all_announcements);
            // 每页显示的公告数
            $announcements_per_page = 5;
            // 当前页数
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            // 分页逻辑
            $total_announcements = count($all_announcements);
            $total_pages = ceil($total_announcements / $announcements_per_page);
            $start = ($paged - 1) * $announcements_per_page;
            $end = $start + $announcements_per_page;
            // 输出表格头部
            echo '<div class="table-responsive">';
            echo '<table class="table">';
            echo '<thead style="background-color: black; color: white;">';
            echo '<tr>';
            echo '<th>标题</th>';
            echo '<th>发布时间</th>';
            echo '<th>查看</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            // 显示当前页的公告
            for ($i = $start; $i < $end && $i < $total_announcements; $i++) {
                $announcement = $all_announcements[$i];
                echo '<tr>';
                echo '<td>';
                // 如果有链接，将整个公告内容添加为一个链接
                if (!empty($announcement['link'])) {
                    echo '<a href="' . esc_url($announcement['link']) . '" class="alert-link">';
                }
                echo '<div class="announcement-content">';
                echo '</div>';
                // 如果有链接，关闭链接标签
                if (!empty($announcement['link'])) {
                    echo '</a>';
                    echo '<p>' . stripslashes($announcement['content']) . '</p>';
                }
                echo '</td>';
                echo '<td class="announcement-time"><span>' . date_i18n('Y年n月j日', $announcement['time']) . '</span></td>';
                echo '<td><a href="' . esc_url($announcement['link']) . '" class="alert-link" target="_blank">查看</a></td>';
                echo '</tr>';
            }
            // 输出表格尾部
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
            // 输出分页链接
            echo '<div class="pagination">';
            $big = 999999999; // 随便一个大数，确保替换所有的页码
            echo paginate_links(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total' => $total_pages,
                'prev_text' => __('« 上一页'),
                'next_text' => __('下一页 »'),
                'mid_size' => 1, // 控制显示的分页按钮数量
                'before_page_number' => '<span class="screen-reader-text">' . __('Page') . ' </span>',
            ));
            echo '</div>';
            ?>
        </div>
    </div>
</div><br>
<?php get_footer(); ?>
