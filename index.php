<?php
include 'header.php';
include 'config.php';
?>
<div id="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">

                <div class="post-container">
                    <?php
                    $limit = 5;
                    if (isset($_GET['page'])) {
                        $page = $_GET['page'];
                    } else {
                        $page = 1;
                    };
                    $offset = ($page - 1) * $limit;

                    $sql = "SELECT post.post_id, post.title, post.description, post.post_date, post.category, post.post_img, category.category_name, user.username FROM post
                LEFT JOIN category ON post.category = category.category_id
                LEFT JOIN user ON post.author = user.user_id
                ORDER BY post.post_id DESC
                LIMIT {$offset}, {$limit}";


                    $result = mysqli_query($conn, $sql) or die('Query Failed!');

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                            <div class="post-content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <a class="post-img" href="single.php?id=<?= $row['post_id']; ?>"><img src="admin/upload/<?= $row['post_img']; ?>" alt="" /></a>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="inner-content clearfix">
                                            <h3><a href='single.php?id=<?= $row['post_id']; ?>'><?= $row['title']; ?></a></h3>
                                            <div class="post-information">
                                                <span>
                                                    <i class="fa fa-tags" aria-hidden="true"></i>
                                                    <a href='category.php'><?= $row['category_name']; ?></a>
                                                </span>
                                                <span>
                                                    <i class="fa fa-user" aria-hidden="true"></i>
                                                    <a href='author.php'><?= $row['username']; ?></a>
                                                </span>
                                                <span>
                                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                                    <?= $row['post_date']; ?>
                                                </span>
                                            </div>
                                            <p class="description">
                                                <?= strlen($row['description']) > 180
                                                    ? substr($row['description'], 0, 180) . '...'
                                                    : $row['description']; ?>
                                            </p>

                                        </div>
                                    </div>
                                </div>
                            </div>

                    <?php
                        }
                    };

                    $sql1 = "SELECT * FROM post";
                    $result1 = mysqli_query($conn, $sql1);
                    if (mysqli_num_rows($result1) > 0) {
                        $total_records = mysqli_num_rows($result1);
                        $total_pages = ceil($total_records / $limit);
                        echo "<ul class='pagination admin-pagination'>";
                        if ($page > 1) {
                            echo "<li><a href='?page=" . ($page - 1) . "'>Prev</a></li>";
                        }
                        for ($i = 1; $i <= $total_pages; $i++) {
                            if ($i == $page) {
                                $active = "active";
                            } else {
                                $active = "";
                            }
                            echo "<li class='{$active}'><a href='?page={$i}'>{$i}</a></li>";
                        }
                        if ($total_pages > $page) {
                            echo "<li><a href='?page=" . ($page + 1) . "'>Next</a></li>";
                        }
                        echo "</ul>";
                    };
                    ?>
                </div>

            </div>
            <?php include 'sidebar.php'; ?>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>