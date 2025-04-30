<?php snippet('header') ?>

<main class="full">
    <div class="site-info site-header">
        <p>This site has <?= $site->find('posts')->children()->count() ?> pages and was last updated on <?= $site->find('posts')->children()->sortBy('date', 'desc')->first()->date()->toDate('F j, Y') ?></p>
    </div>
    
    <div class="gallery-grid">
        <?php
        // Fetch all posts (including unlisted)
        $posts = $site->find('posts')->children();

        // Sort posts by date
        $sortedPosts = $posts->sortBy('date', 'desc');
        
        // Loop through sorted posts and display them
        foreach ($sortedPosts as $post): ?>
            <?php if($post->isListed()): ?>
                <div class="gallery-item">
                    <a href="<?= $post->url() ?>" class="gallery-item-link">
                        <?php if($post->cover_image_url()->isNotEmpty()): ?>
                            <?php 
                                // Get the cover URL from the field
                                $coverUrl = $post->cover_image_url()->value();
                                
                                // Function to determine if a URL is a video
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
                                
                                // Generate a unique thumbnail filename based on the post's slug
                                $thumbnailFilename = $post->slug() . '-thumb.jpg';
                                $thumbnailPath = $post->root() . '/' . $thumbnailFilename;
                                $thumbnailUrl = $post->url() . '/' . $thumbnailFilename;
                                
                                // Check if it's a video URL
                                if (isVideoUrl($coverUrl)) {
                                    // If thumbnail doesn't exist yet, try to generate it
                                    if (!file_exists($thumbnailPath)) {
                                        // Try to generate thumbnail with ffmpeg if available
                                        if (function_exists('exec')) {
                                            exec("ffmpeg -i \"$coverUrl\" -ss 00:00:01.000 -vframes 1 \"$thumbnailPath\" 2>&1", $output, $returnCode);
                                            
                                            // If ffmpeg failed, let's use a fallback approach
                                            if ($returnCode != 0 || !file_exists($thumbnailPath)) {
                                                // If we couldn't generate a thumbnail, display the video itself
                                                ?>
                                                <div class="gallery-media">
                                                    <video src="<?= $coverUrl ?>" class="gallery-thumbnail" preload="metadata" muted></video>
                                                </div>
                                                <?php
                                                // Skip the remaining thumbnail code
                                                $thumbnailGenerated = false;
                                            } else {
                                                $thumbnailGenerated = true;
                                            }
                                        } else {
                                            // If exec is not available, display the video itself
                                            ?>
                                            <div class="gallery-media">
                                                <video src="<?= $coverUrl ?>" class="gallery-thumbnail" preload="metadata" muted></video>
                                            </div>
                                            <?php
                                            $thumbnailGenerated = false;
                                        }
                                    } else {
                                        $thumbnailGenerated = true;
                                    }
                                    
                                    // If we have a thumbnail, display it
                                    if (isset($thumbnailGenerated) && $thumbnailGenerated) {
                                        ?>
                                        <div class="gallery-media">
                                            <img src="<?= $thumbnailUrl ?>" class="gallery-thumbnail" alt="<?= $post->title()->html() ?>">
                                        </div>
                                        <?php
                                    }
                                } else {
                                    // It's an image URL, display it directly
                                    ?>
                                    <div class="gallery-media">
                                        <img src="<?= $coverUrl ?>" class="gallery-thumbnail" alt="<?= $post->title()->html() ?>">
                                    </div>
                                    <?php
                                }
                            ?>
                        <?php else: ?>
                            <div class="gallery-media">
                                <div class="gallery-color-block" style="background-color: <?= $post->color() ?>"></div>
                            </div>
                        <?php endif ?>
                        
                        <div class="gallery-caption">
                            <?php if($post->page_content()->isNotEmpty()): ?>
                                <div class="gallery-title"><?= $post->page_content()->excerpt(32, true, '...') ?></div>
                            <?php endif ?>
                            <div class="gallery-date"><?= $post->date()->toDate('m/d/Y') ?></div>
                        </div>
                    </a>
                </div>
            <?php endif ?>
        <?php endforeach ?>
    </div>

    <div class="site-info site-footer">
        
        <?php
        // Get all unique tags across all posts
        $tags = page('posts')          
            ->children()               
            ->published()             // only published posts
            ->listed()               // only listed posts
            ->pluck('tags', ',', true); 

        $tags = array_unique($tags);   
        sort($tags);                   
        ?>

        <?php if(count($tags) > 0): ?>
            <div class="tags-list">
                <span class="small-label">Tags:</span>
                <?php foreach($tags as $i => $tag): ?>
                    <a href="<?= url('tags/#' . str::slug($tag)) ?>" class="tag"><?= html($tag) ?></a><?= ($i < count($tags) - 1) ? ',' : '' ?>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>
</main>

<style>
    /* Fix top margin */
    body {
        margin: 0;
        padding: 0;
    }
    
    main.full {
        margin-top: 20px;
        padding-top: 20px;
    }
    
    /* Site Info Section */
    .site-info {
        font-size: 14px;
        opacity: 0.8;
    }
    
    .site-header {
        margin-top: 0;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    
    .site-header p {
        margin-top: 0;
    }
    
    .site-footer {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    
    /* Gallery Grid Layout */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        grid-gap: 10px;
        width: 100%;
        margin-bottom: 30px;
    }
    
    .gallery-item {
        position: relative;
        overflow: hidden;
        height: 100%;
        transition: transform 0.3s ease;
    }
    
    .gallery-item:hover {
        transform: scale(0.98);
    }
    
    .gallery-item-link {
        display: block;
        height: 100%;
        text-decoration: none;
        color: inherit;
    }
    
    .gallery-media {
        position: relative;
        width: 100%;
        padding-bottom: 100%; /* 1:1 Aspect Ratio */
        overflow: hidden;
    }
    
    .gallery-thumbnail {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .gallery-color-block {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    
    .gallery-caption {
        padding: 8px;
        background: rgba(255, 255, 255, 0.9);
        font-size: 12px;
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
    }
    
    .gallery-title {
        font-weight: 500;
        margin-bottom: 3px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .gallery-date {
        opacity: 0.7;
        font-size: 11px;
    }
    
    .tags-list {
        margin-top: 10px;
    }
    
    .small-label {
        font-weight: 500;
    }
    
    .tag {
        text-decoration: none;
        color: inherit;
    }
    
    .tag:hover {
        text-decoration: underline;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }
    }
</style>

<?php snippet('footer') ?>