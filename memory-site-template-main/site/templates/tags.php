<?php snippet('header') ?>

<?php
// topics.php

// Get all unique tags across all posts
$tags = page('posts')          // assuming 'posts' is your blog/posts page
  ->children()                 // get all post pages
  ->published()               // only published posts
  ->listed()                   // Add this line to filter out unlisted posts
  ->pluck('tags', ',', true); // get all tags, split by comma, remove empty values

$tags = array_unique($tags);   // remove duplicates
sort($tags);                   // sort alphabetically

?>

<main id="keys">
    <!-- Tag Navigation -->
    <div class="tag-navigation">
      <?php 
      $lastIndex = count($tags) - 1;
      foreach($tags as $index => $tag): ?>
        <a href="#<?= str::slug($tag) ?>"><?= html($tag) ?></a><?= ($index !== $lastIndex) ? ', ' : '' ?>
      <?php endforeach ?>
    </div>

    <!-- Posts by Tag -->
    <div id="tag-posts">
      <?php foreach($tags as $tag): ?>
        <div id="<?= str::slug($tag) ?>" class="tag">
          <div class="tag-title"><?= html($tag) ?></div>
          <ul>
            <?php 
            $taggedPosts = page('posts')
              ->children()
              ->published()
              ->listed()
              ->sortBy('date', 'desc')   // Sort by date, most recent first
              ->filterBy('tags', $tag, ',');
            
            foreach($taggedPosts as $post): ?>
              <li>
                <div class="color-block" style="background-color: <?= $post->color() ?>"></div>
                <a href="<?= $post->url() ?>"><?= $post->title() ?></a>
                <?php if($post->description()->isNotEmpty()): ?>
                  - <?= $post->description() ?>
                <?php endif ?>
              </li>
            <?php endforeach ?>
          </ul>
        </div>
      <?php endforeach ?>
    </div>
</main>

<?php snippet('footer') ?>