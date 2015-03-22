<?php namespace WowTables\Commands;

use WowTables\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Contracts\Filesystem\Cloud;
use DB;
use Log;
use Image;

class ImageResizeSendToCloud extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

    protected $mediaId, $uplodadsDir, $resizedImageName, $imgFilePath, $imgWidth, $imgHeight;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($mediaId, $uplodadsDir, $resizedImageName, $imgFilePath, $imgWidth, $imgHeight)
	{
        $this->mediaId = $mediaId;
        $this->uplodadsDir = $uplodadsDir;
        $this->resizedImageName = $resizedImageName;
        $this->imgFilePath = $imgFilePath;
        $this->imgWidth = $imgWidth;
        $this->imgHeight = $imgHeight;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle(Cloud $cloud)
	{
        $listing_image_upload = $cloud->put(
            $this->uplodadsDir.$this->resizedImageName,
            Image::make($cloud->get($this->imgFilePath))->fit(
                $this->imgWidth,
                $this->imgHeight
            )->encode()
        );

        if($listing_image_upload){
            DB::table('media_resized')->insert([
                'media_id' => $this->mediaId,
                'file' => $this->resizedImageName,
                'height' => $this->imgHeight,
                'width' => $this->imgWidth
            ]);
        }else{
            Log::error('Issue Uploading the Image to the Cloud');
        }

        if($this->attempts() === 3){
            $this->release(1);
        }
	}

}
