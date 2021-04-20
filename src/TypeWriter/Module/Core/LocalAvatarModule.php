<?php
/*
 * Copyright (c) 2019-2020 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TypeWriter\Module\Core;

use Columba\Image\Image;
use stdClass;
use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;
use WP_Comment;
use WP_Error;
use WP_Post;
use WP_User;
use function __;
use function absint;
use function get_comment;
use function hash_file;
use function home_url;
use function in_array;
use function intval;
use function is_dir;
use function is_file;
use function is_int;
use function is_object;
use function mime_content_type;
use function mkdir;
use function str_replace;
use function unlink;
use const TypeWriter\PUBLIC_DIR;
use const TypeWriter\UPLOADS_DIR;
use const UPLOAD_ERR_CANT_WRITE;
use const UPLOAD_ERR_EXTENSION;
use const UPLOAD_ERR_FORM_SIZE;
use const UPLOAD_ERR_INI_SIZE;
use const UPLOAD_ERR_NO_FILE;
use const UPLOAD_ERR_NO_TMP_DIR;
use const UPLOAD_ERR_OK;

/**
 * Class LocalAvatarModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class LocalAvatarModule extends Module
{

    private const ALLOWED_MIME_TYPES = [
        'image/gif',
        'image/jpg',
        'image/jpeg',
        'image/png'
    ];

    private const SIZES = [
        'thumb_32' => [32, 32, true],
        'thumb_64' => [64, 64, true],
        'thumb_256' => [256, 256, true],
        'thumb_512' => [512, 512, true],
        'medium' => [960, 960, false],
        'large' => [1920, 1920, false]
    ];

    /**
     * LocalAvatarModule constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('Use local profile pictures instead of Gravatar.');
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onInitialize(): void
    {
        Hooks::action('user_edit_form_tag', [$this, 'onUserEditFormTag']);
        Hooks::action('user_profile_update_errors', [$this, 'onUserProfileUpdateErrors']);

        Hooks::filter('pre_get_avatar_data', [$this, 'onPreGetAvatarData']);
        Hooks::filter('user_profile_picture_description', [$this, 'onUserProfilePictureDescription']);

        if (!is_dir($this->path())) {
            mkdir($this->path(), 0777, true);
        }
    }

    /**
     * Gets the profile picture url with the given size of the given user.
     *
     * @param int $userId
     * @param string $size
     *
     * @return string|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function getProfilePictureUrl(int $userId, string $size = 'thumb_256'): ?string
    {
        if (!isset(self::SIZES[$size])) {
            return null;
        }

        $path = $this->path("/{$userId}_{$size}.jpg");

        if (!is_file($path)) {
            return null;
        }

        $hash = hash_file('crc32', $path);

        return str_replace(PUBLIC_DIR, home_url(), $path) . '?t=' . $hash;
    }

    /**
     * Returns TRUE if the given user has a profile picture uploaded.
     *
     * @param int $userId
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function hasProfilePicture(int $userId): bool
    {
        return $this->getProfilePictureUrl($userId) !== null;
    }

    /**
     * Removes all the existing profile picture files of the given user.
     *
     * @param int $userId
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function removeAllFiles(int $userId): void
    {
        foreach (self::SIZES as $sizeId => [$width, $height, $cover]) {
            $file = $this->path("{$userId}_{$sizeId}.jpg");

            if (!is_file($file)) {
                continue;
            }

            unlink($file);
        }
    }

    /**
     * Invoked on pre_get_avatar_data filter hook.
     * Fetches our local profile picture and let wordpress know that it shouldn't check Gravatar.
     *
     * @param array $args
     * @param mixed $idOrEmail
     *
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onPreGetAvatarData(array $args, mixed $idOrEmail): array
    {
        $userId = 0;

        if (is_int($idOrEmail)) {
            $userId = $idOrEmail;
        }

        if (is_object($idOrEmail) && isset($idOrEmail->comment_ID)) {
            $idOrEmail = get_comment($idOrEmail);
        }

        if ($idOrEmail instanceof WP_Comment) {
            $userId = $idOrEmail->user_id;
        }

        if ($idOrEmail instanceof WP_Post) {
            $userId = absint($idOrEmail->post_author);
        }

        if ($idOrEmail instanceof WP_User) {
            $userId = $idOrEmail->ID;
        }

        if ($userId === 0) {
            return $args;
        }

        if (!$this->hasProfilePicture($userId)) {
            return $args;
        }

        $size = intval($args['size'] ?? '128');
        $sizeId = '';

        foreach (self::SIZES as $id => [$width]) {
            if ($size > $width) {
                continue;
            }

            $sizeId = $id;
            break;
        }

        $args['url'] = $this->getProfilePictureUrl($userId, $sizeId);

        return $args;
    }

    /**
     * Invoked on user_edit_form_tag action hook.
     * Edits the form to post multipart form data, to support files.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onUserEditFormTag(): void
    {
        echo 'enctype="multipart/form-data"';
    }

    /**
     * Invoked on user_profile_picture_description filter hook.
     * Injects our input field for uploading a local profile picture.
     *
     * @param string $description
     * @param WP_User $user
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     *
     * @noinspection PhpUnusedParameterInspection
     */
    public final function onUserProfilePictureDescription(string $description, WP_User $user): string
    {
        $hasAvatar = $this->hasProfilePicture($user->ID);
        $str = __($hasAvatar ? 'Upload a new profile picture' : 'Upload a profile picture', 'tw');

        return <<<FIELD
        <p><br/></p>
        <p>
            <strong>{$str}</strong>
        </p>
        <input type="file" name="profile_picture" id="profile_picture"/>
        FIELD;
    }

    /**
     * Invoked on user_profile_update_errors action hook.
     * Saves the custom profile picture and overrides a potential old one.
     *
     * @param WP_Error $errors
     * @param bool $isUpdate
     * @param stdClass $user
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onUserProfileUpdateErrors(WP_Error $errors, bool $isUpdate, stdClass $user): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] === UPLOAD_ERR_NO_FILE || !$isUpdate) {
            return;
        }

        $userId = $user->ID;

        if ($_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
            switch ($_FILES['profile_picture']['error']) {
                case UPLOAD_ERR_FORM_SIZE:
                case UPLOAD_ERR_INI_SIZE:
                    $errors->add('tw_avatar_too_big', __('The upload picture is too big.', 'tw'));
                    break;

                case UPLOAD_ERR_CANT_WRITE:
                case UPLOAD_ERR_NO_TMP_DIR:
                case UPLOAD_ERR_EXTENSION:
                    $errors->add('tw_avatar_too_big', __('The server could not write the profile picture file.', 'tw'));
                    break;

                default:
                    $errors->add('tw_avatar_too_big', __('Unknown error while uploading the profile picture.', 'tw'));
                    break;
            }

            return;
        }

        $tmpName = $_FILES['profile_picture']['tmp_name'];
        $mimeType = mime_content_type($tmpName);

        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES)) {
            $errors->add('tw_avatar_too_big', __('The uploaded file is not an image.', 'tw'));

            return;
        }

        $this->removeAllFiles($userId);

        $image = Image::fromFile($tmpName);

        foreach (self::SIZES as $sizeId => [$width, $height, $cover]) {
            $copy = $image->copy();
            $file = $this->path("/{$userId}_{$sizeId}.jpg");

            $copy->resize($width, $height, $cover ? Image::COVER : Image::CONTAIN);
            $copy->jpeg($file);
            $copy->destroy();
        }

        $image->destroy();
    }

    /**
     * Gets a path in the profile pictures directory.
     *
     * @param string $path
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    private function path(string $path = ''): string
    {
        return UPLOADS_DIR . '/profile-pictures' . $path;
    }

}
