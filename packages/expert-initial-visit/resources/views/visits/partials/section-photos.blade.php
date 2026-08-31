<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div class="card-header d-flex align-items-center gap-3"
         style="background:linear-gradient(135deg,#E8EAF6,#C5CAE9);border-radius:12px 12px 0 0;border:none;">
        <div style="width:38px;height:38px;background:linear-gradient(135deg,#7986CB,#5C6BC0);border-radius:10px;display:flex;align-items:center;justify-content:center;">
            <i class="fa fa-camera text-white"></i>
        </div>
        <div>
            <h5 class="mb-0 fw-bold" style="color:#283593;">بخش ۱۳ — مستندات و تصاویر بازدید</h5>
            <small class="text-muted">اختیاری — برای هر تصویر امکان ثبت توضیح وجود دارد</small>
        </div>
    </div>
    <div class="card-body p-4">

        @php
            $photoTypes = \ExpertInitialVisit\Models\ExpertVisitPhoto::getPhotoTypes();
        @endphp

        <div id="photosContainer">
            <div class="photo-row row g-3 mb-3 p-3" style="background:#F8F9FF;border-radius:10px;border:1px dashed #C5CAE9;">
                <div class="col-md-4">
                    <label class="form-label fw-semibold mb-1">نوع تصویر:</label>
                    <select name="photo_type[]" class="form-control form-control-sm"
                            style="border-radius:8px;border:2px solid #E0E0E0;padding:8px 12px;">
                        @foreach($photoTypes as $v=>$l)
                        <option value="{{ $v }}">{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold mb-1">انتخاب تصویر:</label>
                    <input type="file" name="photo_file[]" accept="image/*" class="form-control form-control-sm"
                           style="border-radius:8px;border:2px solid #E0E0E0;padding:7px 12px;">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold mb-1">توضیح کوتاه:</label>
                    <input type="text" name="photo_caption[]" class="form-control form-control-sm"
                           placeholder="توضیح اختیاری..."
                           style="border-radius:8px;border:2px solid #E0E0E0;padding:8px 12px;">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-danger remove-photo-row"
                            style="border-radius:8px;width:36px;height:36px;display:none;">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <button type="button" id="addPhotoRow" class="btn btn-sm text-white mt-2"
                style="background:linear-gradient(135deg,#7986CB,#5C6BC0);border-radius:8px;">
            <i class="fa fa-plus ms-1"></i> افزودن تصویر
        </button>

    </div>
</div>

@push('scripts')
<script>
var photoTypes = @json($photoTypes);

document.getElementById('addPhotoRow')?.addEventListener('click', function() {
    var container = document.getElementById('photosContainer');
    var row = container.querySelector('.photo-row').cloneNode(true);

    // reset values
    row.querySelectorAll('input').forEach(function(i){ i.value=''; });
    row.querySelector('.remove-photo-row').style.display = '';

    container.appendChild(row);
    updateRemoveButtons();
});

document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-photo-row')) {
        e.target.closest('.photo-row').remove();
        updateRemoveButtons();
    }
});

function updateRemoveButtons() {
    var rows = document.querySelectorAll('.photo-row');
    rows.forEach(function(row, i) {
        var btn = row.querySelector('.remove-photo-row');
        if (btn) btn.style.display = rows.length > 1 ? '' : 'none';
    });
}
</script>
@endpush
