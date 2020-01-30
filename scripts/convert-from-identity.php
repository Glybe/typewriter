<?php
declare(strict_types=1);

use Columba\Util\StringUtil;
use const TypeWriter\WP_DIR;

require __DIR__ . '/../src/TypeWriter/boot.php';

function println(string $message): void
{
	echo $message, PHP_EOL;
}

function run(): void
{
	println('Checking for old Identity Framework data.');

	removePostRevisions();
	checkPostMeta();

	println('Done converting!');
}

function checkPostMeta(): void
{
	println('Checking post metadata...');

	$posts = get_posts([
		'numberposts' => -1,
		'posts_per_page' => -1,
		'post_type' => 'any'
	]);

	foreach ($posts as $post)
	{
		println(sprintf('Checking metadata for "%s"...', $post->post_title));

		$meta = get_post_meta($post->ID);
		$meta = array_map(fn($value) => is_array($value) ? array_map('maybe_unserialize', $value) : maybe_unserialize($value), $meta);

		foreach ($meta as $metaKey => $metaValue)
		{
			$result = false;

			switch (true)
			{
				case $metaKey === 'idty_introtext':
					$result = convertIntroText($post, $metaValue[0]);
					break;

				case StringUtil::startsWith($metaKey, 'idty_page_gallery_'):
					$result = convertGallery($post, $metaKey, $metaValue[0]);
					break;

				default:
					println(sprintf('Found meta key: %s=%s', $metaKey, $metaValue));
					break;
			}

			if (!$result)
				continue;

			 delete_post_meta($post->ID, $metaKey);
		}
	}
}

function convertGallery(WP_Post $post, string $metaKey, array $metaValue): bool
{
	$galleryId = substr($metaKey, 18);
	$photos = array_map(fn(array $photo) => intval($photo['attachment_id']), $metaValue);

	println(sprintf('Converted Identity Page Gallery in %s to TypeWriter Gallery: %s:[%s]', $post->post_title, $galleryId, implode(', ', $photos)));
	update_post_meta($post->ID, sprintf('tw_%s_%s_gallery', $post->post_type, $galleryId), $photos);

	return true;
}

function convertIntroText(WP_Post $post, array $metaValue): bool
{
	$heading = $metaValue['heading'] ?? '';
	$leading = $metaValue['leading'] ?? '';

	println(sprintf('Converted Identity Intro Text in %s to TypeWriter IntroTextMetaFields: ["%s", "%s"]', $post->post_title, $heading, $leading));
	update_post_meta($post->ID, 'tw_intro_text', [
		'heading' => $heading,
		'leading' => $leading
	]);

	return true;
}

function removePostRevisions(): void
{
	println('Removing post revisions as we do not need them.');

	$posts = get_posts([
		'numberposts' => -1,
		'posts_per_page' => -1,
		'post_type' => 'revision'
	]);

	foreach ($posts as $post)
		wp_delete_post($post->ID, true);

	println(sprintf('Deleted %d post revisions.', count($posts)));
}

require_once(WP_DIR . '/wp-load.php');
run();
