<?php snippet('header') ?>

<main>
  <section id="dial" aria-label="Colour dial">
    <!-- position indicator (hidden from assistive Tech) -->
    <div id="band-selector" aria-hidden="true"></div>

    <!-- colour chips -->
    <ul id="dial-posts">
      <?php
        // Fetch 62 most recent, including unlisted so the dial is always full
        $posts = $site->find('posts')
                      ->children()
                      ->sortBy('date', 'desc')
                      ->limit(62);
        foreach ($posts as $post):
          $colour   = $post->isListed()
                    ? htmlspecialchars($post->color(), ENT_QUOTES, 'UTF-8')
                    : '#cccccc';
          $title    = $post->isListed()
                    ? $post->title()->escape()
                    : 'Unlisted';
          $attributes = [
            'class'       => 'dial-item',
            'role'        => 'listitem',
            'data-colour' => $colour,
            'aria-label'  => $title,
          ];
      ?>
        <li <?= attr($attributes) ?>>
          <?php if ($post->isListed()): ?>
            <a href="<?= $post->url() ?>">
              <span class="colour-block" style="background-color: <?= $colour ?>"></span>
            </a>
          <?php else: ?>
            <span aria-hidden="true">
              <span class="colour-block" style="background-color: <?= $colour ?>"></span>
            </span>
          <?php endif ?>
        </li>
      <?php endforeach ?>
    </ul>

    <button
      id="audio-toggle"
      class="btn-audio"
      aria-pressed="false"
      aria-label="Toggle synthesizer sound">
      🔇
    </button>
  </section>
</main>

<?= js('assets/js/dial.js', ['defer' => true]) ?>

<?php snippet('footer') ?>
