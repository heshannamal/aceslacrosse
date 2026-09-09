<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EMPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search'));
        $classFilter = trim((string) $request->get('classes'));
        $priceFilter = trim((string) $request->get('price'));

        $query = EMPackage::query();

        if (Schema::hasColumn('em_packages', 'is_active')) {
            $query->where('is_active', 1);
        }

        if (Schema::hasColumn('em_packages', 'active')) {
            $query->where('active', 1);
        }

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('package_name', 'like', '%' . $search . '%')
                    ->orWhere('package_description', 'like', '%' . $search . '%');
            });
        }

        if ($classFilter !== '' && is_numeric($classFilter)) {
            $query->where('available_classes', (int) $classFilter);
        }

        if ($priceFilter !== '' && is_numeric($priceFilter)) {
            $maxPrice = (float) $priceFilter;
            if (Schema::hasColumn('em_packages', 'guest_price')) {
                $query->where(function ($builder) use ($maxPrice) {
                    $builder->where('package_price', '<=', $maxPrice)
                        ->orWhere('guest_price', '<=', $maxPrice);
                });
            } else {
                $query->where('package_price', '<=', $maxPrice);
            }
        }

        $packages = $query
            ->orderBy('available_classes')
            ->orderBy('package_price')
            ->paginate(15)
            ->appends($request->query());

        return view('admin.schedules.packages-index', compact(
            'packages',
            'search',
            'classFilter',
            'priceFilter'
        ));
    }

    public function create()
    {
        return view('admin.schedules.package-form', ['package' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validatePackage($request);
        $data['slug'] = $this->uniqueSlug($data['package_name']);
        $data['uuid'] = (string) Str::uuid();
        $data['user_id'] = auth()->id();
        $data['is_active'] = 1;

        if (Schema::hasColumn('em_packages', 'active')) {
            $data['active'] = 1;
        }

        if (Schema::hasColumn('em_packages', 'status')) {
            $data['status'] = 'active';
        }

        EMPackage::create($data);

        return redirect()
            ->route('admin.em.packages.index')
            ->with('success', 'Package created successfully.');
    }

    public function edit(EMPackage $package)
    {
        return view('admin.schedules.package-form', compact('package'));
    }

    public function update(Request $request, EMPackage $package)
    {
        $data = $this->validatePackage($request);

        if (!$package->slug) {
            $data['slug'] = $this->uniqueSlug($data['package_name'], $package->id);
        }

        $package->update($data);

        return redirect()
            ->route('admin.em.packages.index')
            ->with('success', 'Package updated successfully.');
    }

    public function destroy(EMPackage $package)
    {
        $data = ['is_active' => 0];

        if (Schema::hasColumn('em_packages', 'active')) {
            $data['active'] = 0;
        }

        if (Schema::hasColumn('em_packages', 'status')) {
            $data['status'] = 'inactive';
        }

        $package->update($data);

        return back()->with('success', 'Package deleted successfully.');
    }

    private function validatePackage(Request $request): array
    {
        return $request->validate([
            'package_name' => ['required', 'string', 'max:190'],
            'package_price' => ['required', 'numeric', 'min:0'],
            'guest_price' => ['required', 'numeric', 'min:0'],
            'package_description' => ['nullable', 'string', 'max:5000'],
            'available_classes' => ['required', 'integer', 'min:1'],
        ]);
    }

    private function uniqueSlug(string $name, ?int $ignore = null): string
    {
        $base = Str::slug($name) ?: 'package';
        $slug = $base;
        $counter = 2;

        while (
            EMPackage::query()
                ->where('slug', $slug)
                ->when($ignore, function ($query) use ($ignore) {
                    $query->where('id', '!=', $ignore);
                })
                ->exists()
        ) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }
}
