<?php
	namespace App\Http\Controllers;

	use App\Models\Image;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Str;

	class ImageController extends Controller
	{
		public function index() {
			$images = Image::where('user_id', Auth::id())
				->orderBy('created_at', 'desc')
				->get()
				->map(function($image) {
					return [
						'id' => $image->id,
						'image_alt' => $image->image_alt,
						'image_original_filename' => $image->image_original_filename,
						'created_at' => $image->created_at,
						'medium_url' => $image->getMediumUrl(),
						'small_url' => $image->getSmallUrl(),
						'large_url' => $image->getLargeUrl(),
						'original_url' => $image->getOriginalUrl(),
					];
				});

			return response()->json($images);
		}

		private function resizeImage($sourcePath, $destinationPath, $maxWidth)
		{
			list($originalWidth, $originalHeight, $type) = getimagesize($sourcePath);

			// Calculate new dimensions
			$ratio = $originalWidth / $originalHeight;
			$newWidth = min($maxWidth, $originalWidth);
			$newHeight = $newWidth / $ratio;

			// Create new image
			$newImage = imagecreatetruecolor($newWidth, $newHeight);

			// Handle transparency for PNG images
			if ($type == IMAGETYPE_PNG) {
				imagealphablending($newImage, false);
				imagesavealpha($newImage, true);
				$transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
				imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
			}

			// Load source image
			switch ($type) {
				case IMAGETYPE_JPEG:
					$source = imagecreatefromjpeg($sourcePath);
					break;
				case IMAGETYPE_PNG:
					$source = imagecreatefrompng($sourcePath);
					break;
				case IMAGETYPE_GIF:
					$source = imagecreatefromgif($sourcePath);
					break;
				default:
					return false;
			}

			// Resize
			imagecopyresampled(
				$newImage,
				$source,
				0, 0, 0, 0,
				$newWidth,
				$newHeight,
				$originalWidth,
				$originalHeight
			);

			// Save resized image
			switch ($type) {
				case IMAGETYPE_JPEG:
					imagejpeg($newImage, $destinationPath, 90);
					break;
				case IMAGETYPE_PNG:
					imagepng($newImage, $destinationPath, 9);
					break;
				case IMAGETYPE_GIF:
					imagegif($newImage, $destinationPath);
					break;
			}

			// Free up memory
			imagedestroy($newImage);
			imagedestroy($source);

			return true;
		}

		public function store(Request $request)
		{
			$request->validate([
				'image' => 'required|image|max:5120', // 5MB max
				'alt' => 'nullable|string|max:255'
			]);

			// Create directories if they don't exist
			$directories = ['original', 'large', 'medium', 'small'];
			foreach ($directories as $dir) {
				if (!Storage::disk('public')->exists("upload-images/$dir")) {
					Storage::disk('public')->makeDirectory("upload-images/$dir");
				}
			}

			$image = $request->file('image');
			$guid = Str::uuid();
			$extension = $image->getClientOriginalExtension();

			// Generate filenames
			$originalFilename = $guid . '.' . $extension;
			$largeFilename = $guid . '_large.' . $extension;
			$mediumFilename = $guid . '_medium.' . $extension;
			$smallFilename = $guid . '_small.' . $extension;

			// Save original file
			$originalPath = storage_path('app/public/upload-images/original/' . $originalFilename);
			move_uploaded_file($image->getPathname(), $originalPath);

			// Create resized versions
			$this->resizeImage(
				$originalPath,
				storage_path('app/public/upload-images/large/' . $largeFilename),
				1200
			);
			$this->resizeImage(
				$originalPath,
				storage_path('app/public/upload-images/medium/' . $mediumFilename),
				600
			);
			$this->resizeImage(
				$originalPath,
				storage_path('app/public/upload-images/small/' . $smallFilename),
				300
			);

			// Save to database
			$imageModel = Image::create([
				'user_id' => Auth::id(),
				'image_guid' => $guid,
				'image_alt' => $request->alt ?? $image->getClientOriginalName(),
				'image_original_filename' => $originalFilename,
				'image_large_filename' => $largeFilename,
				'image_medium_filename' => $mediumFilename,
				'image_small_filename' => $smallFilename
			]);

			return response()->json($imageModel);
		}

		public function update(Request $request, $id)
		{
			$request->validate([
				'alt' => 'required|string|max:255'
			]);

			$image = Image::where('user_id', Auth::id())
				->findOrFail($id);

			$image->update([
				'image_alt' => $request->alt
			]);

			return response()->json($image);
		}

		public function destroy($id)
		{
			$image = Image::where('user_id', Auth::id())
				->findOrFail($id);

			// Delete physical files
			$files = [
				'upload-images/original/' . $image->image_original_filename,
				'upload-images/large/' . $image->image_large_filename,
				'upload-images/medium/' . $image->image_medium_filename,
				'upload-images/small/' . $image->image_small_filename
			];

			foreach ($files as $file) {
				if (Storage::disk('public')->exists($file)) {
					Storage::disk('public')->delete($file);
				}
			}

			$image->delete();
			return response()->json(['success' => true]);
		}
	}
