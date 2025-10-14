<div class="profile-form-container">
    <h5 class="section-title">
        <i class="fas fa-image ml-2"></i>تغيير الصورة الشخصية
    </h5>
    
    <form method="POST" action="{{ route('profile.updateavatar') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="avatar-container">
                    <div class="avatar-preview">
                        @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" id="avatar-preview-img">
                        @else
                            <img src="{{ asset('assets/img/default-avatar.png') }}" alt="Default Avatar" id="avatar-preview-img">
                        @endif
                    </div>
                    
                    <label for="avatar" class="custom-file-upload">
                        <i class="fas fa-upload"></i> اختر صورة جديدة
                    </label>
                    <input id="avatar" type="file" class="form-control-file d-none" name="avatar" accept="image/*" required onchange="previewImage(this)">
                    
                    @error('avatar')
                        <span class="text-danger d-block mt-2">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    
                    <div class="text-muted text-center mt-2 small">
                        <p>يفضل استخدام صورة مربعة بأبعاد 300×300 بكسل</p>
                        <p>الحد الأقصى لحجم الملف: 2 ميجابايت</p>
                        <p>الصيغ المدعومة: JPG، PNG، GIF</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-purple">
                <i class="fas fa-save mr-2"></i> حفظ الصورة
            </button>
        </div>
    </form>
</div>

