@csrf

<div>
    <label>Nama Kategori</label>
    <input 
        type="text" 
        name="name"
        value="{{ old('name', $category->name ?? '') }}"
        required
    >
</div>

<div>
    <label>Slug</label>
    <input 
        type="text" 
        name="slug"
        value="{{ old('slug', $category->slug ?? '') }}"
        required
    >
</div>

<button type="submit">
    {{ $button ?? 'Simpan' }}
</button>