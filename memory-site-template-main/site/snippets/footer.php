<footer>
    <div style="border: 1px solid #000000; width: 10px; height: 10px;"></div>
    <p>This is <?= $site->first_name()->isEmpty() ? "my" : $site->first_name() . "'s" ?> <a href="<?= $site->url() ?>"><?= $site->title() ?></a></p>
    <?php if (!$site->email()->isEmpty()): ?>
        <p>You can email me at <?= $site->email() ?></p>
    <?php endif ?>
    <p>Visit the <a href="/dial">radio dial</a></p>
    <br>
    <div id="posts-as-hr">
        <?php 

        // Fetch all posts (including unlisted)
        $posts = $site->find('posts')->children();

        // Sort posts by date
        $sortedPosts = $posts->sortBy('date', 'desc');

        foreach($sortedPosts as $post): ?>
            <?php if($post->isListed()): ?>
                <a href="<?= $post->url() ?>" style="background-color: <?= $post->color() ?>"></a>
            <?php else: ?>
                <span style="background-color: gray"></span>
            <?php endif ?>
        <?php endforeach ?>
    </div>
</footer>

</body>
</html>