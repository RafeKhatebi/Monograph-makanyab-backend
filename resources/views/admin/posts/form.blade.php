<div class="form-group">
    <label for="title">Title <span aria-hidden="true">*</span></label>
    <input id="title" type="text" name="title" class="form-control" value="{{ old('title', $post->title ?? '') }}" required maxlength="255">
    @error('title')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="excerpt">Excerpt</label>
    <textarea id="excerpt" name="excerpt" class="form-control" maxlength="500">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
    @error('excerpt')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="content">Content <span aria-hidden="true">*</span></label>
    <textarea id="content" name="content" rows="8" class="form-control" required>{{ old('content', $post->content ?? '') }}</textarea>
    @error('content')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="image">Image</label>
    <input id="image" type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp">
    <small class="form-text text-muted">JPG, PNG, or WebP up to 2 MB.</small>
    @error('image')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

<div class="checkbox">
    <label for="is_published">
        <input id="is_published" type="checkbox" name="is_published" value="1"
            {{ old('is_published', $post->is_published ?? true) ? 'checked' : '' }}>
        Published
    </label>
</div>
