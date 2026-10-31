<?php

namespace App\Services;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuService
{
    private const IMAGE_DIR = 'menus';

    /**
     * Get all menus
     */
    public function getMenus(): Collection
    {
        return Menu::all();
    }

    /**
     * Create menu
     */
    public function create(array $data): Menu
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $this->storeImage($data['image']);
        }

        return Menu::create($data);
    }

    /**
     * Update menu
     */
    public function update(Menu $menu, array $data): Menu
    {
        // hapus image via tombol X
        if (!empty($data['remove_image']) && $menu->image) {
            $this->deleteImage($menu->image);
            $data['image'] = null;
        }

        // replace image
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            if ($menu->image) {
                $this->deleteImage($menu->image);
            }

            $data['image'] = $this->storeImage($data['image']);
        }

        unset($data['remove_image']);

        $menu->update($data);

        return $menu;
    }

    /**
     * Store image (filename only)
     */
    protected function storeImage(UploadedFile $file): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $file->storeAs(self::IMAGE_DIR, $filename, 'public');

        return $filename;
    }

    /**
     * Delete image file
     */
    protected function deleteImage(string $filename): void
    {
        $path = self::IMAGE_DIR . '/' . $filename;

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
