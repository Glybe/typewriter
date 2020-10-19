<?php
declare(strict_types=1);

use TypeWriter\Facade\DeveloperNotice;
use TypeWriter\Facade\Post;

DeveloperNotice::base('error', 'Could not find template', function (): void {
    $hasPost = Post::has();

    echo <<<HTML
        <p>
            This is index.php in the current active theme, you should always
            create custom templates in the template folder in your theme.
        </p>
    HTML;

    if (!$hasPost) {
        return;
    }

    $postId = Post::id();
    $postName = Post::name();
    $postTitle = Post::title();
    $postType = Post::type();

    echo <<<HTML
        <p>
            The most simple way to create a template for the current post is
            to create one of the following files:
        </p>
        <p><br/></p>
        <p><strong>Twig</strong></p>
        <ul>
            <li>template/{$postType}/default.twig</li>
            <li>template/{$postType}/{$postName}.twig</li>
            <li>template/{$postType}/{$postId}.twig</li>
        </ul>
        <p><strong>PHP</strong></p>
        <ul>
            <li>template/{$postType}/default.php</li>
            <li>template/{$postType}/{$postName}.php</li>
            <li>template/{$postType}/{$postId}.php</li>
        </ul>

        <p>
            <small>The requested post was <strong>{$postTitle}</strong> which has ID <strong>{$postId}</strong>.</small>
        </p>
    HTML;
});
