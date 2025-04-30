<?php snippet('header') ?>

<main id="container">
  <?php 
  $items = $page->children();
  
  // Filter by tag if tag parameter exists
  if($tag = param('tag')) {
    $items = $items->filterBy('tags', $tag, ',');
  }
  ?>
  
  <?php if($items->count()): ?>
    <?php if($tag): ?>
      <p >Showing posts with "<?= html($tag) ?>"</p>
    <?php endif ?>
    
    <ul class="container-pages">
      <?php foreach($items as $item): ?>
      
      <li>
        <?php if($item->isListed()): ?>
          <a href="<?= $item->url() ?>">
            <span class="small-color-block" style="background-color: <?= $item->color() ?>"></span><span class="post-title"><?= $item->title() ?></span>
          </a>
        <?php endif ?>
      </li>
      <?php endforeach ?>
    </ul>
  <?php else: ?>
    <?php if($tag): ?>
      <p>No pages found with tag: <?= html($tag) ?></p>
    <?php else: ?>
      <p>No pages found in this container.</p>
    <?php endif ?>
  <?php endif ?>
</main>

<?php snippet('footer') ?>
