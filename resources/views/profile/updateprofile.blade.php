<div class="profile-form-container">
    <h5 class="section-title">
        <i class="fas fa-user-edit ml-2"></i>المعلومات الشخصية
    </h5>
    
    <form method="POST" action="{{ route('profile.updateprofile') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="form-group">
                    <label for="name" class="form-label font-weight-bold">إسم المستخدم</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-user text-purple"></i>
                            </span>
                        </div>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                            name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    @error('name')
                        <span class="invalid-feedback d-block mt-1" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="form-group">
                    <label for="email" class="form-label font-weight-bold">البريد الإلكتروني</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-envelope text-purple"></i>
                            </span>
                        </div>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                            name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    @error('email')
                        <span class="invalid-feedback d-block mt-1" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="form-group">
                    <label for="sub_group" class="form-label font-weight-bold">المؤسسة / الهيئة</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-building text-purple"></i>
                            </span>
                        </div>
                        <input id="sub_group" type="text" class="form-control bg-light" 
                            value="{{ $user->subGroup->name }}" C>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-left mt-4">
            <button type="submit" class="btn btn-purple">
                <i class="fas fa-save mr-2"></i> حفظ التعديلات
            </button>
        </div>
    </form>
</div>