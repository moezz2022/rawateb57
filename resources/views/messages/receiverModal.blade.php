<div class="modal fade" id="receiverModal" tabindex="-1" role="dialog" aria-labelledby="receiverModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-lg custom-modal" role="document">
    <div class="modal-content" style="border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.2);">
        <div class="modal-header" style="background-color: #6c5ce7; border-bottom: 0;">
            <h5 class="modal-title text-white" id="receiverModalLabel"><i class="fas fa-users mr-2"></i> تحديد المستلمين</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" style="padding: 20px;">
            <div class="mb-3">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="background-color: #f8f9fa; border-right: 0;"><i class="fas fa-search"></i></span>
                    </div>
                    <input type="text" id="searchInput" class="form-control" placeholder="ابحث هنا..." style="border-left: 0;">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="list-group" style="max-height: 250px; overflow-y: auto; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                        <a data-target="#admin" href="javascript:void(0);" class="list-group-item list-group-item-action active"
                            data-toggle="tab" style="border: 0; background-color: #6c5ce7; color: white;">إدارة المديرية</a>
                        <a data-target="#education" href="javascript:void(0);" class="list-group-item list-group-item-action"
                            data-toggle="tab" style="border: 0;">المؤسسات التربوية</a>
                        <a data-target="#inspection" href="javascript:void(0);" class="list-group-item list-group-item-action"
                            data-toggle="tab" style="border: 0;">الهيئة التفتيشية</a>
                    </div>
                    <div class="mt-3 p-2" style="background-color: #f8f9fa; border-radius: 8px; display: inline-block;">
                        <span style="font-weight: 600; color: #495057;">عدد المستلمين:</span>
                        <span id="selectedCount" style="font-weight: bold; color: #6c5ce7;">0</span>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="tab-content" style="background-color: #fff; border-radius: 10px; padding: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                        <!-- إدارة المديرية -->
                        <div class="tab-pane fade show active" id="admin">
                            <div class="list-group" id="adminList" style="max-height: 250px; overflow-y: auto;">
                            @foreach ($adminGroups as $group)
                             <div class="form-check mb-2">
                                      <span class="toggle-btn" data-toggle="collapse"
                                         data-target="#collapseGroup{{ $group->id }}" aria-expanded="false" style="cursor: pointer; color: #6c5ce7;">
                                          <i class="fa fa-plus"></i>
                                      </span>
                                     <input class="form-check-input" type="checkbox"
                                          value="{{ $group->id }}" id="admin{{ $group->id }}">
                                            <label class="form-check-label mb-2"
                                        for="admin{{ $group->id }}" style="font-weight: 500;">{{ $group->name }}</label>
                               <div id="collapseGroup{{ $group->id }}" class="collapse" style="border-left: 2px solid #6c5ce7; margin-left: 10px;">
                                         @foreach ($group->children as $child)
                              <div class="form-check ml-4" style="padding-right: 1.8rem">
                           <input class="form-check-input" type="checkbox"
                          value="{{ $child->id }}" id="admin{{ $child->id }}" name="receiver_ids[]">
                             <label class="form-check-label"
                          for="admin{{ $child->id }}">{{ $child->name }}</label>
                    </div>
                     @endforeach
                </div>
              </div>
                    @endforeach
                            </div>
                        </div>
                        <!-- المؤسسات التربوية -->
                        <div class="tab-pane fade" id="education">
                            <div class="list-group" id="educationList"
                                style="max-height: 250px; overflow-y: auto;">
                                @foreach ($educationGroups as $group)
                                    <div class="form-check mb-2">
                                        <span class="toggle-btn" data-toggle="collapse"
                                            data-target="#collapseEducationGroup{{ $group->id }}"
                                            aria-expanded="false" style="cursor: pointer; color: #6c5ce7;">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                        <input class="form-check-input" type="checkbox"
                                            value="{{ $group->id }}" id="education{{ $group->id }}">
                                        <label class="form-check-label"
                                            for="education{{ $group->id }}" style="font-weight: 500;">{{ $group->name }}</label>
                                        <div id="collapseEducationGroup{{ $group->id }}" class="collapse" style="border-left: 2px solid #6c5ce7; margin-left: 10px;">
                                            @foreach ($group->children as $child)
                                                <div class="form-check ml-4" style="padding-right: 1.8rem">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="{{ $child->id }}"
                                                        id="education{{ $child->id }}" name="receiver_ids[]">
                                                    <label class="form-check-label"
                                                        for="education{{ $child->id }}">{{ $child->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- الهيئة التفتيشية -->
                        <div class="tab-pane fade" id="inspection">
                            <div class="list-group" id="inspectionList"
                                style="max-height: 250px; overflow-y: auto;">
                                @foreach ($inspectionGroups as $group)
                                    <div class="form-check mb-2">
                                        <span class="toggle-btn" data-toggle="collapse"
                                            data-target="#collapseInspectionGroup{{ $group->id }}"
                                            aria-expanded="false" style="cursor: pointer; color: #6c5ce7;">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                        <input class="form-check-input" type="checkbox"
                                            value="{{ $group->id }}" id="inspection{{ $group->id }}">
                                        <label class="form-check-label"
                                            for="inspection{{ $group->id }}" style="font-weight: 500;">{{ $group->name }}</label>
                                        <div id="collapseInspectionGroup{{ $group->id }}" class="collapse" style="border-left: 2px solid #6c5ce7; margin-left: 10px;">
                                            @foreach ($group->children as $child)
                                                <div class="form-check ml-4" style="padding-right: 1.8rem">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="{{ $child->id }}"
                                                        id="inspection{{ $child->id }}" name="receiver_ids[]">
                                                    <label class="form-check-label"
                                                        for="inspection{{ $child->id }}">{{ $child->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="background-color: #f8f9fa; border-top: 1px solid #eaeaea;">
            <button type="button" class="btn strong btn-danger btn-fill" data-dismiss="modal" style="border-radius: 8px; transition: all 0.3s ease;">إغلاق</button>
            <button type="button" class="btn strong btn-purple btn-fill" id="saveRecipient" style="border-radius: 8px; transition: all 0.3s ease; background-color: #6c5ce7; border-color: #6c5ce7;">تأكيد الاختيار</button>
        </div>
    </div>
</div>
</div>
  

