<x-layouts.admin-layout>
    <x-slot:title>Admin • Add Product</x-slot>

    @vite([
        'resources/css/admin/products-create.css',
        'resources/js/admin/products-create.js'
    ])

    <section class="panel">
        <div style="max-width: 1000px; align; margin:0 auto;">
        <div class="panel__head">
            <h1 class="title">Add Product</h1>
            <p class="subtitle">Shto një produkt të ri në databazë (me URL foto).</p>
        </div>

        @if (session('success'))
            <div class="alert alert--success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert--error">
                <ul class="alert__list">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="form" method="POST" action="{{ route('admin.products.store') }}">
            @csrf

            <div class="form__grid">
                <label class="field">
                    <span class="field__label">Name</span>
                    <input class="field__input" name="name" value="{{ old('name') }}" type="text" />
                </label>

                <label class="field">
                    <span class="field__label">Image URL</span>
                    <input id="imageUrlInput" class="field__input" name="image" value="{{ old('image') }}" type="url" placeholder="https://..." />
                </label>

                <div class="row2">
                    <label class="field">
                        <span class="field__label">Type</span>
                        @php $t = old('type','product'); @endphp
                        <select class="field__input" name="type">
                            <option value="product" @selected($t==='product')>product</option>
                            <option value="appliance" @selected($t==='appliance')>appliance</option>
                            <option value="tech" @selected($t==='tech')>tech</option>
                        </select>
                    </label>

                    <label class="field">
                        <span class="field__label">Price (€)</span>
                        <input class="field__input" name="price" value="{{ old('price') }}" type="number" step="0.01" min="0" />
                    </label>
                </div>

                <label class="field">
                    <span class="field__label">Quantity</span>
                    <input class="field__input" name="quantity" value="{{ old('quantity',0) }}" type="number" min="0" />
                </label>

                <label class="field">
                    <span class="field__label">Keywords (ndaji me presje)</span>
                    <input class="field__input" name="keywords" value="{{ old('keywords') }}" type="text" placeholder="iphone, apple, 128gb" />
                </label>

                <div class="preview">
                    <div class="preview__title">Preview Image</div>
                    <div class="preview__box">
                        <img id="imagePreview" class="preview__img" src="" alt="Preview" />
                        <div id="previewPlaceholder" class="preview__placeholder">Vendos një Image URL sipër</div>
                    </div>
                </div>

                <button type="submit" class="btn btn--primary">
                    Add Product
                </button>
            </div>
        </form>
    </div>
    </section>
</x-layouts.admin-layout>
