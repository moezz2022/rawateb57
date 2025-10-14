<div class="profile-form-container">
    <h5 class="section-title">
        <i class="fas fa-lock ml-2"></i>تغيير كلمة المرور
    </h5>
    
    <form method="POST" action="{{ route('profile.updatepassword') }}" id="passwordUpdateForm">
        @csrf
        @method('PATCH')
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="form-group">
                    <label for="current_password" class="form-label font-weight-bold">كلمة المرور الحالية</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-key text-purple"></i>
                            </span>
                        </div>
                        <input id="current_password" type="password" class="form-control" 
                            name="current_password" required>
                        <div class="input-group-append">
                            <span class="input-group-text bg-light cursor-pointer" 
                                onclick="togglePasswordVisibility('current_password', 'toggleIconCurrent')">
                                <i class="fa fa-eye" id="toggleIconCurrent"></i>
                            </span>
                        </div>
                    </div>
                    @error('current_password')
                        <span class="text-danger d-block mt-1">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="form-group">
                    <label for="new_password" class="form-label font-weight-bold">كلمة المرور الجديدة</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-lock text-purple"></i>
                            </span>
                        </div>
                        <input id="new_password" type="password" class="form-control" 
                            name="new_password" required>
                        <div class="input-group-append">
                            <span class="input-group-text bg-light cursor-pointer" 
                                onclick="togglePasswordVisibility('new_password', 'toggleIconNew')">
                                <i class="fa fa-eye" id="toggleIconNew"></i>
                            </span>
                        </div>
                    </div>
                    @error('new_password')
                        <span class="text-danger d-block mt-1">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="mt-2">
                        <div class="progress" style="height: 8px;">
                            <div id="passwordStrengthBar" class="progress-bar" role="progressbar" style="width: 0%; transition: width 0.3s ease;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span id="passwordStrengthText" class="small text-muted d-block mt-1"></span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="form-group">
                    <label for="new_password_confirmation" class="form-label font-weight-bold">تأكيد كلمة المرور الجديدة</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-check-circle text-purple"></i>
                            </span>
                        </div>
                        <input id="new_password_confirmation" type="password" class="form-control" 
                            name="new_password_confirmation" required>
                        <div class="input-group-append">
                            <span class="input-group-text bg-light cursor-pointer" 
                                onclick="togglePasswordVisibility('new_password_confirmation', 'toggleIconConfirm')">
                                <i class="fa fa-eye" id="toggleIconConfirm"></i>
                            </span>
                        </div>
                    </div>
                    <span id="confirmFeedback" class="text-danger d-none mt-1">
                        <strong>كلمة المرور وتأكيدها غير متطابقين!</strong>
                    </span>
                    @error('new_password_confirmation')
                        <span class="text-danger d-block mt-1">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="password-requirements mb-4 p-3 bg-light rounded">
            <h6 class="text-muted mb-2"><i class="fas fa-info-circle mr-1"></i> متطلبات كلمة المرور:</h6>
            <ul class="text-muted small mb-0">
                <li id="length-check" class="requirement-item">يجب أن تحتوي على 8 أحرف على الأقل</li>
                <li id="uppercase-check" class="requirement-item">يجب أن تحتوي على حرف كبير واحد على الأقل</li>
                <li id="number-check" class="requirement-item">يجب أن تحتوي على رقم واحد على الأقل</li>
                <li id="special-check" class="requirement-item">يجب أن تحتوي على رمز خاص واحد على الأقل</li>
            </ul>
        </div>
        
        <div class="text-left mt-4">
            <button type="submit" class="btn btn-purple">
                <i class="fas fa-save mr-2"></i> تحديث كلمة المرور
            </button>
        </div>
    </form>
</div>

