<?php namespace GodSpeed\Essentials\Utils\Lazyload;

use Intervention\Image\Facades\Image;
use October\Rain\Database\Attach\File;
use GodSpeed\Essentials\Models\Settings;

class LazyloadImage
{
    /**
     * File path of image
     */
    protected $filePath;

    /**
     * Image Resizer Settings
     */
    protected $settings;

    /**
     * File Object
     */
    protected $file;

    /**
     * Options Array
     */
    protected $options;

    /**
     * Thumb filename
     */
    protected $thumbFilename;

    public function __construct($filePath = false)
    {
        // Settings are needed often, so offset to variable
        $this->settings = Settings::instance();

        // Create a new file object
        $this->file = new File;

        if ($filePath instanceof File) {
            $this->filePath = $filePath->getLocalPath();
            return;
        }

        $this->filePath = (file_exists($filePath))
            ? $filePath
            : $this->parseFileName($filePath);
    }

    /**
     * Resizes an Image
     *
     * @param integer $width The target width
     * @param integer $height The target height
     * @param array   $options The options
     *
     * @return string
     */
    public function resize($width = false, $height = false, $options = [])
    {
        // Parse the default settings

        $this->options = $this->parseDefaultSettings($options);
        // Not a file? Display the not found image
        if (!is_file($this->filePath)) {
            return $this->notFoundImage($width, $height);
        }

        // If extension is auto, set the actual extension
        if (strtolower($this->options['extension']) == 'auto') {
            $this->options['extension'] = pathinfo($this->filePath)['extension'];
        }

        // Set a disk name, this enables caching
        $this->file->disk_name = $this->diskName();

        // Set the thumbfilename to save passing variables to many functions
        $this->thumbFilename = $this->getThumbFilename($width, $height);

        // If the image is cached, don't try resized it.
        if (! $this->isImageCached()) {
            // Set the file to be created from another file
            $this->file->fromFile($this->filePath);



            $originalFilePath = $this->file->getLocalPath();


            $file = Image::make($originalFilePath)->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })
                ->blur($this->options['blur'])
                ->encode($this->options['extension'], $this->options['quality'])
                ->save($this->getCachedImagePath());

            touch($this->getCachedImagePath(), filemtime($this->filePath));

            // Touch the cached image with the original mtime to align them

            $this->deleteTempFile();
        }

        // Return the URL
        return $this;
    }

    /**
     * Gets the path for the thumbnail
     * @return string
     */
    public function getCachedImagePath($public = false)
    {
        $filePath = $this->file->getStorageDirectory() . $this->getPartitionDirectory() . $this->thumbFilename;

        if ($public === true) {
            return url('/storage/app/' . $filePath);
        }

        return storage_path('app/' . $filePath);
    }

    protected function deleteTempFile()
    {
        $targetFile = storage_path('app/' . $this->file->getStorageDirectory() . $this->getPartitionDirectory() . $this->file->disk_name);
        if (file_exists($targetFile)) {
            unlink($targetFile);
        }
    }

    /**
     * Parse the file name to get a relative path for the file
     * This is mostly required for scenarios where a twig filter, e.g. theme has been applied.
     * @return string
     */
    protected function parseFileName($filePath)
    {
        $path = urldecode(parse_url($filePath, PHP_URL_PATH));

        // Create array of commonly used folders
        // These will be used to try capture the actual file path to an image without the sub-directory path
        $folders = [
            config('cms.themesPath'),
            config('cms.pluginsPath'),
            config('cms.storage.uploads.path'),
            config('cms.storage.media.path')
        ];

        foreach ($folders as $folder) {
            if (str_contains($path, $folder)) {
                $paths = explode($folder, $path, 2);
                return base_path($folder . end($paths));
            }
        }

        return base_path($path);
    }

    /**
     * Works out the default settings
     * @return []
     */
    protected function parseDefaultSettings($options = [])
    {

        if (!isset($options['extension'])) {
            $options['extension'] = $this->settings->lazyload_default_image_extension ?? "jpg";
        }
        if (!isset($options['quality'])) {
            $options['quality'] = $this->settings->lazyload_image_quality ?? 35;
        }
        if (!isset($options['blur'])) {
            $options['blur'] = $this->settings->lazyload_image_blur_rate ?? 15;
        }

        if (!isset($options['image_not_found'])) {
            $options['image_not_found'] = $this->settings->lazyload_image_not_found;
        }


        return $options;
    }

    /**
     * Creates a unique disk name for an image
     * @return string
     */
    protected function diskName()
    {
        $diskName = $this->filePath;

        // Ensures a unique filepath when tinypng compression is enabled

        return md5($diskName);
    }

    /**
     * Serves a not found image
     * @return string
     */
    protected function notFoundImage($width, $height)
    {
        // Have we got a custom not found image? If so, serve this.
        if ($this->settings->not_found_image) {
            $imagePath = base_path() . config('cms.storage.media.path') . $this->settings->not_found_image;
        }

        // If we do not have an existing custom not found image, use the default from this plugin
        if (!isset($imagePath) || !file_exists($imagePath)) {
            $imagePath = plugins_path('godspeed/essentials/assets/default-not-found.jpeg');
        }

        // Create a new Image object to resize
        $file = new self($imagePath);

        // Return in the specified dimensions
        return $file->resize($width, $height);
    }



    /**
     * Checks if the requested resize/compressed image is already cached.
     * Removes the cached image if the original image has a different mtime.
     *
     * @return bool
     */
    protected function isImageCached()
    {
        // if there is no cached image return false
        if (!is_file($cached_img = $this->getCachedImagePath())) {
            return false;
        }

        // if cached image mtime match, the image is already cached
        if (filemtime($this->filePath) === filemtime($cached_img)) {
            return true;
        }

        // delete older cached file
        unlink($cached_img);

        // generate new cache file
        return false;
    }

    /**
     * Checks if image compression is enabled for this image.
     * @return bool
     */


    /**
     * Generates a partition for the file.
     * return /ABC/DE1/234 for an name of ABCDE1234.
     * @param Attachment $attachment
     * @param string $styleName
     * @return mixed
     */
    protected function getPartitionDirectory()
    {
        return implode('/', array_slice(str_split($this->diskName(), 3), 0, 3)) . '/';
    }

    /**
     * Generates a thumbnail filename.
     * @return string
     */
    protected function getThumbFilename($width, $height)
    {
        $width = (integer) $width;
        $height = (integer) $height;

        return 'thumb__' . $width . '_' . $height . '.' . $this->options['extension'];
    }

    /**
     * Render an image tag
     * @return string
     */
    public function render()
    {
        return '<img src="' . $this . '" />';
    }

    /**
     * Magic method to return the file path
     * @return string
     */
    public function __toString()
    {
        return $this->getCachedImagePath(true);
    }
}
