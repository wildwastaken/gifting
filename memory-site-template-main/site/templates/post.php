<?php snippet('header') ?>

<main>
    <article class="post">
      <header class="post-header">
        <?php if($page->show_title()->toBool()): ?>
          <h1 class="post-title"><?= $page->title() ?></h1>
        <?php endif ?>
        
        <div class="post-meta">
          <?php if ($page->hide_dates()->toBool()): ?>
            <?php /* dates are hidden */ ?>
          <?php else: ?>
            <?php if ($page->date()->isNotEmpty()): ?>
              <time datetime="<?= $page->date()->toDate('Y-m-d') ?>">
                <?= $page->date()->toDate('l, F j, Y \a\t H:i') ?>
              </time>
            <?php endif ?>

            <?php if ($page->updated()->isNotEmpty()): ?>
              <br>
              <time id="updated" datetime="<?= $page->updated()->toDate('Y-m-d') ?>">
                Updated on <?= $page->updated()->toDate('l, F j, Y \a\t H:i') ?>
              </time>
            <?php endif ?>
          <?php endif ?>
            
          <?php if ($page->author()->isNotEmpty()): ?>
            <span class="post-author">
              by <?= $page->author() ?>
            </span>
          <?php endif ?>
        </div>
      </header>

      <br>
      
      <?php if($page->cover_image_url()->isNotEmpty()): ?>
        <?php
          // Get the cover URL from the field
          $coverUrl = $page->cover_image_url()->value();
          
          // Check if isVideoUrl function exists before defining
          if (!function_exists('isVideoUrl')) {
            function isVideoUrl($url) {
              // Check common video extensions
              $videoExtensions = ['.mp4', '.mov', '.avi', '.webm', '.mkv'];
              foreach ($videoExtensions as $ext) {
                if (stripos($url, $ext) !== false) {
                  return true;
                }
              }
              
              // Check for common video hosting patterns
              $videoPatterns = [
                'youtube.com/watch',
                'youtu.be/',
                'vimeo.com/',
                'dailymotion.com/',
                'are.na/'
              ];
              
              foreach ($videoPatterns as $pattern) {
                if (stripos($url, $pattern) !== false) {
                  return true;
                }
              }
              
              // If URL contains query parameters, check the response headers
              if (strpos($url, '?') !== false || strpos($url, '.are.na/') !== false) {
                $headers = get_headers($url, 1);
                if (isset($headers['Content-Type']) && 
                    (stripos($headers['Content-Type'], 'video/') !== false || 
                     stripos($headers['Content-Type'], 'application/octet-stream') !== false)) {
                  return true;
                }
              }
              
              return false;
            }
          }
          
          // Generate a unique thumbnail filename based on the page's slug
          $thumbnailFilename = $page->slug() . '-thumb.jpg';
          $thumbnailPath = $page->root() . '/' . $thumbnailFilename;
          $thumbnailUrl = $page->url() . '/' . $thumbnailFilename;
          
          // Check if it's a video URL
          if (isVideoUrl($coverUrl)) {
            // Display video directly in the post page
            ?>
            <div class="post-media">
              <video src="<?= $coverUrl ?>" controls autoplay loop muted class="post-video small-video" preload="auto"></video>
            </div>
            <?php
          } else {
            // It's an image URL, display it directly
            ?>
            <div class="post-media">
              <img src="<?= $coverUrl ?>" class="post-image" alt="<?= $page->title()->html() ?>">
            </div>
            <?php
          }
        ?>
      <?php endif ?>

      <?php if($page->page_content()->isNotEmpty()): ?>
        <div class="post-content">
          <?= $page->page_content()->kt() ?>
        </div>
      <?php endif ?>

      <?php if($page->references()->isNotEmpty()): ?>
        <div class="references">
          <?= $page->references()->kt() ?>
        </div>
      <?php endif ?>
    
      <?php if($page->tags()->isNotEmpty()): ?>
        <?php if (!$page->hide_tags()->toBool()): ?>
          <div class="post-tags">
            <?php 
            $tags = $page->tags()->split();
            $lastIndex = count($tags) - 1;
            foreach($tags as $index => $tag): ?>
              <a href="<?= url('keys') ?>#<?= str::slug($tag) ?>"><?= html($tag) ?></a><?= ($index !== $lastIndex) ? ', ' : '' ?>
            <?php endforeach ?>
          </div>
        <?php endif ?>
      <?php endif ?>

      <?php if($page->related()->isNotEmpty()): ?>
        <div class="related">
          <?php foreach($page->related()->toPages() as $post): ?>
            <?php if($post->isListed()): ?>
              <a href="<?= $post->url() ?>" class="small-color-block" style="background-color: <?= $post->color() ?>" title="<?= $post->title() ?>"></a>
            <?php endif ?>
          <?php endforeach ?>
        </div>
      <?php endif ?>

    </article>

    <br><br><br><br>
</main>

<?php snippet('footer') ?>