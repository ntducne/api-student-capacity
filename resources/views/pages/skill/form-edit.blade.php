@extends('layouts.main')
@section('title', 'Cập nhật kỹ năng')
@section('page-title', 'Cập nhật kỹ năng')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-flush h-lg-100 p-10">
                <form id="formSkill" action="{{ route('admin.skill.update', $data->id) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-10">
                        <label for="">Tên kỹ năng</label>
                        <input type="text" name="name" value="{{ $data->name }}" class=" form-control" placeholder="">
                        @error('name')
                            <p id="checkname" class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="row">


                        <div class="row">
                            <div class="col-8">

                                <div class="form-group mb-10">
                                    <label for="">Mã kỹ năng</label>
                                    <input type="text" name="short_name" value="{{ $data->short_name }}"
                                        class=" form-control" placeholder="">
                                    @error('short_name')
                                        <p id="checkshort_name" class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group mb-10">
                                    <label for="" class="form-label">Thuộc chuyên ngành</label>
                                    <select multiple class="form-select mb-2 select2-hidden-accessible"
                                        data-control="select2" data-hide-search="false" tabindex="-1" aria-hidden="true"
                                        name="major_id[]" value="{{ old('major_id') }}">
                                        @foreach ($dataMajor as $itemMajor)
                                            @php
                                                $dash = '';
                                            @endphp
                                            <option
                                                @foreach ($data->majorSkill as $item) @if ($item->id == $itemMajor->id)
                                        {{ 'selected="selected"' }}
                                        @endif @endforeach
                                                value="{{ $itemMajor->id }}">
                                                Ngành: {{ $itemMajor->name }}
                                            </option>
                                            @include(
                                                'pages.skill.include.listSelecterChisl',
                                                ['majorPrent' => $itemMajor, 'major' => $data]
                                            )
                                        @endforeach
                                    </select>
                                    @if (count($data->majorSkill) > 0)
                                        <input type="hidden" value="{{ $data->majorSkill }}" name="oldMajor">
                                    @endif
                                    @error('major_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group ">
                                    <label for="" class="form-label">Ảnh kỹ năng</label>
                                    <input value="{{ old('image_url') }}" name="image_url" type='file' id="file-input"
                                        class="form-control" accept=".png, .jpg, .jpeg" />
                                    <img class="w-100 mt-4 border rounded-3" id="image-preview"
                                        src="{{ Storage::disk('s3')->has($data->image_url) ? Storage::disk('s3')->temporaryUrl($data->image_url, now()->addMinutes(5)) : 'https://vanhoadoanhnghiepvn.vn/wp-content/uploads/2020/08/112815953-stock-vector-no-image-available-icon-flat-vector.jpg' }}" />
                                </div>
                            </div>

                        </div>



                    </div>

                    <div class="form-group mb-10">
                        <label for="">Mô tả kỹ năng</label>
                        <textarea class="form-control" name="description" id="" rows="3">{{ $data->description }}</textarea>
                        @error('description')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group mb-10 ">
                        <button type="submit" name="" id="" class="btn btn-success btn-lg btn-block">Lưu </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('page-script')
    <script src="assets/js/system/preview-file/previewImg.js"></script>
    <script src="assets/js/system/skill/form.js"></script>
    <script>
        preview.showFile('#file-input', '#image-preview');
    </script>
    <script src="assets/js/system/validate/validate.js"></script>
@endsection
