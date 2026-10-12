<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AppearanceController extends Controller
{
    public function edit()
    {
        $appearance = array_replace_recursive($this->defaults(), SiteSetting::getValue('appearance', []));

        return view('admin.appearance.edit', compact('appearance'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'logo_text' => ['required', 'string', 'max:80'],
            'logo_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_logo_image' => ['sometimes', 'boolean'],
            'promo_text' => ['nullable', 'string', 'max:180'],
            'footer_tagline' => ['nullable', 'string', 'max:250'],
            'social_links' => ['nullable', 'array', 'max:10'],
            'social_links.*.name' => ['nullable', 'string', 'max:40'],
            'social_links.*.url' => ['nullable', 'url', 'max:255'],
            'primary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'accent_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'show_categories' => ['sometimes', 'boolean'],
            'show_featured' => ['sometimes', 'boolean'],
            'show_benefits' => ['sometimes', 'boolean'],
            'show_latest' => ['sometimes', 'boolean'],
            'show_brands' => ['sometimes', 'boolean'],
            'slides' => ['required', 'array', 'min:1', 'max:5'],
            'slides.*.eyebrow' => ['nullable', 'string', 'max:80'],
            'slides.*.title' => ['required', 'string', 'max:140'],
            'slides.*.description' => ['nullable', 'string', 'max:300'],
            'slides.*.button_text' => ['required', 'string', 'max:50'],
            'slides.*.button_url' => ['required', 'string', 'max:255', 'regex:/^(https?:\/\/|\/)[^\s]*$/'],
            'slides.*.active' => ['sometimes', 'boolean'],
            'slides.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'slides.*.remove_image' => ['sometimes', 'boolean'],
        ], [
            'slides.*.button_url.regex' => 'پیوند دکمه باید با / یا نشانی https:// آغاز شود.',
            'slides.*.image.image' => 'تصویر اسلاید باید یک فایل تصویری معتبر باشد.',
            'slides.*.image.max' => 'حجم هر تصویر حداکثر می‌تواند ۵ مگابایت باشد.',
            'primary_color.regex' => 'رنگ اصلی را با کد HEX شش‌رقمی وارد کنید.',
            'accent_color.regex' => 'رنگ مکمل را با کد HEX شش‌رقمی وارد کنید.',
        ]);

        $oldAppearance = array_replace_recursive($this->defaults(), (array) SiteSetting::getValue('appearance', []));
        $socialLinks = collect($validated['social_links'] ?? [])
            ->map(fn ($link) => ['name' => trim($link['name'] ?? ''), 'url' => trim($link['url'] ?? '')])
            ->filter(fn ($link) => $link['name'] !== '' && $link['url'] !== '')
            ->values()->all();
        if (!collect($validated['slides'])->contains(fn ($slide) => (bool) ($slide['active'] ?? false))) {
            throw ValidationException::withMessages([
                'slides' => 'حداقل یک اسلاید باید فعال باشد.',
            ]);
        }

        $slides = [];
        $oldImagesToDelete = [];
        $logoImage = $oldAppearance['logo_image'] ?? null;

        if ($request->boolean('remove_logo_image')) {
            if ($logoImage) {
                $oldImagesToDelete[] = $logoImage;
            }
            $logoImage = null;
        }

        if ($request->hasFile('logo_image')) {
            $logoImage = $request->file('logo_image')->store('site/branding', 'public');
            if (!empty($oldAppearance['logo_image'])) {
                $oldImagesToDelete[] = $oldAppearance['logo_image'];
            }
        }

        foreach ($validated['slides'] as $index => $slide) {
            $oldImage = $oldAppearance['slides'][$index]['image'] ?? null;
            $image = $oldImage;

            if ($request->boolean("slides.$index.remove_image")) {
                if ($oldImage) {
                    $oldImagesToDelete[] = $oldImage;
                }
                $image = null;
            }

            if ($request->hasFile("slides.$index.image")) {
                $image = $request->file("slides.$index.image")->store('site/slides', 'public');
                if ($oldImage) {
                    $oldImagesToDelete[] = $oldImage;
                }
            }

            $slides[] = [
                'eyebrow' => $slide['eyebrow'] ?? '',
                'title' => $slide['title'],
                'description' => $slide['description'] ?? '',
                'button_text' => $slide['button_text'],
                'button_url' => $slide['button_url'],
                'image' => $image,
                'active' => (bool) ($slide['active'] ?? false),
            ];
        }

        $appearance = [
            'logo_text' => $validated['logo_text'],
            'logo_image' => $logoImage,
            'promo_text' => $validated['promo_text'] ?? '',
            'footer_tagline' => $validated['footer_tagline'] ?? '',
            'social_links' => $socialLinks,
            'primary_color' => $validated['primary_color'],
            'accent_color' => $validated['accent_color'],
            'show_categories' => (bool) ($validated['show_categories'] ?? false),
            'show_featured' => (bool) ($validated['show_featured'] ?? false),
            'show_benefits' => (bool) ($validated['show_benefits'] ?? false),
            'show_latest' => (bool) ($validated['show_latest'] ?? false),
            'show_brands' => (bool) ($validated['show_brands'] ?? false),
            'slides' => $slides,
        ];

        SiteSetting::setValue('appearance', $appearance);
        $keptImages = collect([$logoImage, ...collect($slides)->pluck('image')->all()])->filter()->all();
        $oldImages = [
            $oldAppearance['logo_image'] ?? null,
            ...collect($oldAppearance['slides'] ?? [])->pluck('image')->all(),
        ];
        $removedImages = array_diff(array_filter($oldImages), $keptImages);
        foreach (array_unique([...$oldImagesToDelete, ...$removedImages]) as $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return back()->with('success', 'تنظیمات ظاهر فروشگاه ذخیره شد.');
    }

    private function defaults(): array
    {
        return [
            'logo_text' => config('app.name', 'فروشگاه'),
            'logo_image' => null,
            'promo_text' => 'تخفیف‌های تازه و انتخاب‌های جذاب؛ استایل بعدی‌تان را پیدا کنید',
            'footer_tagline' => 'برای هر روز، برای هر سلیقه؛ انتخابی تازه برای استایل شما.',
            'instagram_url' => '',
            'telegram_url' => '',
            'youtube_url' => '',
            'social_links' => [],
            'primary_color' => '#75172c',
            'accent_color' => '#d7193f',
            'show_categories' => true,
            'show_featured' => true,
            'show_benefits' => true,
            'show_latest' => true,
            'show_brands' => true,
            'slides' => [[
                'eyebrow' => 'تازه رسیده‌ها · انتخاب فصل',
                'title' => 'هر روز، یک استایل تازه',
                'description' => 'از میان انتخاب‌های جدید فروشگاه، چیزی را پیدا کن که به حال‌وهوای تو نزدیک‌تر است.',
                'button_text' => 'دیدن همه محصولات',
                'button_url' => '/products',
                'image' => null,
                'active' => true,
            ]],
        ];
    }
}
