
@extends('layouts.master')
@section('title', ($isEdit ? 'Edit' : 'Add') . ' Product | ' . config('app.name'))
@section('style')
    <style>
        .image-preview-grid { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px; }
        .image-preview-item { position: relative; width: 100px; height: 100px; border-radius: 8px; overflow: hidden; border: 2px solid #e0e0e0; }
        .image-preview-item img { width: 100%; height: 100%; object-fit: cover; }
        .image-preview-item .remove-img { position: absolute; top: 2px; right: 2px; background: #ea5455; color: #fff; border: none; border-radius: 50%; width: 22px; height: 22px; font-size: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .drop-zone { border: 2px dashed #7367f0; border-radius: 10px; padding: 30px; text-align: center; cursor: pointer; transition: all 0.3s; background: #f8f8f8; }
        .drop-zone:hover, .drop-zone.dragover { border-color: #28c76f; background: #f0fff4; }
    </style>
@endsection

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <h2 class="content-header-title float-start mb-0">{{ $isEdit ? 'Edit' : 'Add' }} Product</h2>
                </div>
                <div class="content-header-right col-md-3 col-12 mb-2 text-end">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                        <i data-feather='arrow-left'></i> Back
                    </a>
                </div>
            </div>

            <div class="content-body">
                @if(session('status'))
                    <div class="alert alert-{{ session('status') == 'success' ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ $isEdit ? route('admin.products.update', $product->id) : route('admin.products.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header"><h4 class="card-title">Product Information</h4></div>
                                <div class="card-body">
                                    <div class="mb-1">
                                        <label class="form-label fw-bold">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name ?? '') }}" required>
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label fw-bold">Description</label>
                                        <textarea name="description" class="form-control" rows="5">{{ old('description', $product->description ?? '') }}</textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-1">
                                            <label class="form-label fw-bold">Price <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price ?? '') }}" required>
                                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-4 mb-1">
                                            <label class="form-label fw-bold">Sale Price</label>
                                            <input type="number" step="0.01" name="sale_price" class="form-control" value="{{ old('sale_price', $product->sale_price ?? '') }}">
                                        </div>
                                        <div class="col-md-4 mb-1">
                                            <label class="form-label fw-bold">Stock <span class="text-danger">*</span></label>
                                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock ?? 0) }}" required>
                                            @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label fw-bold">Category</label>
                                            <select name="category" class="form-select">
                                                <option value="">Select Category</option>
                                                @foreach(\App\Models\ProductAttribute::active()->categories()->orderBy('name')->get() as $cat)
                                                    <option value="{{ $cat->name }}" {{ old('category', $product->category ?? '') == $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label fw-bold">Brand</label>
                                            <select name="brand" class="form-select">
                                                <option value="">Select Brand</option>
                                                @foreach(\App\Models\ProductAttribute::active()->brands()->orderBy('name')->get() as $br)
                                                    <option value="{{ $br->name }}" {{ old('brand', $product->brand ?? '') == $br->name ? 'selected' : '' }}>{{ $br->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                            <select name="status" class="form-select">
                                                <option value="active" {{ old('status', $product->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ old('status', $product->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-1 d-flex align-items-end">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="featured" id="featured" {{ old('featured', $product->featured ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold" for="featured">Featured Product</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            {{-- Main Image --}}
                            <div class="card">
                                <div class="card-header"><h4 class="card-title">Main Image</h4></div>
                                <div class="card-body">
                                    @if($isEdit && $product->image)
                                        <div class="mb-1">
                                            <img src="{{ asset($product->image) }}" class="img-fluid rounded" style="max-height: 150px">
                                        </div>
                                    @endif
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    <small class="text-muted">Max 2MB. JPEG, PNG, WebP</small>
                                </div>
                            </div>

                            {{-- Additional Images --}}
                            <div class="card">
                                <div class="card-header"><h4 class="card-title">Additional Images</h4></div>
                                <div class="card-body">
                                    @if($isEdit && $product->images->count() > 0)
                                        <div class="image-preview-grid mb-2" id="existing-images">
                                            @foreach($product->images as $img)
                                                <div class="image-preview-item" id="img-{{ $img->id }}">
                                                    <img src="{{ asset($img->image) }}" alt="Product Image">
                                                    <button type="button" class="remove-img" onclick="deleteProductImage({{ $img->id }})">×</button>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="drop-zone" id="dropZone" onclick="document.getElementById('extraImages').click()">
                                        <i data-feather="upload-cloud" style="width:30px;height:30px;color:#7367f0"></i>
                                        <p class="mb-0 mt-1">Click or drag images here</p>
                                        <small class="text-muted">Multiple images allowed</small>
                                    </div>
                                    <input type="file" name="extra_images[]" id="extraImages" class="d-none" multiple accept="image/*">
                                    <div class="image-preview-grid" id="newImagePreview"></div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i data-feather='save'></i> {{ $isEdit ? 'Update' : 'Create' }} Product
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Drop zone
    const dropZone = document.getElementById('dropZone');
    const extraImages = document.getElementById('extraImages');
    const preview = document.getElementById('newImagePreview');

    if(dropZone) {
        dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.classList.add('dragover'); });
        dropZone.addEventListener('dragleave', () => { dropZone.classList.remove('dragover'); });
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            extraImages.files = e.dataTransfer.files;
            showPreview(e.dataTransfer.files);
        });
    }

    if(extraImages) {
        extraImages.addEventListener('change', function() {
            showPreview(this.files);
        });
    }

    function showPreview(files) {
        preview.innerHTML = '';
        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.className = 'image-preview-item';
                div.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    // Delete existing product image via AJAX
    function deleteProductImage(imageId) {
        if (!confirm('Delete this image?')) return;
        fetch(`/admin/product-image/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('img-' + imageId).remove();
            }
        });
    }

    if (typeof feather !== 'undefined') { feather.replace({ width: 14, height: 14 }); }
</script>
@endsection
