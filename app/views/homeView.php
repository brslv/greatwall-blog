<?php require_once Config::get('paths', 'incl') . 'header.php'; ?>

<main class="main container row">
    <section class="eight columns">
        <?php foreach($data['posts'] as $d):?>

            <article class="post">
                <h3 class="u-bold">
                    <a href="<?php echo Config::get('paths', 'root') ?>post/show/<?php echo $d['id']; ?>">
                        <?php echo $d['title']?>
                    </a>
                </h3>

                <div class="post-meta u-upper u-bold">
                    Post added by <?php echo $d['author_username']; ?>
                    in <a href="<?php Config::get('paths', 'root'); ?>search/category/?name=<?php echo rawurlencode(rawurlencode($d['category'])); ?>"><?php echo $d['category']; ?></a> 
                    on <a href="<?php echo Config::get('paths', 'root'); ?>search/ondate/?d=<?php echo $d['publish_date']; ?>"><?php echo $d['publish_date']; ?></a>
                </div>

                <div class="post-content">
                    <?php echo $d['excerpt']; ?>
                </div>

                <div class="read-more">
                    <a href="<?php echo Config::get('paths', 'root') ?>post/show/<?php echo $d['id']; ?>" class="btn">
                        Read more &rarr;
                    </a>
                </div>
            </article>

        <?php endforeach; ?>

        <?php require_once Config::get('paths', 'incl') . 'pagination.php'; ?>

    </section>

    <?php require_once Config::get('paths', 'incl') . 'sidebar.php'; ?>
</main>

<?php require_once Config::get('paths', 'incl') . 'footer.php'; ?>