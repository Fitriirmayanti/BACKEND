<?php

namespace App\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Website;

class WebsiteController extends Controller
{
    public function index()
    {
        return view('admin_pusat.website', [
            'title' => 'Pengaturan',
            'active' => 'Website',
            'user' => auth()->user(),
        ]);
    }



    public function update(Request $request)
    {
        $website = Website::firstOrFail();

        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'keyword'   => 'required|string',
            'alamat'    => 'required|string',
            'telepon'   => 'required|string|max:50',
            'email'     => 'required|email|max:100',
            'facebook'  => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'wa'        => 'nullable|string',
            'gmaps'     => 'required|string',
            'jambuka'   => 'required|string',
            'visi'   => 'required|string',
            'misi'   => 'required|string',
            'icon'      => 'nullable|image|mimes:jpeg,png,jpg,ico,webp|max:1024',
            'logo'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'struktur'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('icon')) {
            if ($website->icon && file_exists(public_path('img/' . $website->icon))) {
                unlink(public_path('img/' . $website->icon));
            }

            $iconName = 'icon.' . $request->icon->extension();
            $request->icon->move(public_path('img'), $iconName);
            $validated['icon'] = $iconName;
        } else {
            $validated['icon'] = $website->icon;
        }

        if ($request->hasFile('logo')) {
            if ($website->logo && file_exists(public_path('img/' . $website->logo))) {
                unlink(public_path('img/' . $website->logo));
            }

            $logoName = 'logo.' . $request->logo->extension();
            $request->logo->move(public_path('img'), $logoName);
            $validated['logo'] = $logoName;
        } else {
            $validated['logo'] = $website->logo;
        }

        if ($request->hasFile('struktur')) {
            if ($website->struktur && file_exists(public_path('img/' . $website->struktur))) {
                unlink(public_path('img/' . $website->struktur));
            }

            $strukturName = 'struktur.' . $request->struktur->extension();
            $request->struktur->move(public_path('img'), $strukturName);
            $validated['struktur'] = $strukturName;
        } else {
            $validated['struktur'] = $website->struktur;
        }

        $website->update($validated);

        return redirect()->route('admin_pusat.website')
            ->with('success', 'Profil perusahaan berhasil diperbarui.');
    }
}
