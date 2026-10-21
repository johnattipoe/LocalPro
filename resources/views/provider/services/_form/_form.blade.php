<div class="form-row">
    <label for="service-title">Service title</label>
    <input id="service-title" name="title" required maxlength="150" value="{{ old('title', $service->title ?? '') }}" placeholder="e.g. Home wiring and repairs">
    @error('title')<small class="field-error">{{ $message }}</small>@enderror
</div>
<div class="form-row">
    <label for="service-category">{{ __('ui.category') }}</label>
    <select id="service-category" name="service_category_id">
        <option value="">Use my professional category</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" @selected(old('service_category_id', $service->service_category_id ?? '') == $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>
    @error('service_category_id')<small class="field-error">{{ $message }}</small>@enderror
</div>
<div class="form-row">
    <label for="service-description">{{ __('ui.description') }}</label>
    <textarea id="service-description" name="description" rows="4" maxlength="2000" placeholder="What is included in this service?">{{ old('description', $service->description ?? '') }}</textarea>
    @error('description')<small class="field-error">{{ $message }}</small>@enderror
</div>
<div class="form-row">
    <label for="pricing-type">Pricing method</label>
    <select id="pricing-type" name="pricing_type" data-pricing-type>
        <option value="quote" @selected(old('pricing_type', $service->pricing_type ?? 'quote') === 'quote')>Quote after discussing the job</option>
        <option value="fixed" @selected(old('pricing_type', $service->pricing_type ?? '') === 'fixed')>Fixed price (GH₵)</option>
        <option value="hourly" @selected(old('pricing_type', $service->pricing_type ?? '') === 'hourly')>Hourly rate (GH₵)</option>
    </select>
    @error('pricing_type')<small class="field-error">{{ $message }}</small>@enderror
</div>
<div class="form-row">
    <label for="service-price">Indicative price in GH₵</label>
    <input id="service-price" data-service-price type="number" name="price" min="0" max="999999" step="0.01" value="{{ old('price', $service->price ?? '') }}" placeholder="Required for fixed or hourly pricing">
    @error('price')<small class="field-error">{{ $message }}</small>@enderror
</div>
<label class="check-row"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $service->is_active ?? true))> Show this service on my profile</label>
