<div class="form-group">
    <label>Title</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $post->title ?? '') }}">
</div>

<div class="form-group">
    <label>Excerpt</label>
    <textarea name="excerpt" class="form-control">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
</div>

<div class="form-group">
    <label>Content</label>
    <textarea name="content" rows="8" class="form-control">{{ old('content', $post->content ?? '') }}</textarea>
</div>

<div class="form-group">
    <label>Image</label>
    <input type="file" name="image" class="form-control">
</div>

<div class="checkbox">
    <label>
        <input type="checkbox" name="is_published" value="1"
            {{ old('is_published', $post->is_published ?? true) ? 'checked' : '' }}>
        Published
    </label>
</div>
